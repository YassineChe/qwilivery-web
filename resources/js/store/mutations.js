import { trimEnd } from "lodash";
import getters from "./getters";
import state from "./state";
let mutations = {
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
    /************** DELIVERY **************** */
    FETCH_DELIVERIES(state, deliveries) {
        return (state.deliveries = deliveries);
    },
    ADD_DELIVERY(state, deliveries) {
        return state.deliveries.push(deliveries);
    },
    DELETE_DELIVERY(state, delivery_id) {
        state.deliveries.splice(
            state.deliveries.indexOf(
                state.deliveries.find(delivery => delivery.id == delivery_id)
            ),
            1
        );
    },
    BLOCK_DELIVERY(state, delivery_id) {
        state.deliveries.find(delivery => {
            if (delivery.id == delivery_id) delivery.blocked_at = Date.now();
        });
    },
    UNBLOCK_DELIVERY(state, delivery_id) {
        state.deliveries.find(delivery => {
            if (delivery.id == delivery_id) delivery.blocked_at = null;
        });
    },
    /********* Categories & variants ******** */
    FETCH_CATEGORIES(state, categories) {
        return (state.categories = categories);
    },
    FETCH_VARIANTS(state, variants) {
        return (state.variants = variants);
    },
    /*********** Guard *********** */
    FETCH_RESTAURANTS(state, restaurants) {
        return (state.restaurants = restaurants);
    },
    DELETE_RESTAURANT(state, restaurant_id) {
        state.restaurants.splice(
            state.restaurants.indexOf(
                state.restaurants.find(
                    restaurant => restaurant.id == restaurant_id
                )
            ),
            1
        );
    },
    BLOCK_RESTAURANT(state, restaurant_id) {
        state.restaurants.find(restaurant => {
            if (restaurant.id == restaurant_id)
                restaurant.blocked_at = Date.now();
        });
    },
    UNBLOCK_RESTAURANT(state, restaurant_id) {
        state.restaurants.find(restaurant => {
            if (restaurant.id == restaurant_id) restaurant.blocked_at = null;
        });
    },
    /********* ORDER & PREORDERS ******** */
    FETCH_PREORDERS(state, preorders) {
        return (state.preorders = preorders);
    },
    FETCH_ORDERS(state, orders) {
        return (state.orders = orders);
    },
    /********* PROFILE & REPORTS ******** */
    FETCH_PROFILE(state, profile) {
        return (state.profile = profile);
    },
    FETCH_REPORTS(state, reports) {
        return (state.reports = reports);
    },
    /************** EXPRESS DELIVERIES **************/
    EXPRESS_DELIVERIES(state, expresses){
        return (state.expresses = expresses);
    },
    /************** CHATFLOWS **************/
    FETCH_CHATFLOWS(state, chatflows) {
        return (state.chatflows = chatflows);
    },
    FETCH_CONVERSATIONS(state, conversations) {
        return (state.conversations = conversations);
    },
    /************** PERSONS ************* */
    FETCH_PERSONS(state, persons) {
        return (state.persons = persons);
    },
     /************** SETTINGS ************* */
     async FETCH_SETTINGS(state, settings) {
        return (state.settings = settings);
    }
};
export default mutations;
