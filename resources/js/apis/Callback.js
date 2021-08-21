export default function(Vue) {
    Vue.callback = {
        handler: (
            dialog,
            expected,
            callagain = null,
            timeout = 5000,
            position = "top-right"
        ) => {
            if (expected != undefined && typeof expected.result == "object") {
                if (expected.result.subMessage != undefined) {
                    expected.result.subMessage.forEach(msg => {
                        dialog.notify[expected.result.type](msg, {
                            position: position,
                            timeout: timeout
                        });
                    });
                }

                if (callagain != null) {
                    if (callagain.hasOwnProperty("router")) {
                        if (callagain.router.incase == expected.result.type) {
                            callagain.router.instance.push({
                                name: callagain.router.to
                            });
                        }
                    }

                    //Clear the expected!
                    if (
                        callagain.hasOwnProperty("clear") &&
                        callagain.clear &&
                        expected.result.type != undefined
                    ) {
                        callagain.store.commit("CLEAR_EXPECTED");
                    }

                    //Execute a function
                    if (
                        callagain.hasOwnProperty("execute") &&
                        callagain.execute.incase == expected.status
                    )
                        callagain.execute.func();

                    //Execute function whatever
                    if (
                        callagain.hasOwnProperty("whatever") &&
                        expected.status != "busy"
                    ) {
                        callagain.whatever.func();
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