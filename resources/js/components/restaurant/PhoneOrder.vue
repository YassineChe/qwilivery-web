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
            orderedVariants: [{}]
        };
    },
    computed: {
        ...mapState(["expected"]),
        //* Is mobile
        isMobile() {
            return this.$vuetify.breakpoint.xsOnly;
        }
    },
    methods: {
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
    created() {}
};
</script>
