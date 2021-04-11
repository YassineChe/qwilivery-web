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
    /************** deliveries ************* */
    deliveries: state => {
        return state.deliveries;
    },
    /********* Categories & Meals ********** */
    categories: state => {
        return state.categories;
    },
    meals: state => {
        return state.meals;
    },
    /************** restaurant ************* */
    restaurants: state => {
        return state.restaurants;
    },
    //handle result
    callback: state => result => {
        return typeof result === "object"
            ? Object.values(result).flat()
            : result;
    }
};
export default getters;
