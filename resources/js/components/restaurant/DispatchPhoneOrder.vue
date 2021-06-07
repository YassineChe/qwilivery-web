<template>
    <div>
        <v-row>
            <v-col>
                <!-- HeadLine -->
                <Headline
                    headline="Commande téléphonique"
                    subheadline="Faire une commande"
                    :headline-classes="[
                        'text-h5',
                        'primary--text',
                        'font-weight-black',
                        'text-uppercase'
                    ]"
                />
            </v-col>
        </v-row>

        <v-stepper v-model="cstep" class="mt-5">
            <v-stepper-header>
                <v-stepper-step :complete="cstep > 1" step="1">
                    Informations client
                </v-stepper-step>

                <v-divider></v-divider>

                <v-stepper-step :complete="cstep > 2" step="2">
                    Commande
                </v-stepper-step>

                <v-divider></v-divider>

                <v-stepper-step step="3">
                    Résumé
                </v-stepper-step>
            </v-stepper-header>

            <v-stepper-items>
                <v-stepper-content step="1" class="mt-2">
                    <validation-observer ref="observer" v-slot="{ invalid }">
                        <v-row class="mt-2">
                            <!-- Fullname -->
                            <v-col cols="6">
                                <validation-provider
                                    v-slot="{
                                        errors
                                    }"
                                    name="Nom complet"
                                    rules="required"
                                >
                                    <v-text-field
                                        dense
                                        outlined
                                        :error-messages="errors"
                                        v-model="order.fullname"
                                        label="Nom complet"
                                        hide-details="auto"
                                    ></v-text-field>
                                </validation-provider>
                            </v-col>

                            <!-- Phone number -->
                            <v-col cols="6">
                                <validation-provider
                                    v-slot="{
                                        errors
                                    }"
                                    name="Télépone"
                                    rules="required|numeric|min:10|max:10"
                                >
                                    <v-text-field
                                        dense
                                        outlined
                                        :error-messages="errors"
                                        v-model="order.phone_number"
                                        label="Télépone"
                                        hide-details="auto"
                                    ></v-text-field>
                                </validation-provider>
                            </v-col>

                            <!-- Fullname -->
                            <v-col cols="12">
                                <validation-provider
                                    v-slot="{
                                        errors
                                    }"
                                    name="Address"
                                    rules="required"
                                >
                                    <vuetify-google-autocomplete
                                        dense
                                        id="address"
                                        append-icon="mdi-magnify"
                                        hide-details
                                        v-model="order.address"
                                        outlined
                                        label="Address"
                                        country="ca"
                                        :error-messages="errors"
                                        v-on:placechanged="getDeparturePlaces"
                                    >
                                    </vuetify-google-autocomplete>
                                </validation-provider>
                            </v-col>
                            <!-- Map -->
                            <v-col cols="12">
                                <GmapMap
                                    :center="focusHere"
                                    :zoom="zoom"
                                    :options="{
                                        streetViewControl: false,
                                        mapTypeControl: false
                                    }"
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
                                    * Veuillez positionner le marqueur sur
                                    l'adresse exacte.
                                </small>
                            </v-col>

                            <v-col cols="12" align="right">
                                <!-- Next -->
                                <v-btn
                                    color="primary"
                                    outlined
                                    @click="cstep++"
                                    :disabled="invalid"
                                    rounded
                                >
                                    Suivant
                                    <v-icon right>mdi-arrow-right</v-icon>
                                </v-btn>
                            </v-col>
                        </v-row>
                    </validation-observer>
                </v-stepper-content>
                <v-stepper-content step="2" class="mt-2">
                    <validation-observer ref="observer" v-slot="{ invalid }">
                        <v-row class="mt-2">
                            <!-- Category -->
                            <v-col cols="12" sm="3" md="3" lg="3">
                                <validation-provider
                                    v-slot="{
                                        errors
                                    }"
                                    name="Catégorie"
                                    rules="required"
                                >
                                    <v-select
                                        v-model="selectedCategoryId"
                                        dense
                                        label="Catégorie"
                                        outlined
                                        hide-details="auto"
                                        :items="categories"
                                        :error-messages="errors"
                                        no-data-text="Sélectionnez une catégorie"
                                        item-value="id"
                                        item-text="name"
                                    ></v-select>
                                </validation-provider>
                            </v-col>
                            <!-- Variant -->
                            <v-col cols="12" sm="3" md="4" lg="3">
                                <validation-provider
                                    v-slot="{
                                        errors
                                    }"
                                    name="Article"
                                    rules="required"
                                >
                                    <v-select
                                        v-model="selectedVariantObj"
                                        dense
                                        label="Article"
                                        outlined
                                        no-data-text="Sélectionnez un article"
                                        hide-details="auto"
                                        :items.sync="variants"
                                        :error-messages="errors"
                                        item-text="name"
                                        item-value="id"
                                        return-object
                                    ></v-select>
                                </validation-provider>
                            </v-col>
                            <!-- Qty stuff -->
                            <v-col cols="12" sm="3" md="3" lg="3">
                                <v-row align="center" justify="center">
                                    <v-col align="center">
                                        <v-btn
                                            fab
                                            color="primary"
                                            x-small
                                            @click="qtyMinus()"
                                        >
                                            <v-icon>mdi-minus</v-icon>
                                        </v-btn>
                                    </v-col>
                                    <v-col align="center">
                                        <span class="font-weight-bold">{{
                                            qty
                                        }}</span>
                                    </v-col>
                                    <v-col align="center">
                                        <v-btn
                                            fab
                                            color="primary"
                                            x-small
                                            @click="qtyPlus()"
                                        >
                                            <v-icon>mdi-plus</v-icon>
                                        </v-btn>
                                    </v-col>
                                </v-row>
                            </v-col>
                            <!-- Append to orders -->
                            <v-col
                                cols="12"
                                sm="2"
                                md="2"
                                lg="2"
                                align="center"
                            >
                                <v-btn
                                    color="success"
                                    text
                                    block
                                    outlined
                                    rounded
                                    :disabled="invalid"
                                    @click="appendOrder()"
                                >
                                    Ajouter
                                    <v-icon right>mdi-plus-circle</v-icon>
                                </v-btn>
                            </v-col>

                            <v-col cols="12">
                                <v-divider class="mb-3" />
                                ({{ order.orders.length }}) Commande(s)
                                <v-data-table
                                    disable-sort
                                    hide-default-footer
                                    disable-pagination
                                    dense
                                    :headers="headers"
                                    :items="order.orders"
                                    no-data-text="Aucune commande disponible"
                                >
                                    <template
                                        v-slot:[`item.variant`]="{ item }"
                                    >
                                        {{ item.variant.name }}
                                    </template>

                                    <template v-slot:[`item.qty`]="{ item }">
                                        <v-chip color="info" small>
                                            {{ item.qty }}
                                        </v-chip>
                                    </template>

                                    <template v-slot:[`item.price`]="{ item }">
                                        {{ item.qty * item.variant.price }}
                                        $
                                    </template>

                                    <template
                                        v-slot:[`item.actions`]="{ item }"
                                    >
                                        <v-icon
                                            small
                                            color="error"
                                            @click="deleteOrder(item.randomId)"
                                        >
                                            mdi-delete
                                        </v-icon>
                                    </template>
                                </v-data-table>
                            </v-col>

                            <v-col cols="6" align="left">
                                <!-- Prevouis -->
                                <v-btn
                                    elevation="0"
                                    color="secondary"
                                    outlined
                                    rounded
                                    @click="cstep--"
                                >
                                    <v-icon left>mdi-arrow-left</v-icon>
                                    précédent
                                </v-btn>
                            </v-col>
                            <v-col cols="6" align="right">
                                <!-- Next -->
                                <v-btn
                                    color="primary"
                                    outlined
                                    @click="cstep++"
                                    :disabled="invalid"
                                    rounded
                                >
                                    Suivant
                                    <v-icon right>mdi-arrow-right</v-icon>
                                </v-btn>
                            </v-col>
                        </v-row>
                    </validation-observer>
                </v-stepper-content>

                <v-stepper-content step="3" class="mt-2">
                    <v-row class="mt-2">
                        <v-col cols="4">
                            <v-subheader>Nom complet</v-subheader>
                        </v-col>
                        <v-col cols="8">
                            <v-text-field
                                outlined
                                v-model="order.fullname"
                                hide-details="auto"
                                dense
                                disabled
                            ></v-text-field>
                        </v-col>

                        <v-col cols="4">
                            <v-subheader>Tax</v-subheader>
                        </v-col>
                        <v-col cols="8">
                            <v-text-field
                                outlined
                                v-model="order.tax"
                                hide-details="auto"
                                dense
                                append-icon="mdi-currency-usd"
                            ></v-text-field>
                        </v-col>

                        <v-col cols="4">
                            <v-subheader>Frais de livraison</v-subheader>
                        </v-col>
                        <v-col cols="8">
                            <v-text-field
                                outlined
                                v-model="order.shipping_cost"
                                hide-details="auto"
                                dense
                                append-icon="mdi-currency-usd"
                            ></v-text-field>
                        </v-col>

                        <v-col cols="4">
                            <v-subheader>Total</v-subheader>
                        </v-col>
                        <v-col cols="8"> {{ totalPrice }} $ </v-col>

                        <v-col cols="4">
                            <v-subheader>Location</v-subheader>
                        </v-col>
                        <v-col cols="8">
                            <GmapMap
                                :center="{ lat: 45.482858, lng: -73.63715 }"
                                :zoom="zoom"
                                map-type-id="terrain"
                                style="width: 100%; height: 150px"
                            >
                                <GmapMarker
                                    :position="{
                                        lat: 45.482858,
                                        lng: -73.63715
                                    }"
                                />
                            </GmapMap>
                        </v-col>
                        <v-col cols="12">
                            <v-data-table
                                disable-sort
                                hide-default-footer
                                disable-pagination
                                dense
                                disabled
                                :headers="headers"
                                :items="order.orders"
                                no-data-text="Aucune commande disponible"
                            >
                                <template v-slot:[`item.variant`]="{ item }">
                                    {{ item.variant.name }}
                                </template>
                                <!-- Qty -->
                                <template v-slot:[`item.qty`]="{ item }">
                                    <v-chip color="info" small>
                                        {{ item.qty }}
                                    </v-chip>
                                </template>
                                <!-- Price -->
                                <template v-slot:[`item.price`]="{ item }">
                                    {{
                                        (
                                            item.qty * item.variant.price
                                        ).toFixed()
                                    }}
                                    $
                                </template>
                            </v-data-table>
                        </v-col>

                        <v-col cols="6" align="left">
                            <!-- Prevouis -->
                            <v-btn
                                elevation="0"
                                color="secondary"
                                outlined
                                rounded
                                @click="cstep--"
                            >
                                <v-icon left>mdi-arrow-left</v-icon>
                                précédent
                            </v-btn>
                        </v-col>
                        <v-col cols="6" align="right">
                            <!-- Next -->
                            <v-btn
                                color="primary"
                                rounded
                                @click="finishOrder()"
                            >
                                passer la commande
                                <v-icon right>mdi-arrow-right</v-icon>
                            </v-btn>
                        </v-col>
                    </v-row>
                </v-stepper-content>
            </v-stepper-items>
        </v-stepper>
    </div>
