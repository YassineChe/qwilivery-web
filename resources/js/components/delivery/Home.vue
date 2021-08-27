<template>
    <v-row class="mt-5" v-if="guard">
        <v-row align="center">
            <v-col cols="8">
                <!-- HeadLine -->
                <Headline
                    :headline="`Bonjour, ${guard['last_name']} !`"
                    :headline-classes="[
                        'accent--text',
                        'display-1',
                        'font-weight-bold'
                    ]"
                >
                    <template #subheadline>
                        <span>Livreur</span>
                        <div class="d-flex mt-3">
                            <img
                                width="120px"
                                class="mr-4"
                                src="/images/icon-app-store-badge.png"
                            />
                            <img
                                width="120px"
                                class="mr-4"
                                src="/images/icon-google-play-badge.png"
                            />
                        </div>
                    </template>
                </Headline>
            </v-col>

            <v-col cols="12" sm="4" md="4" lg="4" class="mb-2">
                <StatisticCard
                    :loading="isBusy('fetch-delivered-count')"
                    :text="count_delivered"
                    description="Commade(s) livrées"
                    icon="mdi-moped-electric"
                    color="primary"
                />
            </v-col>
        </v-row>

        <!-- Divider -->
        <v-col cols="12">
            <v-divider />
        </v-col>

        <v-col cols="6">
            <v-card>
                <v-list>
                    <v-list-item>
                        <v-list-item-avatar>
                            <img :src="`/images/avatars/${guard['avatar']}`" />
                        </v-list-item-avatar>

                        <v-list-item-content>
                            <v-list-item-title>{{
                                `${guard["last_name"]} ${guard["first_name"]}`
                            }}</v-list-item-title>
                            <v-list-item-subtitle>Livreur</v-list-item-subtitle>
                        </v-list-item-content>
                    </v-list-item>
                </v-list>

                <v-divider></v-divider>

                <v-list>
                    <!-- Email -->
                    <v-list-item>
                        <v-list-item-icon>
                            <v-icon color="indigo">
                                mdi-email
                            </v-icon>
                        </v-list-item-icon>

                        <v-list-item-content>
                            <v-list-item-title>
                                {{ guard["email"] }}</v-list-item-title
                            >
                            <v-list-item-subtitle
                                >Adresse e-mail</v-list-item-subtitle
                            >
                        </v-list-item-content>
                    </v-list-item>
                    <!-- Number Phone -->
                    <v-list-item>
                        <v-list-item-icon>
                            <v-icon color="indigo">
                                mdi-phone
                            </v-icon>
                        </v-list-item-icon>

                        <v-list-item-content>
                            <v-list-item-title>
                                {{ guard["phone_number"] }}</v-list-item-title
                            >
                            <v-list-item-subtitle
                                >Numéro de téléphone</v-list-item-subtitle
                            >
                        </v-list-item-content>
                    </v-list-item>
                    <!-- Experience -->
                    <v-list-item>
                        <v-list-item-icon>
                            <v-icon color="indigo">
                                mdi-star
                            </v-icon>
                        </v-list-item-icon>

                        <v-list-item-content>
                            <v-list-item-title>
                                {{ guard["experience"] }}
                                An(s)</v-list-item-title
                            >
                            <v-list-item-subtitle
                                >Années d'expérience</v-list-item-subtitle
                            >
                        </v-list-item-content>
                    </v-list-item>
                    <!-- Registration date -->
                    <v-list-item>
                        <v-list-item-icon>
                            <v-icon color="indigo">
                                mdi-calendar
                            </v-icon>
                        </v-list-item-icon>

                        <v-list-item-content>
                            <v-list-item-title>
                                {{ parseToDate(guard["created_at"]) }}
                            </v-list-item-title>
                            <v-list-item-subtitle
                                >Date d'inscription</v-list-item-subtitle
                            >
                        </v-list-item-content>
                    </v-list-item>
                    <!-- Approved -->
                    <v-list-item>
                        <v-list-item-icon>
                            <v-icon color="success">
                                mdi-circle
                            </v-icon>
                        </v-list-item-icon>

                        <v-list-item-content>
                            <v-list-item-title>
                                {{ parseToDate(guard["approved_at"]) }}
                            </v-list-item-title>
                            <v-list-item-subtitle
                                >Approuvé</v-list-item-subtitle
                            >
                        </v-list-item-content>
                    </v-list-item>
                </v-list>
            </v-card>
        </v-col>

        <v-col cols="6">
            <v-card :loading="isBusy('fetch-five-missions')">
                <v-card-title>
                    Dernières (5) livraisons
                </v-card-title>

                <v-card-text>
                    <div v-if="preorders.length">
                        <v-timeline align-top dense>
                            <v-timeline-item
                                v-for="(item, idx) in preorders"
                                :key="idx"
                                :color="item.delivered_at ? 'success' : 'error'"
                                small
                            >
                                <div>
                                    <div>
                                        {{ parseToTime(item.created_at) }}
                                    </div>
                                    <div>{{ item.restaurant.name }}</div>
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
                                    :to="{ name: 'delivery-historic' }"
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
        //* Count delivered
        count_delivered: function() {
            try {
                return this.$store.getters.dummies("count_delivered")[
                    "count_delivered"
                ];
            } catch (error) {
                return "0";
            }
        },
        //* Current guard
        guard: function() {
            return this.$store.getters.guard;
        }
    },
    methods: {
        //* Init
        init: function() {
            this.$store.dispatch("multipleFetch", [
                {
                    path: "/api/fetch/last/five/missions",
                    mutation: "FETCH_PREORDERS",
                    related: "fetch-five-missions"
                },
                {
                    path: "/api/fetch/delivered/by/delivery",
                    mutation: "FETCH_DUMMY",
                    related: "fetch-delivered-count"
                }
            ]);
        },

        parseToTime: function(date) {
            return moment(date).format("MM/D/YYYY H:mm");
        },
        //* Parse to date
        parseToDate: function(date) {
            return moment(date).format("MM/D/YYYY");
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
    beforeCreate() {
        this.$store.commit("FETCH_PREORDERS", []);
        this.$store.commit("CLEAR_EXPECTED");
    },
    created() {
        this.init();
    }
};
</script>
