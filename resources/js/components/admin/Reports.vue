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

        <v-card class="mt-5">
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
                            <v-avatar size="40">
                                <img
                                    :src="
                                        `/images/avatars/${item.delivery.avatar}`
                                    "
                                />
                            </v-avatar>
                        </template>
                        <!-- Full Name -->
                        <template v-slot:[`item.fullname`]="{ item }">
                            {{ item.delivery.last_name }}
                            {{ item.delivery.first_name }}
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
                            <v-avatar size="40">
                                <img
                                    :src="
                                        `/images/restaurants_logo/${item.restaurant.logo}`
                                    "
                                />
                            </v-avatar>
                        </template>
                        <!-- Full Name -->
                        <template v-slot:[`item.fullname`]="{ item }">
                            {{ item.restaurant.name }}
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

import moment from "moment";
moment.locale("fr");

export default {
    components: {
        Headline,
        ViewReport
    },
    computed: {
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
            this.$store.dispatch("fetchData", {
                path: "/api/fetch/reports",
                mutation: "FETCH_REPORTS",
                related: "fetch-reports"
            });
        },
        //* Delete report
        deleteReport: function(report_id) {
            alert(report_id);
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
        }
    },
    watch: {
        expected() {
            {
                let expected = this.$store.getters.expected("edit-admin");
                if (expected != undefined) {
                    //If sucess
                    if (expected.status === "success") {
                        this.$dialog.message.success(
                            expected.result.subMessage,
                            {
                                position: "bottom",
                                timeout: 2000
                            }
                        );
                    }
                }
            }
            {
                let expected = this.$store.getters.expected("delete-report");
                if (expected != undefined) {
                    //If sucess
                    if (expected.result.status === "success") {
                        this.$dialog.message.success(
                            expected.result.subMessage,
                            {
                                position: "top-right",
                                timeout: 2000
                            }
                        );
                    }
                }
            }
        }
    },
    created() {
        this.init();
    }
};
</script>
