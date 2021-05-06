<template>
    <v-app :style="{ background: this.$vuetify.theme.themes.dark.background }">
        <!-- Navbar -->

        <v-app-bar
            v-if="guard"
            height="65px"
            color="white"
            elevation="3"
            class="mx-7 mt-5"
            fixed
            rounded
            app
        >
            <!-- Toggle navigation drawer -->
            <v-app-bar-nav-icon @click="drawer = !drawer"></v-app-bar-nav-icon>
            <!-- The Spacer -->
            <v-spacer></v-spacer>
            <!-- <v-badge color="error" content="6" overlap class="mr-9">
      <v-icon color="grey lighten-1" medium> mdi-message-outline</v-icon>
    </v-badge>
    <v-badge color="error" content="6" overlap class="mr-9">
      <v-icon color="grey lighten-1" medium> mdi-bell-outline </v-icon>
    </v-badge> -->
            <!-- profil -->
            <v-menu
                bottom
                min-width="200px"
                origin="center center"
                transition="scale-transition"
                rounded
                offset-y
            >
                <template v-slot:activator="{ on }">
                    <v-btn icon x-large v-on="on">
                        <v-avatar size="45">
                            <img :src="`/images/avatars/${guard.avatar}`" />
                        </v-avatar>
                    </v-btn>

                    <div class="mr-2">
                        <v-layout column align-content-center>
                            <v-flex align-self-center>
                                <h4>
                                    {{ guard["last_name"] }}
                                </h4>
                            </v-flex>
                            <v-flex align-self-center>
                                {{ guard["first_name"] }}
                            </v-flex>
                        </v-layout>
                    </div>
                </template>
                <v-card>
                    <v-list dense>
                        <v-subheader>Porfile</v-subheader>
                        <v-list-item-group>
                            <!-- Profile -->
                            <v-list-item :to="{ name: 'delivery-profile' }">
                                <v-list-item-icon>
                                    <v-icon v-text="'mdi-account'"></v-icon>
                                </v-list-item-icon>
                                <v-list-item-content>
                                    <v-list-item-title
                                        v-text="'Profile'"
                                    ></v-list-item-title>
                                </v-list-item-content>
                            </v-list-item>
                            <!-- Singout -->
                            <v-list-item @click="signOut()">
                                <v-list-item-icon>
                                    <v-icon v-text="'mdi-logout'"></v-icon>
                                </v-list-item-icon>
                                <v-list-item-content>
                                    <v-list-item-title
                                        v-text="'Se déconnecter'"
                                    ></v-list-item-title>
                                </v-list-item-content>
                            </v-list-item>
                        </v-list-item-group>
                    </v-list>
                </v-card>
            </v-menu>
        </v-app-bar>

        <!-- Drawer navigation -->
        <v-navigation-drawer
            v-model="drawer"
            app
            floating
            color="bg"
            class="elevation-3"
        >
            <v-list>
                <v-list>
                    <v-list-item>
                        <v-list-item-content>
                            <img src="/images/logo-wt.svg" height="80" />
                        </v-list-item-content>
                    </v-list-item>
                </v-list>
            </v-list>
            <!-- Lisf of content -->
            <v-list
                nav
                dense
                color="primary--text"
                active-class="deep-purple--text text--accent-4"
            >
                <v-subheader>Tableau de bord</v-subheader>
                <!-- Home  -->

                <v-list-item :to="{ name: 'delivery-home' }">
                    <v-list-item-icon>
                        <v-icon>mdi-home</v-icon>
                    </v-list-item-icon>
                    <v-list-item-title>
                        Accueil
                    </v-list-item-title>
                </v-list-item>
                <!-- Historic & Chat -->
                <v-subheader>Chat & Historique</v-subheader>
                <v-list-item :to="{ name: 'delivery-historic' }">
                    <v-list-item-icon>
                        <v-icon>mdi-history</v-icon>
                    </v-list-item-icon>
                    <v-list-item-title>
                        Historique
                    </v-list-item-title>
                </v-list-item>
                <!-- Chat -->
                <v-list-item :to="{ name: 'delivery-messenger' }">
                    <v-list-item-icon>
                        <v-icon>mdi-chat-outline</v-icon>
                    </v-list-item-icon>
                    <v-list-item-title>
                        Boite de messagrie
                    </v-list-item-title>
                </v-list-item>
                <!-- Guide -->
                <v-subheader>Plus</v-subheader>
                <v-list-item :to="{ name: 'guide' }">
                    <v-list-item-icon>
                        <v-icon>mdi-cellphone-iphone</v-icon>
                    </v-list-item-icon>
                    <v-list-item-title>Guide app mobile</v-list-item-title>
                </v-list-item>
                <!-- Singout -->
                <v-subheader>Profile & Paramètres</v-subheader>
                <v-list-item :to="{ name: 'delivery-profile' }">
                    <v-list-item-icon>
                        <v-icon>mdi-account-cog</v-icon>
                    </v-list-item-icon>
                    <v-list-item-title>Profile</v-list-item-title>
                </v-list-item>
                <!-- Reports -->
                <v-list-item @click="postReport()">
                    <v-list-item-icon>
                        <v-icon v-text="'mdi-alert'"></v-icon>
                    </v-list-item-icon>
                    <v-list-item-content>
                        <v-list-item-title
                            v-text="'Reporter'"
                        ></v-list-item-title>
                    </v-list-item-content>
                </v-list-item>
                <v-list-item @click="signOut()">
                    <v-list-item-icon>
                        <v-icon v-text="'mdi-logout'"></v-icon>
                    </v-list-item-icon>
                    <v-list-item-content>
                        <v-list-item-title
                            v-text="'Se déconnecter'"
                        ></v-list-item-title>
                    </v-list-item-content>
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
                {{ new Date().getFullYear() }} —
                <strong>
                    <span>Qwilivery - Made</span>
                    <a class="text-decoration-none" href="https://spveup.com"
                        >by Spoveup
                    </a>
                    ❤️
                </strong>
            </v-col>
        </v-footer>
    </v-app>
</template>

<script>
import PostReport from "../pieces/PostReport";
import { mapState } from "vuex";

export default {
    data: () => ({
        drawer: true
    }),
    computed: {
        ...mapState(["expected"]),
        //* Guard
        guard: function() {
            return this.$store.getters.guard;
        }
    },
    methods: {
        //* Post Report
        postReport: function() {
            this.$dialog.show(PostReport);
        },
        //* Logout
        signOut: function() {
            this.$auth.signOut();
        }
    },
    watch: {
        expected() {
            //Delete Order
            {
                let expected = this.$store.getters.expected("add-report");
                if (expected != undefined && expected.status === "success") {
                    this.$store.commit("CLEAR_EXPECTED");
                    this.$dialog.notify.success(expected.result.subMessage, {
                        position: "top-right",
                        timeout: 3000
                    });
                }
            }
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
