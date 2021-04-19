<template>
    <dialogCard title="Commande téléphonique">
        <v-stepper v-model="cstep" class="elevation-0">
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
                    <v-card>
                        <v-card-text>
                            <v-row>
                                <!-- Fullname -->
                                <v-col cols="12">
                                    <v-text-field
                                        dense
                                        outlined
                                        label="Nom"
                                        hide-details="auto"
                                    ></v-text-field>
                                </v-col>
                                <!-- Map -->
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
                                        * Veuillez positionner le marqueur sur
                                        l'adresse exacte.
                                    </small>
                                </v-col>
                            </v-row>
                        </v-card-text>
                    </v-card>
                </v-stepper-content>

                <v-stepper-content step="2" class="mt-2">
                    <v-card>
                        <v-row>
                            <!-- Category -->
                            <v-col cols="12" sm="3" md="3" lg="3">
                                <v-select
                                    v-model="selectedCategoryId"
                                    dense
                                    label="Catégorie"
                                    outlined
                                    hide-details="auto"
                                    :items="categories"
                                    no-data-text="Sélectionnez une catégorie"
                                    item-value="id"
                                    item-text="name"
                                ></v-select>
                            </v-col>
                            <!-- Variant -->
                            <v-col cols="12" sm="3" md="4" lg="3">
                                <v-select
                                    v-model="selectedVariantObj"
                                    dense
                                    label="Article"
                                    outlined
                                    no-data-text="Sélectionnez un article"
                                    hide-details="auto"
                                    :items.sync="variants"
                                    item-text="name"
                                    item-value="id"
                                    return-object
                                ></v-select>
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
                                    @click="appendOrder()"
                                >
                                    Ajouter
                                    <v-icon right>mdi-plus-circle</v-icon>
                                </v-btn>
                            </v-col>

                            <v-col cols="12">
                                <v-divider class="mb-3" />
                                ({{ this.order.orders.length }}) Commande(s)
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
                        </v-row>
                    </v-card>
                </v-stepper-content>
            </v-stepper-items>
        </v-stepper>
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
    layout: ["default", { width: 800 }],
    data() {
        return {
            headers: [
                { text: "Artcile", value: "variant" },
                { text: "Quantité", value: "qty" },
                { text: "Prix", value: "price" },
                { text: "#", value: "actions" }
            ],
            qty: 1,
            selectedCategoryId: "",
            selectedVariantObj: "",
            focusHere: { lat: 45.482858, lng: -73.63715 },
            cstep: 2,
            order: {
                orders: [],
                fullname: "",
                lat: null,
                lng: null
            }
        };
    },
    computed: {
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
            }
        },
        //* Delete order
        deleteOrder: function(id) {
            this.order.orders.splice(
                this.order.orders.find(order => {
                    order.randomId === id;
                }),
                1
            );
        }
    },
    created() {
        this.init();
    }
};
</script>
