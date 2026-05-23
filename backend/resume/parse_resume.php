<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../../vendor/autoload.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'error' => 'Method not allowed']);
    exit;
}

if (!isset($_FILES['resume']) || $_FILES['resume']['error'] !== UPLOAD_ERR_OK) {
    $code = $_FILES['resume']['error'] ?? -1;
    echo json_encode(['success' => false, 'error' => "Upload error (code $code)"]);
    exit;
}

$file = $_FILES['resume'];
$ext  = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

if ($ext !== 'docx') {
    echo json_encode(['success' => false, 'error' => 'Only .docx files are supported.']);
    exit;
}

if ($file['size'] > 5 * 1024 * 1024) {
    echo json_encode(['success' => false, 'error' => 'File too large. Maximum 5MB.']);
    exit;
}

try {
    $data = parseResume($file['tmp_name']);
    echo json_encode(['success' => true, 'data' => $data]);
} catch (Throwable $e) {
    echo json_encode(['success' => false, 'error' => 'Could not parse resume: ' . $e->getMessage()]);
}

// ── PARSER ────────────────────────────────────────────────────────────────────

function parseResume(string $filePath): array
{
    $phpWord = \PhpOffice\PhpWord\IOFactory::load($filePath);

    $fieldMap = [
        'Last Name *:'       => 'last_name',
        'First Name *:'      => 'first_name',
        'Middle Name:'       => 'middle_name',
        'Suffix:'            => 'suffix',
        'Date of Birth *:'   => 'dob',
        'Contact No. *:'     => 'contact',
        'Email:'             => 'email',
        'House No./Street:'  => 'house_no',
        'Barangay *:'        => 'barangay',
        'District:'          => 'district',
        'City *:'            => 'city',
        '4Ps ID No.:'        => 'ps4_id_no',
    ];

    $checkboxMap = [
        'Sex *:'            => ['column' => 'sex',            'type' => 'options', 'options' => ['Male', 'Female']],
        'Civil Status *:'   => ['column' => 'civil_status',   'type' => 'options', 'options' => ['Single', 'Married', 'Widowed', 'Separated', 'Annulled', 'Cohabiting']],
        '4Ps Member?:'      => ['column' => 'is_4ps',           'type' => 'boolean'],
        'PWD?:'             => ['column' => 'is_pwd',           'type' => 'boolean'],
        'OFW Dependent?:'   => ['column' => 'is_ofw_dependent', 'type' => 'boolean'],
    ];

    $data = [
        'last_name' => null, 'first_name' => null, 'middle_name' => null, 'suffix' => null,
        'dob' => null, 'sex' => null, 'civil_status' => null,
        'contact' => null, 'email' => null,
        'house_no' => null, 'barangay' => null, 'district' => null, 'city' => null,
        'is_4ps' => 0, 'ps4_id_no' => null, 'is_pwd' => 0, 'is_ofw_dependent' => 0,
    ];

    foreach ($phpWord->getSections() as $section) {
        // ── Recurse into ALL block containers, including nested ones ──────────
        processContainer($section, $fieldMap, $checkboxMap, $data);
    }

    return $data;
}

/**
 * Recursively walk any element container (Section, Cell, TextBox, etc.)
 * and process every Table found inside it.
 */
function processContainer($container, array $fieldMap, array $checkboxMap, array &$data): void
{
    foreach ($container->getElements() as $element) {
        if ($element instanceof \PhpOffice\PhpWord\Element\Table) {
            processTable($element, $fieldMap, $checkboxMap, $data);
        }
        // Recurse into nested containers (e.g. TextBox, Footer, Header)
        // Most top-level elements are not containers, so getElements() won't exist —
        // guard with method_exists to avoid fatal errors.
        elseif (method_exists($element, 'getElements')) {
            processContainer($element, $fieldMap, $checkboxMap, $data);
        }
    }
}

