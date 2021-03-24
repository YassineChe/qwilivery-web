//* Clues
import VueRouter from "vue-router";
import Vue from "vue";

//! Global routes
import Clue from "./components/Clue";
import Login from "./components/clue/Login";

Vue.use(VueRouter);

//? Global routes
const routes = [
    //Clue (Control Index's)
    { path: "/", name: "clue", component: Clue, meta: { requiresAuth: false } },
    { path: "/login", name: "login", component: Login },
];

const employerRoutes = {};
const employeeRoutes = {};
const agencyRoutes = {};

/* **********************************************************
# For avoiding duplicated routes 
# e.g: employer profile & employer profile .. and more
# we will check for authenticated guard using local storage.
# - Vue.auth undefined not recognized not until beforeEach routes that we used localstorage. 
# We will push to routes const only the routes concerned
********************************************************** */

const router = new VueRouter({
    routes,
    mode: "history",
    linkExactActiveClass: "",
    linkActiveClass: "active",
});

// router.beforeEach((to, from, next) => {
//     if (to.matched.some((route) => route.meta.requiresAuth)) {
//         if (Vue.auth.isAuthenticated(to.meta.guard)) {
//             next();
//         } else {
//             next({ name: "login" });
//         }
//     } else {
//         // Logic here.
//     }
//     next();
// });

export default router;
