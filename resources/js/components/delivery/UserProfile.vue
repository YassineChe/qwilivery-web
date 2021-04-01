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
          <v-icon v-else> mdi-pencil </v-icon>
        </v-btn>
      </v-toolbar>
      <v-card-text>
        <v-row no-gutters>
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
              v-model="delivery.number_phone"
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
            <input hidden type="file" id="pdf-file" />
          </v-col>
        </v-row>
      </v-card-text>
      <v-divider></v-divider>
      <v-card-actions>
        <v-spacer></v-spacer>
        <v-btn :disabled="!isEditing" color="success" @click="save">
          <v-icon left> mdi-update </v-icon>
          Mettre à jour
        </v-btn>
      </v-card-actions>
      <v-snackbar v-model="hasSaved" :timeout="2000" absolute bottom left>
        Your profile has been updated
      </v-snackbar>
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
};
</script>

<style lang="scss" scoped>
</style>