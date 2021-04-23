<template>
    <div>
        <v-row>
            <v-col>
                <!-- HeadLine -->
                <Headline
                    headline="Commandes téléphonique"
                    subheadline="Traiter la commande téléphonique"
                    :headline-classes="[
                        'text-h5',
                        'primary--text',
                        'font-weight-black',
                        'text-uppercase'
                    ]"
                />
            </v-col>
        </v-row>

        <!-- Buttons actions -->
        <v-row class="mt-5">
            <v-col :align="!isMobile ? 'right' : ''">
                <v-btn
                    color="primary"
                    outlined
                    :block="isMobile"
                    @click="addPhoneOrder()"
                >
                    <v-icon left>mdi-plus</v-icon>
                    Ajouter une commande
                </v-btn>
            </v-col>
        </v-row>

        <v-card>
            <template>
                <v-data-table :items="preorders" :headers="headers">
                    <!-- Orders -->
                    <template v-slot:[`item.orders`]="{ item }">
                        <v-btn fab x-small color="primary">
                            {{ item.orders.length }}
                        </v-btn>
                    </template>
                    <template v-slot:[`item.actions`]="{ item }">
                        <v-icon small color="error">
                            mdi-delete
                        </v-icon>
                    </template>
                </v-data-table>
            </template>
        </v-card>
    </div>
</template>

<script>
import Headline from "../pieces/Headline";
import HandlePhoneOrder from "../pieces/HandlePhoneOrder";
import { mapState } from "vuex";

export default {
    components: {
        Headline
    },
    data() {
        return {
            headers: [
                { text: "#REF", value: "id" },
                { text: "NOM COMPLET", value: "fullname" },
                { text: "Adresse", value: "address" },
                { text: "COMMANDE(S)", value: "orders" },
                { text: "ACTION", value: "actions" }
            ],
            orderedVariants: [{}]
        };
    },
    computed: {
        ...mapState(["expected"]),
        preorders: function() {
            return this.$store.getters.preorders;
        },
        //* Is mobile
        isMobile() {
            return this.$vuetify.breakpoint.xsOnly;
        }
    },
    methods: {
        //* Init
        init: function() {
            this.$store.dispatch("fetchData", {
                path: "/api/fetch/preorders",
                mutation: "FETCH_PREORDERS",
                related: "fetch-preorders"
            });
        },
        //* Trigger new command
        addPhoneOrder: function() {
            this.$dialog.show(HandlePhoneOrder);
        }
    },
    watch: {
        // Variant added
        expected() {
            {
                let expected = this.$store.getters.expected("add-order");
                if (expected != undefined) {
                    //Clear expected
                    this.$store.commit("CLEAR_EXPECTED");

                    if (expected.status === "success") {
                        this.$dialog.notify.success(
                            expected.result.subMessage["msg"],
                            {
                                position: "top-right",
                                timeout: 3000
                            }
                        );

                        //this.init();
                    }
                }
            }
        }
    },
    created() {
        this.init();
    }
};
</script>
