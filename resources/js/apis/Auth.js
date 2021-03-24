export default function(Vue) {
    Vue.auth = {
        //setToken
        setToken: (token, guard) => {
            localStorage.setItem("token", token);
            localStorage.setItem("guard", guard);
        },
        // get authenticated data.
        getToken: () => {
            let token = localStorage.getItem("token");
            let guard = localStorage.getItem("guard");

            if (token == null && guard == null) return false;
            else
                return {
                    guard: guard,
                    token: token
                };
        },

        //is authenticated
        isAuthenticated: whichGuard => {
            let auth = Vue.auth.getToken();
            if (!auth || auth.guard != whichGuard) return false;
            else return true;
        },

        guard: () => {
            return localStorage.getItem("guard");
        },

        signOut: () => {
            localStorage.removeItem("token");
            localStorage.removeItem("guard");

            window.location.href = "/";
        }
    };

    // Prototype
    Object.defineProperties(Vue.prototype, {
        $auth: {
            get: () => {
                return Vue.auth;
            }
        }
    });
}
