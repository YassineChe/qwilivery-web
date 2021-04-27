import Vue from "vue";
import Vuetify from "vuetify";
Vue.use(Vuetify);

import "vuetify/dist/vuetify.min.css";
import "@mdi/font/css/materialdesignicons.css";
import colors from "vuetify/lib/util/colors";

export default new Vuetify({
    icons: {
        iconfont: "mdi" // default - only for display purposes
    },
    theme: {
        themes: {
            light: {
                primary: "#7367f0",
                secondary: "#EEEDFD",
                bold: "#01245C",
                accent: "#556080",
                background: "#F5F5F6"
            },
            dark: false
        }
    }
});
