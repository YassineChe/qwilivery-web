<template>
    <validation-observer ref="observer" v-slot="{ invalid }">
        <dialogCard :title="title" :actions="actions(invalid)">
            <v-row>
                <!--  category name -->
                <v-col cols="12">
                    <validation-provider
                        v-slot="{
                            errors
                        }"
                        name="Catégorie"
                        rules="required|max:50"
                    >
                        <v-text-field
                            prepend-inner-icon="mdi-shape"
                            hide-details="auto"
                            label="Nom de catégorie "
                            placeholder="ex: Les salades"
                            :error-messages="errors"
                            v-model="category.name"
                            outlined
                            dense
                        ></v-text-field>
                    </validation-provider>
                </v-col>
                <!-- category description -->
                <v-col cols="12">
                    <validation-provider
                        v-slot="{
                            errors
                        }"
                        name="Catégorie"
                        rules="required|min:20|max:300"
                    >
                        <v-textarea
                            prepend-inner-icon="mdi-text"
                            hide-details="auto"
                            label="Description"
                            :counter="300"
                            :error-messages="errors"
                            v-model="category.description"
                            dense
                            outlined
                        ></v-textarea>
                    </validation-provider>
                </v-col>
            </v-row>
        </dialogCard>
    </validation-observer>
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
        actions: function(invalid) {
            return {
                close: {
                    text: "Fermer",
                    color: "error",
                    rounded: true
                },
                add: {
                    text: "Valider",
                    color: "primary",
                    disabled: invalid,
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
