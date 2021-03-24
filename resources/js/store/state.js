let state = {
    guard: null,
    /*expected request */
    expected: [],
    /* Vars */
    dummies: [],
    offers: [],
    offer: [],
    /* Employer(s) */
    employer: [],
    employers: [],
    applicants: [],
    /* Employer(s) */
    employes: [],
    employees: [],
    persons: [], // This for both employers & Employees (Used in search chat (start chat))
    /* Chat stuff */
    conversations: [],
    chatflows: [],
    unread_messages_count: 0, // This is for navbar
    notifications: [],
    /* Work Stuff & Profile Stuff */
    work_experiences: [],
    educations: [],
    // Google map service
    service: ""
};

export default state;
