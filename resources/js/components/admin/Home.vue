<template>
    <v-row>
        <v-col cols="12" sm="4" md="4" lg="4" class="mb-2">
            <StatisticCard
                :loading="isBusy('fetch-restaurants-count')"
                :text="count_restaurants"
                description="Restaurants"
                icon="mdi-store"
                color="primary"
            />
        </v-col>
        <v-col cols="12" sm="4" md="4" lg="4" class="mb-2">
            <StatisticCard
                :loading="isBusy('fetch-deliveries-count')"
                :text="count_deliveries"
                description="Livreurs"
                icon="mdi-moped"
                color="info"
            />
        </v-col>
        <v-col cols="12" sm="4" md="4" lg="4" class="mb-2">
            <StatisticCard
                :loading="isBusy('fetch-delivered-count')"
                :text="count_delivered"
                description="Commandes livrées"
                icon="mdi-package-variant-closed"
                color="warning"
            />
        </v-col>
    </v-row>
</template>
<script>
import StatisticCard from "../pieces/StatisticCard";
export default {
    components: {
        StatisticCard
    },
    computed: {
        guard: function() {
            return this.$store.getters.guard;
        },
        //* Count deliveries
        count_deliveries: function() {
            try {
                return this.$store.getters.dummies("count_deliveries")[
                    "count_deliveries"
                ];
            } catch (error) {
                return "...";
            }
        },
        //* Count deliveries
        count_restaurants: function() {
            try {
                return this.$store.getters.dummies("count_restaurants")[
                    "count_restaurants"
                ];
            } catch (error) {
                return "...";
            }
        },
        //* Count delivered
        count_delivered: function() {
            try {
                return this.$store.getters.dummies("count_delivered")[
                    "count_delivered"
                ];
            } catch (error) {
                return "...";
            }
        }
    },
    methods: {
        //* Init Data
        init: function() {
            this.$store.dispatch("multipleFetch", [
                {
                    path: "/api/fetch/count/deliveries",
                    mutation: "FETCH_DUMMY",
                    related: "fetch-deliveries-count"
                },
                {
                    path: "/api/fetch/count/restaurants",
                    mutation: "FETCH_DUMMY",
                    related: "fetch-restaurants-count"
                },
                {
                    path: "/api/fetch/count/delivered",
                    mutation: "FETCH_DUMMY",
                    related: "fetch-delivered-count"
                }
            ]);
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
