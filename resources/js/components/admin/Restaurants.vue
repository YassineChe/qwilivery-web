<template>
    <div>
        <v-row>
            <v-col>
                <!-- HeadLine -->
                <Headline
                    headline="Restaurants"
                    subheadline="Gestion des restaurants"
                    :headline-classes="[
                        'text-h5',
                        'primary--text',
                        'font-weight-black',
                        'text-uppercase'
                    ]"
                />
            </v-col>
        </v-row>

        <v-row class="mt-5" flat>
            <v-col :align="!isMobile ? 'right' : ''">
                <v-btn
                    color="primary"
                    outlined
                    :block="isMobile"
                    @click="handleRestaurant()"
                >
                    <v-icon left>mdi-silverware-variant</v-icon>
                    Ajouter Restaurant
                </v-btn>
            </v-col>
        </v-row>

        <v-card class="mt-5">
            <v-toolbar flat>
                <v-text-field
                    v-model="search"
                    append-icon="mdi-magnify"
                    label="Search"
                    hide-details
                    dense
                    solo
                    clearable
                >
                </v-text-field>
            </v-toolbar>

            <v-data-table
                :headers="headers"
                :items="restaurants"
                :search="search"
                disable-sort
                item-key="id"
            >
                <template v-slot:[`item.name`]="{ item }">
                    <span>{{ item.name }}</span>
                </template>

                <template v-slot:[`item.status`]="{ item }">
                    <v-chip>
                        <v-switch
                            v-model="item.status"
                            color="primary"
                        ></v-switch>
                    </v-chip>
                </template>

                <template v-slot:[`item.actions`]="{}">
                    <v-speed-dial
                        :v-model="true"
                        direction="left"
                        transition="slide-x-reverse-transition"
                    >
                        <template v-slot:activator>
                            <v-btn small fab color="primary">
                                <v-icon> mdi-dots-horizontal </v-icon>
                            </v-btn>
                        </template>

                        <!-- Delete -->
                        <v-tooltip top>
                            <template v-slot:activator="{ on, attrs }">
                                <v-btn
                                    v-on="on"
                                    v-bind="attrs"
                                    color="error"
                                    fab
                                    x-small
                                >
                                    <v-icon> mdi-delete </v-icon>
                                </v-btn>
                            </template>
                            Supprimer
                        </v-tooltip>
                        <!-- Edit -->
                        <v-tooltip top>
                            <template v-slot:activator="{ on, attrs }">
                                <v-btn
                                    v-on="on"
                                    v-bind="attrs"
                                    color="info"
                                    fab
                                    x-small
                                >
                                    <v-icon> mdi-pen </v-icon>
                                </v-btn>
                            </template>
                            Modifier
                        </v-tooltip>

                        <!-- Block -->
                        <v-tooltip top>
                            <template v-slot:activator="{ on, attrs }">
                                <v-btn
                                    v-on="on"
                                    v-bind="attrs"
                                    color="error"
                                    fab
                                    x-small
                                >
                                    <v-icon> mdi-cancel </v-icon>
                                </v-btn>
                            </template>
                            Bloquer
                        </v-tooltip>
                        <!-- Chatter -->
                        <v-tooltip top>
                            <template v-slot:activator="{ on, attrs }">
                                <v-btn
                                    v-on="on"
                                    v-bind="attrs"
                                    color="primary"
                                    fab
                                    x-small
                                >
                                    <v-icon> mdi-chat </v-icon>
                                </v-btn>
                            </template>
                            Chatter
                        </v-tooltip>
                    </v-speed-dial>
                </template>
            </v-data-table>
        </v-card>
    </div>
</template>

<script>
//Components
import Headline from "../pieces/Headline";
import HandleRestaurant from "../pieces/HandleRestaurant";
//libs
import { mapState } from "vuex";

export default {
    components: {
        Headline
    },
    data() {
        return {
            search: "",
            headers: [
                // { value: "avatar", text: "Photo" },
                { value: "name", text: "NOM DU RESTAURANT" },
                { value: "status", text: "APPROUVER/NON-APPROUVER" },
                { value: "email", text: "EMAIL" },
                { value: "phone", text: "TÉLÉPHONE" },
                { value: "actions" }
            ]
        };
    },
    computed: {
        ...mapState(["expected"]),
        //* Restaurants
        restaurants: function() {
            return this.$store.getters.restaurants;
        },
        //* Is mobile
        isMobile() {
            return this.$vuetify.breakpoint.xsOnly;
        }
    },
    methods: {
        //* Init
        init: function() {
            this.$store.dispatch("fetchData", {
                path: "/api/fetch/restaurants",
                mutation: "FETCH_RESTAURANTS",
                related: "fetch-restaurants"
            });
        },
        //* Add Restaurant
        handleRestaurant: function() {
            this.$dialog.show(HandleRestaurant, {
                width: "40%"
            });
        }
    },
    watch: {
        expected() {
            {
                let expected = this.$store.getters.expected("add-restaurant");

                if (expected != undefined) {
                    if (expected.status === "success") {
                        this.$dialog.notify.success(
                            expected.result.subMessage,
                            {
                                position: "top-right",
                                timeout: 3000
                            }
                        );
                    }
                    if (expected.status === "error") {
                        this.$store.getters
                            .callback(expected.result.subMessage)
                            .forEach(error => {
                                this.$dialog.notify.warning(error, {
                                    position: "top-right",
                                    timeout: 3000
                                });
                            });
                    }
                }
            }
        }
    },
    created() {
        this.$store.commit("CLEAR_EXPECTED");
        this.init();
    }
};
</script>
