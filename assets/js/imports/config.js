// ─── Programs per section ───────────────────────────────────────────────────
export const programs = {
    employment_facilitation:
        [
            'Job Matching and Referral',
            'First Time Jobseeker',
            'Job Fair'
        ],
    employers_engagement:
        [
            'Employers Accreditation',
            'Workers Hiring for Infrastructure Projects - Beneficiaries',
            'Workers Hiring for Infrastructure Projects - Projects'
        ],
    youth_employability:
        [
            'SPES',
            //'Schools',
            //'4Ps Beneficiaries',
            //'PWD',
            'Government Internship Program',
            'Work Immersion and Internship Referral Program',
        ],
    /*career_development:
        [
            'Career Development Support Program',
            'LMI Orientation'
        ],*/
};

// ─── Statuses (= Classification) per program ────────────────────────────────
export const statusesByProgram = {
    'Job Matching and Referral': [
        'Registered',
        'Referred',
        'Interviewed',
        'Qualified',
        'Not Qualified',
        'Placed/Hots',
        'For Further Interview',
    ],
    'First Time Jobseeker': [
        'Issued',
        'Interviewed',
        'Qualified',
        'Not Qualified',
        'Placed/Hots',
        'For Further Interview',
    ],
    'Job Fair': [
        'Interviewed',
        'Qualified',
        'Not Qualified',
        'Placed',
        'Hired',
        'For Further Interview',
    ],
    'SPES': [
        'Registered',
        'Referred',
        'Placed',
    ],
    'Government Internship Program': [
        'Peso-Accepted',
        'Dole-Accepted',
    ],
    'Work Immersion and Internship Referral Program': [
        'Inquired',
        'Referred',
        'Interviewed',
        'Peso-Accepted',
        'Private-Accepted',
        'Not Proceeded',
    ],
};

// Import status - (new / duplicate / invalid)
// badge_status values returned by validate_preview.php:
//   'new'       → green   — will be inserted
//   'duplicate' → yellow  → will be skipped
//   'invalid'   → red     → will be skipped (missing required fields)
export const importStatusStyles = {
    new: {
        row: 'bg-emerald-50/60 border-l-4 border-emerald-400',
        pill: 'bg-emerald-100 text-emerald-700',
        icon: '✓',
        label: 'New',
    },
    duplicate: {
        row: 'bg-yellow-50/60 border-l-4 border-yellow-400',
        pill: 'bg-yellow-100 text-yellow-700',
        icon: '⚠',
        label: 'Duplicate',
    },
    invalid: {
        row: 'bg-red-50/60 border-l-4 border-red-400',
        pill: 'bg-red-100 text-red-700',
        icon: '✗',
        label: 'Invalid',
    },
}
// ─── Classification colors ─────────────────────────────────────────────────────

// ─── Classification — business concept (value from Excel 'Classification' col) ─
// Keys are lowercase versions of the raw Excel value.
// Programs without classification (4Ps, PWD, CDSP, LMI, Employers Accreditation,
// WHIP) won't have this column in their Excel so the cell will just be empty.
export const classificationColors = {
    // ── Job Matching & Referral ──────────────────────────────────────────────
    'Registered': 'bg-blue-100 text-blue-700',
    'Referred': 'bg-orange-100 text-orange-600',
    'Interviewed': 'bg-purple-100 text-purple-700',
    'Qualified': 'bg-emerald-100 text-emerald-700',
    'Not Qualified': 'bg-red-100 text-red-600',
    'Placed/Hots': 'bg-teal-100 text-teal-700',
    'HOTS': 'bg-amber-100 text-amber-700',
    'For Further Interview': 'bg-yellow-100 text-yellow-700',

    // ── First Time Job Seekers (shares most with Job Matching) ───────────────
    'First-Time Job Seeker': 'bg-blue-100 text-blue-700',
    'Issued': 'bg-indigo-100 text-indigo-700',
    // Referred, Interviewed, Qualified, Not Qualified, Placed/Hots,
    // For Further Interview — already defined above, reused automatically

    // ── SPES ─────────────────────────────────────────────────────────────────
    'Placed': 'bg-teal-100 text-teal-700',
    // Registered, Referred — already defined above

    // ── Government Internship Program and Work Immersion ────────────────────────────────────────
    'Peso-Accepted': 'bg-emerald-100 text-emerald-700',
    'Private-Accepted': 'bg-teal-100 text-teal-700',

    // ── Work Immersion ───────────────────────────────────────────────────────
    'Inquired': 'bg-blue-100 text-blue-700',
    'Not Proceeded': 'bg-red-100 text-red-600',
    // Referred, Interviewed, Peso-Accepted, Private-Accepted — already defined above
    'Dole-Accepted': 'bg-emerald-100 text-emerald-700',
};

