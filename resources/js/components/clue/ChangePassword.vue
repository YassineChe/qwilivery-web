<template>
    <validation-observer ref="observer" v-slot="{ invalid }">
        <v-container fluid class="mt-16">
            <v-row align="center" justify="center">
                <v-col align="center">
                    <v-card max-width="600">
                        <v-card-text>
                            <v-row>
                                <v-col cols="12">
                                    <!-- HeadLine -->
                                    <Headline
                                        headline="Récupérer votre mot de passe"
                                        :headline-classes="[
                                            'text-h5',
                                            'grey--text text--darken-2'
                                        ]"
                                    >
                                        <template #subheadline>
                                            <p>
                                                Insérez votre nouveau mot de
                                                passe et confirmez-le
                                            </p>
                                        </template>
                                    </Headline>
                                </v-col>
                                <!-- Password -->
                                <v-col cols="12">
                                    <validation-provider
                                        v-slot="{
                                            errors
                                        }"
                                        name="Nouveau mot de passe"
                                        rules="required|min:6|max:50"
                                    >
                                        <v-text-field
                                            dense
                                            hide-details="auto"
                                            type="password"
                                            outlined
                                            label="Mot de passe"
                                            :error-messages="errors"
                                            v-model="credentials.password"
                                        ></v-text-field>
                                    </validation-provider>
                                </v-col>
                                <!-- Confrim password -->
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
                                            is: credentials.password
                                        }"
                                    >
                                        <v-text-field
                                            dense
                                            hide-details="auto"
                                            type="password"
                                            outlined
                                            label="Confirmez le mot de passe"
                                            :error-messages="errors"
                                            v-model="credentials.confirm"
                                        ></v-text-field>
                                    </validation-provider>
                                </v-col>

                                <v-col cols="12">
                                    <v-btn
                                        color="primary"
                                        block
                                        :loading="isBusy('passwordRecovery')"
                                        :disabled="invalid"
                                        @click="passwordRecovery()"
                                    >
                                        <v-icon left> mdi-lock-check </v-icon
                                        >Réinitialiser
                                    </v-btn>
                                </v-col>
                            </v-row>
                        </v-card-text>
                    </v-card>
                </v-col>
            </v-row>
        </v-container>
    </validation-observer>
</template>

<script>
import Headline from "../pieces/Headline";
//libs
import { mapState } from "vuex";
export default {
    components: {
        Headline
    },
    data() {
        return {
            credentials: {
                password: "",
                confirm: ""
            }
        };
    },

    computed: {
        ...mapState(["expected"]),
        //* Is mobile
        isMobile: function() {
            return this.$vuetify.breakpoint.smAndDown;
        }
    },
    methods: {
        //* Login
        passwordRecovery: function() {
            this.$store.dispatch("postData", {
                path: "/api/reset-password",
                data: {
                    ...this.credentials,
                    token: window.location.pathname.slice(16)
                },
                related: "reset-password"
                // redirect_to: "/",
            });
        },
        // Overlly
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
            {
                this.$callback.handler(
                    this.$dialog,
                    this.$store.getters.expected("reset-password"),
                    {
                        router: {
                            instance: this.$router,
                            incase: "success",
                            to: "login"
                        }
                    }
                );
            }
        }
    }
};
</script>

<style lang="scss" scoped></style>
