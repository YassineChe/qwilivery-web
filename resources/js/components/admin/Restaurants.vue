<template>
    <div>
        <v-row>
            <v-col>
                <!-- HeadLine -->
                <Headline
                    :headline="`(${restaurants.length}) Restaurant(s)`"
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
                <!-- Refresh -->
                <v-tooltip top>
                    <template v-slot:activator="{ on, attrs }">
                        <v-btn
                            v-bind="attrs"
                            v-on="on"
                            color="primary"
                            :block="isMobile"
                            @click="init()"
                        >
                            <v-icon>mdi-refresh</v-icon>
                        </v-btn>
                    </template>
                    <span>Rafraîchir</span>
                </v-tooltip>
                <!-- Add Restaurant -->
                <v-btn
                    color="primary"
                    outlined
                    :block="isMobile"
                    @click="addRestaurant()"
                >
                    <v-icon left>mdi-store</v-icon>
                    Ajouter Restaurant
                </v-btn>
            </v-col>
        </v-row>

        <v-card class="mt-5">
            <v-toolbar flat>
                <v-text-field
                    v-model="search"
                    append-icon="mdi-magnify"
                    label="Rechercher e.g: Nom, E-mail, Téléphone..."
                    hide-details
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
                <!-- Logo -->
                <template v-slot:[`item.logo`]="{ item }">
                    <v-avatar size="40">
                        <img
                            :src="`/images/restaurants_logo/${item.logo}`"
                            alt="John"
                        />
                    </v-avatar>
                </template>

                <!-- Name -->
                <template v-slot:[`item.name`]="{ item }">
                    <v-icon color="error" v-if="item.blocked_at != null"
                        >mdi-cancel</v-icon
                    >
                    <span>{{ item.name }}</span>
                </template>

                <!-- Tax -->
                <template v-slot:[`item.rate`]="{ item }">
                    <span>{{ item.rate }}$</span>
                </template>

                <!-- Actions -->
                <template v-slot:[`item.actions`]="{ item }">
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
                                    @click="deleteRestaurant(item.id)"
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
                                    @click="editRestaurant(item)"
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
                                    @click="blockRestaurant(item.id)"
                                    v-if="item.blocked_at == null"
                                >
                                    <v-icon> mdi-cancel </v-icon>
                                </v-btn>
                            </template>
                            Bloquer
                        </v-tooltip>

                        <!-- Block -->
                        <v-tooltip top>
                            <template v-slot:activator="{ on, attrs }">
                                <v-btn
                                    v-on="on"
                                    v-bind="attrs"
                                    color="success"
                                    fab
                                    x-small
                                    @click="unblockRestaurant(item.id)"
                                    v-if="item.blocked_at != null"
                                >
                                    <v-icon> mdi-undo </v-icon>
                                </v-btn>
                            </template>
                            Débloquer
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
                                    @click="unblockRestaurant(item.id)"
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
                { value: "logo", text: "LOGO" },
                { value: "name", text: "NOM DU RESTAURANT" },
                { value: "email", text: "EMAIL" },
                { value: "phone_number", text: "TÉLÉPHONE" },
                { value: "address", text: "ADRESSE" },
                { value: "rate", text: "TARIF" },
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
        //* Delete Restaurant
        deleteRestaurant: function(restaurant_id) {
            this.$dialog.confirm({
                text: "Êtes-vous sûr de supprimer ce restaurant ?",
                title: "Attention!",
                actions: {
                    false: "Non!",
                    true: {
                        color: "error",
                        text: "Je confirme",
                        handle: () => {
                            if (restaurant_id) {
                                //Delete form store
                                this.$store.commit(
                                    "DELETE_RESTAURANT",
                                    restaurant_id
                                );
                                //Delete it form backend
                                this.$store.dispatch("deleteData", {
                                    path: "/api/delete/restaurant",
                                    data: { restaurant_id: restaurant_id },
                                    related: "delete-restaurant"
                                });
                            }
                        }
                    }
                }
            });
        },
        //* Block Restaurant
        blockRestaurant: function(restaurant_id) {
            if (restaurant_id) {
                //Change it in front
                this.$store.commit("BLOCK_RESTAURANT", restaurant_id);
                // Change it in backend
                this.$store.dispatch("patchData", {
                    path: "/api/block/restaurant",
                    data: { restaurant_id: restaurant_id },
                    related: "block-restaurant"
                });
            }
        },
        //* unBlock Restaurant
        unblockRestaurant: function(restaurant_id) {
            if (restaurant_id) {
                //Change it in front
                this.$store.commit("UNBLOCK_RESTAURANT", restaurant_id);
                // Change it in backend
                this.$store.dispatch("patchData", {
                    path: "/api/unblock/restaurant",
                    data: { restaurant_id: restaurant_id },
                    related: "unblock-restaurant"
                });
            }
        },
        //* Init
        init: function() {
            this.$store.dispatch("fetchData", {
                path: "/api/fetch/restaurants",
                mutation: "FETCH_RESTAURANTS",
                related: "fetch-restaurants"
            });
        },
        //* Add Restaurant
        addRestaurant: function() {
            this.$dialog.show(HandleRestaurant, {
                title: "Ajouter nouveau restaurant"
            });
        },
        editRestaurant: function(restaurant) {
            this.$dialog.show(HandleRestaurant, {
                dataToEdit: restaurant, // Props
                title: "Modifier le restaurant"
            });
        }
    },
    watch: {
        expected() {
            // Add Restaurant
            {
                let expected = this.$store.getters.expected("add-restaurant");

                if (expected != undefined) {
                    if (expected.status === "success") {
                        this.$store.commit("CLEAR_EXPECTED");
                        this.$dialog.notify.success(
                            expected.result.subMessage,
                            {
                                position: "top-right",
                                timeout: 3000
                            }
                        );
                        this.init();
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

            // Edit Restaurant
            {
                let expected = this.$store.getters.expected("edit-restaurant");

                if (expected != undefined) {
                    if (expected.status === "success") {
                        this.$store.commit("CLEAR_EXPECTED");
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
                    //Clear expected
                    this.$store.commit("CLEAR_EXPECTED");
                }
            }

            //Block Restaurant
            {
                let expected = this.$store.getters.expected(
                    "delete-restaurant"
                );
                if (expected != undefined && expected.status === "success") {
                    this.$dialog.notify.success(expected.result.subMessage, {
                        position: "top-right",
                        timeout: 3000
                    });
                    //Clear expected
                    this.$store.commit("CLEAR_EXPECTED");
                }
            }

            //Block Restaurant
            {
                let expected = this.$store.getters.expected("block-restaurant");
                if (expected != undefined && expected.status === "success") {
                    this.$dialog.notify.success(expected.result.subMessage, {
                        position: "top-right",
                        timeout: 3000
                    });
                    //Clear expected
                    this.$store.commit("CLEAR_EXPECTED");
                }
            }

            //Block Restaurant
            {
                let expected = this.$store.getters.expected(
                    "unblock-restaurant"
                );
                if (expected != undefined && expected.status === "success") {
                    this.$dialog.notify.success(expected.result.subMessage, {
                        position: "top-right",
                        timeout: 3000
                    });
                    //Clear expected
                    this.$store.commit("CLEAR_EXPECTED");
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
