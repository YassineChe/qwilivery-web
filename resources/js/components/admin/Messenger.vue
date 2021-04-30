<template>
    <v-card>
        <v-toolbar color="primary white--text" elevation="0">
            <v-toolbar-title v-if="!isMobile">
                ({{ 0 }}) Conversation(s)
            </v-toolbar-title>
            <v-spacer v-if="!isMobile"></v-spacer>
        </v-toolbar>
        <v-row class="no-gutters">
            <v-col cols="12" sm="3" style="border-right: 1px solid #E9E9E9;">
                <v-list dense height="100%" color="sbg">
                    <v-list-item-group color="primary">
                        <v-text-field
                            class="mb-3"
                            prepend-inner-icon="mdi-magnify"
                            v-model="search"
                            hide-details
                            dense
                            rounded
                            placeholder="Trouver une discussion..."
                        ></v-text-field>
                        <v-divider />
                        <v-data-iterator
                            :items="deliveries"
                            :search="search"
                            disable-pagination
                            hide-default-footer
                        >
                            <!-- No data -->
                            <template v-slot:no-data>
                                <div class="text-center grey--text mt-2">
                                    Aucun livreur trouvé
                                </div>
                            </template>
                            <!-- No Result -->
                            <template v-slot:no-results>
                                <div class="text-center grey--text mt-2">
                                    Aucun livreur trouvé
                                </div>
                            </template>
                            <template v-slot="{ items }">
                                <v-list-item
                                    two-line
                                    v-for="(delivery, idx) in items"
                                    :key="idx"
                                    link
                                    @click="showChatFlow(conversation)"
                                >
                                    <v-list-item-avatar>
                                        <v-img
                                            :src="
                                                `images/avatars/${delivery.avatar}`
                                            "
                                        ></v-img>
                                    </v-list-item-avatar>

                                    <v-list-item-content>
                                        <v-list-item-title
                                            v-text="
                                                `${delivery.last_name} ${delivery.first_name} `
                                            "
                                        ></v-list-item-title>
                                        <v-list-item-subtitle>
                                            last message...
                                        </v-list-item-subtitle>
                                    </v-list-item-content>
                                </v-list-item>
                            </template>
                        </v-data-iterator>
                    </v-list-item-group>
                </v-list>
            </v-col>
            <v-col cols="auto" class="flex-grow-1 flex-shrink-0">
                <v-responsive height="70vh">
                    <v-card tile class="d-flex flex-column fill-height">
                        <v-card-title class="sbg">
                            <h5>{{ "Name here" }}</h5>
                        </v-card-title>

                        <v-card-text
                            class="flex-grow-1 overflow-y-auto"
                            id="scrollHere"
                        >
                            <!-- d-flex flex-row-reverse -->
                            <template>
                                <div class="my-2">
                                    <v-chip class="white--text" color="primary">
                                        <v-avatar left>
                                            <v-img
                                                :src="
                                                    `images/avatars/avatar.png`
                                                "
                                            ></v-img>
                                        </v-avatar>
                                        Hello It's me</v-chip
                                    >
                                </div>
                            </template>
                        </v-card-text>

                        <v-divider />
                        <v-card-actions>
                            <v-text-field
                                label="Message ..."
                                v-model="message"
                                clearable
                                outlined
                                @keydown.enter="sendMessage()"
                                append-outer-icon="mdi-send"
                                @click:append-outer="sendMessage()"
                                hide-details
                            />
                        </v-card-actions>
                    </v-card>
                </v-responsive>
            </v-col>
        </v-row>
    </v-card>
</template>
<script>
export default {
    data() {
        return {
            search: "",
            message: ""
        };
    },
    computed: {
        deliveries: function() {
            return this.$store.getters.deliveries;
        },
        //* Ismobile
        isMobile() {
            return this.$vuetify.breakpoint.xsOnly;
        }
    },
    methods: {
        init: function() {
            this.$store.dispatch("fetchData", {
                path: "/api/fetch/deliveries",
                mutation: "FETCH_DELIVERIES",
                related: "fetch-deliveries"
            });
        },
        //* The famous isBusy funtion haha
        isBusy: function(fetcher) {
            try {
                return this.$store.getters.expected(fetcher).status == "busy"
                    ? true
                    : false;
            } catch (error) {
                return false;
            }
        },
        //*Scroll to bottom
        scrollToBottom: function() {
            setTimeout(() => {
                var feed = this.$el.querySelector("#scrollHere");
                feed.scrollTop = feed.scrollHeight;
            }, 50);
        }
    },
    created() {
        this.init();
    }
};
</script>
