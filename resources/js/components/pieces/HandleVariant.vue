<template>
    <validation-observer ref="observer" v-slot="{ invalid }">
        <dialogCard :title="title" :actions="actions(invalid)">
            <v-row>
                <!-- Photo of Variant -->
                <v-col cols="12" align="center">
                    <v-avatar
                        tile
                        size="100"
                        color="grey lighten-2"
                        @click.native="triggerMe()"
                    >
                        <v-icon v-if="!variantTempPhoto" size="50"
                            >mdi-cloud-upload</v-icon
                        >
                        <img v-else :src="variantTempPhoto" />
                    </v-avatar>
                    <validation-provider
                        v-slot="{
                            errors
                        }"
                        name="image"
                        rules="required|ext:png,jpg"
                    >
                        <v-file-input
                            v-show="false"
                            ref="triggerMe"
                            hide-details="auto"
                            :error-messages="errors"
                            truncate-length="15"
                            @change.native="imageChanged"
                        ></v-file-input>
                    </validation-provider>
                </v-col>
                <!-- Variant name -->
                <v-col cols="12">
                    <validation-provider
                        v-slot="{
                            errors
                        }"
                        name="Article"
                        rules="required|max:50"
                    >
                        <v-text-field
                            dense
                            outlined
                            prepend-inner-icon="mdi-food"
                            :error-messages="errors"
                            hide-details="auto"
                            label="Nom d'article"
                            v-model="variant.name"
                        ></v-text-field>
                    </validation-provider>
                </v-col>

                <!-- Variant name -->
                <v-col cols="12">
                    <validation-provider
                        v-slot="{
                            errors
                        }"
                        name="Prix"
                        :rules="{ required: true, regex: /^\d*\.?\d*$/ }"
                    >
                        <v-text-field
                            dense
                            outlined
                            hide-details="auto"
                            label="Prix"
                            v-model="variant.price"
                            prepend-inner-icon="mdi-currency-usd"
                            :error-messages="errors"
                        ></v-text-field>
                    </validation-provider>
                </v-col>
                <!-- Description -->
                <v-col cols="12">
                    <validation-provider
                        v-slot="{
                            errors
                        }"
                        name="Description"
                        rules="max:100"
                    >
                        <v-textarea
                            dense
                            outlined
                            rows="3"
                            hide-details="auto"
                            :counter="100"
                            label="Description"
                            :error-messages="errors"
                            v-model="variant.description"
                            prepend-inner-icon="mdi-text"
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
        variantToEdit: { required: false },
        title: { required: true, type: String },
        categoryId: { required: false }
    },
    data() {
        return {
            variantTempPhoto: "",
            variant: {
                category_id: "",
                name: "",
                price: "",
                description: ""
            }
        };
    },
    methods: {
        triggerMe: function() {
            this.$refs.triggerMe.$refs.input.click();
        },
        //* If image change load it as avatar (UI)
        imageChanged: function(e) {
            var fileReader = new FileReader();
            fileReader.readAsDataURL(e.target.files[0]);
            var extension = e.target.files[0].name.split(".")[1];

            fileReader.onload = e => {
                this.variantTempPhoto = e.target.result;
            };
        },
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
                        //Prepare consts
                        const image = this.$refs.triggerMe.$refs.input;
                        const formData = new FormData();
                        //Append image
                        formData.append("avatar", image.files[0]);
                        //Append to form data
                        for (const [key, value] of Object.entries(
                            this.variant
                        )) {
                            formData.append(key, value);
                        }

                        if (!this.variantToEdit)
                            return this.$store.dispatch("postData", {
                                path: "/api/add/variant",
                                data: formData,
                                related: "add-variant",
                                returned: true
                            });
                        else
                            return this.$store.dispatch("postData", {
                                path: "/api/edit/variant",
                                data: formData,
                                related: "edit-variant",
                                returned: true
                            });
                    }
                }
            };
        }
    },
    created() {
        if (this.variantToEdit) {
            this.variant = this.variantToEdit;
            this.variantTempPhoto = `/images/variants/${this.variantToEdit.photo}`;
        } else this.variant.category_id = this.categoryId;
    }
};
</script>
