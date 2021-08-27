<template>
    <div>
        <v-row>
            <v-col>
                <!-- HeadLine -->
                <Headline
                    :headline="`(${deliveries.length}) Livreur(s)`"
                    subheadline="Gérer les livreurs"
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
                <!-- Add delivery -->
                <v-btn
                    color="primary"
                    outlined
                    :block="isMobile"
                    @click="addDelivery()"
                >
                    <v-icon left>mdi-moped</v-icon>
                    Ajouter Livreur
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
                :items="deliveries"
                :search="search"
                :loading="
                    isBusy('fetch-deliveries') || isBusy('approve-delivery')
                "
                :disabled="
                    isBusy('fetch-deliveries') || isBusy('approve-delivery')
                "
                disable-sort
                item-key="id"
            >
                <template v-slot:[`item.name`]="{ item }">
                    <v-icon color="error" v-if="item.blocked_at != null"
                        >mdi-cancel</v-icon
                    >
                    <span>{{ item.first_name + " " + item.last_name }}</span>
                </template>

                <!-- Avatar -->
                <template v-slot:[`item.avatar`]="{ item }">
                    <v-avatar size="40">
                        <img :src="`/images/avatars/${item.avatar}`" />
                    </v-avatar>
                </template>

                <!-- Approve -->
                <template v-slot:[`item.approved_at`]="{ item }">
                    <v-switch
                        :disabled="item.approved_at ? true : false"
                        v-model="item.approved_at"
                        @change="approveDelivery(item.id)"
                        :color="item.approved_at ? 'success' : 'primary'"
                    ></v-switch>
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
                                    @click="deleteDelivery(item.id)"
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
                                    @click="editDelivery(item)"
                                >
                                    <v-icon> mdi-pen </v-icon>
                                </v-btn>
                            </template>
                            Modifier
                        </v-tooltip>
                        <!-- Permis de conduire -->
                        <v-tooltip top>
                            <template v-slot:activator="{ on, attrs }">
                                <v-btn
                                    v-on="on"
                                    v-bind="attrs"
                                    color="warning"
                                    fab
                                    x-small
                                >
                                    <v-icon> mdi-card-account-details </v-icon>
                                </v-btn>
                            </template>
                            Télécharger permis
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
                                    @click="blockDelivery(item.id)"
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
                                    @click="unblockDelivery(item.id)"
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
import HandleDelivery from "../pieces/HandleDelivery";
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
                { value: "avatar", text: "Photo" },
                { value: "name", text: "NOM ET PRÉNOM" },
                { value: "approved_at", text: "APPROUVER" },
                { value: "email", text: "EMAIL" },
                { value: "phone_number", text: "TÉLÉPHONE" },
                { value: "actions" }
            ]
        };
    },
    computed: {
        ...mapState(["expected"]),
        //*Get no blocked delivery
        deliveries: function() {
            return this.$store.getters.deliveries == null
                ? []
                : this.$store.getters.deliveries;
        },
        //* Is mobile
        isMobile() {
            return this.$vuetify.breakpoint.xsOnly;
        }
    },
    methods: {
        //* Init
        init() {
            //* Get all deliveries
            this.$store.dispatch("fetchData", {
                path: "/api/fetch/deliveries",
                mutation: "FETCH_DELIVERIES",
                related: "fetch-deliveries"
            });
        },
        //* Add delivery
        addDelivery: function() {
            this.$store.commit("CLEAR_EXPECTED");

            this.$dialog.show(HandleDelivery, {
                title: "Ajouter nouveau Livreur"
            });
        },
        //* Edit delivery
        editDelivery: function(Delivery) {
            this.$store.commit("CLEAR_EXPECTED");

            this.$dialog.show(HandleDelivery, {
                deliveryToEdit: Delivery, // Props
                title: "Modifier le Livreur"
            });
        },
        // * Edit approvement delivery man.
        approveDelivery(delivery_id) {
            this.$store.dispatch("postData", {
                path: `/api/approved/delivery-man`,
                data: { delivery_id: delivery_id },
                related: "approve-delivery"
            });
        },
        //* Delete delivery
        async deleteDelivery(delivery_id) {
            this.$store.commit("CLEAR_EXPECTED");
            this.$dialog.confirm({
                text: "Êtes-vous sûr de supprimer ce livreur ?",
                title: "Attention!",
                actions: {
                    false: "Non!",
                    true: {
                        color: "red",
                        text: "Je confirme",
                        handle: () => {
                            this.$store.dispatch("deleteData", {
                                path: `/api/delete/delivery-man`,
                                data: { delivery_id: delivery_id },
                                related: `delete-delivery-man`
                            });
                            this.dummy = delivery_id;
                        }
                    }
                }
            });
        },
        //* Block delivery
        blockDelivery(delivery_id) {
            this.$store.commit("CLEAR_EXPECTED");

            this.$dialog.confirm({
                text: "Êtes-vous sûr de bloquer ce livreur ?",
                title: "Attention!",
                actions: {
                    false: "Non!",
                    true: {
                        color: "red",
                        text: "Je confirme",
                        handle: () => {
                            this.$store.dispatch("patchData", {
                                path: `/api/block/delivery-man`,
                                data: { delivery_id: delivery_id },
                                related: `block-delivery-man`,
                                returned: true
                            });
                            this.$store.commit("BLOCK_DELIVERY", delivery_id);
                        }
                    }
                }
            });
        },
        //* unBlock delivery
        unblockDelivery: function(delivery_id) {
            this.$store.commit("CLEAR_EXPECTED");

            if (delivery_id) {
                this.$dialog.confirm({
                    text: "Êtes-vous sûr de débloquer ce livreur ?",
                    title: "Attention!",
                    actions: {
                        false: "Non!",
                        true: {
                            color: "red",
                            text: "Je confirme",
                            handle: () => {
                                // Change it in backend
                                this.$store.dispatch("patchData", {
                                    path: "/api/unblock/delivery-man",
                                    data: { delivery_id: delivery_id },
                                    related: "unblock-delivery"
                                });
                                //Change it in front
                                this.$store.commit(
                                    "UNBLOCK_DELIVERY",
                                    delivery_id
                                );
                            }
                        }
                    }
                });
            }
        },
        handleChat: function(restaurant_id) {
            this.$dialog.show(HandleChat, {
                guard_id: restaurant_id,
                guard: "delivery"
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
            //* Approuve delivery
            {
                this.$callback.handler(
                    this.$dialog,
                    this.$store.getters.expected("approve-delivery")
                );
            }

            //* Delete delivery
            {
                this.$callback.handler(
                    this.$dialog,
                    this.$store.getters.expected("delete-delivery-man")
                );
            }

            //* Block delivery
            {
                this.$callback.handler(
                    this.$dialog,
                    this.$store.getters.expected("block-delivery-man")
                );
            }

            //* Unblock delivery
            {
                this.$callback.handler(
                    this.$dialog,
                    this.$store.getters.expected("unblock-delivery")
                );
            }

            //*  Add delivery
            {
                this.$callback.handler(
                    this.$dialog,
                    this.$store.getters.expected("add-delivery")
                );
            }

            //* Edit delivery
            {
                this.$callback.handler(
                    this.$dialog,
                    this.$store.getters.expected("edit-delivery")
                );
            }

            //* Send message
            {
                this.$callback.handler(
                    this.$dialog,
                    this.$store.getters.expected("send-message")
                );
            }
        }
    },
    created() {
        this.init();
    }
};
</script>
