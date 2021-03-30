import state from "./state";
let getters = {
    guard: state => {
        return state.guard;
    },
    /************** offer ************* */

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
    deliveries: state => {
        return state.deliveries;
    }


};
export default getters;
