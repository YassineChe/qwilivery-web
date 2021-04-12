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

        <!-- Buttons actions -->
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

        <!-- Contents -->
        <v-row>
            <v-col>
                <v-card
                    :loading="isBusy('fetch-categories')"
                    :disabled="isBusy('fetch-categories')"
                >
                    <v-card-title>
                        <v-chip-group
                            mandatory
                            show-arrows
                            v-model="selectedCategory"
                            active-class="primary"
                        >
                            <v-chip
                                v-for="(category, idx) in categories"
                                :key="idx"
                                :value="category.id"
                            >
                                {{ category.name }}
                            </v-chip>
                        </v-chip-group>
                    </v-card-title>
                    <v-divider />
                    <!-- Category Information + Add Buttons -->
                    <v-toolbar flat v-if="selectedCategory">
                        <v-toolbar-title>{{
                            categoryById(selectedCategory)["name"]
                        }}</v-toolbar-title>
                        <v-spacer></v-spacer>
                        <v-btn
                            color="primary"
                            outlined
                            fab
                            small
                            @click="addVariant()"
                        >
                            <v-icon>mdi-plus</v-icon>
                        </v-btn>
                    </v-toolbar>
                    <!-- Divide here -->
                    <v-divider />
                    <!-- Variants list -->
                    <v-card-text>
                        <v-card max-width="400" outlined elevation="3">
                            <v-list-item three-line>
                                <v-list-item-content>
                                    <v-list-item-title class="headline mb-1">
                                        Headline 5
                                    </v-list-item-title>
                                    <v-list-item-subtitle
                                        >Greyhound divisely hello coldly
                                        fonwderfully</v-list-item-subtitle
                                    >
                                </v-list-item-content>

                                <v-list-item-avatar tile size="100">
                                    <v-img
                                        src="https://storage.buonmenu.com/s3mcmzx4n3luqzl8xc8bgvkdeoau"
                                    ></v-img>
                                </v-list-item-avatar>
                            </v-list-item>
                        </v-card>
                    </v-card-text>
                </v-card>
            </v-col>
        </v-row>
    </div>
</template>
<script>
import Headline from "../pieces/Headline";
import HandleCategory from "../pieces/HandleCategory";
import HandleVariant from "../pieces/HandleVariant";

import { mapState } from "vuex";
export default {
    components: {
        Headline
    },
    data() {
        return {
            selectedCategory: "",
            selection: 1
        };
    },
    computed: {
        ...mapState(["expected"]),
        //*Get no blocked delivery
        categories: function() {
            return this.$store.getters.categories;
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
                    path: "/api/fetch/categories",
                    mutation: "FETCH_CATEGORIES",
                    related: "fetch-categories"
                }
            ]);
        },
        //* Add Category
        addCategory: function() {
            this.$dialog.show(HandleCategory, {
                title: "Ajouter nouveau Catregory",
                "min-width": "45%"
            });
        },
        addVariant: function() {
            this.$dialog.show(HandleVariant, {
                title: "Ajouter une variante",
                "min-width": "45%"
            });
        },
        //* Categories
        categoryById: function(id) {
            return this.categories.find(category => category.id == id);
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
