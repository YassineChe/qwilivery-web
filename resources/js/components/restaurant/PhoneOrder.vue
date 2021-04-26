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
                <v-btn color="primary" v-if="!isMobile" @click="init()">
                    <v-icon>mdi-refresh</v-icon>
                </v-btn>

                <v-btn
                    color="primary"
                    outlined
                    :block="isMobile"
                    @click="addPhoneOrder()"
                >
                    <v-icon>mdi-plus</v-icon>
                    Ajouter une commande
                </v-btn>
            </v-col>
        </v-row>

        <v-card class="mt-5">
            <template>
                <v-data-table
                    :items="preorders"
                    :headers="headers"
                    :loading="isBusy('fetch-preorders')"
                    loading-text="Chargement en cours ..."
                    :disabled="isBusy('fetch-preorders')"
                >
                    <!-- Orders -->
                    <template v-slot:[`item.orders`]="{ item }">
                        <v-btn fab x-small color="primary">
                            {{ item.orders.length }}
                        </v-btn>
                    </template>
                    <!-- Delivered Status -->
                    <template v-slot:[`item.delivered_at`]="{ item }">
                        <v-chip
                            small
                            :color="item.delivered_at ? 'success' : 'error'"
                            v-text="item.delivered_at ? 'Livré' : 'En cours'"
                        ></v-chip>
                    </template>
                    <!-- Actions -->
                    <template v-slot:[`item.actions`]="{ item }">
                        <!-- Delete Order -->
                        <v-icon
                            small
                            color="error"
                            @click="deleteOrder(item.id)"
                        >
                            mdi-delete
                        </v-icon>
                        <!--  -->
                        <v-icon
                            small
                            color="info"
                            @click="orderDetails(item.id)"
                        >
                            mdi-eye
                        </v-icon>
                    </template>
                </v-data-table>
            </template>
        </v-card>
    </div>
</template>

<script>
import Headline from "../pieces/Headline";
import HandlePhoneOrder from "../pieces/HandlePhoneOrder";
import ViewOrderList from "../pieces/ViewOrderList";
import { mapState } from "vuex";

export default {
    components: {
        Headline
    },
    data() {
        return {
            headers: [
                { text: "#REF", value: "id" },
                { text: "Nom complet", value: "fullname" },
                { text: "Téléphone", value: "phone_number" },
                { text: "Adresse", value: "address" },
                { text: "Commande(S)", value: "orders" },
                { text: "Livré", value: "delivered_at" },
                { text: "Actions", value: "actions" }
            ],
            orderedVariants: [{}]
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
            this.$store.dispatch("fetchData", {
                path: "/api/fetch/preorders",
                mutation: "FETCH_PREORDERS",
                related: "fetch-preorders"
            });
        },
        //* Trigger new command
        addPhoneOrder: function() {
            this.$dialog.show(HandlePhoneOrder);
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
            // Add variant
            {
                let expected = this.$store.getters.expected("add-order");
                if (expected != undefined) {
                    //Clear expected
                    this.$store.commit("CLEAR_EXPECTED");

                    if (expected.status === "success") {
                        this.$dialog.notify.success(
                            expected.result.subMessage,
                            {
                                position: "top-right",
                                timeout: 3000
                            }
                        );

                        this.init();
                    }
                }
            }
            //Delete Order
            {
                let expected = this.$store.getters.expected("delete-order");
                if (expected != undefined && expected.status === "success") {
                    this.$store.commit("CLEAR_EXPECTED");
                    this.$dialog.notify.success(expected.result.subMessage, {
                        position: "top-right",
                        timeout: 3000
                    });
                    this.init();
                }
            }
        }
    },
    created() {
        this.init();
    }
};
</script>
