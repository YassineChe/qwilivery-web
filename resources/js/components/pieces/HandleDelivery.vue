<template>
    <validation-observer ref="observer" v-slot="{ invalid }">
        <dialogCard title="Ajouter nouveau livreur" :actions="actions(invalid)">
            <v-row>
                <!--  Last Name -->
                <v-col cols="6">
                    <validation-provider
                        v-slot="{
                            errors
                        }"
                        name="Nom"
                        rules="required"
                    >
                        <v-text-field
                            dense
                            prepend-inner-icon="mdi-account"
                            :error-messages="errors"
                            hide-details="auto"
                            outlined
                            label="Nom"
                            v-model="delivery.last_name"
                        ></v-text-field>
                    </validation-provider>
                </v-col>
                <!-- First name -->
                <v-col cols="6">
                    <validation-provider
                        v-slot="{
                            errors
                        }"
                        name="Prénom"
                        rules="required"
                    >
                        <v-text-field
                            prepend-inner-icon="mdi-account"
                            :error-messages="errors"
                            hide-details="auto"
                            v-model="delivery.first_name"
                            label="Prénom"
                            outlined
                            dense
                        >
                        </v-text-field>
                    </validation-provider>
                </v-col>
                <!-- Email -->
                <v-col cols="6">
                    <validation-provider
                        v-slot="{
                            errors
                        }"
                        name="E-mail"
                        rules="required|email"
                    >
                        <v-text-field
                            prepend-inner-icon="mdi-email"
                            v-model="delivery.email"
                            type="email"
                            label="Email"
                            :error-messages="errors"
                            hide-details="auto"
                            dense
                            outlined
                        >
                        </v-text-field>
                    </validation-provider>
                </v-col>
                <!-- Phone Number -->
                <v-col cols="6">
                    <validation-provider
                        v-slot="{
                            errors
                        }"
                        name="Télépone"
                        rules="required|numeric|min:10|max:10"
                    >
                        <v-text-field
                            prepend-inner-icon="mdi-phone"
                            :error-messages="errors"
                            hide-details="auto"
                            label="Numéro de téléphone"
                            v-model="delivery.phone_number"
                            outlined
                            dense
                        ></v-text-field>
                    </validation-provider>
                </v-col>
                <!-- How many years of experience -->
                <v-col cols="12">
                    <validation-provider
                        v-slot="{
                            errors
                        }"
                        name="Expérience(s)"
                        rules="required|numeric|min:0|max:2"
                    >
                        <v-text-field
                            prepend-inner-icon="mdi-calendar"
                            :error-messages="errors"
                            hide-details="auto"
                            label="Combien d'années d'expérience ?"
                            type="number"
                            v-model="delivery.experience"
                            outlined
                            dense
                        ></v-text-field>
                    </validation-provider>
                </v-col>
                <!-- Upload -->
                <v-col cols="12">
                    <v-btn
                        block
                        elevation="5"
                        color="primary"
                        @click="rander()"
                        outlined
                        :large="!isMobile"
                        :small="isMobile"
                    >
                        <v-icon left>mdi-cloud-upload</v-icon>
                        <span v-if="!delivery.fileName"
                            >joindre permis (PNG)</span
                        >
                        <span v-else>{{ delivery.fileName.name }}</span>
                    </v-btn>
                    <v-file-input
                        v-model="delivery.fileName"
                        v-show="false"
                        id="png-permit"
                    >
                    </v-file-input>
                </v-col>
            </v-row>
        </dialogCard>
    </validation-observer>
</template>

<script>
export default {
    layout: ["default", { width: 600 }],
    props: {
        deliveryToEdit: { required: false },
        title: { required: true, type: String }
    },
    data() {
        return {
            delivery: {
                first_name: "",
                last_name: "",
                email: "",
                phone_number: "",
                experience: "",
                fileName: null
            }
        };
    },
    computed: {
        //* Is mobile
        isMobile: function() {
            return this.$vuetify.breakpoint.smAndDown;
        }
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
                    text: "Enregistrer",
                    color: "primary",
                    disabled: invalid,
                    rounded: true,
                    handle: () => {
                        // Empty the images so that we upload new ones
                        const file = document.querySelector("#png-permit");
                        // Make sure the file input is not null
                        if (!file.files) return;

                        // The file is a valid file.
                        const formData = new FormData();
                        formData.append("permit", file.files[0]);

                        for (const [key, value] of Object.entries(
                            this.delivery
                        )) {
                            formData.append(key, value);
                        }

                        if (!this.deliveryToEdit)
                            return this.$store.dispatch("postData", {
                                path: "/api/add/delivery-man",
                                data: formData,
                                related: "add-delivery",
                                returned: true
                            });
                        else
                            return this.$store.dispatch("postData", {
                                path: "/api/edit/delivery-man",
                                data: formData,
                                related: "edit-delivery",
                                returned: true
                            });
                    }
                }
            };
        },
        //* Rander input
        rander() {
            document.getElementById("png-permit").click();
        }
    },
    created() {
        if (this.deliveryToEdit) {
            this.delivery = this.deliveryToEdit;
        }
    }
};
</script>
