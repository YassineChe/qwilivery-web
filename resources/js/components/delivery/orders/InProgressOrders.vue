<template>
    <v-card
        :loading="isBusy('fetch-inprogress')"
        :disabled="isBusy('fetch-inprogress')"
    >
        <v-card-text>
            <v-list>
                <template v-for="(preorder, idx) in preorders">
                    <v-divider :key="idx * 265" v-if="idx > 0"></v-divider>
                    <v-list-item :key="idx">
                        <v-list-item-content>
                            <v-timeline align-top dense>
                                <!-- From to -->
                                <v-timeline-item
                                    color="warning"
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
                                <v-timeline-item class="mb-4" hide-dot>
                                    <GmapMap
                                        :center="{
                                            lat: parseFloat(
                                                preorder.restaurant.lat
                                            ),
                                            lng: parseFloat(
                                                preorder.restaurant.lng
                                            )
                                        }"
                                        :zoom="13"
                                        map-type-id="terrain"
                                        style="width: 80%; height: 200px"
                                    >
                                        <GmapMarker
                                            :position="{
                                                lat: parseFloat(
                                                    preorder.restaurant.lat
                                                ),
                                                lng: parseFloat(
                                                    preorder.restaurant.lng
                                                )
                                            }"
                                            :clickable="true"
                                        />
                                    </GmapMap>
                                </v-timeline-item>
                                <!-- to -->
                                <v-timeline-item
                                    icon="mdi-map-marker"
                                    color="warning"
                                >
                                    <div>
                                        <strong>{{ preorder.fullname }}</strong>
                                        <div>
                                            {{ preorder.address }}
                                        </div>
                                    </div>
                                </v-timeline-item>
                            </v-timeline>
                        </v-list-item-content>
                        <v-list-item-action>
                            <v-tooltip top>
                                <template v-slot:activator="{ on, attrs }">
                                    <v-btn
                                        @click="orderDelivered(preorder.id)"
                                        v-bind="attrs"
                                        v-on="on"
                                        color="warning"
                                        fab
                                        small
                                    >
                                        <v-icon>mdi-moped</v-icon>
                                    </v-btn>
                                </template>
                                <span>Livrée ?</span>
                            </v-tooltip>
                        </v-list-item-action>
                    </v-list-item>
                </template>
            </v-list>
        </v-card-text>
    </v-card>
</template>
<script>
import { mapState } from "vuex";
export default {
    computed: {
        ...mapState(["expected"]),
        //* Get Order to be deliver
        preorders: function() {
            return this.$store.getters.preorders;
        }
    },
    methods: {
        //* Init
        init: function() {
            this.$store.dispatch("fetchData", {
                path: "/api/fetch/inprogress/orders",
                mutation: "FETCH_PREORDERS",
                related: "fetch-inprogress"
            });
        },
        //* Take in charge
        orderDelivered: function(preorder_id) {
            this.$store.dispatch("postData", {
                path: "/api/delivered/order",
                data: { id: preorder_id },
                related: "take-in-charge"
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
            //Delete Order
            {
                let expected = this.$store.getters.expected("take-in-charge");
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
                        this.$dialog.notify.error(expected.result.subMessage, {
                            position: "top-right",
                            timeout: 3000
                        });
                    }

                    this.$store.commit("CLEAR_EXPECTED");
                    this.init();
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
