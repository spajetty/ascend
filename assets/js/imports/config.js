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
            'Schools',
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
    'registered': 'bg-blue-100 text-blue-700',
    'referred': 'bg-orange-100 text-orange-600',
    'interviewed': 'bg-purple-100 text-purple-700',
    'qualified': 'bg-emerald-100 text-emerald-700',
    'not qualified': 'bg-red-100 text-red-600',
    'placed/hots': 'bg-teal-100 text-teal-700',
    'for further interview': 'bg-yellow-100 text-yellow-700',

    // ── First Time Job Seekers (shares most with Job Matching) ───────────────
    'first-time job seeker': 'bg-blue-100 text-blue-700',
    'issued': 'bg-indigo-100 text-indigo-700',
    // referred, interviewed, qualified, not qualified, placed/hots,
    // for further interview — already defined above, reused automatically

    // ── SPES ─────────────────────────────────────────────────────────────────
    'placed': 'bg-teal-100 text-teal-700',
    // registered, referred — already defined above

    // ── Government Internship Program and Work Immersion ────────────────────────────────────────
    'peso-accepted': 'bg-emerald-100 text-emerald-700',
    'private-accepted': 'bg-teal-100 text-teal-700',

    // ── Work Immersion ───────────────────────────────────────────────────────
    'inquired': 'bg-blue-100 text-blue-700',
    'not proceeded': 'bg-red-100 text-red-600',
    // referred, interviewed, peso-accepted, private-accepted — already defined above
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

