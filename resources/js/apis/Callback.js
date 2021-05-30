export default function(Vue) {
    Vue.callback = {
        handler: (
            dialog,
            expected,
            callagain = null,
            timeout = 3000,
            position = "top-right"
        ) => {
            if (
                expected != undefined &&
                typeof expected.result == "object" &&
                expected.result.subMessage != undefined
            ) {
                expected.result.subMessage.forEach(msg => {
                    dialog.notify[expected.result.type](msg, {
                        position: position,
                        timeout: timeout
                    });
                });

                //Clear expected here!
                if (callagain != null) {
                    //Clear the expected!
                    callagain.store.commit("CLEAR_EXPECTED");
                    //if the callback has own property called path so wee need to call something!
                    if (callagain.hasOwnProperty("path")) {
                        callagain.store.dispatch("fetchData", {
                            path: callagain.path,
                            mutation: callagain.mutation,
                            related: callagain.related
                        });
                    }
                }
            }
        }
    };

    Object.defineProperties(Vue.prototype, {
        $callback: {
            get: () => {
                return Vue.callback;
            }
        }
    });
}
