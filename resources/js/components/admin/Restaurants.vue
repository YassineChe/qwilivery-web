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
                        'font-weight-bold',
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
                :loading="
                    isBusy('fetch-restaurants') || isBusy('approve-restaurant')
                "
                :disabled="
                    isBusy('fetch-restaurants') || isBusy('approve-restaurant')
                "
                disable-sort
                item-key="id"
            >
                <!-- Logo -->
                <template v-slot:[`item.logo`]="{ item }">
                    <v-avatar size="40">
                        <img :src="`/images/avatars/${item.avatar}`" />
                    </v-avatar>
                </template>

                <!-- Name -->
                <template v-slot:[`item.name`]="{ item }">
                    <v-icon color="error" v-if="item.blocked_at != null"
                        >mdi-cancel</v-icon
                    >
                    <span>{{ item.name }}</span>
                </template>

                <!-- Approve -->
                <template v-slot:[`item.approved_at`]="{ item }">
                    <v-switch
                        :disabled="item.approved_at ? true : false"
                        v-model="item.approved_at"
                        @change="approveRestaurant(item.id)"
                        :color="item.approved_at ? 'success' : 'primary'"
                    ></v-switch>
                </template>

                <!-- Tax -->
                <template v-slot:[`item.rate`]="{ item }">
                    <span>{{ item.rate }} {{ item.rate ? "$" : "-" }}</span>
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

                        <!-- unBlock -->
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
                                    @click="handleChat(item.id)"
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
import HandleChat from "../pieces/HandleChat";
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
                { value: "approved_at", text: "APPROUVER" },
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
        // * Edit approvement delivery man.
        approveRestaurant(restaurant_id) {
            this.$store.dispatch("postData", {
                path: `/api/approved/restaurant`,
                data: { restaurant_id: restaurant_id },
                related: "approve-restaurant"
            });
        },
        //* Edit Resataurant
        editRestaurant: function(restaurant) {
            this.$dialog.show(HandleRestaurant, {
                dataToEdit: restaurant, // Props
                title: "Modifier le restaurant"
            });
        },
        //* Send message
        handleChat: function(restaurant_id) {
            this.$dialog.show(HandleChat, {
                guard_id: restaurant_id,
                guard: "restaurant"
            });
        },
        //* The famous isBusy funtion haha
        isBusy: function(fetcher) {
            try {
                return this.$store.getters.expected(fetcher).status == "busy"
                    ? true
                    : false;
            } catch (error) {
                return false;
            }
        }
    },
    watch: {
        expected() {
            //* Approuve restaurant
            {
                this.$callback.handler(
                    this.$dialog,
                    this.$store.getters.expected("approve-restaurant"),
                    { store: this.$store, clear: true }
                );
            }

            //* Add Restaurant
            {
                this.$callback.handler(
                    this.$dialog,
                    this.$store.getters.expected("add-restaurant"),
                    { store: this.$store, clear: true }
                );
            }

            //* Edit Restaurant
            {
                this.$callback.handler(
                    this.$dialog,
                    this.$store.getters.expected("edit-restaurant"),
                    { store: this.$store, clear: true }
                );
            }

            //* Block Restaurant
            {
                this.$callback.handler(
                    this.$dialog,
                    this.$store.getters.expected("block-restaurant"),
                    { store: this.$store, clear: true }
                );
            }

            //* Unblock Restaurant
            {
                this.$callback.handler(
                    this.$dialog,
                    this.$store.getters.expected("unblock-restaurant"),
                    { store: this.$store, clear: true }
                );
            }

            //* Send message
            {
                this.$callback.handler(
                    this.$dialog,
                    this.$store.getters.expected("send-message"),
                    { store: this.$store, clear: true }
                );
            }
        }
    },
    created() {
        this.$store.commit("CLEAR_EXPECTED");
        this.init();
    }
};
</script>
