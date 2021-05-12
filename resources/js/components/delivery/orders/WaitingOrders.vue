<template>
    <v-card
        :loading="isBusy('fetch-wait-orders')"
        :disabled="isBusy('fetch-wait-orders')"
    >
        <v-card-text>
            <v-list>
                <template v-for="(preorder, idx) in preorders">
                    <v-divider :key="idx * 265" v-if="idx > 0"></v-divider>
                    <v-list-item :key="idx">
                        <v-list-item-content>
                            <v-timeline align-top dense>
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
                                <!--  -->
                                <v-timeline-item
                                    icon="mdi-map-marker"
                                    color="error"
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
                                        v-bind="attrs"
                                        v-on="on"
                                        color="error"
                                        fab
                                        small
                                        @click="takeInCharge(preorder.id)"
                                    >
                                        <v-icon>mdi-moped</v-icon>
                                    </v-btn>
                                </template>
                                <span>Prendre en charge</span>
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
        clear: function() {
            alert("cc");
        },
        //*
        init: function() {
            this.$store.dispatch("fetchData", {
                path: "/api/fetch/orders/to/deliver",
                mutation: "FETCH_PREORDERS",
                related: "fetch-wait-orders"
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
