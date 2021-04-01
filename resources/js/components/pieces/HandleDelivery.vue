<template>
  <dialogCard title="Ajouter nouveau livreur" :actions="actions()">
    <v-row>
      <!--  first_name -->
      <v-col cols="12">
        <v-text-field
          dense
          hide-details="auto"
          outlined
          label="Nom"
          v-model="delivery.first_name"
        ></v-text-field>
      </v-col>
      <!-- Name of restaurant -->
      <v-col cols="12">
        <v-text-field
          dense
          hide-details="auto"
          outlined
          label="Prénom"
          v-model="delivery.last_name"
        ></v-text-field>
      </v-col>
      <!-- Email -->
      <v-col cols="12">
        <v-text-field
          v-model="delivery.email"
          type="email"
          label="Email"
          placeholder="Email"
          dense
          outlined
          hide-details="auto"
        >
        </v-text-field>
      </v-col>
      <v-col cols="12">
        <v-text-field
          dense
          outlined
          hide-details="auto"
          label="Experience"
          type="number"
          v-model="delivery.experience"
        ></v-text-field>
      </v-col>
      <v-col cols="12">
        <v-btn
          block
          elevation="5"
          color="primary"
          @click="rander()"
          outlined
          v-if="isMobile ? 'small' : 'x-large'"
        >
          <v-icon left>mdi-cloud-upload</v-icon>
          joindre votre permis (PDF)
        </v-btn>
        <input hidden type="file" id="pdf-file" />
      </v-col>
    </v-row>
  </dialogCard>
</template>

<script>
export default {
  data() {
    return {
      delivery: {
        first_name: "",
        last_name: "",
        experience: "",
        email: "",
      },
    };
  },
  computed: {
    //* Is mobile
    isMobile: function () {
      return this.$vuetify.breakpoint.smAndDown;
    },
  },
  methods: {
    //* Actions
    actions: function () {
      return {
        close: {
          text: "Fermer",
          color: "error",
          rounded: true,
        },
        add: {
          text: "Ajouter",
          color: "primary",
          rounded: true,
          handle: () => {
            return this.$store.dispatch("postData", {
              path: "/api/add/restaurant",
              data: this.delivery,
              related: "add-restaurant",
              returned: true,
            });
          },
        },
      };
    },
  },
};
</script>
