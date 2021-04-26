//* Clues
import VueRouter from "vue-router";
import Vue from "vue";

//! Global routes
import Clue from "./components/Clue";
import Login from "./components/clue/Login";
import Register from "./components/clue/Register";
import ChangePassword from "./components/clue/ChangePassword";

//* Admin.
import Dashboard from "./components/admin/Dashboard";
import AdminHome from "./components/admin/AdminHome";
import Deliveries from "./components/admin/Deliveries";
import Restaurants from "./components/admin/Restaurants";
//* delivery
import DeliveryDashboard from "./components/delivery/DeliveryDashboard";
import Profile from "./components/delivery/Profile";

//* Restaurant
import RestaurantDashboard from "./components/restaurant/RestuarantDashboard";
import Menu from "./components/restaurant/Menu";
import PhoneOrder from "./components/restaurant/PhoneOrder";

Vue.use(VueRouter);

const routes = [];

//? Global routes
const LoginRoutes = [
    //Clue (Control Index's)
    { path: "/login", name: "login", component: Login },
    { path: "/register", name: "register", component: Register },
    {
        path: "/",
        name: "clue",
        component: Clue,
        meta: { requiresAuth: false }
    },
    {
        path: "/reset-password/:id",
        name: "password-reset",
        component: ChangePassword
    }
];

//? Admin Dashboard
const adminRoutes = {
    path: "/",
    name: "dashboard",
    component: Dashboard,
    name: "admin-dashboard",
    redirect: "admin-home-dashboard", //This will redirect to home of dashboard!,
    meta: { requiresAuth: true, guard: "admin" },
    children: [
        {
            path: "/",
            name: "admin-home-dashboard",
            component: AdminHome,
            meta: { guard: "admin" }
        },
        {
            path: "/deliveries",
            name: "deliveries",
            component: Deliveries,
            meta: { guard: "admin" }
        },
        {
            path: "/restaurants",
            name: "restaurants",
            component: Restaurants,
            meta: { guard: "admin" }
        }
    ]
};

//? Restaurant Dashboard
const restaurantRoutes = {
    path: "/",
    name: "dashboard",
    component: RestaurantDashboard,
    name: "Dashboard",
    meta: { requiresAuth: true, guard: "restaurant" },
    children: [
        {
            path: "/restaurant-menu",
            name: "menu",
            component: Menu,
            meta: { guard: "restaurant" }
        },
        {
            path: "/order",
            name: "order",
            component: PhoneOrder,
            meta: { guard: "restaurant" }
        }
    ]
};

//? Delivery Dashboard

const deliveryRoutes = {
    path: "/",
    name: "dashboard",
    component: DeliveryDashboard,
    name: "Dashboard",
    meta: { requiresAuth: true, guard: "delivery" },
    children: [
        {
            path: "/profile",
            name: "profile",
            component: Profile,
            meta: { guard: "delivery" }
        }
    ]
};

/* **********************************************************
# For avoiding duplicated routes 
# e.g: employer profile & employer profile .. and more
# we will check for authenticated guard using local storage.
# - Vue.auth undefined not recognized not until beforeEach routes that we used localstorage. 
# We will push to routes const only the routes concerned
********************************************************** */

switch (localStorage.getItem("guard")) {
    case "admin":
        routes.push(adminRoutes);
        break;
    case "restaurant":
        routes.push(restaurantRoutes);
        break;
    case "delivery":
        routes.push(deliveryRoutes);
        break;
    case null:
        routes.push(...LoginRoutes);
        break;
}

const router = new VueRouter({
    routes,
    mode: "history",
    linkExactActiveClass: "",
    linkActiveClass: "active"
});

router.beforeEach((to, from, next) => {
    if (to.matched.some(route => route.meta.requiresAuth)) {
        if (Vue.auth.isAuthenticated(to.meta.guard)) {
            next();
        } else {
            next({ name: "login" });
        }
    } else {
        // Logic here.
    }
    next();
});

export default router;
