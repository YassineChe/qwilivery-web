<template>
    <v-container fluid fill-height class="background ma-0 pa-0">
        <v-row class="fill-height">
            <v-col cols="12" sm="8" md="8" lg="8" v-if="!isMobile">
                <v-container fill-height fluid>
                    <v-row align="center" justify="center">
                        <v-col align="center">
                            <v-img
                                v-if="!restPasword"
                                src="images/svg/fingerprint.svg"
                                height="450px"
                                width="550px"
                            />
                            <v-img
                                v-if="restPasword"
                                src="images/svg/forgot-password.svg"
                                height="450px"
                                width="550px"
                            />
                        </v-col>
                    </v-row>
                </v-container>
            </v-col>
            <v-col cols="12" sm="12" md="4" lg="4" class="my-0 py-0">
                <v-card tile class="fill-height" flat>
                    <v-container fill-height fluid>
                        <v-row
                            align="center"
                            justify="center"
                            class="pa-10 ma-0"
                        >
                            <v-col align="center">
                                <!-- Login  -->
                                <validation-observer
                                    ref="observer"
                                    v-slot="{ invalid }"
                                >
                                    <v-layout column v-if="!restPasword">
                                        <v-flex mt-5>
                                            <!-- HeadLine -->
                                            <Headline
                                                headline="Bienvenue, Qwilivery👋"
                                                :headline-classes="[
                                                    'text-h5',
                                                    'grey--text text--darken-2'
                                                ]"
                                            >
                                                <template #subheadline>
                                                    <span
                                                        class="grey--text text--darken-1"
                                                    >
                                                        Connectez-vous à votre
                                                        compte
                                                    </span>
                                                </template>
                                            </Headline>
                                        </v-flex>
                                        <v-flex mt-5>
                                            <validation-provider
                                                v-slot="{
                                                    errors
                                                }"
                                                name="E-mail"
                                                rules="required|max:50"
                                            >
                                                <v-text-field
                                                    :error-messages="errors"
                                                    dense
                                                    hide-details="auto"
                                                    outlined
                                                    label="E-mail"
                                                    placeholder="your@email.com"
                                                    v-model="credentials.email"
                                                ></v-text-field>
                                            </validation-provider>
                                        </v-flex>
                                        <v-flex mt-3 align-self-end>
                                            <v-btn
                                                text
                                                small
                                                @click="
                                                    restPasword = !restPasword
                                                "
                                                >Mot de passe oublié ?</v-btn
                                            >
                                        </v-flex>
                                        <v-flex mt-1>
                                            <validation-provider
                                                v-slot="{
                                                    errors
                                                }"
                                                name="Ancien mot de passe"
                                                rules="required|min:6|max:50"
                                            >
                                                <v-text-field
                                                    :error-messages="errors"
                                                    dense
                                                    hide-details="auto"
                                                    type="password"
                                                    outlined
                                                    label="Mot de passe"
                                                    placeholder="Mot de passe"
                                                    v-model="
                                                        credentials.password
                                                    "
                                                ></v-text-field>
                                            </validation-provider>
                                        </v-flex>
                                        <v-flex mt-5>
                                            <v-btn
                                                color="primary"
                                                block
                                                elevation="0"
                                                :disabled="invalid"
                                                @click="doLogin()"
                                                >Se Connecter</v-btn
                                            >
                                        </v-flex>
                                        <v-flex mt-5>
                                            <v-divider />
                                        </v-flex>
                                        <v-flex mt-3>
                                            <small>
                                                Vous n'avez pas un compte?
                                                <router-link to="register"
                                                    >Créer un
                                                    compte</router-link
                                                >
                                            </small>
                                        </v-flex>
                                    </v-layout>
                                </validation-observer>

                                <!-- Mot de passe oublié? -->
                                <v-layout column v-if="restPasword">
                                    <v-flex mt-5>
                                        <!-- HeadLine -->
                                        <Headline
                                            headline="Mot de passe oublié? "
                                            :headline-classes="[
                                                'text-h5',
                                                'grey--text text--darken-2'
                                            ]"
                                        >
                                            <template #subheadline>
                                                <span
                                                    class="grey--text text--darken-1"
                                                >
                                                    Vous recevrez un email avec
                                                    le lien de récupération
                                                </span>
                                            </template>
                                        </Headline>
                                    </v-flex>
                                    <v-flex mt-5>
                                        <v-text-field
                                            dense
                                            hide-details="auto"
                                            outlined
                                            label="E-mail"
                                            placeholder="your@email.com"
                                            v-model="emailRest"
                                        ></v-text-field>
                                    </v-flex>
                                    <v-flex mt-5>
                                        <v-btn
                                            color="primary"
                                            block
                                            elevation="0"
                                            :loading="isBusy('reset')"
                                            @click="restPassword()"
                                            >Envoyer un lien de
                                            réinitialisation</v-btn
                                        >
                                    </v-flex>
                                    <v-flex mt-5>
                                        <v-divider />
                                    </v-flex>
                                    <v-flex mt-3>
                                        <small>
                                            <v-btn
                                                color="primary"
                                                text
                                                @click="
                                                    restPasword = !restPasword
                                                "
                                            >
                                                {{ ` Retour connexion` }}</v-btn
                                            >
                                        </small>
                                    </v-flex>
                                </v-layout>
                            </v-col>
                        </v-row>
                    </v-container>
                </v-card>
            </v-col>
        </v-row>
    </v-container>
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
                email: "",
                password: ""
            },
            restPasword: false,
            emailRest: ""
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
        doLogin: function() {
            this.$store.dispatch("signin", {
                path: "/api/login",
                data: this.credentials,
                related: "do-login"
                // redirect_to: "/",
            });
        },

        //* Forget password
        restPassword: function() {
            this.$store.dispatch("postData", {
                path: "/api/reset/password",
                data: { email: this.emailRest },
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
                // do-login (expected)
                this.$callback.handler(
                    this.$dialog,
                    this.$store.getters.expected("reset-password")
                );
            }

            {
                // do-login (expected)
                this.$callback.handler(
                    this.$dialog,
                    this.$store.getters.expected("do-login")
                );
            }
        }
    },
    created() {
        this.$store.commit("CLEAR_EXPECTED");
    }
};
</script>
