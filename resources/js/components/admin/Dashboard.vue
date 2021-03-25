<template>
  <v-app :style="{ background: this.$vuetify.theme.themes.light.background }">
    <!-- Navbar -->
    <AdminNavbar app color="" elevate-on-scroll>
      <template #appBarNavIcon>
        <v-app-bar-nav-icon @click="drawer = !drawer"></v-app-bar-nav-icon>
      </template>
    </AdminNavbar>

    <!-- Drawer navigation -->
    <v-navigation-drawer
      v-model="drawer"
      app
      floating
      color=""
      class="elevation-3"
    >
      <v-list>
        <v-list-item>
          <v-list-item-content>
            <img src="" width="150" />
          </v-list-item-content>
        </v-list-item>
      </v-list>
      <!-- Lisf of content -->
      <v-list nav dense>
        <v-list-item-group>
          <v-subheader>GESTION DES UTILISATEURS</v-subheader>
          <v-divider />

          <!-- Offers  -->
          <v-list-item v-for="(item, i) in nabItem" :key="i" :to="item.to">
            <v-list-item-icon>
              <v-icon>{{ item.icon }}</v-icon>
            </v-list-item-icon>
            <v-list-item-title>{{ item.title }}</v-list-item-title>
          </v-list-item>
        </v-list-item-group>
      </v-list>
    </v-navigation-drawer>
    <!-- Dashboard Container -->
    <v-main class="ma-10">
      <router-view></router-view>
    </v-main>
    <!-- Footer -->
    <v-footer padless height="50px" color="transparent">
      <v-col class="text-center" cols="12">
        {{ new Date().getFullYear() }} — <strong>Delivery</strong>
      </v-col>
    </v-footer>
    <preLoader></preLoader>
  </v-app>
</template>

<script>
import preLoader from "../clue/preLoader";
import AdminNavbar from "./AdminNavbar";
export default {
  components: { AdminNavbar, preLoader },
  data: () => ({
    drawer: true,
    nabItem: [
      {
        title: "Livreurs",
        icon: "mdi-account",
        to: "livreurs",
      },
      {
        title: "Livreurs",
        icon: "mdi-account",
        to: "/Sss",
      },
    ],
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