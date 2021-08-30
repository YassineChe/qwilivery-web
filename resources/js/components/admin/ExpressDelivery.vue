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
            </v-col>
        </v-row>

        <v-data-table
            class="mt-5"
            :items="expresses"
            :headers="headers"
            :loading="isBusy('fetch-express-deliveries')"
        >
            <!-- Restaurant -->
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
                <v-tooltip top v-else>
                    <template v-slot:activator="{ on, attrs }">
                        <v-chip
                            color="warning"
                            small
                            v-bind="attrs"
                            v-on="on"
                            @click="setDeliveryToExpress(item.id)"
                        >
                            <v-icon left small>mdi-moped-electric</v-icon>
                            En attente
                        </v-chip>
                    </template>
                    <span>Assigner un livreur</span>
                </v-tooltip>
            </template>
            <!-- Taken at date -->
            <template v-slot:[`item.taken_at`]="{ item }">
                <span
                    v-text="item.taken_at == null ? '-' : item.taken_at"
                ></span>
            </template>
            <!-- Delete express -->
            <template v-slot:[`item.actions`]="{ item }">
                <v-btn icon x-small @click="deleteExpress(item.id)">
                    <v-icon color="error">mdi-delete</v-icon>
                </v-btn>
            </template>
        </v-data-table>
    </div>
</template>

<script>
//libs
import { mapState } from "vuex";
//Components
import Headline from "../pieces/Headline";
import SetDeliveryToExpress from "../pieces/SetDeliveryToExpress";

export default {
    components: {
        Headline
    },
    data() {
        return {
            headers: [
                { text: "Restaurant", value: "restaurant" },
                { text: "Date de demande", value: "created_at" },
                { text: "Livreur", value: "delivery" },
                { text: "Confirmé dans", value: "taken_at" },
                { text: "#", value: "actions" }
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
                path: `/api/admin/fetch/express/deliveries`,
                mutation: "EXPRESS_DELIVERIES",
                related: `fetch-express-deliveries`
            });
        },
        //* elete express
        deleteExpress: function(express_id) {
            this.$store.dispatch("deleteData", {
                path: `/api/admin/delete/express/delivery`,
                data: { express_id: express_id },
                related: `delete-express`
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
        },
        setDeliveryToExpress: function(express_id) {
            this.$dialog.show(SetDeliveryToExpress, {
                expressId: express_id
            });
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

            {
                this.$callback.handler(
                    this.$dialog,
                    this.$store.getters.expected("delete-express"),
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

            {
                this.$callback.handler(
                    this.$dialog,
                    this.$store.getters.expected("assign-delivery"),
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
