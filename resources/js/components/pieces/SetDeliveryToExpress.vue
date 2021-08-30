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
                                @click="setExpress(delivery.id, idx)"
                            >
                                Affecter
                                <v-icon right>mdi-moped-electric</v-icon>
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
        expressId: { required: true }
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
        setExpress: function(delivery_id, key) {
            //Make Button loading
            Vue.set(this.setButtonsLoading, key, 1);

            this.$store.dispatch("postData", {
                path: "/api/admin/set/delivery/express",
                data: {
                    delivery_id: delivery_id,
                    express_id: this.expressId
                },
                related: "set-express",
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
                this.$callback.handler(
                    this.$dialog,
                    this.$store.getters.expected("set-express"),
                    {
                        store: this.$store,
                        clear: true
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
