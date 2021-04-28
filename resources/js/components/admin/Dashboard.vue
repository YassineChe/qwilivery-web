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
                            <v-list-item :to="{ name: 'admin-profile' }">
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
                    <v-subheader>Tableau de bord</v-subheader>
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
                    <v-list-item :to="{ name: 'admin-messenger' }">
                        <v-list-item-icon>
                            <v-icon>mdi-chat-outline</v-icon>
                        </v-list-item-icon>
                        <v-list-item-title>
                            Boite de messagrie
                        </v-list-item-title>
                    </v-list-item>
                    <!-- Settings -->
                    <v-subheader>Paramètres & Rapports</v-subheader>
                    <v-list-item :to="{ name: 'admin-profile' }">
                        <v-list-item-icon>
                            <v-icon>mdi-account-cog</v-icon>
                        </v-list-item-icon>
                        <v-list-item-title>Profile</v-list-item-title>
                    </v-list-item>
                    <!-- Reports -->
                    <v-list-item :to="{ name: 'admin-reports' }">
                        <v-list-item-icon>
                            <v-icon>mdi-alert</v-icon>
                        </v-list-item-icon>
                        <v-list-item-title>
                            Rapports
                            <v-chip
                                v-if="count_unseen_reports > 0"
                                class="float-right"
                                small
                                color="error"
                                >{{ count_unseen_reports }}</v-chip
                            >
                        </v-list-item-title>
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
                <strong>
                    <span>Qwilivery - Made</span>
                    <a class="text-decoration-none" href="https://spveup.com"
                        >by Spoveup
                    </a>
                    ❤️
                </strong>
            </v-col>
        </v-footer>
        <!-- <preLoader></preLoader> -->
    </v-app>
</template>

<script>
export default {
    data: () => ({
        drawer: true
    }),
    computed: {
        //* Count unseen reprots
        count_unseen_reports: function() {
            try {
                return this.$store.getters.dummies("count_unseen_reports")[
                    "count_unseen_reports"
                ];
            } catch (error) {
                return "0";
            }
        },
        //* Guard
        guard: function() {
            return this.$store.getters.guard;
        }
    },
    methods: {
        //* init
        init: function() {
            this.$store.dispatch("multipleFetch", [
                {
                    path: "/api/fetch/count/reports/unseen",
                    mutation: "FETCH_DUMMY",
                    related: "count-unseen-reports"
                }
            ]);
        },
        //* Logout
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
