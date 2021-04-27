<template>
    <validation-observer ref="observer" v-slot="{ invalid }">
        <dialogCard :title="title" :actions="actions(invalid)">
            <v-row>
                <!-- Name of restaurant -->
                <v-col cols="12">
                    <validation-provider
                        v-slot="{
                            errors
                        }"
                        name="Nom du restaurant"
                        rules="required|max:50"
                    >
                        <v-text-field
                            prepend-inner-icon="mdi-text"
                            v-model="restaurant.name"
                            placeholder="Nom du restaurant"
                            :error-messages="errors"
                            hide-details="auto"
                            dense
                            solo
                            clearable
                        >
                        </v-text-field>
                    </validation-provider>
                </v-col>
                <!-- Email -->
                <v-col cols="12">
                    <validation-provider
                        v-slot="{
                            errors
                        }"
                        name="E-mail"
                        rules="required|email|max:50"
                    >
                        <v-text-field
                            v-model="restaurant.email"
                            type="email"
                            prepend-inner-icon="mdi-email"
                            placeholder="Email"
                            :error-messages="errors"
                            hide-details="auto"
                            dense
                            solo
                            clearable
                        >
                        </v-text-field>
                    </validation-provider>
                </v-col>
                <!-- Phone Number -->
                <v-col cols="12">
                    <validation-provider
                        v-slot="{
                            errors
                        }"
                        name="Téléphone"
                        rules="required|min:10|max:10"
                    >
                        <v-text-field
                            v-model="restaurant.phone_number"
                            prepend-inner-icon="mdi-phone"
                            placeholder="Téléphone"
                            :error-messages="errors"
                            hide-details="auto"
                            dense
                            solo
                            clearable
                        >
                        </v-text-field>
                    </validation-provider>
                </v-col>
                <!-- Address -->
                <v-col cols="12">
                    <validation-provider
                        v-slot="{
                            errors
                        }"
                        name="Téléphone"
                        rules="required|max:200"
                    >
                        <v-text-field
                            v-model="restaurant.address"
                            prepend-inner-icon="mdi-map-marker"
                            placeholder="Addresse"
                            hide-details="auto"
                            :error-messages="errors"
                            solo
                            clearable
                        >
                        </v-text-field>
                    </validation-provider>
                </v-col>
                <!-- Phone Number -->
                <v-col cols="12">
                    <validation-provider
                        v-slot="{
                            errors
                        }"
                        name="Tarif"
                        rules="required|numeric"
                    >
                        <v-text-field
                            type="numeric"
                            v-model="restaurant.rate"
                            prepend-inner-icon="mdi-currency-usd"
                            placeholder="Tarif"
                            hide-details="auto"
                            :error-messages="errors"
                            dense
                            solo
                            clearable
                        >
                        </v-text-field>
                    </validation-provider>
                </v-col>
                <!-- Google Map Marker -->
                <v-col cols="12">
                    <GmapMap
                        :center="focusHere"
                        :zoom="10"
                        map-type-id="terrain"
                        style="width: 100%; height: 200px"
                    >
                        <GmapMarker
                            :position="focusHere"
                            :clickable="true"
                            :draggable="true"
                            @drag="updateCoordinates"
                        />
                    </GmapMap>
                    <small class="error--text">
                        * Veuillez positionner le marqueur sur l'adresse exacte.
                    </small>
                </v-col>
            </v-row>
        </dialogCard>
    </validation-observer>
</template>

<script>
import Vue from "vue";
import * as VueGoogleMaps from "vue2-google-maps";

Vue.use(VueGoogleMaps, {
    load: {
        key: "AIzaSyBriQ2VfQyXzswFnsiLB8NkjmOejl77FmA"
    }
});

export default {
    layout: ["default", { width: 550 }],
    props: {
        dataToEdit: { required: false },
        title: { required: true, type: String }
    },
    data() {
        return {
            focusHere: { lat: 45.482858, lng: -73.63715 },
            restaurant: {
                name: "",
                email: "",
                phone_number: "",
                address: "",
                rate: "",
                lat: null,
                lng: null
            }
        };
    },
    methods: {
        //* Coordinate Changed
        updateCoordinates: function(location) {
            this.restaurant.lat = location.latLng.lat();
            this.restaurant.lng = location.latLng.lng();
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
                        if (!this.dataToEdit)
                            return this.$store.dispatch("postData", {
                                path: "/api/add/restaurant",
                                data: this.restaurant,
                                related: "add-restaurant",
                                returned: true
                            });
                        else
                            return this.$store.dispatch("editData", {
                                path: "/api/edit/restaurant",
                                data: this.restaurant,
                                related: "edit-restaurant",
                                returned: true
                            });
                    }
                }
            };
        }
    },
    created() {
        if (this.dataToEdit) {
            //Init Data
            this.focusHere.lat = Number(this.dataToEdit.lat);
            this.focusHere.lng = Number(this.dataToEdit.lng);
            this.restaurant = this.dataToEdit;
        }
    }
};
</script>
