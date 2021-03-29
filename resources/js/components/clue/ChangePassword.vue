<template>
  <v-container fluid class="mt-16">
    <v-row align="center" justify="center">
      <v-col align="center">
        <v-card max-width="600" class="mt-16 pa-3">
          <v-layout column>
            <v-flex mt-5>
              <!-- HeadLine -->
              <Headline
                headline="Récupération de mot de
                passe? "
                :headline-classes="['text-h5', 'grey--text text--darken-2']"
              >
                <template #subheadline>
                  <span class="grey--text text--darken-1">
                    Entrez votre email et nous vous enverrons des instructions
                    pour réinitialiser votre mot de passe
                  </span>
                </template>
              </Headline>
            </v-flex>
            <v-flex mt-1>
              <v-text-field
                dense
                hide-details="auto"
                type="password"
                outlined
                label="Mot de passe"
                v-model="credentials.password"
              ></v-text-field> </v-flex
            ><v-flex mt-1>
              <v-text-field
                dense
                hide-details="auto"
                type="password"
                outlined
                label="Confirmez le mot de passe"
                v-model="credentials.confirm"
              ></v-text-field>
            </v-flex>
            <v-flex mt-5>
              <v-btn
                v-if="!isMobile"
                color="primary"
                block
                elevation="0"
                :loading="isBusy('passwordRecovery')"
                @click="passwordRecovery()"
                ><v-icon left> mdi-lock-check </v-icon>Récupération de mot de
                passe</v-btn
              >
              <v-btn
                v-if="isMobile"
                color="primary"
                block
                elevation="0"
                :loading="isBusy('passwordRecovery')"
                @click="passwordRecovery()"
                ><v-icon> mdi-lock-check </v-icon></v-btn
              >
            </v-flex>
            <v-flex mt-5>
              <v-divider />
            </v-flex>
            <v-flex mt-3>
              <small>
                <v-btn color="primary" text @click="restPasword = !restPasword">
                  {{ `< Retour connexion` }}</v-btn
                >
              </small>
            </v-flex>
          </v-layout>
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
        password: "",
        confirm: "",
      },
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
    //* Login
    passwordRecovery: function () {
      this.$store.dispatch("postData", {
        path: "/api/reset-password",
        data: {
          ...this.credentials,
          token: window.location.pathname.slice(16),
        },
        related: "reset-password",
        // redirect_to: "/",
      });
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
        let expected = this.$store.getters.expected("reset-password");
        if (expected != undefined) {
          if (expected.status === "success") {
            this.$dialog.notify.success(expected.result.subMessage, {
              email: expected.result.email,
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

<style lang="scss" scoped>
</style>