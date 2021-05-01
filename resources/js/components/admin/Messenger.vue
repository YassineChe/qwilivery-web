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
                                    @click="showChatFlow(delivery.id)"
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
            <!-- Messages (Content) -->
            <v-col cols="auto" class="flex-grow-1 flex-shrink-0">
                <v-responsive height="70vh">
                    <v-card
                        tile
                        class="d-flex flex-column fill-height"
                        :loading="isBusy('fetch-chatflows')"
                        :disabled="isBusy('fetch-chatflows')"
                    >
                        <v-card-title class="font-weight-thin">
                            {{ "Name here" }}
                        </v-card-title>

                        <v-card-text
                            class="flex-grow-1 overflow-y-auto"
                            id="scrollHere"
                        >
                            <!-- d-flex flex-row-reverse -->
                            <template v-for="(chatflow, idx) in chatflows">
                                <div
                                    class="my-2"
                                    :key="idx"
                                    :class="
                                        chatflow.from == 'admin'
                                            ? 'd-flex flex-row-reverse'
                                            : ''
                                    "
                                >
                                    <v-chip
                                        class="white--text"
                                        :color="
                                            chatflow.from == 'admin'
                                                ? 'primary'
                                                : 'accent'
                                        "
                                    >
                                        {{ chatflow.message }}</v-chip
                                    >
                                </div>
                            </template>
                        </v-card-text>
                        <v-divider />
                        <v-card-actions>
                            <v-text-field
                                label="Message ..."
                                v-model="flow.message"
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
            flow: {
                delivery_id: null,
                admin_id: "",
                from: "admin",
                to: "delivery",
                message: ""
            }
        };
    },
    computed: {
        chatflows: function() {
            return this.$store.getters.chatflows;
        },
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
        showChatFlow: function(delivery_id) {
            //Set
            this.flow.delivery_id = delivery_id;
            //Get
            this.$store.dispatch("fetchData", {
                path: `/api/fetch/chatflow/delivery/${delivery_id}`,
                mutation: "FETCH_CHATFLOWS",
                related: "fetch-chatflows"
            });
        },
        //Send message
        sendMessage: function() {
            if (this.flow.delivery_id != null)
                this.$store.dispatch("postData", {
                    path: "/api/admin/send/message",
                    data: this.flow,
                    related: "send-message"
                });

            this.chatflows.push(Object.assign({}, this.flow));
            this.scrollToBottom();
            this.flow.message = "";
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
