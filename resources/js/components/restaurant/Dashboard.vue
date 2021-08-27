<template>
    <v-app :style="{ background: this.$vuetify.theme.themes.light.background }">
        <!-- Navbar -->

        <v-app-bar
            height="60px"
            color="white"
            elevation="3"
            app
            class="mx-7 mt-5"
            flat
            v-if="guard"
            rounded
        >
            <!-- Toogle drawer -->
            <v-app-bar-nav-icon @click="drawer = !drawer"></v-app-bar-nav-icon>
            <!-- The Spacer -->
            <v-btn
                color="primary"
                outlined
                rounded
                :to="{ name: 'restaurant-order' }"
            >
                <v-icon :left="!isMobile">mdi-phone-forward</v-icon>
                <span v-if="!isMobile">Commande téléphonique</span>
            </v-btn>
            <v-spacer></v-spacer>
            <!-- <v-badge color="error" content="6" overlap class="mr-9">
                <v-icon color="grey lighten-1" medium>
                    mdi-message-outline</v-icon
                >
            </v-badge>
            <v-badge color="error" content="6" overlap class="mr-9">
                <v-icon color="grey lighten-1" medium>
                    mdi-bell-outline
                </v-icon>
            </v-badge> -->
            <!-- Profil menu -->
            <!-- Profile menu -->
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
                                    {{ guard["name"] }}
                                </h4>
                            </v-flex>
                            <v-flex align-self-center>
                                Restaurant
                            </v-flex>
                        </v-layout>
                    </div>
                </template>
                <v-card>
                    <v-list dense>
                        <v-subheader>Porfile & Paramètres</v-subheader>
                        <v-list-item-group>
                            <!-- Profile -->
                            <v-list-item :to="{ name: 'restaurant-profile' }">
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
            <v-row>
                <v-col align="center" class="ma-3">
                    <img src="/images/logo-wt.svg" height="60px" />
                    <h3
                        class="primary--text text-center"
                        style="text-shadow: 2px 2px 2px #CCC;"
                    >
                        Qwilivery
                    </h3>
                </v-col>
            </v-row>
            <!-- Lisf of content -->
            <v-list
                nav
                dense
                color="primary--text"
                active-class="deep-purple--text text--accent-4"
            >
                <v-subheader>Tableau de bord</v-subheader>
                <!-- Home  -->
                <v-list-item to="/">
                    <v-list-item-icon>
                        <v-icon>mdi-home</v-icon>
                    </v-list-item-icon>
                    <v-list-item-title>Accueil</v-list-item-title>
                </v-list-item>

                <v-subheader>Utilisations</v-subheader>
                <!-- Menu -->
                <v-list-item :to="{ name: 'menu' }">
                    <v-list-item-icon>
                        <v-icon>mdi-silverware</v-icon>
                    </v-list-item-icon>
                    <v-list-item-title>Menu</v-list-item-title>
                </v-list-item>
                <!-- Phone order -->
                <v-list-item :to="{ name: 'restaurant-order' }">
                    <v-list-item-icon>
                        <v-icon>mdi-phone-forward</v-icon>
                    </v-list-item-icon>
                    <v-list-item-title>Commandes télé.</v-list-item-title>
                </v-list-item>
                <!-- Express delivery -->
                <v-list-item :to="{ name: 'express-delivery' }">
                    <v-list-item-icon>
                        <v-icon>mdi-moped-electric</v-icon>
                    </v-list-item-icon>
                    <v-list-item-title>Express livreur</v-list-item-title>
                </v-list-item>
                <!-- Profile -->
                <v-subheader>Profile & Paramètres</v-subheader>
                <v-list-item :to="{ name: 'restaurant-profile' }">
                    <v-list-item-icon>
                        <v-icon>mdi-account-cog</v-icon>
                    </v-list-item-icon>
                    <v-list-item-title>Porfile</v-list-item-title>
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
                <!-- Logout -->
                <v-list-item @click="signOut()">
                    <v-list-item-icon>
                        <v-icon>mdi-logout</v-icon>
                    </v-list-item-icon>
                    <v-list-item-title>Se déconnecter</v-list-item-title>
                </v-list-item>
            </v-list>
        </v-navigation-drawer>
        <!-- Dashboard Container -->
        <v-main class="ma-10">
            <!-- Bind routes here -->
            <router-view></router-view>
            <!-- Footer -->
            <v-footer padless height="50px" color="transparent">
                <v-col class="text-center" cols="12">
                    <small>
                        <span>Qwilivery - Made</span>
                        <a
                            class="text-decoration-none"
                            href="https://www.spoveup.com/"
                            >by Spoveup
                        </a>
                        ❤️
                        {{ new Date().getFullYear() }}
                    </small>
                </v-col>
            </v-footer>
        </v-main>
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
            try {
                return this.$store.getters.guard;
            } catch (error) {
                return [];
            }
        },
        //* Is mobile
        isMobile() {
            return this.$vuetify.breakpoint.xsOnly;
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
            {
                this.$callback.handler(
                    this.$dialog,
                    this.$store.getters.expected("add-report"),
                    {
                        store: this.$store,
                        clear: true
                    }
                );
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
