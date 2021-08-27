<template>
    <div>
        <v-row>
            <v-col>
                <!-- HeadLine -->
                <Headline
                    headline="Historiques"
                    :subheadline="`(${preorders.length}) Commande(s)`"
                    :headline-classes="[
                        'text-h5',
                        'primary--text',
                        'font-weight-bold',
                        'text-uppercase'
                    ]"
                />
            </v-col>
        </v-row>

        <!-- Buttons actions -->
        <v-row class="mt-5">
            <v-col :align="!isMobile ? 'right' : ''">
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
            </v-col>
        </v-row>

        <v-card class="mt-5">
            <!-- Search -->
            <v-toolbar flat>
                <v-text-field
                    v-model="search"
                    append-icon="mdi-magnify"
                    label="Rechercher"
                    hide-details
                    solo
                    clearable
                ></v-text-field>
            </v-toolbar>
            <!-- Table -->
            <v-data-table
                :search="search"
                :headers="headers"
                :items="preorders"
                :loading="isBusy('fetch-historic')"
                :disabled="isBusy('fetch-historic')"
                loading-text="Chargement en cours ..."
                no-data-text="Aucune commande trouvée"
                no-results-text="Aucune commande trouvée"
            >
                <!-- Orders -->
                <template v-slot:[`item.id`]="{ item }">
                    {{ item.id }}
                    <v-tooltip top>
                        <template v-slot:activator="{ on, attrs }">
                            <v-icon
                                v-if="item.deleted_at"
                                color="error"
                                v-bind="attrs"
                                v-on="on"
                                small
                            >
                                mdi-cancel
                            </v-icon>
                        </template>
                        <span>{{
                            `Commande supprimée par le restaurant : ${parseToDate(
                                item.deleted_at
                            )}`
                        }}</span>
                    </v-tooltip>
                </template>
                <!-- Orders -->
                <template v-slot:[`item.orders`]="{ item }">
                    <v-tooltip top>
                        <template v-slot:activator="{ on, attrs }">
                            <v-btn
                                v-bind="attrs"
                                v-on="on"
                                fab
                                x-small
                                color="primary"
                                @click="orderDetails(item.id)"
                            >
                                {{ item.orders.length }}
                            </v-btn>
                        </template>
                        <span>Nombre d'articles commandés</span>
                    </v-tooltip>
                </template>
                <!-- Delivery -->
                <template v-slot:[`item.delivery`]="{ item }">
                    <v-avatar size="40" v-if="item.delivery != null">
                        <v-tooltip top>
                            <template v-slot:activator="{ on, attrs }">
                                <img
                                    v-bind="attrs"
                                    v-on="on"
                                    :src="
                                        `/images/avatars/${item.delivery.avatar}`
                                    "
                                />
                            </template>
                            <span>{{
                                `${item.delivery.last_name} ${item.delivery.first_name}`
                            }}</span>
                        </v-tooltip>
                    </v-avatar>

                    <v-tooltip top v-else>
                        <template v-slot:activator="{ on, attrs }">
                            <v-btn
                                v-bind="attrs"
                                v-on="on"
                                fab
                                x-small
                                color="warning"
                                @click="setDeliveryToOrder(item.id)"
                            >
                                <v-icon>mdi-moped</v-icon>
                            </v-btn>
                        </template>
                        <span>Définir un livreur</span>
                    </v-tooltip>
                </template>
                <!-- Order Date -->
                <template v-slot:[`item.created_at`]="{ item }">
                    {{ parseToDate(item.created_at) }}
                </template>
                <!-- Delivered Status -->
                <template v-slot:[`item.delivered_at`]="{ item }">
                    <v-chip
                        small
                        :color="
                            order_status_color(
                                item.delivery_id,
                                item.delivered_at
                            )
                        "
                    >
                        {{
                            order_status_text(
                                item.delivery_id,
                                item.delivered_at
                            )
                        }}
                    </v-chip>
                </template>
                <!-- Actions -->
                <template v-slot:[`item.actions`]="{ item }">
                    <!-- Delete Order -->
                    <v-icon small color="error" @click="deleteOrder(item.id)">
                        mdi-delete
                    </v-icon>
                </template>
            </v-data-table>
        </v-card>
    </div>
</template>

<script>
import Headline from "../pieces/Headline";
import ViewOrderList from "../pieces/ViewOrderList";
import SetDeliveryToOrder from "../pieces/SetDeliveryToOrder";
import { mapState } from "vuex";

import moment from "moment";
moment.locale("fr");

export default {
    components: {
        Headline
    },
    data() {
        return {
            search: "",
            headers: [
                { text: "#REF", value: "id" },
                { text: "Nom complet", value: "fullname" },
                { text: "Téléphone", value: "phone_number" },
                { text: "Adresse", value: "address" },
                { text: "Article(s)", value: "orders" },
                { text: "Statut livraison", value: "delivered_at" },
                { text: "Date de commande", value: "created_at" }
            ]
        };
    },
    computed: {
        ...mapState(["expected"]),
        //* Preorders (Orders)
        preorders: function() {
            return this.$store.getters.preorders;
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
                path: "/api/fetch/delivery/historic",
                mutation: "FETCH_PREORDERS",
                related: "fetch-historic"
            });
        },
        //* View order details
        orderDetails: function(pre_order_id) {
            this.$dialog.show(ViewOrderList, {
                preOrderId: pre_order_id
            });
        },
        //* Delete
        deleteOrder: function(order_id) {
            this.$dialog.confirm({
                text: "Etes-vous sûr de supprimer cette commande ?",
                title: "Attention!",
                actions: {
                    false: "Non!",
                    true: {
                        color: "red",
                        text: "Je confirme",
                        handle: () => {
                            //Delete it form backend
                            this.$store.dispatch("deleteData", {
                                path: `/api/delete/order/${order_id}`,
                                related: "delete-order"
                            });
                        }
                    }
                }
            });
        },
        //* Parse to date
        parseToDate: function(date) {
            return moment(date).format("MM/D/YYYY H:mm");
        },
        setDeliveryToOrder: function(pre_order_id) {
            this.$dialog.show(SetDeliveryToOrder, {
                preOrderId: pre_order_id
            });
        },
        order_status_text: function(delivery_id, delivered_at) {
            if (delivery_id == null && delivered_at == null)
                return "En attente";

            if (delivery_id != null && delivered_at == null) return "En cours";
            if (delivery_id != null && delivered_at != null) return "Livrée";
        },
        order_status_color: function(delivery_id, delivered_at) {
            if (delivery_id == null && delivered_at == null) return "error";
            if (delivery_id != null && delivered_at == null) return "warning";
            if (delivery_id != null && delivered_at != null) return "success";
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
            //Delete Order
            {
                this.$callback.handler(
                    this.$dialog,
                    this.$store.getters.expected("delete-order")
                );
            }
        }
    },
    beforeCreate() {
        this.$store.commit("FETCH_PREORDERS", []);
        this.$store.commit("CLEAR_EXPECTED");
    },
    created() {
        this.init();
    }
};
</script>
