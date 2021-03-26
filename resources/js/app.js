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
    localize,
} from "vee-validate";

import fr from "vee-validate/dist/locale/fr.json";
import * as rules from "vee-validate/dist/rules";
//? Install VeeValidate rules and localization
Object.keys(rules).forEach((rule) => {
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

//* Auth plugin.
import Auth from "./apis/Auth";
Vue.use(Auth);

new Vue({
    store,
    router,
    vuetify,
}).$mount("#app");
