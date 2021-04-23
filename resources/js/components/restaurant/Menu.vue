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
        <v-row class="mt-5">
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
            <v-col
                align="center"
                v-if="!isBusy('fetch-categories') && categories.length === 0"
                cols="12"
            >
                <p>Aucune catégorie ajoutée ...</p>
            </v-col>
            <v-col cols="12" v-else>
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
                                close
                                v-for="(category, idx) in categories"
                                :key="idx"
                                :value="category.id"
                                @click:close="deleteCategory(category.id)"
                            >
                                {{ category.name }}
                            </v-chip>
                        </v-chip-group>
                    </v-card-title>
                    <v-divider />
                    <!-- Category Information + Add Buttons -->
                    <v-data-iterator
                        :items="displayVariants"
                        :search="search"
                        :disable-pagination="true"
                        hide-default-footer
                    >
                        <!-- No Result -->
                        <template v-slot:no-results>
                            <v-row>
                                <v-col align="center" class="ma-10">
                                    Aucun article trouvé
                                </v-col>
                            </v-row>
                        </template>
                        <!-- No Data -->
                        <template v-slot:no-data>
                            <v-row>
                                <v-col align="center" class="ma-10">
                                    <v-btn
                                        color="primary"
                                        rounded
                                        @click="addVariant()"
                                    >
                                        Ajouter un article
                                    </v-btn>
                                </v-col>
                            </v-row>
                        </template>

                        <!-- Header -->
                        <template v-slot:header>
                            <v-toolbar flat v-if="categoryName">
                                <v-toolbar-title>
                                    {{ categoryName }}
                                </v-toolbar-title>
                                <v-spacer></v-spacer>
                                <v-text-field
                                    solo
                                    dense
                                    label="Rechercher un article"
                                    hide-details
                                    v-if="!isMobile"
                                    v-model="search"
                                    class="mr-3"
                                ></v-text-field>
                                <v-btn
                                    color="primary"
                                    fab
                                    small
                                    @click="addVariant()"
                                >
                                    <v-icon>mdi-plus</v-icon>
                                </v-btn>
                            </v-toolbar>
                            <v-divider />
                        </template>

                        <template v-slot:default="props">
                            <v-card-text>
                                <v-row>
                                    <v-col
                                        v-for="(variant, idx) in props.items"
                                        :key="idx"
                                        cols="12"
                                        sm="6"
                                        md="4"
                                        lg="4"
                                    >
                                        <v-card outlined flat elevation="1">
                                            <v-list-item three-line>
                                                <v-list-item-content>
                                                    <div class="overline">
                                                        {{ variant.price }}$
                                                    </div>
                                                    <v-list-item-title
                                                        class="headline mb-1"
                                                    >
                                                        {{ variant.name }}
                                                    </v-list-item-title>
                                                    <v-list-item-subtitle>{{
                                                        variant.description
                                                    }}</v-list-item-subtitle>
                                                </v-list-item-content>

                                                <v-list-item-avatar
                                                    tile
                                                    size="100"
                                                >
                                                    <v-img
                                                        :src="
                                                            `/images/variants/${variant.photo}`
                                                        "
                                                    ></v-img>
                                                </v-list-item-avatar>
                                            </v-list-item>

                                            <v-card-actions>
                                                <v-spacer></v-spacer>
                                                <v-menu top>
                                                    <template
                                                        v-slot:activator="{
                                                            on,
                                                            attrs
                                                        }"
                                                    >
                                                        <v-btn
                                                            fab
                                                            elevation="0"
                                                            x-small
                                                            v-bind="attrs"
                                                            v-on="on"
                                                        >
                                                            <v-icon
                                                                >mdi-dots-vertical</v-icon
                                                            >
                                                        </v-btn>
                                                    </template>

                                                    <v-list>
                                                        <!-- Edit -->
                                                        <v-list-item
                                                            @click="
                                                                editVariant(
                                                                    variant
                                                                )
                                                            "
                                                        >
                                                            <v-list-item-title>
                                                                Modifier
                                                            </v-list-item-title>
                                                        </v-list-item>
                                                        <!-- Delete -->
                                                        <v-list-item
                                                            @click="
                                                                deleteVariant(
                                                                    variant.id
                                                                )
                                                            "
                                                        >
                                                            <v-list-item-title>
                                                                Supprimer
                                                            </v-list-item-title>
                                                        </v-list-item>
                                                    </v-list>
                                                </v-menu>
                                            </v-card-actions>
                                        </v-card>
                                    </v-col>
                                </v-row>
                            </v-card-text>
                        </template>
                    </v-data-iterator>
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
            showAction: false,
            search: "",
            selectedCategory: ""
        };
    },
    computed: {
        ...mapState(["expected"]),
        //*Get no blocked delivery
        categories: function() {
            return this.$store.getters.categories;
        },
        //* Get category name
        categoryName: function() {
            try {
                return this.categories.find(
                    category => category.id == this.selectedCategory
                )["name"];
            } catch (error) {
                return null;
            }
        },
        //* Which Category to dispaly
        displayVariants: function() {
            try {
                if (this.selectedCategory) {
                    return this.categories.find(
                        category => category.id == this.selectedCategory
                    )["variants"];
                } else {
                    return [];
                }
            } catch (error) {
                return this.categories;
            }
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
        //* Add Variant
        addVariant: function() {
            this.$dialog.show(HandleVariant, {
                title: "Ajouter un article",
                categoryId: this.selectedCategory,
                "min-width": "45%"
            });
        },
        //* Edit Variant
        editVariant: function(variant) {
            this.$dialog.show(HandleVariant, {
                variantToEdit: variant,
                title: "Modifer l'article",
                "min-width": "45%"
            });
        },
        //* Delete Varaint
        deleteVariant: function(variant_id) {
            this.$dialog.confirm({
                text: "Etes-vous sûr de supprimer cet article ?",
                title: "Attention!",
                actions: {
                    false: "Non!",
                    true: {
                        color: "red",
                        text: "Je confirme",
                        handle: () => {
                            //Delete it form backend
                            this.$store.dispatch("deleteData", {
                                path: `/api/delete/variant/${variant_id}`,
                                related: "delete-variant"
                            });
                        }
                    }
                }
            });
        },
        //* Categories
        categoryById: function(id) {
            return this.categories.find(category => category.id == id);
        },
        deleteCategory: function(category_id) {
            this.$dialog.confirm({
                text:
                    "En supprimant cette catégorie, tous les articles seront supprimés !!",
                title: "Attention!",
                actions: {
                    false: "Non!",
                    true: {
                        color: "red",
                        text: "Je confirme",
                        handle: () => {
                            //Delete it form backend
                            this.$store.dispatch("deleteData", {
                                path: `/api/delete/category/${category_id}`,
                                related: "delete-category"
                            });
                        }
                    }
                }
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
            //Add delivery
            {
                let expected = this.$store.getters.expected("add-category");
                if (expected != undefined) {
                    //Clear expected
                    this.$store.commit("CLEAR_EXPECTED");
                    if (expected.status === "success") {
                        this.$dialog.notify.success(
                            expected.result.subMessage["msg"],
                            {
                                position: "top-right",
                                timeout: 3000
                            }
                        );

                        this.init();
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

            // Variant added
            {
                let expected = this.$store.getters.expected("add-variant");
                if (expected != undefined) {
                    //Clear expected
                    this.$store.commit("CLEAR_EXPECTED");

                    if (expected.status === "success") {
                        this.$dialog.notify.success(
                            expected.result.subMessage["msg"],
                            {
                                position: "top-right",
                                timeout: 3000
                            }
                        );

                        this.init();
                    }
                    if (expected.status === "error") {
                        this.$store.getters
                            .callback(expected.result.subMessage)
                            .forEach(error => {
                                this.$dialog.notify.warning(error, {
                                    position: "top-right",
                                    timeout: 3000
                                });
                            });
                    }
                }
            }
            //Delete category
            {
                let expected = this.$store.getters.expected("delete-category");
                if (expected != undefined && expected.status === "success") {
                    this.init();
                    //Clear expected
                    this.$store.commit("CLEAR_EXPECTED");
                }
            }

            //Delete variant
            {
                let expected = this.$store.getters.expected("delete-variant");
                if (expected != undefined && expected.status === "success") {
                    this.$store.getters
                        .callback(expected.result.subMessage)
                        .forEach(error => {
                            this.$dialog.notify.success(error, {
                                position: "top-right",
                                timeout: 3000
                            });
                        });
                    this.init();
                    //Clear expected
                    this.$store.commit("CLEAR_EXPECTED");
                }
            }

            //Article edited
            {
                let expected = this.$store.getters.expected("edit-variant");
                if (expected != undefined && expected.status === "success") {
                    this.$store.getters
                        .callback(expected.result.subMessage)
                        .forEach(error => {
                            this.$dialog.notify.success(error, {
                                position: "top-right",
                                timeout: 3000
                            });
                        });
                    this.init();
                    //Clear expected
                    this.$store.commit("CLEAR_EXPECTED");
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
