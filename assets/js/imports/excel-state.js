// Single source of truth for all mutable state shared across excel modules.
// Import this object, mutate its properties directly — no setters needed.

export const state = {
    parsedExcelData: [],
    selectedFile:    null,
    detectedPeriod:  { month: '', year: '', confidence: 'low', source: 'none' },
    latestImportResultsData: null,
    latestUndoToken: null,
};
