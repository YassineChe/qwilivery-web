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
                        'font-weight-black',
                        'text-uppercase'
                    ]"
                />
            </v-col>
        </v-row>

        <!-- Buttons actions -->
        <v-row class="mt-5">
            <v-col :align="!isMobile ? 'right' : ''">
                <v-tooltip top>
                    <template v-slot:activator="{ on, attrs }">
                        <v-btn
                            v-bind="attrs"
                            v-on="on"
                            color="primary"
                            v-if="!isMobile"
                            @click="init()"
                        >
                            <v-icon>mdi-refresh</v-icon>
                        </v-btn>
                    </template>
                    <span>Rafraîchir</span>
                </v-tooltip>

                <v-btn
                    color="primary"
                    outlined
                    :block="isMobile"
                    :loading="isBusy('call-express-delivery')"
                    :disabled="isBusy('call-express-delivery')"
                    @click="expressDelivery()"
                >
                    <v-icon left>mdi-moped-electric</v-icon>
                    Demander un livreur
                </v-btn>
            </v-col>
        </v-row>

        <v-data-table
            class="mt-5"
            :items="expresses"
            :headers="headers"
            :loading="isBusy('fetch-express-deliveries')"
        >
            <template v-slot:[`item.created_at`]="{ item }">
                {{ parseToDate(item.created_at) }}
            </template>
            <!-- Delivery man -->
            <template v-slot:[`item.delivery`]="{ item }">
                <v-avatar size="40" v-if="item.delivery != null">
                    <v-menu>
                        <template v-slot:activator="{ on, attrs }">
                            <img
                                :src="`/images/avatars/${item.delivery.avatar}`"
                                v-bind="attrs"
                                v-on="on"
                            />
                        </template>
                        <v-card>
                            <v-list>
                                <v-list-item>
                                    <v-list-item-icon>
                                        <v-icon v-text="'mdi-moped'"></v-icon>
                                    </v-list-item-icon>
                                    <v-list-item-content>
                                        <v-list-item-title>
                                            {{
                                                `${item.delivery.last_name} ${item.delivery.first_name}`
                                            }}
                                        </v-list-item-title>
                                        <v-list-item-subtitle>
                                            Nom complet
                                        </v-list-item-subtitle>
                                    </v-list-item-content>
                                </v-list-item>
                                <!--  -->
                                <v-list-item>
                                    <v-list-item-icon>
                                        <v-icon v-text="'mdi-phone'"></v-icon>
                                    </v-list-item-icon>

                                    <v-list-item-content>
                                        <v-list-item-title>
                                            {{ item.delivery.phone_number }}
                                        </v-list-item-title>
                                        <v-list-item-subtitle>
                                            Téléphone
                                        </v-list-item-subtitle>
                                    </v-list-item-content>
                                </v-list-item>
                            </v-list>
                        </v-card>
                    </v-menu>
                </v-avatar>
            </template>
        </v-data-table>
    </div>
</template>

<script>
//libs
import { mapState } from "vuex";
import moment from "moment";
moment.locale("fr");
//Component
import Headline from "../pieces/Headline";

export default {
    components: {
        Headline
    },
    data() {
        return {
            headers: [
                { text: "Date de demande", value: "created_at" },
                { text: "Confirmé", value: "taken_at" },
                { text: "Livreur", value: "delivery" }
            ]
        };
    },
    computed: {
        ...mapState(["expected"]),
        expresses: function() {
            return this.$store.getters.expresses;
        },
        //* Is mobile
        isMobile() {
            return this.$vuetify.breakpoint.mobile;
        }
    },
    methods: {
        //* Init
        init: function() {
            this.$store.dispatch("fetchData", {
                path: `/api/fetch/express/deliveries`,
                mutation: "EXPRESS_DELIVERIES",
                related: `fetch-express-deliveries`
            });
        },
        //* Call express delivery
        expressDelivery: function() {
            this.$store.dispatch("postData", {
                path: `/api/call/express/delivery`,
                data: {},
                related: `call-express-delivery`
            });
        },
        //* Parse to date
        parseToDate: function(date) {
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
    watch: {
        expected() {
            //* Add order
            {
                this.$callback.handler(
                    this.$dialog,
                    this.$store.getters.expected("call-express-delivery"),
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
