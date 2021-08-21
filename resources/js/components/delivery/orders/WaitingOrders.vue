<template>
    <v-card
        :loading="isBusy('fetch-wait-orders')"
        :disabled="isBusy('fetch-wait-orders')"
    >
        <v-card-text>
            <v-list>
                <div
                    class="text-center"
                    v-if="!isBusy('fetch-inprogress') && preorders.length == 0"
                >
                    <img src="/images/svg/no-data.svg" width="250px" />
                    <p>Aucune commande trouvée</p>
                </div>

                <template v-for="(preorder, idx) in preorders" v-else>
                    <v-divider :key="idx * 265" v-if="idx > 0"></v-divider>
                    <v-list-item :key="idx">
                        <v-list-item-content>
                            <v-timeline align-top dense>
                                <!-- From -->
                                <v-timeline-item
                                    color="error"
                                    icon="mdi-store"
                                    v-if="preorder.restaurant != null"
                                >
                                    <div>
                                        <strong
                                            :class="
                                                preorder.restaurant.deleted_at
                                                    ? 'text-decoration-line-through'
                                                    : ''
                                            "
                                            >{{
                                                preorder.restaurant.name
                                            }}</strong
                                        >
                                        <div>
                                            {{ preorder.restaurant.address }}
                                        </div>
                                    </div>
                                </v-timeline-item>
                                <v-timeline-item
                                    class="mb-4"
                                    hide-dot
                                    v-if="preorder.lat && preorder.lng"
                                >
                                    <GmapMap
                                        :center="{
                                            lat: parseFloat(preorder.lat),
                                            lng: parseFloat(preorder.lng)
                                        }"
                                        :zoom="13"
                                        map-type-id="terrain"
                                        style="width: 80%; height: 200px"
                                    >
                                        <GmapMarker
                                            :position="{
                                                lat: parseFloat(preorder.lat),
                                                lng: parseFloat(preorder.lng)
                                            }"
                                            :clickable="true"
                                        />
                                    </GmapMap>
                                </v-timeline-item>
                                <!-- To -->
                                <v-timeline-item
                                    icon="mdi-map-marker"
                                    color="error"
                                >
                                    <div>
                                        <strong>{{ preorder.fullname }}</strong>

                                        <div>
                                            {{ preorder.address }}
                                        </div>
                                        <span class="grey--text">
                                            Date de commande:
                                            {{
                                                parseToDate(preorder.created_at)
                                            }}
                                        </span>
                                    </div>
                                </v-timeline-item>
                            </v-timeline>
                        </v-list-item-content>
                        <!-- Actions -->
                        <v-list-item-action>
                            <v-layout row>
                                <v-flex>
                                    <v-tooltip top>
                                        <template
                                            v-slot:activator="{ on, attrs }"
                                        >
                                            <v-btn
                                                @click="
                                                    takeInCharge(preorder.id)
                                                "
                                                v-bind="attrs"
                                                v-on="on"
                                                color="error"
                                                fab
                                                small
                                            >
                                                <v-icon
                                                    >mdi-moped-electric</v-icon
                                                >
                                            </v-btn>
                                        </template>
                                        <span>Prendre en charge</span>
                                    </v-tooltip>
                                </v-flex>
                                <v-flex ml-2>
                                    <v-tooltip top>
                                        <template
                                            v-slot:activator="{ on, attrs }"
                                        >
                                            <v-btn
                                                @click="
                                                    orderDetails(preorder.id)
                                                "
                                                v-bind="attrs"
                                                v-on="on"
                                                color="info"
                                                fab
                                                small
                                            >
                                                <v-icon>mdi-food</v-icon>
                                            </v-btn>
                                        </template>
                                        <span>Commande(s)</span>
                                    </v-tooltip>
                                </v-flex>
                            </v-layout>
                        </v-list-item-action>
                    </v-list-item>
                </template>
            </v-list>
        </v-card-text>
    </v-card>
</template>
<script>
import ViewOrderList from "../../pieces/ViewOrderList";
import { mapState } from "vuex";
import moment from "moment";
moment.locale("fr");
// import { gmapApi } from "vue2-google-maps";

export default {
    computed: {
        // google: gmapApi,
        ...mapState(["expected"]),
        //* Get Order to be deliver
        preorders: function() {
            return this.$store.getters.preorders;
        }
    },
    methods: {
        //*
        init: function() {
            this.$store.dispatch("fetchData", {
                path: `/api/fetch/orders/to/deliver`,
                mutation: `FETCH_PREORDERS`,
                related: `fetch-wait-orders`
            });
        },
        //* Take in charge
        takeInCharge: function(preorder_id) {
            this.$store.dispatch("postData", {
                path: "/api/order/take/in/charge",
                data: { id: preorder_id },
                related: "take-in-charge"
            });
        },
        //* Parse to date
        parseToDate: function(date) {
            return moment(date).format("MM/D/YYYY H:mm");
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
            //* Take in charge
            {
                this.$callback.handler(
                    this.$dialog,
                    this.$store.getters.expected("take-in-charge"),
                    {
                        store: this.$store,
                        clear: true,
                        execute: {
                            incase: "success",
                            func: () => {
                                this.init();
                            }
                        }
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