// ─── Extra fields per program key for Manual Entry ──────────────────────────────────────────────
// Each field: { name, label, type, placeholder?, options?, required? }
// type: 'text' | 'number' | 'date' | 'select' | 'textarea'
export const programFields = {

    // ── Employment Facilitation ──────────────────────────────────────────────
    'Job Matching and Referral': [
        { name: 'skills', label: 'Skills / Qualifications', type: 'textarea', placeholder: 'e.g. Accounting, MS Office, Driving', required: true },
        { name: 'desired_position', label: 'Desired Position', type: 'text', placeholder: 'e.g. Accounting Clerk', required: true },
        { name: 'work_experience', label: 'Years of Work Experience', type: 'number', placeholder: '0', required: false },
        { name: 'employer_name', label: 'Referred Employer', type: 'text', placeholder: 'Employer name (if already referred)', required: false },
    ],
    'First Time Jobseeker': [
        { name: 'school', label: 'School / University', type: 'text', placeholder: 'Enter school name', required: true },
        { name: 'course', label: 'Course / Degree', type: 'text', placeholder: 'e.g. BS Accountancy', required: true },
        { name: 'graduation_year', label: 'Year Graduated', type: 'number', placeholder: 'e.g. 2024', required: true },
        { name: 'desired_position', label: 'Desired Position', type: 'text', placeholder: 'e.g. Office Staff', required: false },
    ],
    'Job Fair': [
        { name: 'skills', label: 'Skills / Qualifications', type: 'textarea', placeholder: 'List relevant skills', required: true },
        { name: 'desired_position', label: 'Desired Position', type: 'text', placeholder: 'e.g. Cashier', required: false },
        { name: 'job_fair_date', label: 'Job Fair Date Attended', type: 'date', required: false },
    ],

    // ── Employers Engagement ─────────────────────────────────────────────────
    'Employers Accreditation': [
        { name: 'company_name', label: 'Company Name', type: 'text', placeholder: 'Enter company name', required: true },
        { name: 'industry', label: 'Industry', type: 'text', placeholder: 'e.g. Manufacturing, BPO', required: true },
        { name: 'job_vacancies', label: 'Number of Job Vacancies', type: 'number', placeholder: '0', required: true },
        { name: 'tin', label: 'TIN / Business Reg. No.', type: 'text', placeholder: 'Enter TIN or registration', required: false },
    ],
    'Workers Hiring for Infrastructure Projects': [
        { name: 'company_name', label: 'Company / Contractor', type: 'text', placeholder: 'Enter company or contractor name', required: true },
        { name: 'project_name', label: 'Project Name', type: 'text', placeholder: 'Enter project name', required: true },
        { name: 'position', label: 'Position / Trade', type: 'text', placeholder: 'e.g. Carpenter, Mason', required: true },
        { name: 'contract_duration', label: 'Contract Duration', type: 'text', placeholder: 'e.g. 6 months', required: false },
    ],

    // ── Youth Employability ──────────────────────────────────────────────────
    'SPES': [
        { name: 'school', label: 'School', type: 'text', placeholder: 'Enter school name', required: true },
        { name: 'year_level', label: 'Year Level', type: 'select', options: ['Grade 11', 'Grade 12', '1st Year', '2nd Year', '3rd Year', '4th Year'], required: true },
        { name: 'guardian_name', label: "Guardian's Name", type: 'text', placeholder: "Enter guardian's name", required: true },
        { name: 'guardian_contact', label: "Guardian's Contact", type: 'tel', placeholder: 'e.g. 09171234567', required: false },
    ],
    '4Ps Beneficiaries': [
        { name: 'household_id', label: '4Ps Household ID', type: 'text', placeholder: 'Enter household ID', required: true },
        { name: 'school', label: 'School', type: 'text', placeholder: 'Enter school name', required: false },
        { name: 'year_level', label: 'Year Level', type: 'text', placeholder: 'e.g. Grade 10', required: false },
    ],
    'PWD': [
        { name: 'pwd_id', label: 'PWD ID Number', type: 'text', placeholder: 'Enter PWD ID number', required: true },
        { name: 'disability_type', label: 'Type of Disability', type: 'select', options: ['Visual', 'Hearing', 'Speech', 'Physical / Orthopedic', 'Intellectual', 'Psychosocial', 'Others'], required: true },
        { name: 'skills', label: 'Skills / Abilities', type: 'textarea', placeholder: 'Describe skills or abilities', required: false },
    ],
    'Government Internship Program': [
        { name: 'school', label: 'School / University', type: 'text', placeholder: 'Enter school name', required: true },
        { name: 'course', label: 'Course / Degree', type: 'text', placeholder: 'e.g. BS Public Administration', required: true },
        { name: 'agency_assigned', label: 'Agency Assigned', type: 'text', placeholder: 'Enter government agency', required: false },
        { name: 'internship_start', label: 'Internship Start Date', type: 'date', required: false },
        { name: 'internship_end', label: 'Internship End Date', type: 'date', required: false },
    ],
    'Work Immersion and Internship Referral Program': [
        { name: 'school', label: 'School', type: 'text', placeholder: 'Enter school name', required: true },
        { name: 'year_level', label: 'Year Level', type: 'text', placeholder: 'e.g. Grade 12', required: true },
        { name: 'company_partner', label: 'Partner Company', type: 'text', placeholder: 'Enter company name', required: false },
        { name: 'immersion_hours', label: 'Total Immersion Hours', type: 'number', placeholder: '80', required: false },
    ],

    // ── Career Development ───────────────────────────────────────────────────
    'Career Development Support Program': [
        { name: 'career_goal', label: 'Career Goal', type: 'text', placeholder: 'e.g. Become a licensed nurse', required: false },
        { name: 'training_needed', label: 'Training / Support Needed', type: 'textarea', placeholder: 'Describe needed training or support', required: false },
        { name: 'current_status', label: 'Current Employment Status', type: 'select', options: ['Unemployed', 'Underemployed', 'Student', 'Self-employed'], required: true },
    ],
    'LMI Orientation': [
        { name: 'orientation_date', label: 'Orientation Date', type: 'date', required: false },
        { name: 'venue', label: 'Venue', type: 'text', placeholder: 'Enter venue', required: false },
        { name: 'current_status', label: 'Current Employment Status', type: 'select', options: ['Unemployed', 'Underemployed', 'Student', 'Self-employed'], required: true },
    ],
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
    'Government Internship Program': 
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
        //'Birthday', 
        'Age', 
        'Email address', 
        'Contact Number', 
        'Civil Status', 
        'Program', 
        'Classification', 
        'School Name', 
        'College/SHS',
        'Office Assignment', 
        'Course/Degree/Strand', 
        'Required Hours',
        'Preferred Host Organization Type', 
        'Preferred Industry / Field of Internship', 
        'Are you willing to be assigned outside your preferred field if not available?', 
        'Curriculum Vitae / Resume', 
        'Proof of Residency', 
        'Latest Credentials', 
        'Letter of Intent', 
        'Recommendation Letter'
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
