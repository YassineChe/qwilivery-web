<template>
    <v-app :style="{ background: this.$vuetify.theme.themes.dark.background }">
        <!-- Navbar -->

        <v-app-bar
            v-if="guard"
            tile
            color="white"
            app
            elevate-on-scroll
            fixed
            elevation="3"
        >
            <!-- The Spacer -->
            <v-btn color="primary" outlined rounded>
                <v-icon :left="!isMobile">mdi-phone-forward</v-icon>
                <span v-if="!isMobile">Commande téléphonique</span>
            </v-btn>
            <v-spacer></v-spacer>
            <v-badge color="error" content="6" overlap class="mr-9">
                <v-icon color="grey lighten-1" medium>
                    mdi-message-outline</v-icon
                >
            </v-badge>
            <v-badge color="error" content="6" overlap class="mr-9">
                <v-icon color="grey lighten-1" medium>
                    mdi-bell-outline
                </v-icon>
            </v-badge>
            <!-- profil -->
            <v-menu bottom min-width="200px" rounded offset-y>
                <template v-slot:activator="{ on }">
                    <v-btn icon x-large v-on="on">
                        <v-avatar size="45">
                            <img :src="`/images/avatar.png`" />
                        </v-avatar>
                    </v-btn>
                    <div>
                        <v-layout class="mr-3 mt-5">
                            <v-flex>
                                <h4 class="grey--text">Elon</h4>
                                <p>Musk</p>
                            </v-flex>
                        </v-layout>
                    </div>
                </template>
                <v-card>
                    <v-list-item-content class="justify-center">
                        <div class="mx-auto text-center">
                            <v-btn depressed text :to="{ name: 'profile' }">
                                Modifier le compte
                            </v-btn>
                            <v-divider class="my-3"></v-divider>
                            <v-btn depressed text @click="signOut()">
                                Se déconnecter
                            </v-btn>
                        </div>
                    </v-list-item-content>
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
                <v-divider />
                <!-- Home  -->
                <v-list-item to="/">
                    <v-list-item-icon>
                        <v-icon>mdi-home</v-icon>
                    </v-list-item-icon>
                    <v-list-item-title>Accueil</v-list-item-title>
                </v-list-item>
                <!-- Menu -->
                <v-list-item :to="{ name: 'menu' }">
                    <v-list-item-icon>
                        <v-icon>mdi-silverware</v-icon>
                    </v-list-item-icon>
                    <v-list-item-title>Menu</v-list-item-title>
                </v-list-item>
                <!-- Phone order -->
                <v-list-item :to="{ name: 'order' }">
                    <v-list-item-icon>
                        <v-icon>mdi-silverware</v-icon>
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
export default {
    data: () => ({
        drawer: true
    }),
    computed: {
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
