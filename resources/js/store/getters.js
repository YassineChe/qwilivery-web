import state from "./state";
let getters = {
    guard: state => {
        return state.guard;
    },
    /************** offer ************* */
    offer: state => {
        return state.offer;
    },
    offers: state => {
        return state.offers;
    },
    getOffer: state => offer_id => {
        return state.offers.find(offer => offer.id == offer_id);
    },
    /************** expected ************* */
    expected: state => related => {
        try {
            return state.expected.find(expected => {
                if (expected.related == related) return expected;
            });
        } catch (error) {
            return undefined;
        }
    },
    /** DUMMIES **/
    dummies: state => dummie => {
        try {
            return state.dummies.find(item => {
                return item[dummie];
            });
        } catch (error) {
            return undefined;
        }
    },
    /** EMPLOYERS **/
    employer: state => {
        return state.employer;
    },
    employers: state => {
        return state.employers;
    },
    applicants: state => {
        return state.applicants;
    },
    /** EMPLOYES **/
    employee: state => {
        return state.employee;
    },
    employees: state => {
        return state.employees;
    },
    /** PERSONS **/
    persons: state => {
        return state.persons;
    },
    /** Conversation & chat **/
    conversations: state => {
        return state.conversations;
    },
    chatflows: state => {
        return state.chatflows;
    },
    unread_messages_count: state => {
        return state.unread_messages_count;
    },
    notifications: state => {
        return state.notifications;
    },
    /** Work Stuff & Profile Stuff **/
    work_experiences: state => {
        return state.work_experiences;
    },
    educations: state => {
        return state.educations;
    },
    // Google maps api
    service: state => {
        return state.service;
    },
    //handle result
    callback: state => result => {
        return typeof result === "object"
            ? Object.values(result).flat()
            : result;
    }
};
export default getters;
