<template>
  <v-app :style="{ background: this.$vuetify.theme.themes.dark.background }">
    <!-- Navbar -->

    <RestaurantNavbar
      app
      color=""
      elevation="2"
      classes="mx-7 mt-5"
      height="55"
    >
      <template #appBarNavIcon>
        <v-app-bar-nav-icon @click="drawer = !drawer"></v-app-bar-nav-icon>
      </template>
    </RestaurantNavbar>

    <!-- Drawer navigation -->
    <v-navigation-drawer
      v-model="drawer"
      app
      floating
      color="bg"
      class="elevation-3"
    >
      <v-list>
        <v-list-item>
          <v-list-item-content>
            <img src="/images/logo-wt.svg" width="150" />
          </v-list-item-content>
        </v-list-item>
      </v-list>
      <!-- Lisf of content -->
      <v-list
        nav
        dense
        color="primary--text"
        active-class="deep-purple--text text--accent-4"
      >
        <v-subheader>Tableau de bord</v-subheader>
        <v-divider />
        <!-- Home  -->
        <v-list-item to="/">
          <v-list-item-icon>
            <v-icon>mdi-home</v-icon>
          </v-list-item-icon>
          <v-list-item-title>Accueil</v-list-item-title>
        </v-list-item>
        <!--  -->
        <v-list-item to="menu">
          <v-list-item-icon>
            <v-icon>mdi-face</v-icon>
          </v-list-item-icon>
          <v-list-item-title>Menu</v-list-item-title>
        </v-list-item>
      </v-list>
    </v-navigation-drawer>
    <!-- Dashboard Container -->
    <v-main class="ma-10">
      <router-view></router-view>
    </v-main>
    <!-- Footer -->
    <v-footer padless height="50px" color="transparent">
      <v-col class="text-center" cols="12">
        {{ new Date().getFullYear() }} — <strong>ReadyToGo</strong>
      </v-col>
    </v-footer>
  </v-app>
</template>

<script>
import RestaurantNavbar from "./RestaurantNavbar";
export default {
  components: { RestaurantNavbar },
  data: () => ({
    drawer: true,
  }),
  beforeCreate() {
    //* fetchGuard
    this.$store.dispatch("fetchData", {
      path: "/api/fetch/authenticated/guard",
      mutation: "FETCH_GUARD",
      related: "fetch-guard",
    });
  },
};
</script>