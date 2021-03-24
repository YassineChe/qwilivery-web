import axios from "axios";
import token from "../apis/token";

let actions = {
    async expected({ commit }, payload) {
        commit("SET_EXPECTED_STATUS", {
            status: payload.status,
            result: payload.result,
            related: payload.related
        });
    },

    //* Login (create a token).
    async signin({ dispatch }, payload) {
        //? Init action status.
        dispatch("expected", {
            status: "busy",
            result: [],
            related: payload.related
        });
        await axios
            .post(payload.path, payload.data)
            .then(response => {
                //Store token and guard
                Vue.auth.setToken(
                    response.data["token"],
                    response.data["guard"]
                );

                //Redirect
                // window.location.href = payload.redirect_to;
            })
            .catch(err => {
                dispatch("expected", {
                    status: "error",
                    result: err.response.data,
                    related: payload.related
                });
            });
    },

    //* Logout
    async singout({ }, payload) {
        //? Init action status.
        await axios
            .post(payload.path, payload.data, {
                headers: {
                    Accept: "application/json",
                    Authorization: `Bearer ${token.getToken()}`
                }
            })
            .then(() => {
                // remove token & guard.
                localStorage.removeItem("token");
                localStorage.removeItem("guard");
                //redirect to home
                window.location.href = "/";
            })
            .catch(() => { });
    },

    //* Fetch one or multiple records
    async fetchData({ dispatch, commit }, payload) {
        //? Init action status.
        dispatch("expected", {
            status: "busy",
            result: [],
            related: payload.related
        });
        //? Communicate with the server.
        // Second request

        axios
            .get(payload.path, {
                headers: {
                    Accept: "application/json",
                    Authorization: `Bearer ${token.getToken()}`
                },
                params: {
                    data: payload.data
                }
            })
            .then(response => {
                // debugger
                dispatch("expected", {
                    status: "success",
                    result: [],
                    related: payload.related
                });
                // Sometime we don't need to asign incoming data (ex: verification email). so we don't this line
                if (payload.mutation != false)
                    commit(payload.mutation, response.data);
            })
            .catch(err => {
                dispatch("expected", {
                    status: "error",
                    result: err.response.data,
                    related: payload.related
                });

                if (payload.mutation == "FETCH_GUARD")
                    commit(payload.mutation, err.response.data);
            });
    },

    //* This is for multiples requests.
    async multipleFetch({ dispatch }, requests) {
        await axios
            .all(requests)
            .then(
                axios.spread((...responses) => {
                    responses.forEach(response => {
                        //Set timer between request.
                        setTimeout(() => {
                            dispatch("fetchData", response);
                        }, 200);
                    });
                })
            )
            .catch();
    },

    //* This add records or trigger something
    async postData({ dispatch }, payload) {
        //? Init action status.
        dispatch("expected", {
            status: "busy",
            result: [],
            related: payload.related
        });

        return await axios
            .post(payload.path, payload.data, {
                headers: {
                    Accept: "application/json",
                    Authorization: `Bearer ${token.getToken()}`
                }
            })
            .then(response => {
                dispatch("expected", {
                    status: "success",
                    result: response.data,
                    related: payload.related
                });

                if (payload.returned) return true;
            })
            .catch(err => {
                dispatch("expected", {
                    status: "error",
                    result: err.response.data,
                    related: payload.related
                });
                if (payload.returned) return false;
            });
    },

    //* Delete Data
    async deleteData({ dispatch }, payload) {
        dispatch("expected", {
            status: "busy",
            result: [],
            related: payload.related
        });

        await axios
            .delete(payload.path, {
                headers: {
                    Accept: "application/json",
                    Authorization: `Bearer ${token.getToken()}`
                },
                data: payload.data
            })
            .then(response => {
                dispatch("expected", {
                    status: "success",
                    result: response.data,
                    related: payload.related
                });
            })
            .catch(err => {
                dispatch("expected", {
                    status: "error",
                    result: err.response.data,
                    related: payload.related
                });
            });
    },

    //* Fetch particular (this for particlar used with modal)
    async fetchParticular({ dispatch }, payload) {
        //? Init action status.
        dispatch("expected", {
            status: "busy",
            result: [],
            related: payload.related
        });

        return await axios
            .get(payload.path, {
                headers: {
                    Accept: "application/json",
                    Authorization: `Bearer ${token.getToken()}`
                },
                params: {
                    data: payload.data
                }
            })
            .then(response => {
                dispatch("expected", {
                    status: "success",
                    result: [],
                    related: payload.related
                });
                return response.data;
            })
            .catch(err => {
                dispatch("expected", {
                    status: "error",
                    result: err.response.data,
                    related: payload.related
                });
                return err.response.data;
            });
    },

    //* Patch used to update entity when u send only the field that need updated
    async patchData({ dispatch }, payload) {
        //? Init action status.
        dispatch("expected", {
            status: "busy",
            result: [],
            related: payload.related
        });

        await axios
            .patch(payload.path, payload.data, {
                headers: {
                    Accept: "application/json",
                    Authorization: `Bearer ${token.getToken()}`
                }
            })
            .then(response => {
                dispatch("expected", {
                    status: "success",
                    result: response.data,
                    related: payload.related
                });
            })
            .catch(err => {
                dispatch("expected", {
                    status: "error",
                    result: err.response.data,
                    related: payload.related
                });
            });
    },

    async editData({ dispatch }, payload) {
        //TODO 1 : Set the status progress, until axios finish
        //? Init action status.
        dispatch("expected", {
            status: "busy",
            result: [],
            related: payload.related
        });

        //TODO 2 : Get Data from server.
        await axios
            .put(payload.path, payload.data, {
                headers: {
                    Accept: "application/json",
                    Authorization: `Bearer ${token.getToken()}`
                }
            })
            .then(response => {
                dispatch("expected", {
                    status: "success",
                    result: response.data,
                    related: payload.related
                });
            })
            .catch(err => {
                dispatch("expected", {
                    status: "error",
                    result: err.response.data,
                    related: payload.related
                });
            });
    }
    //End of file
};

export default actions;
