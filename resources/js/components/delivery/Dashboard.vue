<template>
    <v-app :style="{ background: this.$vuetify.theme.themes.light.background }">
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
                <v-icon color="grey lighten-1" medium>
                    mdi-message-outline</v-icon
                >
            </v-badge>
            <v-badge color="error" content="6" overlap class="mr-9">
                <v-icon color="grey lighten-1" medium>
                    mdi-bell-outline
                </v-icon>
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
                                    {{ guard["first_name"] }}
                                </h4>
                            </v-flex>
                            <v-flex align-self-center>
                                Livreur
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
                            <h3
                                class="primary--text text-center"
                                style="text-shadow: 2px 2px 2px #CCC;"
                            >
                                Qwilivery
                            </h3>
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
                <!-- App -->
                <v-subheader>App</v-subheader>

                <v-list-group prepend-icon="mdi-moped-electric" no-action>
                    <template v-slot:activator>
                        <v-list-item-content>
                            <v-list-item-title>
                                Commande
                            </v-list-item-title>
                        </v-list-item-content>
                    </template>

                    <v-list-item :to="{ name: 'waiting-orders' }">
                        <v-list-item-title>
                            En attente
                        </v-list-item-title>
                        <v-list-item-icon>
                            <v-icon>mdi-moped</v-icon>
                        </v-list-item-icon>
                    </v-list-item>

                    <v-list-item :to="{ name: 'inprogress-orders' }">
                        <v-list-item-title>
                            En cours
                        </v-list-item-title>
                        <v-list-item-icon>
                            <v-icon>mdi-road-variant</v-icon>
                        </v-list-item-icon>
                    </v-list-item>

                    <v-list-item :to="{ name: 'delivery-historic' }">
                        <v-list-item-title>
                            Historique
                        </v-list-item-title>
                        <v-list-item-icon>
                            <v-icon>mdi-history</v-icon>
                        </v-list-item-icon>
                    </v-list-item>
                </v-list-group>

                <!-- Express delivery -->
                <v-list-group prepend-icon="mdi-moped-electric" no-action>
                    <template v-slot:activator>
                        <v-list-item-content>
                            <v-list-item-title>
                                Express livreur
                            </v-list-item-title>
                        </v-list-item-content>
                    </template>

                    <v-list-item link :to="{ name: 'waiting-express' }">
                        <v-list-item-content>
                            <v-list-item-title>
                                Express en attente
                            </v-list-item-title>
                        </v-list-item-content>
                        <v-list-item-icon>
                            <v-icon>mdi-flash</v-icon>
                        </v-list-item-icon>
                    </v-list-item>
                    <v-list-item :to="{ name: 'history-express' }">
                        <v-list-item-content>
                            <v-list-item-title
                                >Historique Express</v-list-item-title
                            >
                        </v-list-item-content>
                        <v-list-item-icon>
                            <v-icon>mdi-history</v-icon>
                        </v-list-item-icon>
                    </v-list-item>
                </v-list-group>
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
            this.$store.dispatch("postData", {
                path: "/api/logout",
                data: {},
                related: "do-logout"
            });
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
    },
    watch: {
        expected() {
            {
                let expected = this.$store.getters.expected("do-logout");
                if (expected != undefined) this.$auth.signOut();
            }
        }
    },
    created() {
        //Request permision for native native notification!
        this.$notification.requestPermission();
    },
    mounted() {
        let pusher = new Pusher(process.env.MIX_PUSHER_APP_KEY, {
            cluster: "eu"
        });

        //Subscribe channels
        let channels = [
            "new-order-channel",
            "new-express-channel"
        ].map(channelName => pusher.subscribe(channelName));

        channels.forEach(channel => {
            //New order
            channel.bind("new-order", () => {
                this.$notification.show(
                    "Nouvelle commande",
                    {
                        body:
                            "Nouvelle commande à livrer! Cliquez pour voir les détails"
                    },
                    {
                        onclick: function() {
                            window.location.href = "/orders";
                        }
                    }
                );
            });
            //New express
            channel.bind("new-express", () => {
                this.$notification.show(
                    "Express livreur ⚡️",
                    {
                        body: "Un restaurant demande un livreur Express.."
                    },
                    {
                        onclick: function() {
                            window.location.href = "/waiting-express";
                        }
                    }
                );
            });
        });

        channel.bind("new-order", data => {});
    }
};
</script>
