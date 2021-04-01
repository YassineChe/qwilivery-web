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
          <v-card-text>
            <UserProfile></UserProfile> </v-card-text
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
import UserProfile from "./UserProfile";
export default {
  components: {
    SecurityProfile,
    UserProfile,
  },
  data() {
    return {
      hasSaved: false,
      isEditing: null,
      model: null,
      credentials: {
        first_name: "",
        last_name: "",
        experience: "",
        email: "admin@mail.com",
        password: "123456",
        avatar: "avatar.png",
        number_phone: "",
      },
    };
  },
};
</script>

<style lang="scss" scoped>
</style>