<template>
    <dialogCard title="Ajouter nouveau restaurant" :actions="actions()">
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
                    placeholder="Search"
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
                        :position="{ lat: 45.482858, lng: -73.63715 }"
                        :clickable="true"
                        :draggable="true"
                        @drag="updateCoordinates"
                    />
                </GmapMap>
                <small>
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
    data() {
        return {
            focusHere: { lat: 45.482858, lng: -73.63715 },
            restaurant: {
                name: "",
                email: "",
                phone_number: "",
                address: "",
                rate: "",
                location: {
                    lat: null,
                    lng: null
                }
            }
        };
    },
    methods: {
        //* Coordinate Changed
        updateCoordinates: function(location) {
            this.restaurant.location = {
                lat: location.latLng.lat(),
                lng: location.latLng.lng()
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
                    text: "Ajouter",
                    color: "primary",
                    rounded: true
                }
            };
        }
    }
};
</script>
