<template>
    <div>
        <v-row>
            <v-col>
                <!-- HeadLine -->
                <Headline
                    headline="Restaurants"
                    subheadline="Gestion des restaurants"
                    :headline-classes="[
                        'text-h5',
                        'primary--text',
                        'font-weight-black',
                        'text-uppercase'
                    ]"
                />
            </v-col>
        </v-row>

        <v-row class="mt-5" flat>
            <v-col :align="!isMobile ? 'right' : ''">
                <v-btn
                    color="primary"
                    outlined
                    :block="isMobile"
                    @click="handleRestaurant()"
                >
                    <v-icon left>mdi-silverware-variant</v-icon>
                    Ajouter Restaurant
                </v-btn>
            </v-col>
        </v-row>

        <v-card class="mt-5">
            <v-toolbar flat>
                <v-text-field
                    v-model="search"
                    append-icon="mdi-magnify"
                    label="Search"
                    hide-details
                    dense
                    solo
                    clearable
                >
                </v-text-field>
            </v-toolbar>

            <v-data-table
                :headers="headers"
                :items="user"
                :search="search"
                disable-sort
                item-key="id"
            >
                <template v-slot:[`item.name`]="{ item }">
                    <p>{{ item.first_name + " " + item.last_name }}</p>
                </template>
                <template v-slot:[`item.avatar`]="{ item }">
                    <v-avatar size="40">
                        <img
                            src="https://cdn.vuetifyjs.com/images/john.jpg"
                            alt="John"
                        />
                    </v-avatar>
                </template>

                <template v-slot:[`item.status`]="{ item }">
                    <v-chip>
                        <v-switch
                            v-model="item.status"
                            color="primary"
                        ></v-switch>
                    </v-chip>
                </template>

                <template v-slot:[`item.actions`]="{ item }">
                    <v-speed-dial
                        :v-model="true"
                        direction="left"
                        transition="slide-x-reverse-transition"
                    >
                        <template v-slot:activator>
                            <v-btn small fab color="primary">
                                <v-icon> mdi-dots-horizontal </v-icon>
                            </v-btn>
                        </template>

                        <!-- Delete -->
                        <v-tooltip top>
                            <template v-slot:activator="{ on, attrs }">
                                <v-btn
                                    v-on="on"
                                    v-bind="attrs"
                                    color="error"
                                    fab
                                    x-small
                                >
                                    <v-icon> mdi-delete </v-icon>
                                </v-btn>
                            </template>
                            Supprimer
                        </v-tooltip>
                        <!-- Edit -->
                        <v-tooltip top>
                            <template v-slot:activator="{ on, attrs }">
                                <v-btn
                                    v-on="on"
                                    v-bind="attrs"
                                    color="info"
                                    fab
                                    x-small
                                >
                                    <v-icon> mdi-pen </v-icon>
                                </v-btn>
                            </template>
                            Modifier
                        </v-tooltip>
                        <!-- Permis de conduire -->
                        <v-tooltip top>
                            <template v-slot:activator="{ on, attrs }">
                                <v-btn
                                    v-on="on"
                                    v-bind="attrs"
                                    color="warning"
                                    fab
                                    x-small
                                >
                                    <v-icon> mdi-card-account-details </v-icon>
                                </v-btn>
                            </template>
                            Télécharger permis
                        </v-tooltip>
                        <!-- Block -->
                        <v-tooltip top>
                            <template v-slot:activator="{ on, attrs }">
                                <v-btn
                                    v-on="on"
                                    v-bind="attrs"
                                    color="error"
                                    fab
                                    x-small
                                >
                                    <v-icon> mdi-cancel </v-icon>
                                </v-btn>
                            </template>
                            Bloquer
                        </v-tooltip>
                        <!-- Chatter -->
                        <v-tooltip top>
                            <template v-slot:activator="{ on, attrs }">
                                <v-btn
                                    v-on="on"
                                    v-bind="attrs"
                                    color="primary"
                                    fab
                                    x-small
                                >
                                    <v-icon> mdi-chat </v-icon>
                                </v-btn>
                            </template>
                            Chatter
                        </v-tooltip>
                    </v-speed-dial>
                </template>
            </v-data-table>
        </v-card>
    </div>
</template>

<script>
//Components
import Headline from "../pieces/Headline";
import HandleRestaurant from "../pieces/HandleRestaurant";

export default {
    components: {
        Headline
    },
    data() {
        return {
            search: "",
            headers: [
                {
                    value: "avatar",
                    text: "Photo"
                },
                {
                    value: "name",
                    text: "NOM ET PRÉNOM"
                },

                {
                    value: "status",
                    text: "APPROUVER"
                },
                {
                    value: "email",
                    text: "EMAIL"
                },
                {
                    value: "phone",
                    text: "TÉLÉPHONE"
                },
                {
                    value: "actions"
                }
            ],
            user: [
                {
                    id: 1,
                    name: "Stive Jobs",
                    avatar: "wwww",
                    approuver: true,
                    email: "99stive@mail.com",
                    phone: "+1-334-42323"
                },
                {
                    id: 2,
                    name: "Stive",
                    avatar: "wwwsdw",
                    approuver: false,
                    email: "99stisve@mail.com",
                    phone: "+1-834-42323"
                },
                {
                    id: 3,
                    name: "Stiven",
                    avatar: "avv",
                    approuver: true,
                    email: "98stive@mail.com",
                    phone: "+2-334-42323"
                }
            ]
        };
    },
    computed: {
        //* Is mobile
        isMobile() {
            return this.$vuetify.breakpoint.xsOnly;
        }
    },
    methods: {
        //* Add Restaurant
        handleRestaurant: function() {
            this.$dialog.show(HandleRestaurant, {
                width: "40%"
            });
        }
    }
};
</script>
