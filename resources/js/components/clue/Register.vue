<template>
  <v-container fluid fill-height class="background ma-0 pa-0">
    <v-row class="fill-height">
      <v-col cols="12" sm="8" md="8" lg="8" v-if="!isMobile">
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
      <v-col cols="12" sm="12" md="4" lg="4" class="my-0 py-0">
        <v-card tile class="fill-height" flat>
          <v-container fill-height fluid>
            <v-row align="center" justify="center" class="pa-10">
              <v-col align="center">
                <!-- Login  -->
                <v-layout column v-if="!restPasword">
                  <v-flex mt-5>
                    <!-- HeadLine -->
                    <Headline
                      headline="Pour créer votre compte sur Ready2Go👋"
                      :headline-classes="[
                        'text-h5',
                        'grey--text text--darken-2',
                      ]"
                    >
                      <template #subheadline>
                        <span class="grey--text text--darken-1">
                          veuillez remplir le formulaire ci-dessous. Vous
                          receverez un email d'activation et votre mot de passe
                          par email.
                        </span>
                      </template>
                    </Headline>
                  </v-flex>
                  <v-flex mt-5>
                    <v-text-field
                      dense
                      hide-details="auto"
                      outlined
                      label="nom"
                      v-model="credentials.first_name"
                    ></v-text-field>
                  </v-flex>
                  <v-flex mt-5>
                    <v-text-field
                      dense
                      hide-details="auto"
                      outlined
                      label="prénom"
                      v-model="credentials.last_name"
                    ></v-text-field>
                  </v-flex>
                  <v-flex mt-5>
                    <v-text-field
                      dense
                      hide-details="auto"
                      outlined
                      label="Experience"
                      type="number"
                      v-model="credentials.experience"
                    ></v-text-field>
                  </v-flex>
                  <v-flex mt-5>
                    <v-text-field
                      dense
                      hide-details="auto"
                      outlined
                      label="E-mail"
                      v-model="credentials.email"
                    ></v-text-field>
                  </v-flex>
                  <v-flex mt-5>
                    <v-text-field
                      dense
                      hide-details="auto"
                      type="password"
                      outlined
                      label="Mot de passe"
                      v-model="credentials.password"
                    ></v-text-field>
                  </v-flex>
                  <v-flex mt-5>
                    <v-text-field
                      dense
                      hide-details="auto"
                      type="password"
                      outlined
                      label="Mot de passe"
                      v-model="credentials.confirm"
                    ></v-text-field>
                  </v-flex>
                  <v-flex mt-5>
                    <v-btn
                      block
                      elevation="5"
                      color="primary"
                      @click="rander()"
                      outlined
                      x-large
                    >
                      <v-icon left>mdi-cloud-upload</v-icon>
                      joindre votre permis (PDF)
                    </v-btn>
                    <input hidden type="file" id="pdf-file" />
                  </v-flex>
                  <v-flex mt-5>
                    <v-btn
                      color="primary"
                      block
                      elevation="0"
                      @click="register()"
                    >
                      s'inscrire
                    </v-btn>
                  </v-flex>
                  <v-flex mt-5>
                    <v-divider />
                  </v-flex>
                  <v-flex mt-3>
                    <small>
                      Vous n'avez pas un compte?
                      <router-link to="register">Créer un compte</router-link>
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
    Headline,
  },
  data() {
    return {
      credentials: {
        first_name: "",
        last_name: "",
        phone: "",
        experience: "",
        permit: "",
        email: "admin@mail.com",
        password: "123456",
        avatar: "avatar.png",
      },
      folder: {},
      field: {},
      file: "",
      restPasword: false,
      emailRest: "",
    };
  },
  computed: {
    ...mapState(["expected"]),
    //* Is mobile
    isMobile: function () {
      return this.$vuetify.breakpoint.smAndDown;
    },
  },
  methods: {
    //* register
    register: function () {
      // Empty the images so that we upload new ones
      const file = document.querySelector("#pdf-file");
      // Make sure the file input is not null
      if (!file.files) return;

      // The file is a valid file.
      const formData = new FormData();
      formData.append("file", file.files[0]);

      this.$store.dispatch("uploadFile", {
        path: "/api/register/delivery",
        data: { formData: formData },

        related: "do-register",
        // redirect_to: "/",
      });
    },
    //* Rander input
    rander() {
      document.getElementById("pdf-file").click();
    },
    // Overlly
    isBusy: function (fetcher) {
      try {
        return this.$store.getters.expected(fetcher).status == "busy"
          ? true
          : false;
      } catch (error) {
        return false;
      }
    },
  },
  watch: {
    expected() {
      {
        let expected = this.$store.getters.expected("do-register");
        if (expected != undefined) {
          if (expected.status === "success") {
            this.$dialog.notify.success(expected.result.subMessage, {
              position: "top-right",
              timeout: 5000,
            });
          }
          if (expected.status === "error") {
            this.$dialog.notify.warning(expected.result.subMessage, {
              position: "top-right",
              timeout: 5000,
            });
          }
        }
      }
    },
  },
};
</script>
