<template>
    <div>
        <v-row>
            <v-col>
                <!-- HeadLine -->
                <Headline
                    headline="Menu"
                    subheadline="Gestion de menu"
                    :headline-classes="[
                        'text-h5',
                        'primary--text',
                        'font-weight-black',
                        'text-uppercase'
                    ]"
                />
            </v-col>
        </v-row>

        <v-row class="mt-5" flat>
            <v-col :align="!isMobile ? 'right' : ''">
                <v-btn
                    color="primary"
                    outlined
                    :block="isMobile"
                    @click="addCategory()"
                >
                    <v-icon left>mdi-plus</v-icon>
                    Ajouter une catégorie
                </v-btn>
            </v-col>
        </v-row>

        <v-row>
            <v-col>
                <v-card>
                    <v-card-title>
                        <v-chip-group show-arrows>
                            <v-chip v-for="(meal, idx) in meals" :key="idx">
                                {{ meal.name }}
                            </v-chip>
                        </v-chip-group>
                    </v-card-title>
                    <v-card-text>
                        <v-list>
                            <v-list-group
                                v-for="item in categories"
                                :key="item.title"
                                v-model="item.active"
                                :prepend-icon="item.action"
                                no-action
                            >
                                <template v-slot:activator>
                                    <v-list-item-content>
                                        <v-list-item-title
                                            v-text="item.title"
                                        ></v-list-item-title>
                                    </v-list-item-content>
                                </template>

                                <v-list-item
                                    v-for="child in item.items"
                                    :key="child.title"
                                >
                                    <v-list-item-content>
                                        <v-list-item-title
                                            v-text="child.title"
                                        ></v-list-item-title>
                                    </v-list-item-content>
                                </v-list-item>
                            </v-list-group>
                        </v-list>
                    </v-card-text>
                </v-card>
            </v-col>
        </v-row>
    </div>
</template>
<script>
import Headline from "../pieces/Headline";
import HandleCategory from "../pieces/HandleCategory";

import { mapState } from "vuex";
export default {
    components: {
        Headline
    },
    data: () => ({
        tags: [
            "Work",
            "Home Improvement",
            "Vacation",
            "Food",
            "Drawers",
            "Shopping",
            "Art",
            "Tech",
            "Creative Writing"
        ],
        categories: [
            {
                action: "mdi-ticket",
                items: [{ title: "List Item" }],
                title: "Attractions"
            },
            {
                action: "mdi-silverware-fork-knife",
                active: true,
                items: [
                    { title: "Breakfast & brunch" },
                    { title: "New American" },
                    { title: "Sushi" }
                ],
                title: "Dining"
            }
        ]
    }),
    computed: {
        ...mapState(["expected"]),
        //*Get no blocked delivery
        categories: function() {
            return this.$store.getters.categories;
        },
        meals: function() {
            return this.$store.getters.meals;
        },
        //* Is mobile
        isMobile() {
            return this.$vuetify.breakpoint.xsOnly;
        }
    },
    methods: {
        //* Init
        init() {
            //* Get all deliveries
            this.$store.dispatch("multipleFetch", [
                {
                    path: "/api/fetch/restaurant/categories",
                    mutation: "FETCH_CATEGORIES",
                    related: "fetch-categories"
                },
                {
                    path: "/api/fetch/meals",
                    mutation: "FETCH_MEALS",
                    related: "fetch-meals"
                }
            ]);
        },
        //* Add Category
        addCategory: function() {
            this.$store.commit("CLEAR_EXPECTED");

            this.$dialog.show(HandleCategory, {
                title: "Ajouter nouveau Catr",
                width: "40%"
            });
        }
    },

    watch: {
        expected() {
            //* Add delivery
            {
                let expected = this.$store.getters.expected("add-category");
                if (expected != undefined) {
                    if (expected.status === "success") {
                        this.$dialog.notify.success(
                            expected.result.subMessage["msg"],
                            {
                                position: "top-right",
                                timeout: 3000
                            }
                        );
                    }
                    if (expected.status === "error") {
                        for (const [key, value] of Object.entries(
                            expected.result.errors
                        )) {
                            this.$dialog.notify.warning(value[0], {
                                position: "top-right",
                                timeout: 5000
                            });
                        }
                    }
                }
            }
        }
    },
    created() {
        this.$store.commit("CLEAR_EXPECTED");
        this.init();
    }
};
</script>
