<template>
    <v-app-bar
        :height="height"
        color="white"
        :app="app"
        :class="classes"
        :elevate-on-scroll="elevateOnScroll"
        :elevation="elevation"
        :fixed="fixed"
        :flat="flat"
        v-if="guard"
        rounded
    >
        <!-- This slot to bind drawer status on/off -->
        <slot v-if="isMobile" name="appBarNavIcon"></slot>
        <!-- The Spacer -->
        <v-spacer></v-spacer>
        <v-badge color="error" content="6" overlap class="mr-9">
            <v-icon color="grey lighten-1" medium> mdi-message-outline</v-icon>
        </v-badge>
        <v-badge color="error" content="6" overlap class="mr-9">
            <v-icon color="grey lighten-1" medium> mdi-bell-outline </v-icon>
        </v-badge>
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
</template>

<script>
import { mapState } from "vuex";
export default {
    props: {
        app: { type: Boolean, default: false },
        elevateOnScroll: { type: Boolean, default: false },
        fixed: { type: Boolean, default: false },
        elevation: { default: 0 },
        flat: { type: Boolean, default: false },
        height: { default: "70px" },
        classes: { default: "" }
    },
    computed: {
        ...mapState(["guard"]),
        isMobile() {
            return this.$vuetify.breakpoint.mdAndDown;
        },
        guard: function() {
            try {
                return this.$store.getters.guard;
            } catch (error) {
                return [];
            }
        }
    },
    methods: {
        signOut: function() {
            this.$auth.signOut();
        }
    }
};
</script>
