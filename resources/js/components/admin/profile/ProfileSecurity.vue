<template>
    <validation-observer ref="observer" v-slot="{ invalid }">
        <v-card
            flat
            :loading="isBusy('update-password')"
            :disabled="isBusy('update-password')"
        >
            <v-card-text>
                <v-row justify="center" align="center">
                    <v-col cols="12" sm="5" lg="5" md="5" align="center">
                        <img src="/images/svg/security.svg" height="200" />
                    </v-col>
                    <v-col>
                        <v-row>
                            <v-col cols="12">
                                <v-subheader>Ancien mot de passe</v-subheader>
                                <validation-provider
                                    v-slot="{
                                        errors
                                    }"
                                    name="Ancien mot de passe"
                                    rules="required|min:6|max:50"
                                >
                                    <v-text-field
                                        type="password"
                                        v-model="passwords.old"
                                        dense
                                        outlined
                                        prepend-inner-icon="mdi-account"
                                        :error-messages="errors"
                                        hide-details="auto"
                                        label="Ancien mot de passe"
                                    ></v-text-field>
                                </validation-provider>
                            </v-col>

                            <v-col cols="12">
                                <v-subheader>Nouveau mot de passe</v-subheader>
                                <validation-provider
                                    v-slot="{
                                        errors
                                    }"
                                    name="Nouveau mot de passe"
                                    rules="required|min:6|max:50"
                                >
                                    <v-text-field
                                        type="password"
                                        v-model="passwords.new"
                                        dense
                                        outlined
                                        prepend-inner-icon="mdi-account"
                                        :error-messages="errors"
                                        hide-details="auto"
                                        label="Nouveau mot de passe"
                                    ></v-text-field>
                                </validation-provider>
                            </v-col>
                            <!-- Confirm password -->
                            <v-col cols="12">
                                <validation-provider
                                    v-slot="{
                                        errors
                                    }"
                                    name="Confirmez le mot de passe"
                                    :rules="{
                                        required: true,
                                        min: 6,
                                        max: 50,
                                        is: passwords.new
                                    }"
                                >
                                    <v-text-field
                                        type="password"
                                        v-model="passwords.cfm"
                                        dense
                                        outlined
                                        prepend-inner-icon="mdi-account"
                                        :error-messages="errors"
                                        hide-details="auto"
                                        label="Confirmez le mot de passe"
                                    ></v-text-field>
                                </validation-provider>
                            </v-col>
                        </v-row>
                    </v-col>
                </v-row>
            </v-card-text>
            <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn
                    outlined
                    rounded
                    text
                    color="primary"
                    :disabled="invalid"
                    @click="updatePassword()"
                    >Valider</v-btn
                >
            </v-card-actions>
        </v-card>
    </validation-observer>
</template>

<script>
import { mapState } from "vuex";
export default {
    data() {
        return {
            passwords: {
                old: "",
                new: "",
                cfm: ""
            }
        };
    },
    computed: {
        ...mapState(["expected"])
    },
    methods: {
        updatePassword: function() {
            this.$store.dispatch("editData", {
                path: "/api/edit/admin/security",
                data: this.passwords,
                related: "update-password"
            });
        },
        //* The famous isBusy funtion haha
        isBusy: function(fetcher) {
            try {
                return this.$store.getters.expected(fetcher).status == "busy"
                    ? true
                    : false;
            } catch (error) {
                return false;
            }
        }
    },
    watch: {
        expected() {
            //Delete Order
            {
                let expected = this.$store.getters.expected("update-password");
                if (expected != undefined) {
                    if (expected.status === "success") {
                        this.$dialog.notify.success(
                            expected.result.subMessage,
                            {
                                position: "top-right",
                                timeout: 3000
                            }
                        );

                        this.$store.commit("CLEAR_EXPECTED");
                    }

                    if (expected.status === "error") {
                        this.$dialog.notify.error(expected.result.subMessage, {
                            position: "top-right",
                            timeout: 3000
                        });

                        this.$store.commit("CLEAR_EXPECTED");
                    }
                }
            }
        }
    }
};
</script>
