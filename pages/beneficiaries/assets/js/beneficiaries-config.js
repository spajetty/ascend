/**
 * Configuration for beneficiaries sections, programs, and statuses.
 */

// Programs organized by section
export const programsBySection = {
    employment_facilitation: [
        'Job Matching and Referral',
        'First Time Jobseeker',
        'Job Fair'
    ],
    employers_engagement: [
        'Employers Accreditation',
        'Workers Hiring for Infrastructure Projects'
    ],
    youth_employability: [
        'SPES',
        'Government Internship Program',
        'Work Immersion and Internship Referral Program'
    ]
};

// Statuses organized by program
export const statusesByProgram = {
    'Job Matching and Referral': [
        'registered',
        'referred',
        'interviewed',
        'qualified',
        'not qualified',
        'placed/hots',
        'for further interview'
    ],
    'First Time Jobseeker': [
        'issued',
        'interviewed',
        'qualified',
        'not qualified',
        'placed/hots',
        'for further interview'
    ],
    'Job Fair': [
        'interviewed',
        'qualified',
        'not qualified',
        'placed/hots',
        'for further interview'
    ],
    'SPES': [
        'registered',
        'referred',
        'placed'
    ],
    'Government Internship Program': [
        'peso-accepted',
        'dole-accepted'
    ],
    'Work Immersion and Internship Referral Program': [
        'inquired',
        'referred',
        'interviewed',
        'peso-accepted',
        'private-accepted',
        'not proceeded'
    ]
};

// Global fallback for non-module import environments
try {
    if (typeof window !== 'undefined') {
        window.__beneficiariesConfig = {
            programsBySection,
            statusesByProgram,
        };
    }
} catch (e) {
    // ignore
}
