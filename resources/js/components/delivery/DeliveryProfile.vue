<template>
  <!-- Information personnel -->
  <v-card elevation="7" flat v-if="delivery">
    <v-container>
      <v-toolbar flat>
        <v-spacer></v-spacer>
        <v-btn
          color="purple darken-3"
          fab
          small
          @click="isEditing = !isEditing"
        >
          <v-icon v-if="isEditing"> mdi-close </v-icon>
          <v-icon v-else color="white"> mdi-pencil </v-icon>
        </v-btn>
      </v-toolbar>
      <v-card-text>
        <v-row no-gutters>
          <v-col cols="12" md="12" sm="12" align="center" class="mb-3">
            <v-avatar size="100" v-if="delivery.avatar !== 'avatar.png'">
              <img alt="Avatar" :src="'/Avatar/'.delivery.avatar" />
            </v-avatar>
            <v-avatar color="indigo" size="100">
              <v-icon dark> mdi-account-circle </v-icon>
            </v-avatar>
          </v-col>
          <v-col cols="12" md="6">
            <v-text-field
              class="mt-5"
              dense
              hide-details="auto"
              label="Nom"
              v-model="delivery.first_name"
              :outlined="isEditing"
              :disabled="!isEditing"
            ></v-text-field>
          </v-col>
          <v-col cols="12" md="6">
            <v-text-field
              class="mt-5"
              dense
              hide-details="auto"
              label="Prénom"
              v-model="delivery.last_name"
              :outlined="isEditing"
              :disabled="!isEditing"
            ></v-text-field>
          </v-col>
          <v-col cols="12" md="6">
            <v-text-field
              class="mt-5"
              dense
              hide-details="auto"
              label="Experience"
              type="number"
              v-model="delivery.experience"
              :outlined="isEditing"
              :disabled="!isEditing"
            ></v-text-field>
          </v-col>
          <v-col cols="12" md="6">
            <v-text-field
              class="mt-5"
              dense
              hide-details="auto"
              label="Téléphone"
              v-model="delivery.phone_number"
              :outlined="isEditing"
              :disabled="!isEditing"
            ></v-text-field>
          </v-col>

          <v-col cols="12" md="12" sm="12">
            <v-btn
              block
              class="mt-5"
              :large="!isMobile"
              :small="isMobile"
              outlined
              elevation="5"
              color="primary"
              @click="rander()"
              v-if="isEditing"
            >
              <v-icon left>mdi-cloud-upload</v-icon>
              <span v-if="!isMobile"> joindre votre permis (PDF) </span>
            </v-btn>
            <input hidden type="file" id="pdf-file" />

            <v-btn
              block
              elevation="5"
              outlined
              color="primary"
              @click="download()"
              :large="!isMobile"
              :small="isMobile"
              class="mt-5"
              v-if="!isEditing"
            >
              <v-icon left>mdi-cloud-download</v-icon>
              <span v-if="!isMobile"> Télécharger votre permis </span>
            </v-btn>
          </v-col>
        </v-row>
      </v-card-text>
      <v-divider></v-divider>
      <v-card-actions>
        <v-spacer></v-spacer>
        <v-btn :disabled="!isEditing" color="success" @click="save()">
          <v-icon left> mdi-update </v-icon>
          Mettre à jour
        </v-btn>
      </v-card-actions>
    </v-container>
  </v-card>
</template>

<script>
import { mapState } from "vuex";

export default {
  data() {
    return {
      hasSaved: false,
      isEditing: null,
      model: null,
    };
  },
  computed: {
    ...mapState(["expected"]),
    //* Is mobile
    isMobile: function () {
      return this.$vuetify.breakpoint.smAndDown;
    },
    //* guard
    delivery: function () {
      try {
        return this.$store.getters.guard;
      } catch (error) {
        return [];
      }
    },
  },
  methods: {
    //* Update info delivery
    save: function () {
      // Empty the images so that we upload new ones
      const file = document.querySelector("#pdf-file");
      // Make sure the file input is not null
      if (!file.files) return;

      // The file is a valid file.
      const formData = new FormData();
      formData.append("Permit", file.files[0]);

      for (const [key, value] of Object.entries(this.delivery)) {
        formData.append(key, value);
      }

      this.$store.dispatch("editData", {
        path: "/api/update/delivery",
        data: formData,
        related: "do-edit",
        // redirect_to: "/",
      });
    },
    //* Rander input
    rander() {
      document.getElementById("pdf-file").click();
    },

    download: function () {
      axios({
        url: "/api/download/file",
        headers: {
          // Accept: "application/json",
          "content-type": "multipart/form-data",
          Authorization: `Bearer ${localStorage.getItem("token")}`,
        },
        method: "GET",
        responseType: "blob",
      }).then((response) => {
        var fileURL = window.URL.createObjectURL(new Blob([response.data]));
        var fileLink = document.createElement("a");

        fileLink.href = fileURL;
        fileLink.setAttribute("download", "file.pdf");
        document.body.appendChild(fileLink);

        fileLink.click();
      });
    },
  },
};
</script>

<style lang="scss" scoped>
</style>