function processTable(
    \PhpOffice\PhpWord\Element\Table $table,
    array $fieldMap,
    array $checkboxMap,
    array &$data
): void {
    foreach ($table->getRows() as $row) {
        $cells = $row->getCells();
        $count = count($cells);
        if ($count < 2) continue;

        for ($i = 0; $i < $count - 1; $i++) {
            $labelText = getCellText($cells[$i]);

            if (!isset($fieldMap[$labelText]) && !isset($checkboxMap[$labelText])) {
                continue;
            }

            $valueText = getCellText($cells[$i + 1]);

            if (isset($fieldMap[$labelText])) {
                $col     = $fieldMap[$labelText];
                $cleaned = cleanValue($valueText, $col);
                if ($cleaned !== null) {
                    $data[$col] = $cleaned;
                }
            }

            if (isset($checkboxMap[$labelText])) {
                $def = $checkboxMap[$labelText];
                if ($def['type'] === 'boolean') {
                    // ☑ Yes = 1, ☑ No = 0
                    $data[$def['column']] = str_contains($valueText, '☑ Yes') ? 1 : 0;
                } elseif ($def['type'] === 'options') {
                    foreach ($def['options'] as $option) {
                        if (str_contains($valueText, "☑ $option")) {
                            $data[$def['column']] = $option;
                            break;
                        }
                    }
                }
            }

            $i++; // skip the value cell
        }
    }
}

/**
 * Extract all text from a table cell.
 *
 * PhpWord stores bold/formatted text as:
 *   Cell → TextRun → Text (runs)
 * and plain text as:
 *   Cell → Text
 *
 * This function handles BOTH forms and also handles deeply nested
 * AbstractContainer elements (e.g. TextBox inside a cell).
 */
function getCellText(\PhpOffice\PhpWord\Element\Cell $cell): string
{
    return trim(extractText($cell));
}

/**
 * Recursively extract text from any PhpWord element container.
 * Joins text runs within the same paragraph with no separator,
 * and joins separate paragraphs/runs with a single space.
 */
function extractText($container): string
{
    $parts = [];

    foreach ($container->getElements() as $element) {
        if ($element instanceof \PhpOffice\PhpWord\Element\Text) {
            $t = trim($element->getText());
            if ($t !== '') $parts[] = $t;
        } elseif ($element instanceof \PhpOffice\PhpWord\Element\TextRun) {
            // Gather all Text children of the run without adding spaces between them
            $runParts = [];
            foreach ($element->getElements() as $child) {
                if ($child instanceof \PhpOffice\PhpWord\Element\Text) {
                    $runParts[] = $child->getText();
                }
            }
            $runText = trim(implode('', $runParts));
            if ($runText !== '') $parts[] = $runText;
        } elseif (method_exists($element, 'getElements')) {
            // Nested container (TextBox, AbstractElement subclass, etc.)
            $nested = trim(extractText($element));
            if ($nested !== '') $parts[] = $nested;
        }
    }

    return implode(' ', $parts);
}
/**
 * Sanitise a raw cell value before storing.
 *
 * - Strips non-printable / non-ASCII characters that Word sometimes embeds
 *   (smart quotes, non-breaking spaces, BOM fragments, etc.)
 * - Converts MM/DD/YYYY dates to YYYY-MM-DD for the `dob` field
 * - Returns null for blank or placeholder values
 */
function cleanValue(string $value, string $field): ?string
{
    // Normalize Unicode spaces/nbsp to regular space first
    $value = preg_replace('/[\xC2\xA0]/u', ' ', $value); // non-breaking space
    // Strip remaining non-printable non-ASCII (but NOT on checkbox fields —
    // cleanValue is only called for plain-text fields so this is safe)
    $value = preg_replace('/[^\x20-\x7E]/', '', $value);
    $value = trim($value);

    if ($value === '' || $value === 'MM/DD/YYYY') {
        return null;
    }

    if ($field === 'dob') {
        if (preg_match('/^(\d{2})\/(\d{2})\/(\d{4})$/', $value, $m)) {
            return "{$m[3]}-{$m[1]}-{$m[2]}";
        }
        return null;
    }

    return $value;
}