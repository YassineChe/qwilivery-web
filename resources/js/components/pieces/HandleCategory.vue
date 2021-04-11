<template>
    <dialogCard title="Ajouter nouveau catégorie" :actions="actions()">
        <v-row>
            <!--  Type of meal -->
            <v-col cols="12">
                <v-select
                    hide-details="auto"
                    v-model="category.meal_id"
                    :items="meals"
                    label="Type de repas"
                    dense
                    outlined
                    item-text="name"
                    item-value="id"
                >
                </v-select>
            </v-col>
            <!--  category name -->
            <v-col cols="12">
                <v-text-field
                    dense
                    outlined
                    hide-details="auto"
                    label="Nom de catégorie "
                    placeholder="ex: Les salades"
                    v-model="category.name"
                ></v-text-field>
            </v-col>
            <!-- category description -->
            <v-col cols="12">
                <v-textarea
                    dense
                    outlined
                    hide-details="auto"
                    label="La description"
                    v-model="category.description"
                ></v-textarea>
            </v-col>
        </v-row>
    </dialogCard>
</template>
<script>
export default {
    props: {
        categoryToEdit: { required: false },
        title: { required: true, type: String }
    },
    data() {
        return {
            category: {
                meal_id: "",
                name: "",
                description: ""
            }
        };
    },
    computed: {
        meals: function() {
            return this.$store.getters.meals;
        }
    },
    methods: {
        //* Actions
        actions: function() {
            return {
                close: {
                    text: "Fermer",
                    color: "error",
                    rounded: true
                },
                add: {
                    text: "Enregistrer",
                    color: "primary",
                    rounded: true,
                    handle: () => {
                        if (!this.categoryToEdit)
                            return this.$store.dispatch("postData", {
                                path: "/api/add/restaurant/category",
                                data: this.category,
                                related: "add-category",
                                returned: true
                            });
                    }
                }
            };
        }
    },
    created() {
        if (this.categoryToEdit) {
            this.category = this.categoryToEdit;
        }
    }
};
</script>
