<template>
    <v-container fill-height fluid>
        <v-row align="center" justify="center">
            <v-col align="center">
                <v-card flat>
                    <v-card-text>
                        <!-- HeadLine -->
                        <v-row>
                            <v-col cols="12">
                                <!-- HeadLine -->
                                <Headline
                                    headline="This page isn't available !"
                                    :headline-classes="[
                                        'text-h4',
                                        'grey--text text--darken-1',
                                        'font-weight-bold'
                                    ]"
                                >
                                    <template #subheadline>
                                        <span>
                                            The link you followed may be broken,
                                            or the page may have been removed.
                                            <!-- Link to back -->
                                            <router-link :to="{ path: '/' }">
                                                Home!
                                            </router-link>
                                        </span>
                                    </template>
                                </Headline>
                            </v-col>

                            <v-col cols="12">
                                <v-img
                                    max-width="500px"
                                    src="images/svg/not-found.svg"
                                ></v-img>
                            </v-col>
                        </v-row>
                    </v-card-text>
                </v-card>
            </v-col>
        </v-row>
    </v-container>
</template>

<script>
import Headline from "./Headline";
export default {
    components: { Headline }
};
</script>
