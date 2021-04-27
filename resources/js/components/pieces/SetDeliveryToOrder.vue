<template>
    <dialogCard
        :title="
            isBusy('fetch-deliveries')
                ? 'Chargement en cours...'
                : `(${deliveries.length}) Livreur(s)`
        "
    >
        <v-card flat :disabled="isBusy('set-delivery')">
            <v-list three-line>
                <template v-for="(delivery, idx) in deliveries">
                    <v-list-item :key="idx">
                        <v-list-item-avatar size="55">
                            <v-img
                                :src="`/images/avatars/${delivery.avatar}`"
                            ></v-img>
                        </v-list-item-avatar>

                        <v-list-item-content>
                            <v-list-item-title
                                v-text="
                                    `${delivery.last_name} ${delivery.first_name}`
                                "
                            ></v-list-item-title>
                            <v-list-item-subtitle>
                                <v-chip
                                    pill
                                    small
                                    color="error"
                                    v-if="delivery.pre_order.length > 0"
                                >
                                    <v-icon left small>
                                        mdi-moped-electric
                                    </v-icon>
                                    {{
                                        `En cours de livraison - (${delivery.pre_order.length}) commande(s)`
                                    }}
                                </v-chip>

                                <v-chip v-else pill small color="success">
                                    <v-icon left small>
                                        mdi-human-male
                                    </v-icon>
                                    Prêt à livrer
                                </v-chip>
                            </v-list-item-subtitle>
                        </v-list-item-content>
                        <v-list-item-action>
                            <v-list-item-action-text>{{
                                `Télé: ${delivery.phone_number}`
                            }}</v-list-item-action-text>
                            <v-btn
                                rounded
                                outlined
                                small
                                color="primary"
                                :loading="setButtonsLoading[idx] ? true : false"
                                @click="setOrder(delivery.id, idx)"
                            >
                                Affecter
                                <v-icon right>mdi-moped</v-icon>
                            </v-btn>
                        </v-list-item-action>
                    </v-list-item>
                </template>
            </v-list>
        </v-card>
    </dialogCard>
</template>

<script>
import { mapState } from "vuex";
export default {
    layout: ["default", { width: "700px" }],
    props: {
        preOrderId: { required: true }
    },
    data() {
        return {
            setButtonsLoading: [],
            deliveries: []
        };
    },
    computed: {
        ...mapState(["expected"])
    },
    methods: {
        setOrder: function(delivery_id, key) {
            //Make Button loading
            Vue.set(this.setButtonsLoading, key, 1);

            this.$store.dispatch("editData", {
                path: "/api/add/delivery/to/order",
                data: {
                    delivery_id: delivery_id,
                    pre_order_id: this.preOrderId
                },
                related: "set-delivery",
                returned: true
            });

            setTimeout(() => {
                Vue.set(this.setButtonsLoading, key, 0);
            }, 1000);
        },
        //* Init
        init: function() {
            this.$store
                .dispatch("fetchParticular", {
                    path: `/api/fetch/deliveries/restricted`,
                    related: "fetch-deliveries"
                })
                .then(response => {
                    this.deliveries = response;
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
                let expected = this.$store.getters.expected("set-delivery");
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
                        this.$dialog.notify.error(expected.result.subMessage, {
                            position: "top-right",
                            timeout: 5000
                        });
                    }
                }
            }
        }
    },
    created() {
        this.init();
    }
};
</script>
