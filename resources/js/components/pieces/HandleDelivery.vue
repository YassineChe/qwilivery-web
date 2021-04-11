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
        <v-text-field
          dense
          hide-details="auto"
          outlined
          label="numéro de téléphone"
          v-model="delivery.phone_number"
        ></v-text-field
      ></v-col>
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
          <v-icon left>mdi-cloud-upload</v-icon>
          <span v-if="!isMobile && !delivery.permit"
            >joindre votre permis (PDF)</span
          >
          <span v-if="delivery.permit">{{ delivery.permit }}</span>
        </v-btn>
        <input hidden type="file" id="pdf-file" />
      </v-col>
    </v-row>
  </dialogCard>
</template>

<script>
export default {
  props: {
    deliveryToEdit: { required: false },
    title: { required: true, type: String },
  },
  data() {
    return {
      delivery: {
        first_name: "",
        last_name: "",
        experience: "",
        phone_number: "",
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
          text: "Enregistrer",
          color: "primary",
          rounded: true,
          handle: () => {
            // Empty the images so that we upload new ones
            const file = document.querySelector("#pdf-file");
            // Make sure the file input is not null
            if (!file.files) return;

            // The file is a valid file.
            const formData = new FormData();
            formData.append("permit", file.files[0]);

            for (const [key, value] of Object.entries(this.delivery)) {
              formData.append(key, value);
            }

            if (!this.deliveryToEdit)
              return this.$store.dispatch("postData", {
                path: "/api/add/delivery-man",
                data: formData,
                related: "add-delivery",
                returned: true,
              });
            else
              return this.$store.dispatch("postData", {
                path: "/api/edit/delivery-man",
                data: formData,
                related: "edit-delivery",
                returned: true,
              });
          },
        },
      };
    },
    //* Rander input
    rander() {
      document.getElementById("pdf-file").click();
    },
  },
  created() {
    if (this.deliveryToEdit) {
      this.delivery = this.deliveryToEdit;
    }
  },
};
</script>
