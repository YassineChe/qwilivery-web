<template>
    <dialogCard :title="title" :actions="actions()">
        <v-row>
            <!-- Name of restaurant -->
            <v-col cols="12">
                <v-text-field
                    prepend-inner-icon="mdi-text"
                    v-model="restaurant.name"
                    placeholder="Nom du restaurant"
                    hide-details
                    dense
                    solo
                    clearable
                >
                </v-text-field>
            </v-col>
            <!-- Email -->
            <v-col cols="12">
                <v-text-field
                    v-model="restaurant.email"
                    type="email"
                    prepend-inner-icon="mdi-email"
                    placeholder="Email"
                    hide-details
                    dense
                    solo
                    clearable
                >
                </v-text-field>
            </v-col>
            <!-- Phone Number -->
            <v-col cols="12">
                <v-text-field
                    v-model="restaurant.phone_number"
                    prepend-inner-icon="mdi-phone"
                    placeholder="Téléphone"
                    hide-details
                    dense
                    solo
                    clearable
                >
                </v-text-field>
            </v-col>
            <!-- Address -->
            <v-col cols="12">
                <v-text-field
                    v-model="restaurant.address"
                    prepend-inner-icon="mdi-map-marker"
                    placeholder="Addresse"
                    hide-details
                    solo
                    clearable
                >
                </v-text-field>
            </v-col>
            <!-- Phone Number -->
            <v-col cols="12">
                <v-text-field
                    v-model="restaurant.rate"
                    prepend-inner-icon="mdi-currency-usd"
                    placeholder="Tarif"
                    hide-details
                    dense
                    solo
                    clearable
                >
                </v-text-field>
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
