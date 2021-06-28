<template>
    <v-row class="mt-5" v-if="guard">
        <v-row>
            <v-col cols="12" sm="4" md="4" lg="4" class="mb-2">
                <StatisticCard
                    :loading="isBusy('fetch-delivered-count')"
                    :text="count_preorders_restaurant"
                    description="Commade(s) téléphoniques"
                    icon="mdi-phone-forward"
                    color="primary"
                    :to="{ name: 'restaurant-order' }"
                />
            </v-col>

            <v-col cols="12" sm="4" md="4" lg="4" class="mb-2">
                <StatisticCard
                    :loading="isBusy('fetch-delivered-count')"
                    :text="count_delivred_by_restaurant"
                    description="Commade(s) livrées"
                    icon="mdi-moped-electric"
                    color="info"
                    :to="{ name: 'restaurant-order' }"
                />
            </v-col>

            <v-col cols="12" sm="4" md="4" lg="4" class="mb-2">
                <StatisticCard
                    :loading="isBusy('fetch-delivered-count')"
                    :text="count_categories_by_restaurant"
                    description="Menu"
                    icon="mdi-silverware"
                    color="warning"
                    :to="{ name: 'menu' }"
                />
            </v-col>

            <v-col cols="12" sm="6" md="6" lg="6" class="mb-2">
                <v-card :loading="isBusy('fetch-teen-missions')">
                    <v-card-title>
                        Dernières (10) livraisons
                    </v-card-title>

                    <v-card-text>
                        <div v-if="preorders.length">
                            <v-timeline align-top dense>
                                <v-timeline-item
                                    v-for="(item, idx) in preorders"
                                    :key="idx"
                                    :color="
                                        item.delivered_at ? 'success' : 'error'
                                    "
                                    small
                                >
                                    <div>
                                        <div>
                                            <strong>{{
                                                item.fullname.toUpperCase()
                                            }}</strong>
                                            —
                                            {{ parseToTime(item.created_at) }}
                                        </div>
                                        <div>{{ item.address }}</div>
                                    </div>
                                </v-timeline-item>
                            </v-timeline>
                        </div>
                        <div v-else class="text-center">
                            <img src="/images/svg/no-data.svg" width="250px" />
                            <p>Aucune commande trouvée</p>
                        </div>
                    </v-card-text>
                    <v-divider />
                    <v-card-actions v-if="preorders.length">
                        <div>
                            <v-icon color="error" small>mdi-circle</v-icon>
                            <small>En cours</small>
                        </div>
                        <v-spacer></v-spacer>
                        <div>
                            <v-icon color="success" small>mdi-circle</v-icon>
                            <small>Livré</small>
                        </div>
                        <v-spacer></v-spacer>
                        <div>
                            <v-tooltip top>
                                <template v-slot:activator="{ on, attrs }">
                                    <v-btn
                                        v-bind="attrs"
                                        v-on="on"
                                        icon
                                        outlined
                                        color="primary"
                                        :to="{ name: 'restaurant-order' }"
                                    >
                                        <v-icon>mdi-eye-plus-outline</v-icon>
                                    </v-btn>
                                </template>
                                <span>Voir plus</span>
                            </v-tooltip>
                        </div>
                    </v-card-actions>
                </v-card>
            </v-col>
        </v-row>
    </v-row>
</template>

<script>
import StatisticCard from "../pieces/StatisticCard";
import Headline from "../pieces/Headline";
import moment from "moment";
moment.locale("fr");
export default {
    components: {
        StatisticCard,
        Headline
    },
    computed: {
        //* Last five orders status
        preorders: function() {
            return this.$store.getters.preorders;
        },
        //* Current guard
        guard: function() {
            return this.$store.getters.guard;
        },
        count_preorders_restaurant: function() {
            try {
                return this.$store.getters.dummies(
                    "count_preorders_restaurant"
                )["count_preorders_restaurant"];
            } catch (error) {
                return "0";
            }
        },
        count_delivred_by_restaurant: function() {
            try {
                return this.$store.getters.dummies(
                    "count_delivred_by_restaurant"
                )["count_delivred_by_restaurant"];
            } catch (error) {
                return "0";
            }
        },
        count_categories_by_restaurant: function() {
            try {
                return this.$store.getters.dummies(
                    "count_categories_by_restaurant"
                )["count_categories_by_restaurant"];
            } catch (error) {
                return "0";
            }
        }
    },
    methods: {
        //* Init
        init: function() {
            this.$store.dispatch("multipleFetch", [
                {
                    path: "/api/fetch/count/restaurant/categories",
                    mutation: "FETCH_DUMMY",
                    related: "count-cate-rest"
                },
                {
                    path: "/api/fetch/count/restaurant/preorder",
                    mutation: "FETCH_DUMMY",
                    related: "count-preorder-rest"
                },
                {
                    path: "/api/fetch/count/restaurant/delivered",
                    mutation: "FETCH_DUMMY",
                    related: "count-delivred-rest"
                },
                {
                    path: "/api/fetch/last/teen/missions",
                    mutation: "FETCH_PREORDERS",
                    related: "fetch-teen-missions"
                }
            ]);
        },
        //* Parse to time
        parseToTime: function(date) {
            return moment(date).format("MM/D/YYYY H:mm");
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
    created() {
        this.init();
    }
};
</script>
