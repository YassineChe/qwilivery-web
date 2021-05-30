require("./bootstrap");
window.Vue = require("vue").default;

import Vue from "vue";
import vuetify from "./vuetify"; //Path to vuetify export

//? Store management
import store from "./store/index";

import router from "./routes";

//? Validate Stuff.
import {
    ValidationObserver,
    ValidationProvider,
    extend,
    localize
} from "vee-validate";

import fr from "vee-validate/dist/locale/fr.json";
import * as rules from "vee-validate/dist/rules";
//? Install VeeValidate rules and localization
Object.keys(rules).forEach(rule => {
    extend(rule, rules[rule]);
});
localize("fr", fr);
// Install VeeValidate components globally
Vue.component("ValidationObserver", ValidationObserver);
Vue.component("ValidationProvider", ValidationProvider);

//* Vuetify Dialogs
import VuetifyDialog from "vuetify-dialog";
import "vuetify-dialog/dist/vuetify-dialog.css";
Vue.use(VuetifyDialog, {
    context: {
        vuetify,
        store,
        router
    }
});

//* Google Map API
import * as VueGoogleMaps from "vue2-google-maps";
Vue.use(VueGoogleMaps, {
    load: {
        key: "AIzaSyBriQ2VfQyXzswFnsiLB8NkjmOejl77FmA"
    }
});

//* Local native browser notications
import VueNativeNotification from "vue-native-notification";
Vue.use(VueNativeNotification, {
    // Automatic permission request before
    // showing notification (default: true)
    requestOnNotify: true
});

//* Auth plugin.
import Auth from "./apis/Auth";
Vue.use(Auth);

import Callback from "./apis/Callback";
Vue.use(Callback, { VuetifyDialog });

new Vue({
    store,
    router,
    vuetify
}).$mount("#app");
