<template>
    <dialogCard :title="title" :actions="actions()">
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
                <v-file-input
                    v-show="false"
                    ref="triggerMe"
                    truncate-length="15"
                    @change.native="imageChanged"
                ></v-file-input>
            </v-col>
            <!-- Variant name -->
            <v-col cols="12">
                <v-text-field
                    dense
                    outlined
                    hide-details="auto"
                    label="Nom de variante "
                    v-model="variant.name"
                ></v-text-field>
            </v-col>

            <!-- Variant name -->
            <v-col cols="12">
                <v-text-field
                    type="numeric"
                    dense
                    outlined
                    hide-details="auto"
                    label="Prix"
                    v-model="variant.price"
                    append-icon="mdi-currency-usd"
                ></v-text-field>
            </v-col>
            <!-- Description -->
            <v-col cols="12">
                <v-textarea
                    dense
                    outlined
                    rows="3"
                    hide-details="auto"
                    label="Description"
                    v-model="variant.description"
                ></v-textarea>
            </v-col>
        </v-row>
    </dialogCard>
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
