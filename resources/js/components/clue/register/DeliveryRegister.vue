<template>
    <v-card
        tile
        class="fill-height"
        flat
        :disabled="isBusy('do-register-delivery')"
    >
        <v-container fill-height fluid>
            <validation-observer ref="observer" v-slot="{ invalid }">
                <v-row class="pa-10">
                    <!-- HeadLine -->
                    <v-col cols="12" align="center">
                        <Headline
                            headline="Créer un compte livreur"
                            :headline-classes="[
                                'text-h5',
                                'grey--text text--darken-2'
                            ]"
                        >
                            <template #subheadline>
                                <router-link
                                    class="text-decoration-none"
                                    :to="{ name: 'login' }"
                                    >Se connecter à un compte
                                    existant</router-link
                                >
                            </template>
                        </Headline>
                    </v-col>

                    <v-col cols="12" align="center">
                        <v-row>
                            <!-- First name -->
                            <v-col cols="6">
                                <validation-provider
                                    v-slot="{
                                        errors
                                    }"
                                    name="Nom"
                                    rules="required|max:50"
                                >
                                    <v-text-field
                                        :error-messages="errors"
                                        dense
                                        hide-details="auto"
                                        outlined
                                        label="Nom"
                                        v-model="credentials.last_name"
                                    ></v-text-field>
                                </validation-provider>
                            </v-col>
                            <!-- Last name -->
                            <v-col cols="6">
                                <validation-provider
                                    v-slot="{
                                        errors
                                    }"
                                    name="Prénom"
                                    rules="required|max:50"
                                >
                                    <v-text-field
                                        :error-messages="errors"
                                        dense
                                        hide-details="auto"
                                        outlined
                                        label="Prénom"
                                        v-model="credentials.first_name"
                                    ></v-text-field>
                                </validation-provider>
                            </v-col>
                            <!-- Phone number -->
                            <v-col cols="12">
                                <validation-provider
                                    v-slot="{
                                        errors
                                    }"
                                    name="Prénom"
                                    rules="required|max:50"
                                >
                                    <v-text-field
                                        :error-messages="errors"
                                        dense
                                        hide-details="auto"
                                        outlined
                                        label="numéro de téléphone"
                                        v-model="credentials.phone_number"
                                    ></v-text-field>
                                </validation-provider>
                            </v-col>
                            <!-- Email -->
                            <v-col cols="12">
                                <validation-provider
                                    v-slot="{
                                        errors
                                    }"
                                    name="E-mail"
                                    rules="required|email|max:50"
                                >
                                    <v-text-field
                                        :error-messages="errors"
                                        dense
                                        hide-details="auto"
                                        outlined
                                        label="E-mail"
                                        v-model="credentials.email"
                                    ></v-text-field>
                                </validation-provider>
                            </v-col>
                            <!-- Experience -->
                            <v-col cols="12">
                                <validation-provider
                                    v-slot="{
                                        errors
                                    }"
                                    name="Expérience"
                                    rules="required|numeric|min:1|max:9"
                                >
                                    <v-text-field
                                        :error-messages="errors"
                                        dense
                                        hide-details="auto"
                                        outlined
                                        label="Experience"
                                        type="number"
                                        v-model="credentials.experience"
                                    ></v-text-field>
                                </validation-provider>
                            </v-col>
                            <!-- Password -->
                            <v-col cols="12">
                                <validation-provider
                                    v-slot="{
                                        errors
                                    }"
                                    name="Mot de passe"
                                    rules="required|min:6|max:50"
                                >
                                    <v-text-field
                                        :error-messages="errors"
                                        dense
                                        hide-details="auto"
                                        type="password"
                                        outlined
                                        label="Mot de passe"
                                        v-model="credentials.password"
                                    ></v-text-field>
                                </validation-provider>
                            </v-col>
                            <!-- Confrim password -->
                            <v-col>
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
                                        :error-messages="errors"
                                        dense
                                        hide-details="auto"
                                        type="password"
                                        outlined
                                        label="Mot de passe"
                                        v-model="credentials.confirm"
                                    ></v-text-field>
                                </validation-provider>
                            </v-col>
                            <!-- Add permit -->
                            <v-col cols="12">
                                <v-btn
                                    block
                                    elevation="5"
                                    color="primary"
                                    @click="rander()"
                                    outlined
                                    :large="!isMobile"
                                    :small="isMobile"
                                    class=""
                                >
                                    <v-icon left>mdi-cloud-upload</v-icon>
                                    <span v-if="file"
                                        >{{
                                            file.slice(
                                                file.length - 20,
                                                file.length
                                            )
                                        }}
                                    </span>
                                    <span v-else>
                                        joindre votre permis (PNG, JPG)
                                    </span>
                                </v-btn>
                                <input
                                    hidden
                                    @change="changeName()"
                                    type="file"
                                    id="png-permit"
                                />
                            </v-col>
                            <v-col cols="12">
                                <v-btn
                                    color="primary"
                                    block
                                    :disabled="invalid"
                                    @click="registerDelivery()"
                                    :loading="isBusy('do-register-delivery')"
                                >
                                    s'inscrire
                                </v-btn>
                            </v-col>
                        </v-row>
                    </v-col>
                </v-row>
            </validation-observer>
        </v-container>
    </v-card>
</template>

<script>
import Headline from "../../pieces/Headline";
//libs
import { mapState } from "vuex";
export default {
    components: {
        Headline
    },
    data() {
        return {
            credentials: {
                first_name: "",
                last_name: "",
                experience: "",
                phone_number: "",
                email: "",
                password: "",
                confirm: "",
                avatar: ""
            },
            file: ""
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
        changeName() {
            const file = document.querySelector("#png-permit");
            // Make sure the file input is not null
            this.file = file.files[0].name;
        },
        //* register
        registerDelivery: function() {
            // Empty the images so that we upload new ones
            const file = document.querySelector("#png-permit");
            // Make sure the file input is not null
            if (!file.files) return;
            // The file is a valid file.
            const formData = new FormData();
            formData.append("permit", file.files[0]);
            for (const [key, value] of Object.entries(this.credentials)) {
                formData.append(key, value);
            }
            this.$store.dispatch("postData", {
                path: "/api/register/delivery",
                data: formData,
                related: "do-register-delivery"
            });
        },
        //* Rander input
        rander() {
            document.getElementById("png-permit").click();
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
                let expected = this.$store.getters.expected(
                    "do-register-delivery"
                );
                if (expected != undefined) {
                    if (expected.status === "success") {
                        this.$dialog.notify.success(
                            expected.result.subMessage,
                            {
                                position: "top-right",
                                timeout: 3000
                            }
                        );
                        // Clear expected
                        this.$store.commit("CLEAR_EXPECTED");
                        this.$router.push("login");
                    }
                    if (expected.status === "error") {
                        for (const [key, value] of Object.entries(
                            expected.result.subMessage
                        )) {
                            this.$dialog.notify.warning(value[0], {
                                position: "top-right",
                                timeout: 3000
                            });
                        }
                        // Clear expected
                        this.$store.commit("CLEAR_EXPECTED");
                    }
                }
            }
        }
    },
    created() {
        this.$store.commit("CLEAR_EXPECTED");
    }
};
</script>