</template>

<script>
import Headline from "../pieces/Headline";
import { mapState } from "vuex";
export default {
    components: {
        Headline
    },
    data() {
        return {
            headers: [
                { text: "id", value: "randomId" },
                { text: "Artcile", value: "variant" },
                { text: "Quantité", value: "qty" },
                { text: "Prix", value: "price" },
                { text: "#", value: "actions" }
            ],
            qty: 1,
            selectedCategoryId: "",
            selectedVariantObj: "",
            focusHere: { lat: 45.482858, lng: -73.63715 },
            cstep: 1,
            zoom: 10,

            order: {
                orders: [],
                tax: 14.975,
                shipping_cost: 2,
                fullname: "",
                phone_number: "",
                address: "",
                lat: null,
                lng: null
            }
        };
    },
    computed: {
        ...mapState(["expected"]),
        categories: function() {
            return this.$store.getters.categories;
        },
        //* Which Category to dispaly
        variants: function() {
            try {
                if (this.selectedCategoryId) {
                    return this.categories.find(
                        category => category.id == this.selectedCategoryId
                    )["variants"];
                } else {
                    return [];
                }
            } catch (error) {
                return [];
            }
        },
        totalPrice: function() {
            var total = 0;
            this.order.orders.forEach(order => {
                total += order.qty * order.variant.price;
            });
            return Number((total * this.order.tax) / 100 + total).toFixed(2);
        }
    },
    methods: {
        //* Init
        init: function() {
            this.$store.dispatch("fetchData", {
                path: "/api/fetch/categories",
                mutation: "FETCH_CATEGORIES",
                related: "fetch-categories"
            });
        },
        //* Coordinate Changed
        updateCoordinates: function(location) {
            this.order.lat = location.latLng.lat();
            this.order.lng = location.latLng.lng();
        },
        //* Qty Plus
        qtyPlus: function() {
            this.qty++;
        },
        //* Qty Plus
        qtyMinus: function() {
            if (this.qty > 0 && this.qty != 1 && this.qty > 1) {
                this.qty--;
            }
        },
        //* Append to order
        appendOrder: function() {
            if (this.selectedCategoryId && this.selectedVariantObj) {
                this.order.orders.push({
                    randomId: Math.floor(Math.random() * 999999),
                    variant: this.selectedVariantObj,
                    qty: this.qty
                });
                this.qty = 1;
            }
        },
        //* Delete order
        deleteOrder: function(id) {
            this.order.orders.splice(
                this.order.orders.indexOf(
                    this.order.orders.find(order => {
                        return order.randomId == id;
                    })
                ),
                1
            );
        },
        finishOrder: function() {
            return this.$store.dispatch("postData", {
                path: "/api/add/order",
                data: this.order,
                related: "add-order",
                returned: true
            });
        },
        //* This used to get places
        getDeparturePlaces: function(addressData, placeResultData, id) {
            if (addressData.latitude && addressData.longitude) {
                this.focusHere.lat = addressData.latitude;
                this.focusHere.lng = addressData.longitude;
                //Set order lat and lng
                this.order.lat = addressData.latitude;
                this.order.lng = addressData.longitude;
                this.zoom = 17;
            }
        }
    },
    watch: {
        expected() {
            //* Add order
            {
                this.$callback.handler(
                    this.$dialog,
                    this.$store.getters.expected("add-order"),
                    {
                        store: this.$store,
                        clear: true,
                        router: {
                            instance: this.$router,
                            incase: "success",
                            to: "restaurant-order"
                        }
                    }
                );
            }
        }
    },
    created() {
        this.init();
    }
};
</script>
