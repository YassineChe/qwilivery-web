<template>
    <div>
        <v-row>
            <v-col>
                <!-- HeadLine -->
                <Headline
                    headline="Commandes téléphoniques"
                    subheadline="Traiter les commandes téléphoniques"
                    :headline-classes="[
                        'text-h5',
                        'primary--text',
                        'font-weight-black',
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
                            v-if="!isMobile"
                            @click="init()"
                        >
                            <v-icon>mdi-refresh</v-icon>
                        </v-btn>
                    </template>
                    <span>Rafraîchir</span>
                </v-tooltip>

                <v-btn
                    color="primary"
                    outlined
                    :block="isMobile"
                    :to="{ name: 'dispatch-order' }"
                >
                    <v-icon>mdi-plus</v-icon>
                    Ajouter une commande
                </v-btn>
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
                :items="preorders"
                :headers="headers"
                :search="search"
                :loading="isBusy('fetch-preorders')"
                :disabled="isBusy('fetch-preorders')"
                loading-text="Chargement en cours ..."
                no-data-text="Aucune commande trouvée"
                no-results-text="Aucune commande trouvée"
            >
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
                <!-- Delivered Status -->
                <template v-slot:[`item.delivered_at`]="{ item }">
                    <v-chip
                        small
                        :color="item.delivered_at ? 'success' : 'error'"
                        v-text="item.delivered_at ? 'Livré' : 'En cours'"
                    >
                    </v-chip>
                </template>
                <!-- Order Date -->
                <template v-slot:[`item.created_at`]="{ item }">
                    {{ parseToDate(item.created_at) }}
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
import HandlePhoneOrder from "../pieces/HandlePhoneOrder";
import ViewOrderList from "../pieces/ViewOrderList";
import { mapState } from "vuex";

import moment from "moment";
moment.locale("fr");

export default {
    components: {
        Headline
    },
    data() {
        return {
            initData: {
                path: "/api/fetch/preorders",
                mutation: "FETCH_PREORDERS",
                related: "fetch-preorders"
            },
            search: "",
            headers: [
                { text: "#REF", value: "id" },
                { text: "Nom complet", value: "fullname" },
                { text: "Téléphone", value: "phone_number" },
                { text: "Adresse", value: "address" },
                { text: "Article(s)", value: "orders" },
                { text: "Livré", value: "delivered_at" },
                { text: "Date de commande", value: "created_at" },
                { text: "Actions", value: "actions" }
            ]
        };
    },
    computed: {
        ...mapState(["expected"]),
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
            this.$store.dispatch("fetchData", this.initData);
        },
        //* Parse to date
        parseToDate: function(date) {
            return moment(date).format("MM/D/YYYY H:mm");
        },
        //* Trigger new command
        addPhoneOrder: function() {
            this.$dialog.show(HandlePhoneOrder, {
                persistent: true
            });
        },
        //* Delete order
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
        //* View order details
        orderDetails: function(pre_order_id) {
            this.$dialog.show(ViewOrderList, {
                preOrderId: pre_order_id
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
            //* Delete Order
            {
                this.$callback.handler(
                    this.$dialog,
                    this.$store.getters.expected("delete-order"),
                    {
                        store: this.$store,
                        clear: true,
                        path: this.initData.path,
                        mutation: this.initData.mutation,
                        related: this.initData.path
                    }
                );
            }
        }
    },
    created() {
        this.init();
    }
};
</script>
