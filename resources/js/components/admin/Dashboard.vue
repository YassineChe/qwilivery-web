<template>
    <v-app :style="{ background: this.$vuetify.theme.themes.light.background }">
        <!-- Navbar -->
        <AdminNavbar app color="" elevation="2" classes="mx-7 mt-5" height="55">
            <template #appBarNavIcon>
                <v-app-bar-nav-icon
                    @click="drawer = !drawer"
                ></v-app-bar-nav-icon>
            </template>
        </AdminNavbar>

        <!-- Drawer navigation -->
        <v-navigation-drawer v-model="drawer" class="elevation-3" app floating>
            <v-list>
                <v-list-item>
                    <v-list-item-content>
                        <img src="/images/logo-wt.svg" height="80" />
                    </v-list-item-content>
                </v-list-item>
            </v-list>
            <!-- Lisf of content -->
            <v-list nav dense>
                <v-list-item-group color="primary">
                    <!-- Dashboard -->
                    <v-subheader>Dashboard</v-subheader>
                    <v-list-item :to="{ name: 'admin-home-dashboard' }">
                        <v-list-item-icon>
                            <v-icon>mdi-view-dashboard</v-icon>
                        </v-list-item-icon>
                        <v-list-item-title>Tableau de bord</v-list-item-title>
                    </v-list-item>
                    <v-subheader>Utilisateurs</v-subheader>
                    <!-- Restaurants  -->
                    <v-list-item :to="{ name: 'restaurants' }">
                        <v-list-item-icon>
                            <v-icon>mdi-store</v-icon>
                        </v-list-item-icon>
                        <v-list-item-title>Restaurants</v-list-item-title>
                    </v-list-item>
                    <!-- Delivery man -->
                    <v-list-item :to="{ name: 'deliveries' }">
                        <v-list-item-icon>
                            <v-icon>mdi-moped</v-icon>
                        </v-list-item-icon>
                        <v-list-item-title>Livreurs</v-list-item-title>
                    </v-list-item>

                    <v-subheader>Chat & Historique</v-subheader>
                    <v-list-item :to="{ name: 'historic' }">
                        <v-list-item-icon>
                            <v-icon>mdi-history</v-icon>
                        </v-list-item-icon>
                        <v-list-item-title>
                            Historique
                        </v-list-item-title>
                    </v-list-item>
                    <!-- Chat -->
                    <v-list-item>
                        <v-list-item-icon>
                            <v-icon>mdi-chat-outline</v-icon>
                        </v-list-item-icon>
                        <v-list-item-title>
                            Boite de messagrie
                        </v-list-item-title>
                    </v-list-item>
                    <!-- Dashboard -->
                    <v-subheader>Paramètres</v-subheader>
                    <v-list-item :to="{ name: 'admin-profile' }">
                        <v-list-item-icon>
                            <v-icon>mdi-account-cog</v-icon>
                        </v-list-item-icon>
                        <v-list-item-title>Profile</v-list-item-title>
                    </v-list-item>
                    <!-- Logout -->
                    <v-list-item @click="signOut()">
                        <v-list-item-icon>
                            <v-icon>mdi-logout</v-icon>
                        </v-list-item-icon>
                        <v-list-item-title>Se déconnecter</v-list-item-title>
                    </v-list-item>
                </v-list-item-group>
            </v-list>
        </v-navigation-drawer>
        <!-- Dashboard Container -->
        <v-main class="mx-7 mt-11">
            <router-view></router-view>
        </v-main>
        <!-- Footer -->
        <v-footer padless height="50px" color="transparent">
            <v-col class="text-center" cols="12">
                {{ new Date().getFullYear() }} —
                <strong>Qwilivery - Made by Spoveup ♥</strong>
            </v-col>
        </v-footer>
        <!-- <preLoader></preLoader> -->
    </v-app>
</template>

<script>
import preLoader from "../clue/preLoader";
import AdminNavbar from "./AdminNavbar";

export default {
    components: { AdminNavbar, preLoader },
    data: () => ({
        drawer: true
    }),
    methods: {
        signOut: function() {
            this.$auth.signOut();
        }
    },
    beforeCreate() {
        //* fetchGuard
        this.$store.dispatch("fetchData", {
            path: "/api/fetch/authenticated/guard",
            mutation: "FETCH_GUARD",
            related: "fetch-guard"
        });
    }
};
</script>
