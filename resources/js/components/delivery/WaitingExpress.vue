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
        <!-- Data table -->

        <v-data-table
            class="mt-5 white"
            :items="expresses"
            :headers="headers"
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
            <!-- Actions -->
            <template v-slot:[`item.actions`]="{ item }">
                <v-btn
                    x-small
                    fab
                    color="primary"
                    @click="takeExpress(item.id)"
                >
                    <v-icon>mdi-moped-electric</v-icon>
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
export default {
    components: {
        Headline
    },
    data() {
        return {
            headers: [
                { text: "Restaurant", value: "restaurant" },
                { text: "Demmandé", value: "created_at" },
                { text: "#", value: "actions" }
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
                path: `/api/fetch/express/calls`,
                mutation: `EXPRESS_DELIVERIES`,
                related: `fetch-express`
            });
        },
        takeExpress: function(express_id) {
            this.$store.dispatch("postData", {
                path: `/api/take/express`,
                data: { id: express_id },
                related: "take-express"
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
            //* Add order
            {
                this.$callback.handler(
                    this.$dialog,
                    this.$store.getters.expected("take-express"),
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
