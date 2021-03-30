<template>
    <div>
        <v-row>
            <v-col>
                <!-- HeadLine -->
                <Headline
                    headline="Livreur(s)"
                    subheadline="Gestion des offres publiées"
                    :headline-classes="[
                        'text-h5',
                        'primary--text',
                        'font-weight-black',
                        'text-uppercase'
                    ]"
                />
            </v-col>
        </v-row>

        <v-card class="mt-5">
            <v-toolbar flat>
                <v-text-field
                    v-model="search"
                    append-icon="mdi-magnify"
                    label="Search"
                    hide-details
                    solo
                    clearable
                ></v-text-field>
            </v-toolbar>

            <v-data-table
                :headers="headers"
                :items="user"
                :search="search"
                disable-sort
                :hide-default-header="isMobile"
            >
                <template v-slot:item.avatar="{ item }">
                    <v-avatar size="40">
                        <img
                            src="https://cdn.vuetifyjs.com/images/john.jpg"
                            alt="John"
                        />
                    </v-avatar>
                </template>

                <template v-slot:item.approuver="{ item }">
                    <v-chip>
                        <v-switch
                            v-model="item.approuver"
                            color="primary"
                        ></v-switch>
                    </v-chip>
                </template>
            </v-data-table>
        </v-card>
    </div>
</template>

<script>
import Headline from "../pieces/Headline";

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
                    text: ""
                },
                {
                    value: "name",
                    text: "NOM ET PRÉNOM"
                },

                {
                    value: "approuver",
                    text: "APPROUVER"
                },
                {
                    value: "email",
                    text: "EMAIL"
                },
                {
                    value: "phone",
                    text: "TÉLÉPHONE"
                }
            ],
            user: [
                {
                    name: "Stive Jobs",
                    avatar: "wwww",
                    approuver: true,
                    email: "99stive@mail.com",
                    phone: "+1-334-42323"
                },
                {
                    name: "Stive Jobs",
                    avatar: "wwww",
                    approuver: true,
                    email: "99stive@mail.com",
                    phone: "+1-334-42323"
                },
                {
                    name: "Stive Jobs",
                    avatar: "wwww",
                    approuver: true,
                    email: "99stive@mail.com",
                    phone: "+1-334-42323"
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
        tt() {
            alert("jdjd");
        }
    }
};
</script>

<style lang="scss" scoped></style>
