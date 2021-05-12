<template>
    <v-container fluid fill-height class="background ma-0 pa-0">
        <v-row class="fill-height">
            <v-col cols="12" sm="8" md="8" lg="8" v-if="!isMobile">
                <v-container fill-height fluid>
                    <v-row align="center" justify="center">
                        <v-col align="center">
                            <v-img
                                src="/images/svg/register.svg"
                                height="450px"
                                width="550px"
                            />
                        </v-col>
                    </v-row>
                </v-container>
            </v-col>

            <v-col cols="12" sm="12" md="5" lg="4">
                <v-card tile class="fill-height" flat>
                    <v-container fill-height fluid>
                        <v-row align="center" justify="center" class="pa-10">
                            <v-col align="center">
                                <!-- Login  -->
                                <v-row>
                                    <!-- HeadLine -->
                                    <v-col cols="12">
                                        <Headline
                                            headline="Créez votre compte 👋"
                                            :headline-classes="[
                                                'text-h5',
                                                'grey--text text--darken-2'
                                            ]"
                                        >
                                            <template #subheadline>
                                                <span
                                                    class="grey--text text--darken-1"
                                                >
                                                    Assurez-vous d'insérer un
                                                    e-mail valide, vous recevrez
                                                    un e-mail de confirmation
                                                </span>
                                            </template>
                                        </Headline>
                                    </v-col>
                                    <!-- First name -->
                                    <v-col cols="6">
                                        <v-text-field
                                            dense
                                            hide-details="auto"
                                            outlined
                                            label="Nom"
                                            v-model="credentials.first_name"
                                        ></v-text-field>
                                    </v-col>
                                    <!-- Last name -->
                                    <v-col cols="6">
                                        <v-text-field
                                            dense
                                            hide-details="auto"
                                            outlined
                                            label="Prénom"
                                            v-model="credentials.last_name"
                                        ></v-text-field>
                                    </v-col>
                                    <!-- Phone number -->
                                    <v-col cols="12">
                                        <v-text-field
                                            dense
                                            hide-details="auto"
                                            outlined
                                            label="numéro de téléphone"
                                            v-model="credentials.phone_number"
                                        ></v-text-field>
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
                                    <!-- Experience -->
                                    <v-col cols="12">
                                        <v-text-field
                                            dense
                                            hide-details="auto"
                                            outlined
                                            label="Experience"
                                            type="number"
                                            v-model="credentials.experience"
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
                                    <!-- Confrim password -->
                                    <v-col>
                                        <v-text-field
                                            dense
                                            hide-details="auto"
                                            type="password"
                                            outlined
                                            label="Mot de passe"
                                            v-model="credentials.confirm"
                                        ></v-text-field>
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
                                            <v-icon left
                                                >mdi-cloud-upload</v-icon
                                            >
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
                                            elevation="0"
                                            @click="register()"
                                            :loading="isBusy('do-register')"
                                        >
                                            s'inscrire
                                        </v-btn>
                                    </v-col>
                                    <v-col cols="12">
                                        <v-divider />
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
                avatar: "",
                phone_number: ""
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
        register: function() {
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
            this.$store.dispatch("uploadFile", {
                path: "/api/register/delivery",
                data: formData,
                related: "do-register"
                // redirect_to: "/",
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
