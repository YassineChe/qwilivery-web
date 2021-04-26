<template>
    <!-- Information personnel -->
    <v-card elevation="7" flat v-if="user" :loading="isBusy('edit-password')">
        <validation-observer ref="observer" v-slot="{ invalid }">
            <v-container class="grey lighten-5 mb-6">
                <v-row no-gutters style="height: 150px">
                    <v-col>
                        <validation-provider
                            v-slot="{ errors }"
                            name="le mot de passe"
                            rules="required|min:6"
                        >
                            <v-text-field
                                id="password"
                                solo
                                clearable
                                :error-messages="errors"
                                label="Password"
                                name="password"
                                prepend-inner-icon="mdi-lock"
                                v-model="password.new"
                                :append-icon="
                                    newpass ? 'mdi-eye' : 'mdi-eye-off'
                                "
                                :type="newpass ? 'text' : 'password'"
                                @click:append="newpass = !newpass"
                            ></v-text-field>
                        </validation-provider>

                        <v-row no-gutters>
                            <v-col>
                                <validation-provider
                                    v-slot="{ errors }"
                                    name="Confirmez le mot de passe"
                                    rules="required|min:6"
                                >
                                    <v-text-field
                                        id="password"
                                        solo
                                        clearable
                                        :error-messages="errors"
                                        label="Confirmez le mot de passe"
                                        name="password"
                                        prepend-inner-icon="mdi-lock"
                                        v-model="password.confirm"
                                        :append-icon="
                                            confirm ? 'mdi-eye' : 'mdi-eye-off'
                                        "
                                        :type="confirm ? 'text' : 'password'"
                                        @click:append="confirm = !confirm"
                                    ></v-text-field>
                                </validation-provider>
                            </v-col>
                        </v-row>
                    </v-col>
                    <v-col class="ml-3">
                        <validation-provider
                            v-slot="{ errors }"
                            name="Ancien mot de passe"
                            rules="required|min:6"
                        >
                            <v-text-field
                                id="password"
                                solo
                                :error-messages="errors"
                                label="Ancien mot de passe"
                                name="password"
                                prepend-inner-icon="mdi-lock-open-remove"
                                v-model="password.old"
                                :append-icon="old ? 'mdi-eye' : 'mdi-eye-off'"
                                :type="old ? 'text' : 'password'"
                                @click:append="old = !old"
                            ></v-text-field>
                        </validation-provider>
                    </v-col>
                </v-row>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn
                        :disabled="invalid"
                        elevation="7"
                        color="primary"
                        @click="updatePassword()"
                    >
                        mise à jour
                    </v-btn>
                </v-card-actions>
            </v-container>
        </validation-observer>
    </v-card>
</template>
<script>
import { mapState } from "vuex";
export default {
    data() {
        return {
            tab: 1,
            confirm: false,
            old: false,
            newpass: false,
            password: {
                old: null,
                new: null,
                confirm: null
            }
        };
    },
    computed: {
        ...mapState(["expected"]),
        user: function() {
            return this.$store.getters.guard;
        }
    },
    watch: {
        expected() {
            {
                let expected = this.$store.getters.expected("edit-admin");
                if (expected != undefined) {
                    //If sucess
                    if (expected.status === "success") {
                        this.$dialog.message.success(
                            expected.result.subMessage,
                            {
                                position: "bottom",
                                timeout: 2000
                            }
                        );
                    }
                }
            }
            {
                let expected = this.$store.getters.expected("edit-password");
                if (expected != undefined) {
                    //If sucess
                    if (expected.result.status === "success") {
                        console.log(expected);
                        this.$dialog.message.success(
                            expected.result.subMessage,
                            {
                                position: "top-right",
                                timeout: 2000
                            }
                        );
                    }
                    if (expected.result.status === "errors") {
                        this.$dialog.notify.error(expected.result.subMessage, {
                            position: "top-right",
                            timeout: 2000
                        });
                    }
                }
            }
        }
    },
    methods: {
        //* Edit passsword.
        updatePassword: function() {
            return this.$store.dispatch("editData", {
                path: `/api/edit/delivery/password`,
                data: this.password,
                related: `edit-password`,
                returned: true
            });
        },
        //* The famous is busy function
        isBusy: function(fetcher) {
            try {
                return this.$store.getters.expected(fetcher).status == "busy"
                    ? true
                    : false;
            } catch (error) {
                return false;
            }
        }
    }
};
</script>
