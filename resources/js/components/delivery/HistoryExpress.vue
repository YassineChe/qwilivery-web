<template>
    <div>
        <v-row>
            <v-col>
                <!-- HeadLine -->
                <Headline
                    headline="Express livreur"
                    subheadline="Demander un express livreur"
                    :headline-classes="[
                        'text-h5',
                        'primary--text',
                        'font-weight-bold',
                        'text-uppercase'
                    ]"
                />
            </v-col>
        </v-row>

        <!-- Buttons actions -->
        <v-row class="mt-5">
            <v-col align="right">
                <v-tooltip top>
                    <template v-slot:activator="{ on, attrs }">
                        <v-btn
                            v-bind="attrs"
                            v-on="on"
                            color="primary"
                            @click="init()"
                        >
                            <v-icon>mdi-refresh</v-icon>
                        </v-btn>
                    </template>
                    <span>Rafraîchir</span>
                </v-tooltip>
            </v-col>
        </v-row>

        <!-- Data table -->
        <v-data-table
            class="mt-5 white"
            :items="expresses"
            :headers="headers"
            disable-sort
            :loading="isBusy('fetch-express')"
        >
            <template v-slot:[`item.restaurant`]="{ item }">
                <v-list-item>
                    <v-list-item-avatar>
                        <v-img
                            :src="`/images/avatars/${item.restaurant.avatar}`"
                        >
                        </v-img>
                    </v-list-item-avatar>
                    <v-list-item-content>
                        <v-list-item-title
                            v-html="item.restaurant.name"
                        ></v-list-item-title>
                        <v-list-item-subtitle
                            v-html="item.restaurant.address"
                        ></v-list-item-subtitle>
                    </v-list-item-content>
                </v-list-item>
            </template>
        </v-data-table>
    </div>
</template>

<script>
//libs
import { mapState } from "vuex";
//Components
import Headline from "../pieces/Headline";
export default {
    components: {
        Headline
    },
    data() {
        return {
            headers: [
                { text: "Restaurant", value: "restaurant" },
                { text: "Demmandé", value: "created_at" },
                { text: "Confirmé dans", value: "taken_at" }
            ]
        };
    },
    computed: {
        ...mapState(["expected"]),
        expresses: function() {
            return this.$store.getters.expresses;
        }
    },
    methods: {
        init: function() {
            this.$store.dispatch("fetchData", {
                path: `/api/fetch/express/history`,
                mutation: `EXPRESS_DELIVERIES`,
                related: `fetch-express`
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
    created() {
        this.init();
    }
};
</script>
