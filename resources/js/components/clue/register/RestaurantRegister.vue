<template>
    <v-card
        tile
        class="fill-height"
        flat
        :disabled="isBusy('do-register-restaurant')"
    >
        <v-container fill-height fluid>
            <v-row align="center" justify="center" class="pa-10">
                <!-- HeadLine -->
                <v-col cols="12" align="center">
                    <Headline
                        headline="Créer un compte restaurant"
                        :headline-classes="[
                            'text-h5',
                            'grey--text text--darken-2'
                        ]"
                    >
                        <template #subheadline>
                            <router-link
                                class="text-decoration-none"
                                :to="{ name: 'login' }"
                                >Se connecter à un compte existant</router-link
                            >
                        </template>
                    </Headline>
                </v-col>

                <!-- Name of restaurant -->
                <v-col cols="12" align="center">
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
                            label="Nom du restaurant"
                            :error-messages="errors"
                            hide-details="auto"
                            dense
                            outlined
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
                            label="Téléphone"
                            :error-messages="errors"
                            hide-details="auto"
                            dense
                            outlined
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
                            label="Addresse"
                            hide-details="auto"
                            :error-messages="errors"
                            outlined
                            dense
                        >
                        </v-text-field>
                    </validation-provider>
                </v-col>
                <!-- Password -->
                <v-col cols="12">
                    <v-text-field
                        dense
                        hide-details="auto"
                        type="password"
                        outlined
                        label="Mot de passe"
                        v-model="restaurant.password"
                    ></v-text-field>
                </v-col>
                <!-- Confrim password -->
                <v-col>
                    <v-text-field
                        dense
                        hide-details="auto"
                        type="password"
                        outlined
                        label="Mot de passe"
                        v-model="restaurant.confirm"
                    ></v-text-field>
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
                <v-col cols="12">
                    <v-btn
                        color="primary"
                        block
                        elevation="0"
                        @click="registerRestaurant()"
                        :loading="isBusy('do-register-restaurant')"
                    >
                        s'inscrire
                    </v-btn>
                </v-col>
            </v-row>
        </v-container>
    </v-card>
</template>

<script>
import Headline from "../../pieces/Headline";
//libs
import { mapState } from "vuex";
export default {
    components: {
        Headline
    },
    data() {
        return {
            focusHere: { lat: 45.482858, lng: -73.63715 },
            restaurant: {
                name: "",
                email: "",
                phone_number: "",
                address: "",
                password: "",
                confirm: "",
                lat: null,
                lng: null
            }
        };
    },
    computed: {
        ...mapState(["expected"])
    },
    methods: {
        //* Coordinate Changed
        updateCoordinates: function(location) {
            this.restaurant.lat = location.latLng.lat();
            this.restaurant.lng = location.latLng.lng();
        },
        registerRestaurant: function() {
            this.$store.dispatch("postData", {
                path: "/api/register/restaurant",
                data: this.restaurant,
                related: "do-register-restaurant"
            });
        },
        //* Is Busy function
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
            {
                this.$callback.handler(
                    this.$dialog,
                    this.$store.getters.expected("do-register-restaurant"),
                    {
                        router: {
                            instance: this.$router,
                            incase: "success",
                            to: "login"
                        }
                    }
                );
            }
        }
    }
};
</script>
