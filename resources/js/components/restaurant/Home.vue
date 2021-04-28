<template>
    <v-row class="mt-5" v-if="guard">
        <v-row align="center">
            <v-col cols="12" sm="4" md="4" lg="4" class="mb-2">
                <StatisticCard
                    :loading="isBusy('fetch-delivered-count')"
                    :text="count_preorders_restaurant"
                    description="Commade(s) téléphoniques"
                    icon="mdi-phone-forward"
                    color="primary"
                />
            </v-col>

            <v-col cols="12" sm="4" md="4" lg="4" class="mb-2">
                <StatisticCard
                    :loading="isBusy('fetch-delivered-count')"
                    :text="count_delivred_by_restaurant"
                    description="Commade(s) livrées"
                    icon="mdi-moped-electric"
                    color="info"
                />
            </v-col>

            <v-col cols="12" sm="4" md="4" lg="4" class="mb-2">
                <StatisticCard
                    :loading="isBusy('fetch-delivered-count')"
                    :text="count_categories_by_restaurant"
                    description="Menu"
                    icon="mdi-silverware"
                    color="warning"
                />
            </v-col>
        </v-row>
    </v-row>
</template>

<script>
import StatisticCard from "../pieces/StatisticCard";
import Headline from "../pieces/Headline";
export default {
    components: {
        StatisticCard,
        Headline
    },
    computed: {
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
