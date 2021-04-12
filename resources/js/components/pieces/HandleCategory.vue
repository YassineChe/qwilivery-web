<template>
    <dialogCard title="Ajouter nouveau catégorie" :actions="actions()">
        <v-row>
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
                name: "",
                description: ""
            }
        };
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
                    text: "Valider",
                    color: "primary",
                    rounded: true,
                    handle: () => {
                        // if (!this.categoryToEdit)
                        return this.$store.dispatch("postData", {
                            path: "/api/add/category",
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
