<template>
  <v-card>
    <v-toolbar flat color="primary" dark>
      <v-toolbar-title>User Profile</v-toolbar-title>
    </v-toolbar>
    <v-tabs vertical>
      <v-tab>
        <v-icon left> mdi-account </v-icon>
        profile
      </v-tab>
      <v-tab>
        <v-icon left> mdi-lock </v-icon>
        Sécurité
      </v-tab>

      <v-tab-item>
        <v-card flat>
          <v-card-text> <DeliveryProfile /> </v-card-text
        ></v-card>
      </v-tab-item>
      <v-tab-item>
        <v-card flat>
          <v-card-text>
            <SecurityProfile></SecurityProfile>
          </v-card-text>
        </v-card>
      </v-tab-item>
    </v-tabs>
  </v-card>
</template>

<script>
import SecurityProfile from "./SecurityProfile";
import DeliveryProfile from "./DeliveryProfile";
export default {
  components: {
    SecurityProfile,
    DeliveryProfile,
  },
};
</script>
 