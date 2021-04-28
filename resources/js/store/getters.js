import state from "./state";
let getters = {
    /************** guard ************* */
    guard: state => {
        return state.guard;
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
    /************** DELIVERIES ************* */
    deliveries: state => {
        return state.deliveries;
    },
    /********** CATEGORIES & VARIANTS ********* */
    categories: state => {
        return state.categories;
    },
    /************** RESTAURANTS ************* */
    restaurants: state => {
        return state.restaurants;
    },
    /********** ORDER & PREORDER ********* */
    preorders: state => {
        return state.preorders;
    },
    orders: state => {
        return state.orders;
    },
    /********** PROFILE & REPORTS ********* */
    profile: state => {
        return state.profile;
    },
    reports: state => {
        return state.reports;
    },
    //handle result
    callback: state => result => {
        return typeof result === "object"
            ? Object.values(result).flat()
            : result;
    }
};
export default getters;
