//* Clues
import VueRouter from "vue-router";
import Vue from "vue";

//! Global routes
import Clue from "./components/Clue";
import Login from "./components/clue/Login";
import Register from "./components/clue/Register";
import ChangePassword from "./components/clue/ChangePassword";
import Guide from "./components/pieces/Guide";
import NotFound from "./components/pieces/404";

//* Admin.
import Dashboard from "./components/admin/Dashboard";
import AdminHome from "./components/admin/Home";
import Deliveries from "./components/admin/Deliveries";
import Restaurants from "./components/admin/Restaurants";
import AdminHistoric from "./components/admin/Historic";
import AdminProfile from "./components/admin/Profile";
import AdminReports from "./components/admin/Reports";

//* delivery
import DeliveryHome from "./components/delivery/Home";
import DeliveryDashboard from "./components/delivery/Dashboard";
import DeliveryProfile from "./components/delivery/Profile";
import DeliveryHistoric from "./components/delivery/Historic";

//* Restaurant
import RestaurantDashboard from "./components/restaurant/Dashboard";
import Menu from "./components/restaurant/Menu";
import PhoneOrder from "./components/restaurant/PhoneOrder";

Vue.use(VueRouter);

const routes = [{ path: "*", name: "not-found", component: NotFound }];

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
        },
        {
            path: "/historic",
            name: "historic",
            component: AdminHistoric,
            meta: { guard: "admin" }
        },
        {
            path: "/profile",
            name: "admin-profile",
            component: AdminProfile,
            meta: { guard: "admin" }
        },
        {
            path: "/reports",
            name: "admin-reports",
            component: AdminReports,
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
            name: "restaurant-order",
            component: PhoneOrder,
            meta: { guard: "restaurant" }
        },
        {
            path: "/order",
            name: "restaurant-profile",
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
    redirect: "delivery-home",
    meta: { requiresAuth: true, guard: "delivery" },
    children: [
        {
            path: "/",
            name: "delivery-home",
            component: DeliveryHome,
            meta: { guard: "delivery" }
        },
        {
            path: "/profile",
            name: "delivery-profile",
            component: DeliveryProfile,
            meta: { guard: "delivery" }
        },
        {
            path: "/historic",
            name: "delivery-historic",
            component: DeliveryHistoric,
            meta: { guard: "delivery" }
        },
        {
            path: "/guide",
            name: "guide",
            component: Guide,
            meta: { guard: "delivery" }
        }
    ]
};

/* **********************************************************
# For avoiding duplicated routes 
# e.g: delivery profile & admin profile .. and more
# we will check for authenticated guard using local storage.
# - Vue.auth undefined not recognized not until beforeEach routes that why we used localstorage. 
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
