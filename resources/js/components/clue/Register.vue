<template>
    <v-container fluid fill-height class="background ma-0 pa-0">
        <v-row class="fill-height">
            <v-col cols="12" sm="1" md="7" lg="8" v-if="!isMobile">
                <v-container fill-height fluid>
                    <v-row align="center" justify="center">
                        <v-col align="center">
                            <v-img
                                src="images/svg/register.svg"
                                height="450px"
                                width="550px"
                            />
                        </v-col>
                    </v-row>
                </v-container>
            </v-col>
            <v-col cols="12" sm="12" md="5" lg="4" class="my-0 py-0">
                <v-card tile class="fill-height" flat>
                    <v-container fill-height fluid>
                        <v-row align="center" justify="center" class="pa-10">
                            <v-col align="center">
                                <!-- Login  -->
                                <v-row column v-if="!restPasword">
                                    <v-col cols="12">
                                        <!-- HeadLine -->
                                        <Headline
                                            headline="Pour créer votre compte surs Ready2Go👋"
                                            :headline-classes="[
                                                'text-h5',
                                                'grey--text text--darken-2'
                                            ]"
                                        >
                                            <template #subheadline>
                                                <span
                                                    class="grey--text text--darken-1"
                                                >
                                                    veuillez remplir le
                                                    formulaire ci-dessous. Vous
                                                    receverez un email
                                                    d'activation et votre mot de
                                                    passe par email.
                                                </span>
                                            </template>
                                        </Headline>
                                    </v-col>
                                    <!-- First Name -->
                                    <v-col cols="6">
                                        <v-text-field
                                            dense
                                            hide-details="auto"
                                            outlined
                                            label="Nom"
                                            v-model="credentials.first_name"
                                        ></v-text-field>
                                    </v-col>
                                    <!-- Last Name -->
                                    <v-col cols="6">
                                        <v-text-field
                                            dense
                                            hide-details="auto"
                                            outlined
                                            label="Prénom"
                                            v-model="credentials.last_name"
                                        ></v-text-field>
                                    </v-col>
                                    <!-- Experience -->
                                    <v-col cols="6">
                                        <v-text-field
                                            dense
                                            hide-details="auto"
                                            outlined
                                            label="Nombre d'année d'experience"
                                            type="number"
                                            v-model="credentials.experience"
                                        ></v-text-field>
                                    </v-col>
                                    <!-- Phone Number -->
                                    <v-col cols="6">
                                        <v-text-field
                                            dense
                                            hide-details="auto"
                                            outlined
                                            label="Téléphone"
                                            v-model="credentials.phone_number"
                                        ></v-text-field>
                                    </v-col>
                                    <!-- Divider -->
                                    <v-col cols="12">
                                        <v-divider />
                                    </v-col>
                                    <!-- Email -->
                                    <v-col cols="12">
                                        <v-text-field
                                            dense
                                            hide-details="auto"
                                            outlined
                                            label="E-mail"
                                            v-model="credentials.email"
                                        ></v-text-field>
                                    </v-col>
                                    <!-- Password -->
                                    <v-col cols="12">
                                        <v-text-field
                                            dense
                                            hide-details="auto"
                                            type="password"
                                            outlined
                                            label="Mot de passe"
                                            v-model="credentials.password"
                                        ></v-text-field>
                                    </v-col>
                                    <!-- Confim Password -->
                                    <v-col cols="12">
                                        <v-text-field
                                            dense
                                            hide-details="auto"
                                            type="password"
                                            outlined
                                            label="Mot de passe"
                                            v-model="credentials.confirm"
                                        ></v-text-field>
                                    </v-col>
                                    <!-- Upload PDF Driving License -->
                                    <v-col cols="12">
                                        <v-btn
                                            block
                                            elevation="5"
                                            color="primary"
                                            @click="rander()"
                                            outlined
                                            :large="!isMobile"
                                            :small="isMobile"
                                        >
                                            <v-icon left
                                                >mdi-cloud-upload</v-icon
                                            >
                                            joindre votre permis (PDF)
                                        </v-btn>
                                        <input
                                            hidden
                                            type="file"
                                            id="pdf-file"
                                        />
                                    </v-col>
                                    <!-- Button Register -->
                                    <v-col cols="12">
                                        <v-btn
                                            color="primary"
                                            block
                                            elevation="0"
                                            @click="register()"
                                            :loading="isBusy('do-register')"
                                        >
                                            s'inscrire
                                        </v-btn>
                                    </v-col>
                                    <v-col cols="12">
                                        <v-divider />
                                    </v-col>
                                    <v-col cols="12">
                                        <small>
                                            <router-link to="login"
                                                >Se connecter à un compte
                                                existant</router-link
                                            >
                                        </small>
                                    </v-col>
                                </v-row>
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
                first_name: "",
                last_name: "",
                experience: "",
                email: "",
                password: "",
                avatar: "avatar.png",
                phone_number: ""
            },
            folder: {},
            field: {},
            file: "",
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
        //* register
        register: function() {
            // Empty the images so that we upload new ones
            const file = document.querySelector("#pdf-file");
            // Make sure the file input is not null
            if (!file.files) return;

            // The file is a valid file.
            const formData = new FormData();
            formData.append("Permit", file.files[0]);

            for (const [key, value] of Object.entries(this.credentials)) {
                formData.append(key, value);
            }

            this.$store.dispatch("uploadFile", {
                path: "/api/register/delivery",
                data: formData,
                related: "do-register"
                // redirect_to: "/",
            });
        },
        //* Rander input
        rander() {
            document.getElementById("pdf-file").click();
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
                let expected = this.$store.getters.expected("do-register");
                if (expected != undefined) {
                    if (expected.status === "success") {
                        this.$dialog.notify.success(
                            expected.result.subMessage,
                            {
                                position: "top-right",
                                timeout: 5000
                            }
                        );
                        this.$router.push("login");
                        this.$dialog.notify.warning(
                            "Vous devez attendre que l'administration Ready2Go vous approuve",
                            {
                                position: "top-right",
                                timeout: 9000
                            }
                        );
                    }
                    if (expected.status === "error") {
                        for (const [key, value] of Object.entries(
                            expected.result.subMessage
                        )) {
                            this.$dialog.notify.warning(value[0], {
                                position: "top-right",
                                timeout: 5000
                            });
                        }
                    }
                }
            }
        }
    }
};
</script>
