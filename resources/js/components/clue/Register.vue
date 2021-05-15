<template>
    <v-container fluid fill-height class="background ma-0 pa-0">
        <v-row class="fill-height">
            <v-col cols="12" sm="8" md="8" lg="8" v-if="!isMobile">
                <v-container fill-height fluid>
                    <v-row align="center" justify="center">
                        <v-col align="center">
                            <img
                                :src="`/images/svg/${setBackground}`"
                                width="600px"
                            />
                        </v-col>
                    </v-row>
                </v-container>
            </v-col>

            <v-col cols="12" sm="12" md="5" lg="4" class="my-0 py-0">
                <v-tabs centered v-model="whichTab" icons-and-text grow>
                    <v-tab :key="0"
                        >Livreur
                        <v-icon color="primary">mdi-moped</v-icon>
                    </v-tab>
                    <v-tab :key="1"
                        >Restaurant
                        <v-icon color="primary">mdi-store</v-icon>
                    </v-tab>
                    <!-- Tab Item -->
                    <v-tabs-items v-model="whichTab">
                        <v-tab-item>
                            <DeliveryRegister />
                        </v-tab-item>
                        <v-tab-item>
                            <RestaurantRegister />
                        </v-tab-item>
                    </v-tabs-items>
                </v-tabs>
            </v-col>
        </v-row>
    </v-container>
</template>

<script>
import DeliveryRegister from "./register/DeliveryRegister";
import RestaurantRegister from "./register/RestaurantRegister";
export default {
    components: {
        DeliveryRegister,
        RestaurantRegister
    },
    data() {
        return {
            whichTab: ""
        };
    },
    computed: {
        //* Is mobile
        isMobile: function() {
            return this.$vuetify.breakpoint.smAndDown;
        },
        setBackground: function() {
            switch (this.whichTab) {
                case 0:
                    return "delivery.svg";
                    break;

                case 1:
                    return "chef.svg";
                    break;
                default:
                    return "delivery.svg";
                    break;
            }
        }
    }
};
</script>
