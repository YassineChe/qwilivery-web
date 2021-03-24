import { trimEnd } from "lodash";
import getters from "./getters";
import state from "./state";
let mutations = {
    /************** OFFer ************* */
    FETCH_OFFERS(state, offers) {
        return (state.offers = offers);
    },
    DELETE_OFFER(state, offer_id) {
        state.offers.splice(
            state.offers.indexOf(
                state.offers.find(offer => offer.id == offer_id)
            ),
            1
        );
    },
    FETCH_OFFER(state, offer) {
        state.offer = offer;
    },
    BLOCK_OFFER(state, offer_id) {
        state.offers.find(offer => {
            if (offer.id == offer_id) offer.blocked_at = Date.now();
        });
    },
    UNBLOCK_OFFER(state, offer_id) {
        state.offers.find(offer => {
            if (offer.id == offer_id) offer.blocked_at = null;
        });
    },
    OFFER_APPROVED(state, offer_id) {
        state.offers.find(offer => {
            if (offer.id == offer_id) offer.approved_at = Date.now();
        });
    },
    SUSPEND_OFFER(offer_id) {
        state.offers.find(offer => {
            if (offer.id == offer_id) offer.status = 0;
        });
    },
    UNSUSPEND_OFFER(offer_id) {
        state.offers.find(offer => {
            if (offer.id == offer_id) offer.status = 1;
        });
    },
    /************** ACTIONS ************* */
    async SET_EXPECTED_STATUS(state, { status, result, related }) {
        //Get the index of exsiting mExpected status.
        let index = await state.expected.indexOf(
            state.expected.find(expected => expected.related == related)
        );
        //Delete it.
        if (~index) state.expected.splice(index, 1);

        //Push it to the mExpected.
        return state.expected.push({ status, result, related });
    },
    DELETE_EXPECTED_STATUS(state, related) {
        state.expected.splice(
            state.expected.indexOf(
                state.expected.find(expected => expected.related == related)
            ),
            1
        );
    },
    CLEAR_EXPECTED(state) {
        state.expected = [];
    },
    /*********** Guard *********** */
    async FETCH_GUARD(state, guard) {
        return (state.guard = guard);
    },
    /*********** PROVINCES *********** */
    FETCH_PROVINCES(state, provinces) {
        return (state.provinces = provinces);
    },
    /************** RESSOURCES ************* */
    async FETCH_DUMMY(state, dummies) {
        //Search for duplicate object
        let index = await state.dummies.indexOf(
            state.dummies.find(
                ressource =>
                    Object.keys(ressource)[0] == Object.keys(dummies)[0]
            )
        );
        //Remove it from state
        if (~index) state.dummies.splice(index, 1);
        //Push the new one
        return state.dummies.push(dummies);
    },
    /************** EMPLOYER ************* */
    FETCH_EMPLOYER(state, employer) {
        return (state.employer = employer);
    },
    FETCH_EMPLOYERS(state, employers) {
        return (state.employers = employers);
    },
    DELETE_EMPLOYER(state, employer_id) {
        state.employers.splice(
            state.employers.indexOf(
                state.employers.find(employer => employer.id == employer_id)
            ),
            1
        );
    },
    BLOCK_EMPLOYER(state, employer_id) {
        state.employers.find(employer => {
            if (employer.id == employer_id) employer.blocked_at = Date.now();
        });
    },
    UNBLOCK_EMPLOYER(state, employer_id) {
        state.employers.find(employer => {
            if (employer.id == employer_id) employer.blocked_at = null;
        });
    },
    FETCH_APPLICANTS(state, applicants) {
        state.applicants = applicants;
    },
    /************** EMPLOYEE ************* */
    FETCH_EMPLOYEE(state, employee) {
        return (state.employee = employee);
    },
    FETCH_EMPLOYEES(state, employees) {
        return (state.employees = employees);
    },
    DELETE_EMPLOYEE(state, employee_id) {
        state.employees.splice(
            state.employees.indexOf(
                state.employees.find(employee => employee.id == employee_id)
            ),
            1
        );
    },
    BLOCK_EMPLOYEE(state, employee_id) {
        state.employees.find(employee => {
            if (employee.id == employee_id) employee.blocked_at = Date.now();
        });
    },
    UNBLOCK_EMPLOYEE(state, employee_id) {
        state.employees.find(employee => {
            if (employee.id == employee_id) employee.blocked_at = null;
        });
    },
    /************** PERSONS ************* */
    FETCH_PERSONS(state, persons) {
        return (state.persons = persons);
    },
    /******* CONVERSATIONS & CHAT ******* */
    FETCH_CONVERSATIONS(state, conversations) {
        return (state.conversations = conversations);
    },
    FETCH_CHATFLOWS(state, chatflows) {
        return (state.chatflows = chatflows);
    },
    FETCH_NOTIFICATIONS(state, notifications) {
        return (state.notifications = notifications);
    },
    MARK_ALL_NOTIFICATIONS_AS_READ(state) {
        state.notifications.map(
            notification => (notification.read_at = Date.now())
        );
    },
    MARK_NOTIFICATION_AS_READ(state, notfication_id) {
        state.notifications.find(notification => {
            if (notification.id == notfication_id)
                notification.read_at = Date.now();
        });
    },
    /******* Work stuff  & Profile Stuff ******* */
    FETCH_WORK_EXPERIENCES(state, work_experiences) {
        return (state.work_experiences = work_experiences);
    },
    DELETE_WORK_EXPERIENCE(state, experience_id) {
        state.work_experiences.splice(
            state.work_experiences.indexOf(
                state.work_experiences.find(
                    experience => experience.id == experience_id
                )
            ),
            1
        );
    },
    FETCH_EDUCATIONS(state, educations) {
        return (state.educations = educations);
    },
    DELETE_EDUCATION(state, education_id) {
        state.educations.splice(
            state.educations.indexOf(
                state.educations.find(education => education.id == education_id)
            ),
            1
        );
    },
    FETCH_UNREAD_MESSAGES_COUNT(state, unread_messages_count) {
        state.unread_messages_count = unread_messages_count;
    },
    //Service Google maps api
    service(state, service) {
        state.service = service;
    }
};
export default mutations;
