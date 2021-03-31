<template>
    <div>
        <v-card
            elevation="2"
            align="center"
            style="position: fixed; width: 100%; z-index: 2; overflow: hidden"
            class="pb-0"
        >
            <v-app-bar max-width="1200" color="white" elevation="0">
                <v-app-bar-nav-icon
                    v-if="isMobile"
                    @click.stop="drawer = !drawer"
                ></v-app-bar-nav-icon>

                <v-toolbar-title>
                    <img height="50px" src="images/logo.svg" />
                </v-toolbar-title>

                <v-toolbar-items v-if="!isMobile" class="ml-16">
                    <v-btn text v-for="item in navItems" :key="item.id">
                        <router-link
                            tag="span"
                            :to="item.to"
                            v-html="item.title"
                        >
                        </router-link>
                    </v-btn>
                </v-toolbar-items>
                <v-spacer></v-spacer>
                <v-toolbar-items>
                    <div class="text-center mt-3">
                        <v-btn
                            outlined
                            v-if="!isMobile"
                            color="#6c63ff"
                            href="/login"
                        >
                            Sign up / Sign in
                        </v-btn>
                    </div>
                </v-toolbar-items>
            </v-app-bar>
        </v-card>
        <!-- Mobile items start -->

        <v-navigation-drawer v-model="drawer" width="80%" absolute temporary>
            <v-toolbar>
                <v-btn text> LOGO </v-btn>
                <v-spacer></v-spacer>
            </v-toolbar>

            <v-divider></v-divider>

            <v-list dense>
                <v-list-item v-for="item in navItems" :key="item.id" link>
                    <v-list-item-icon>
                        <v-icon>{{ item.icon }}</v-icon>
                    </v-list-item-icon>

                    <v-list-item-content>
                        <v-list-item-title>{{ item.title }}</v-list-item-title>
                    </v-list-item-content>
                </v-list-item>
            </v-list>
            <v-divider></v-divider>
            <v-list-item>
                <v-btn outlined @click="login()"> Sign up / Sign in </v-btn>
            </v-list-item>
        </v-navigation-drawer>

        <v-main>
            <v-row align="center" justify="center" class="mt-16">
                <v-col align="center">
                    <v-card flat>
                        <v-card-text>
                            <!-- HeadLine -->
                            <v-row>
                                <v-col cols="12">
                                    <!-- HeadLine -->
                                    <Headline
                                        headline="Bientôt disponible!"
                                        :headline-classes="[
                                            'text-h4',
                                            'grey--text text--darken-1',
                                            'font-weight-bold'
                                        ]"
                                    >
                                        <template #subheadline>
                                            <span>
                                                Ready2Go en construction..
                                                Spoveup Team.
                                            </span>
                                        </template>
                                    </Headline>
                                </v-col>

                                <v-col cols="12">
                                    <v-img
                                        max-width="400px"
                                        src="images/logo.svg"
                                    ></v-img>
                                </v-col>
                            </v-row>
                        </v-card-text>
                    </v-card>
                </v-col>
            </v-row>
        </v-main>
    </div>
</template>
<script>
import Headline from "../pieces/Headline";
export default {
    components: { Headline },
    data: () => ({
        items: [["Inbox"], ["Supervisors"], ["Clock-in"]],
        navItems: [{ title: "Home", to: "/", icon: "mdi-home" }],
        drawer: false,
        group: null
    }),
    computed: {
        isMobile() {
            return this.$vuetify.breakpoint.smAndDown;
        }
    },
    watch: {
        group() {
            this.drawer = false;
        }
    }
};
</script>
<style scoped>
* {
    text-transform: none !important;
}
</style>
