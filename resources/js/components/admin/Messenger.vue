<template>
    <v-card>
        <v-toolbar color="primary white--text" elevation="0">
            <v-toolbar-title v-if="!isMobile">
                ({{ 0 }}) Conversation(s)
            </v-toolbar-title>
        </v-toolbar>
        <v-row class="no-gutters">
            <v-col cols="12" sm="3" style="border-right: 1px solid #E9E9E9;">
                <v-list dense height="100%" color="sbg">
                    <v-list-item-group color="primary">
                        <v-data-iterator
                            :loading="isBusy('fetch-conversations')"
                            :items="conversations"
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
                                    v-for="(cnv, idx) in items"
                                    :key="idx"
                                    link
                                    @click="showChatFlow(cnv)"
                                >
                                    <v-list-item-avatar>
                                        <v-img
                                            :src="
                                                `images/avatars/${
                                                    getPersonFromConversation(
                                                        cnv
                                                    )['person']['avatar']
                                                }`
                                            "
                                        ></v-img>
                                    </v-list-item-avatar>

                                    <v-list-item-content>
                                        <v-list-item-title>
                                            {{
                                                getPersonFromConversation(cnv)[
                                                    "person"
                                                ]["first_name"]
                                                    ? getPersonFromConversation(
                                                          cnv
                                                      )["person"]["last_name"] +
                                                      " " +
                                                      getPersonFromConversation(
                                                          cnv
                                                      )["person"]["first_name"]
                                                    : getPersonFromConversation(
                                                          cnv
                                                      )["person"]["name"]
                                            }}
                                        </v-list-item-title>
                                        <v-list-item-subtitle
                                            v-if="cnv.last_message != null"
                                        >
                                            {{ cnv.last_message.message }}
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
                        v-if="this.selectedConversation"
                    >
                        <v-card-title class="font-weight-thin">
                            {{ shouldBeHere }}
                        </v-card-title>

                        <v-card-text
                            class="flex-grow-1 overflow-y-auto"
                            id="scrollHere"
                        >
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

                    <v-container fill-height fluid v-else>
                        <v-row align="center" justify="center">
                            <img src="/images/svg/chat.svg" width="250px" />
                        </v-row>
                    </v-container>
                </v-responsive>
            </v-col>
        </v-row>
    </v-card>
</template>
<script>
import { mapState } from "vuex";
export default {
    data() {
        return {
            keyword: "",
            search: "",
            message: "",
            selectedConversation: null
        };
    },
    computed: {
        ...mapState(["expected"]),
        shouldBeHere: function() {
            if (this.selectedConversation != null) {
                return this.getPersonFromConversation(
                    this.selectedConversation
                )["person"]["first_name"]
                    ? this.getPersonFromConversation(this.selectedConversation)[
                          "person"
                      ]["last_name"]
                    : this.getPersonFromConversation(this.selectedConversation)[
                          "person"
                      ]["name"];
            }
        },
        persons: function() {
            return this.$store.getters.persons;
        },
        chatflows: function() {
            return this.$store.getters.chatflows;
        },
        conversations: function() {
            return this.$store.getters.conversations;
        },
        //* Ismobile
        isMobile() {
            return this.$vuetify.breakpoint.xsOnly;
        }
    },
    methods: {
        //* Init
        init: function() {
            this.$store.dispatch("fetchData", {
                path: "/api/fetch/conversations",
                mutation: "FETCH_CONVERSATIONS",
                related: "fetch-conversations"
            });
        },
        //! Will edited
        showChatFlow: function(conversation) {
            this.selectedConversation = conversation;
            this.$store.dispatch("fetchData", {
                path: `/api/fetch/chatflow/${conversation.id}`,
                mutation: "FETCH_CHATFLOWS",
                related: "fetch-chatflows"
            });
        },
        sendMessage: function() {
            if (this.message != "") {
                if (this.selectedConversation) {
                    this.$store.dispatch("postData", {
                        path: "/api/admin/send/message",
                        data: {
                            exist: true,
                            message: this.message,
                            conversation_id: this.selectedConversation.id,
                            from: this.guard,
                            to: this.getPersonFromConversation(
                                this.selectedConversation
                            )["guard"]
                        },
                        related: "send-message"
                    });

                    this.chatflows.push({
                        conversation_id: this.selectedConversation.id,
                        from: "admin",
                        to: this.getPersonFromConversation(
                            this.selectedConversation
                        )["guard"],
                        message: this.message
                    });
                }
            }

            this.scrollToBottom();
            this.message = "";
        },
        retrievePerson: function(person) {
            try {
                if (person.type == "delivery") {
                    return person.last_name + " " + person.first_name;
                } else {
                    return person.name;
                }
            } catch (error) {
                retrun[{}];
            }
        },
        //* This will return delivery or restaurant
        getPersonFromConversation(conversation) {
            return conversation.delivery == null
                ? { person: conversation.restaurant, guard: "restaurant" }
                : { person: conversation.delivery, guard: "delivery" };
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
