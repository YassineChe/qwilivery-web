<template>
    <div>
        <v-row>
            <v-col>
                <!-- HeadLine -->
                <Headline
                    headline="Commandes"
                    subheadline="Commandes nécessitant une livraison"
                    :headline-classes="[
                        'text-h5',
                        'primary--text',
                        'font-weight-black',
                        'text-uppercase'
                    ]"
                />
            </v-col>
        </v-row>
        <v-card class="mt-5">
            <v-tabs centered grow>
                <v-tab @change="initWaitingOrders()">
                    <v-icon left> mdi-timer-sand </v-icon>
                    En attente
                </v-tab>
                <v-tab @change="initInProgressOrders()">
                    <v-icon left> mdi-moped </v-icon>
                    En cours
                </v-tab>
                <v-tab-item>
                    <WaitingOrders />
                </v-tab-item>
                <v-tab-item>
                    <InProgressOrders />
                </v-tab-item>
            </v-tabs>
        </v-card>
    </div>
</template>

<script>
import Headline from "../pieces/Headline";
import WaitingOrders from "./orders/WaitingOrders";
import InProgressOrders from "./orders/InProgressOrders";
export default {
    components: {
        Headline,
        WaitingOrders,
        InProgressOrders
    },
    methods: {
        initWaitingOrders: function() {
            this.$store.commit("FETCH_PREORDERS", []);
            this.$store.dispatch("fetchData", {
                path: "/api/fetch/orders/to/deliver",
                mutation: "FETCH_PREORDERS",
                related: "fetch-wait-orders"
            });
        },
        initInProgressOrders: function() {
            this.$store.commit("FETCH_PREORDERS", []);
            this.$store.dispatch("fetchData", {
                path: "/api/fetch/inprogress/orders",
                mutation: "FETCH_PREORDERS",
                related: "fetch-inprogress"
            });
        }
    },
    mounted() {
        let pusher = new Pusher(process.env.MIX_PUSHER_APP_KEY, {
            cluster: "eu"
        });

        //Subscribe to the channel we specified in our Adonis Application
        let channel = pusher.subscribe("new-order-channel");

        channel.bind("new-order", data => {
            alert("new order here");
            this.$notification.show(
                "Nouvelle commande",
                {
                    body:
                        "Nouvelle commande à livrer! Cliquez pour voir les détails"
                },
                {
                    onclick: function() {
                        window.location.href = "/orders";
                    }
                }
            );
        });
    }
};
</script>
