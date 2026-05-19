// Single source of truth for all mutable state shared across excel modules.
// Import this object, mutate its properties directly — no setters needed.

export const state = {
    parsedExcelData: [],
    selectedFile:    null,
    unknownEmployers: [],
    detectedPeriod:  { month: '', year: '', confidence: 'low', source: 'none' },
    selectedJobFairEvent: '',
    jobFairMismatchMode: '',
    latestImportResultsData: null,
    latestUndoToken: null,
    currentEventParticipants: [],  // participants for the currently selected job fair event
};
