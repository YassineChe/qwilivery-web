<template>
    <div>
        <v-row>
            <v-col>
                <!-- HeadLine -->
                <Headline
                    headline="Paramètres"
                    subheadline="Paramètres généraux de l'application"
                    :headline-classes="[
                        'text-h5',
                        'primary--text',
                        'font-weight-black',
                        'text-uppercase'
                    ]"
                />
            </v-col>
        </v-row>

        <v-row>
            <v-col cols="12">
                <validation-observer ref="observer" v-slot="{ invalid }">
                    <v-card :loading="isBusy('fetch-app-settings')">
                        <v-card-title>
                            Commande notification
                        </v-card-title>
                        <v-card-text>
                            <v-row>
                                <v-col cols="12">
                                    <validation-provider
                                        v-slot="{
                                            errors
                                        }"
                                        name="Titre de la commande"
                                        rules="required|max:50"
                                    >
                                        <v-text-field
                                            prepend-inner-icon="mdi-bell"
                                            label="Titre"
                                            hide-details="auto"
                                            :error-messages="errors"
                                            dense
                                            solo
                                            clearable
                                            v-model="appSettings.order_title"
                                        >
                                        </v-text-field>
                                    </validation-provider>
                                </v-col>
                                <v-col cols="12">
                                    <validation-provider
                                        v-slot="{
                                            errors
                                        }"
                                        name="Description de la commande"
                                        rules="required|max:100"
                                    >
                                        <v-textarea
                                            hide-details="auto"
                                            label="Description"
                                            :error-messages="errors"
                                            solo
                                            dense
                                            rows="3"
                                            v-model="appSettings.order_body"
                                            clearable
                                        ></v-textarea>
                                    </validation-provider>
                                </v-col>
                            </v-row>
                        </v-card-text>
                        <v-card-actions>
                            <v-spacer />
                            <v-btn
                                color="primary"
                                :disabled="invalid"
                                :loading="isBusy('change-order')"
                                @click="
                                    changeOrderSettings() ||
                                        isBusy('change-order')
                                "
                                >Valider</v-btn
                            >
                        </v-card-actions>
                    </v-card>
                </validation-observer>
            </v-col>
            <!-- Express notification -->
            <v-col cols="12">
                <validation-observer ref="observer" v-slot="{ invalid }">
                    <v-card :loading="isBusy('fetch-app-settings')">
                        <v-card-title>
                            Express notification
                        </v-card-title>
                        <v-card-text>
                            <v-row>
                                <v-col cols="12">
                                    <validation-provider
                                        v-slot="{
                                            errors
                                        }"
                                        name="Titre d'Express"
                                        rules="required|max:50"
                                    >
                                        <v-text-field
                                            prepend-inner-icon="mdi-bell"
                                            label="Titre"
                                            hide-details="auto"
                                            :error-messages="errors"
                                            v-model="appSettings.express_title"
                                            dense
                                            solo
                                            clearable
                                        >
                                        </v-text-field>
                                    </validation-provider>
                                </v-col>
                                <v-col cols="12">
                                    <validation-provider
                                        v-slot="{
                                            errors
                                        }"
                                        name="Description d'Express"
                                        rules="required|max:100"
                                    >
                                        <v-textarea
                                            hide-details="auto"
                                            label="Description"
                                            solo
                                            v-model="appSettings.express_body"
                                            :error-messages="errors"
                                            dense
                                            rows="3"
                                            clearable
                                        ></v-textarea>
                                    </validation-provider>
                                </v-col>
                            </v-row>
                        </v-card-text>
                        <v-card-actions>
                            <v-spacer />
                            <v-btn
                                color="primary"
                                :disabled="invalid"
                                :loading="
                                    isBusy('change-express') ||
                                        isBusy('change-express')
                                "
                                @click="changeExpressSettings()"
                                >Valider</v-btn
                            >
                        </v-card-actions>
                    </v-card>
                </validation-observer>
            </v-col>
        </v-row>
    </div>
</template>

<script>
import { mapState } from "vuex";
import Headline from "../pieces/Headline";
export default {
    components: {
        Headline
    },
    data() {
        return {};
    },
    computed: {
        ...mapState(["expected"]),
        appSettings: function() {
            return this.$store.getters.settings;
        }
    },
    methods: {
        init: function() {
            this.$store.dispatch("fetchData", {
                path: `/api/fetch/app/settings`,
                mutation: `FETCH_SETTINGS`,
                related: `fetch-app-settings`
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
        changeOrderSettings: function() {
            this.$store.dispatch("patchData", {
                path: `/api/change/order/settings`,
                data: {
                    order_title: this.appSettings.order_title,
                    order_body: this.appSettings.order_body
                },
                related: `change-order`
            });
        },
        changeExpressSettings: function() {
            this.$store.dispatch("patchData", {
                path: `/api/change/express/settings`,
                data: {
                    express_title: this.appSettings.express_title,
                    express_body: this.appSettings.express_body
                },
                related: `change-express`
            });
        }
    },
    watch: {
        expected() {
            //* Change express
            {
                this.$callback.handler(
                    this.$dialog,
                    this.$store.getters.expected("change-express"),
                    {
                        store: this.$store,
                        clear: true
                    }
                );
            }
            //* Change order
            {
                this.$callback.handler(
                    this.$dialog,
                    this.$store.getters.expected("change-order"),
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
