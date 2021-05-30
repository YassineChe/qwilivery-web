<template>
    <div>
        <v-row>
            <v-col>
                <!-- HeadLine -->
                <Headline
                    headline="Rapports"
                    subheadline="Voir tous les rapports"
                    :headline-classes="[
                        'text-h5',
                        'primary--text',
                        'font-weight-black',
                        'text-uppercase'
                    ]"
                />
            </v-col>
        </v-row>

        <v-card
            class="mt-5"
            :loading="isBusy('fetch-reports')"
            :disabled="isBusy('fetch-reports')"
        >
            <v-tabs
                fixed-tabs
                centered
                icons-and-text
                dark
                background-color="primary"
            >
                <v-tab>
                    <v-icon left> mdi-moped </v-icon>
                    Livreur(s)
                </v-tab>
                <v-tab>
                    <v-icon left> mdi-store </v-icon>
                    Restaurant(s)
                </v-tab>

                <v-tab-item>
                    <v-data-table
                        no-data-text="Aucun rapport trouvé"
                        :headers="[
                            {
                                text: 'REF',
                                value: 'id'
                            },
                            {
                                text: 'Photo',
                                value: 'avatar'
                            },
                            {
                                text: 'Nom complet',
                                value: 'fullname'
                            },
                            {
                                text: 'Sujet',
                                value: 'subject'
                            },
                            {
                                text: 'Description',
                                value: 'description'
                            },
                            {
                                text: 'Date',
                                value: 'created_at'
                            },
                            {
                                text: 'Actions',
                                value: 'actions'
                            }
                        ]"
                        :items="reports_deliveries"
                    >
                        <!-- avatar -->
                        <template v-slot:[`item.avatar`]="{ item }">
                            <v-avatar size="40" v-if="item.delivery">
                                <img
                                    :src="
                                        `/images/avatars/${item.delivery.avatar}`
                                    "
                                />
                            </v-avatar>
                            <span v-else>—</span>
                        </template>
                        <!-- Full Name -->
                        <template v-slot:[`item.fullname`]="{ item }">
                            <span v-if="item.delivery">
                                {{ item.delivery.last_name }}
                                {{ item.delivery.first_name }}
                            </span>
                            <span v-else>—</span>
                        </template>
                        <!-- Description -->
                        <template v-slot:[`item.description`]="{ item }">
                            {{ item.description.substring(0, 30) }}...
                        </template>
                        <!-- Date -->
                        <template v-slot:[`item.created_at`]="{ item }">
                            {{ parseToDate(item.created_at) }}
                        </template>
                        <!-- Actions -->
                        <template v-slot:[`item.actions`]="{ item }">
                            <!-- View -->
                            <v-icon
                                small
                                color="info"
                                @click="viewReport(item)"
                            >
                                mdi-eye
                            </v-icon>
                            <!-- Delete Order -->
                            <v-icon
                                small
                                color="error"
                                @click="deleteReport(item.id)"
                            >
                                mdi-delete
                            </v-icon>
                        </template>
                    </v-data-table>
                </v-tab-item>
                <v-tab-item>
                    <v-data-table
                        no-data-text="Aucun rapport trouvé"
                        :headers="[
                            {
                                text: 'REF',
                                value: 'id'
                            },
                            {
                                text: 'Photo',
                                value: 'avatar'
                            },
                            {
                                text: 'Nom complet',
                                value: 'fullname'
                            },
                            {
                                text: 'Sujet',
                                value: 'subject'
                            },
                            {
                                text: 'Description',
                                value: 'description'
                            },
                            {
                                text: 'Date',
                                value: 'created_at'
                            },
                            {
                                text: 'Actions',
                                value: 'actions'
                            }
                        ]"
                        :items="reports_restaurants"
                    >
                        <!-- avatar -->
                        <template v-slot:[`item.avatar`]="{ item }">
                            <v-avatar size="40" v-if="item.restaurant">
                                <img
                                    :src="
                                        `/images/avatars/${item.restaurant.avatar}`
                                    "
                                />
                            </v-avatar>
                            <span v-else>—</span>
                        </template>
                        <!-- Full Name -->
                        <template v-slot:[`item.fullname`]="{ item }">
                            <span v-if="item.restaurant">{{
                                item.restaurant.name
                            }}</span>
                            <span v-else>—</span>
                        </template>
                        <!-- Description -->
                        <template v-slot:[`item.description`]="{ item }">
                            {{ item.description.substring(0, 30) }}...
                        </template>
                        <!-- Date -->
                        <template v-slot:[`item.created_at`]="{ item }">
                            {{ parseToDate(item.created_at) }}
                        </template>
                        <!-- Actions -->
                        <template v-slot:[`item.actions`]="{ item }">
                            <!-- View -->
                            <v-icon
                                small
                                color="info"
                                @click="viewReport(item)"
                            >
                                mdi-eye
                            </v-icon>
                            <!-- Delete Order -->
                            <v-icon
                                small
                                color="error"
                                @click="deleteReport(item.id)"
                            >
                                mdi-delete
                            </v-icon>
                        </template>
                    </v-data-table>
                </v-tab-item>
            </v-tabs>
        </v-card>
    </div>
</template>
<script>
import Headline from "../pieces/Headline";
import ViewReport from "../pieces/ViewReport";

import { mapState } from "vuex";
import moment from "moment";
moment.locale("fr");

export default {
    components: {
        Headline,
        ViewReport
    },
    data() {
        return {
            initData: {
                path: "/api/fetch/reports",
                mutation: "FETCH_REPORTS",
                related: "fetch-reports"
            }
        };
    },
    computed: {
        ...mapState(["expected"]),
        //* Reports deliveries
        reports_deliveries: function() {
            try {
                return this.$store.getters.reports["deliveries"];
            } catch (e) {
                return [];
            }
        },
        //* Reports restaurants
        reports_restaurants: function() {
            try {
                return this.$store.getters.reports["restaurants"];
            } catch (e) {
                return [];
            }
        },
        //* Is mobile
        isMobile() {
            return this.$vuetify.breakpoint.xsOnly;
        }
    },
    methods: {
        init: function() {
            this.$store.dispatch("fetchData", this.initData);
        },
        //* Delete report
        deleteReport: function(report_id) {
            this.$dialog.confirm({
                text: "Êtes-vous sûr de vouloir supprimer ?",
                title: "Attention!",
                actions: {
                    false: "Non!",
                    true: {
                        color: "red",
                        text: "Je confirme",
                        handle: () => {
                            this.$store.dispatch("deleteData", {
                                path: `/api/delete/report/${report_id}`,
                                related: "delete-report"
                            });
                        }
                    }
                }
            });
        },
        //* View Report
        viewReport: function(report) {
            this.$dialog.show(ViewReport, {
                report: report
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
            this.$callback.handler(
                this.$dialog,
                this.$store.getters.expected("delete-report"),
                {
                    store: this.$store,
                    clear: true,
                    path: this.initData.path,
                    mutation: this.initData.mutation,
                    related: this.initData.path
                }
            );
        }
    },
    created() {
        this.init();
    }
};
</script>