export const statusColors = {
    new: 'bg-emerald-100 text-emerald-700',
    duplicate: 'bg-yellow-100 text-yellow-700',
    invalid: 'bg-red-100 text-red-600',
};

export const statusLabels = {
    new: 'New Record',
    duplicate: 'Already Exists',
    invalid: 'Invalid',
};


// ─── Excel Import Headers Validation Schema ────────────────────────────────
// Defines the EXACT columns expected in the Excel upload for each program.
// Any missing columns will flag the file as invalid.
export const programHeaders = {
    'DEFAULT': ['Last Name', 'First Name', 'Middle Name', 'Suffix', 'Sex', 'Birthday', 'Contact Number'],

    // Non-person programs
    'Employers Accreditation': 
    [   'Month', 
        'Accreditation', 
        'Company', 
        'Est. Type', 
        'Industry', 
        'City/Municipality/Province'
    ],
    
    'Workers Hiring for Infrastructure Projects - Beneficiaries': [
        'Last Name',
        'First Name',
        'Middle Name',
        'Suffix',
        'Sex',
        'House No.',
        'Barangay',
        'City',
        'District',
        'Birthday',
        'Age',
        'Email',
        'Contact',
        'Civil Status',
        'Program',
        'Position',
        'Date Hired',
        'Company',
        'Name of Project',
        'Resume',
        'B-Cert',
        'Valid ID',
        'Brgy Clearance',
    ],
    'Workers Hiring for Infrastructure Projects - Projects': [
        'Project Title / Name of Implementing Partner',
        'Nature of Project',
        'Duration',
        'Budget',
        'Fund Source',
        'No. of Persons Employed from the Locality',
        'Skills Required for the Job',
        'Skills Deficiencies',
        'Project Contractor',
        'Legitimate Contractors (YES or NO)',
        'Filled',
        'Unfilled',
    ],

    // Person-based programs (Base + Specific)
    'Job Matching and Referral': [
        'Last Name',
        'First Name',
        'Middle Name',
        'Suffix',
        'Sex',
        'House No.',
        'Barangay',
        'City',
        'District',
        'Birthday',
        'Age',
        'Email',
        'Contact',
        'Civil Status',
        'Program',
        'Classification',
        'Company',
        'Position',
        'Resume',
        'B-Cert',
        'Valid ID',
        'Brgy Clearance',
    ],
    'First Time Jobseeker': [
        'Last Name',
        'First Name',
        'Middle Name',
        'Suffix',
        'Sex',
        'House No.',
        'Barangay',
        'City',
        'District',
        'Birthday',
        'Age',
        'Email',
        'Contact',
        'Civil Status',
        'Program',
        'Occ. Permit',
        'Health Card',
        'Classification',
        'Company',
        'Resume',
        'B-Cert',
        'Valid ID',
        'Brgy Clearance',
    ],
    'Job Fair': [
        'Last Name',
        'First Name',
        'Middle Name',
        'Suffix',
        'Sex',
        'House No.',
        'Barangay',
        'City',
        'District',
        'Birthday',
        'Age',
        'Email',
        'Contact',
        'Civil Status',
        'Program',
        'Classification',
        'Position',
        'Company',
        'Resume',
        'B-Cert',
        'Valid ID',
        'Brgy Clearance',
    ],
    
    'SPES': [
        'Last Name', 
        'First Name', 
        'Middle Name', 
        'Suffix', 
        'Sex', 
        'House No/Street', 
        'Barangay', 
        'City', 
        'District', 
        'Birthday', 
        'Age', 
        'Email', 
        'Contact', 
        'Civil Status', 
        'Student/OSY', 
        'Program', 
        'Classification', 
        'Status',
        'School', 
        'Highest Educ. Attainment', 
        'Course', 
        'OFW Dependent',
        'PWD', 
        '4PS Beneficiary', 
        '4PS Household ID No.', 
        'Company', 
        'Store Assignment', 
        'Start of Contract', 
        'End of Contract', 
        'Resume', 
        'B-Cert', 
        'Valid ID', 
        'Brgy Clearance'
    ],
    'Schools': [
        'School Name',
        'Congressional District',
        'Grades Offered'
    ],
    'Government Internship Program': [
        'Last Name', 'First Name', 'Middle Name', 'Suffix', 'House No/Street', 
        'Barangay', 'City', 'District', 'Birthdate', 'Age', 'Sex', 
        'Contact Number', 'Student/OSY', 'Highest Educ. Attainment'
    ],
    'Government Internship Program - LGU': [
        'Last Name', 'First Name', 'Middle Name', 'Suffix', 'House No/Street', 
        'Barangay', 'City', 'District', 'Birthdate', 'Age', 'Sex', 
        'Contact Number', 'Student/OSY', 'Status', 'School', 
        'Highest Educ. Attainment', 'Course', 'PWD', 'OFW Dependent', 
        '4Ps Beneficiary', 'Proponent', 'Office Assignment', 
        'Start of Contract', 'End of Contract', 
        'Curriculum Vitae / Resume', 'Proof of Residency', 
        'Latest Credentials', 'Letter of Intent', 'Recommendation Letter'
    ],
    'Government Internship Program - DOLE': [
        'Last Name', 'First Name', 'Middle Name', 'Suffix', 'House No/Street', 
        'Barangay', 'City', 'District', 'Birthdate', 'Age', 'Sex', 
        'Contact Number', 'Student/OSY', 'Highest Educ. Attainment', 'PWD', 
        'GSIS Beneficiary', 'Relationship', 'GSIS Benef. Contact No.',
        'Curriculum Vitae / Resume', 'Proof of Residency', 
        'Latest Credentials', 'Letter of Intent', 'Recommendation Letter'
    ],

    'Work Immersion and Internship Referral Program': 
    [
        'Last Name', 
        'First Name', 
        'Middle Name', 
        'Extension Name', 
        'Sex', 
        'House/Block No./Street', 
        'Barangay', 
        'City', 
        'District', 
        'Age', 
        'Email address', 
        'Contact Number', 
        'Civil Status', 
        'Program', 
        'Classification', 
        'Inquiry Via', 
        'School Name', 
        'Year Level', 
        'Course/Degree/Strand', 
        'Required Work Immersion / Internship Hours', 
        'Preferred Host Organization Type', 
        'Preferred Industry / Field of Internship', 
        'Are you willing to be assigned outside your preferred field if not available?', 
        'Internship Schedule / Availability',
        'Internship Availability Date (Start of Internship)',
        'Curriculum Vitae / Resume', 
        'Proof of Residency', 
        'Latest Credentials', 
        'Letter of Intent', 
        'Recommendation Letter'
    ],

    //'Career Development Support Program': ['Last Name', 'First Name', 'Middle Name', 'Suffix', 'Sex', 'House No.', 'Barangay', 'City', 'District', 'Birthday', 'Age', 'Email', 'Contact', 'Civil Status', 'Program', 'Classification', 'CareerGoal', 'TrainingNeeded', 'CurrentStatus'],
    //'LMI Orientation': ['Last Name', 'First Name', 'Middle Name', 'Suffix', 'Sex', 'House No.', 'Barangay', 'City', 'District', 'Birthday', 'Age', 'Email', 'Contact', 'Civil Status', 'Program', 'Classification', 'OrientationDate', 'Venue', 'CurrentStatus']
};
