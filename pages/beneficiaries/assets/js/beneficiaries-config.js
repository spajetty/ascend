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
        'Registered',
        'Referred',
        'Interviewed',
        'Qualified',
        'Not Qualified',
        'Placed/Hots',
        'For Further Interview'
    ],
    'First Time Jobseeker': [
        'Issued',
        'Interviewed',
        'Qualified',
        'Not Qualified',
        'Placed/Hots',
        'For Further Interview'
    ],
    'Job Fair': [
        'Interviewed',
        'Qualified',
        'Not Qualified',
        'Placed/Hots',
        'For Further Interview'
    ],
    'SPES': [
        'Registered',
        'Referred',
        'Placed'
    ],
    'Government Internship Program': [
        'Peso-Accepted',
        'Dole-Accepted'
    ],
    'Work Immersion and Internship Referral Program': [
        'Inquired',
        'Referred',
        'Interviewed',
        'Peso-Accepted',
        'Private-Accepted',
        'Not Proceeded'
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
