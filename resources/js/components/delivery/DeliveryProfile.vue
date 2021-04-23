<template>
    <!-- Information personnel -->
    <v-card elevation="7" flat v-if="delivery">
        <v-container>
            <v-toolbar flat>
                <v-spacer></v-spacer>
                <v-btn
                    color="purple darken-3"
                    fab
                    small
                    @click="isEditing = !isEditing"
                >
                    <v-icon v-if="isEditing"> mdi-close </v-icon>
                    <v-icon v-else color="white"> mdi-pencil </v-icon>
                </v-btn>
            </v-toolbar>
            <v-card-text>
                <v-row no-gutters>
                    <!-- Image Profil -->
                    <v-col cols="6" md="6" sm="12" align="center" class="mb-3">
                        <v-avatar
                            size="300"
                            v-if="!isEditing"
                            color="grey lighten-2"
                        >
                            <img
                                alt="Avatar"
                                :src="'Avatar/' + delivery.avatar"
                            />
                        </v-avatar>

                        <v-hover
                            v-slot="{ hover }"
                            close-delay="100"
                            v-if="isEditing"
                        >
                            <v-avatar
                                size="300"
                                @click="rander('image-file')"
                                :style="
                                    hover
                                        ? 'border:2px solid #7367f0'
                                        : 'border:2px solid black'
                                "
                            >
                                <img
                                    alt="Avatar"
                                    :src="'Avatar/' + delivery.avatar"
                                />
                                <input hidden type="file" id="image-file" />
                            </v-avatar>
                        </v-hover>
                    </v-col>
                    <v-col cols="6" md="6" sm="12">
                        <!-- Name -->
                        <v-col cols="12" md="12" sm="12">
                            <v-text-field
                                class="mt-5"
                                dense
                                hide-details="auto"
                                label="Nom"
                                v-model="delivery.first_name"
                                :outlined="isEditing"
                                :disabled="!isEditing"
                            ></v-text-field>
                        </v-col>
                        <!-- Name -->
                        <v-col cols="12" md="12" sm="12">
                            <v-text-field
                                class="mt-5"
                                dense
                                hide-details="auto"
                                label="Prénom"
                                v-model="delivery.last_name"
                                :outlined="isEditing"
                                :disabled="!isEditing"
                            ></v-text-field>
                        </v-col>
                        <!-- experience -->
                        <v-col cols="12" md="12" sm="12">
                            <v-text-field
                                class="mt-5"
                                dense
                                hide-details="auto"
                                label="Experience"
                                type="number"
                                v-model="delivery.experience"
                                :outlined="isEditing"
                                :disabled="!isEditing"
                            ></v-text-field>
                        </v-col>
                        <!-- Phone Number -->
                        <v-col cols="12" md="12" sm="12">
                            <v-text-field
                                class="mt-5"
                                dense
                                hide-details="auto"
                                label="Téléphone"
                                v-model="delivery.phone_number"
                                :outlined="isEditing"
                                :disabled="!isEditing"
                            ></v-text-field>
                        </v-col>

                        <v-col cols="12" md="12" sm="12">
                            <v-btn
                                block
                                class="mt-5"
                                :large="!isMobile"
                                :small="isMobile"
                                outlined
                                elevation="5"
                                color="primary"
                                @click="rander('pdf-file')"
                                v-if="isEditing"
                            >
                                <v-icon left>mdi-cloud-upload</v-icon>
                                <span v-if="!isMobile">
                                    joindre votre permis (PDF)
                                </span>
                            </v-btn>
                            <input hidden type="file" id="pdf-file" />

                            <v-btn
                                block
                                elevation="5"
                                color="green"
                                @click="download()"
                                :large="!isMobile"
                                :small="isMobile"
                                class="mt-5"
                                v-if="!isEditing"
                            >
                                <v-icon left>mdi-cloud-download</v-icon>
                                <span v-if="!isMobile">
                                    Télécharger votre permis
                                </span>
                            </v-btn>
                        </v-col>
                    </v-col>
                </v-row>
            </v-card-text>
            <v-divider></v-divider>
            <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn
                    :disabled="!isEditing"
                    :loading="isBusy('do-edit')"
                    color="success"
                    @click="save()"
                >
                    <v-icon left> mdi-update </v-icon>
                    Mettre à jour
                </v-btn>
            </v-card-actions>
        </v-container>
    </v-card>
</template>

<script>
import { mapState } from "vuex";

export default {
    data() {
        return {
            hasSaved: false,
            isEditing: null,
            model: null
        };
    },
    computed: {
        ...mapState(["expected"]),
        //* Is mobile
        isMobile: function() {
            return this.$vuetify.breakpoint.smAndDown;
        },
        //* guard
        delivery: function() {
            try {
                return this.$store.getters.guard;
            } catch (error) {
                return [];
            }
        }
    },
    methods: {
        //* Update info delivery
        save: function() {
            // Empty the file so that we upload new ones
            const file = document.querySelector("#pdf-file");
            const image = document.querySelector("#image-file");

            // The file is a valid file.
            const formData = new FormData();
            formData.append("permit", file.files[0]);
            formData.append("avatar", image.files[0]);

            for (const [key, value] of Object.entries(this.delivery)) {
                formData.append(key, value);
            }

            this.$store.dispatch("uploadFile", {
                path: "/api/update/delivery",
                data: formData,
                related: "do-edit"
                // redirect_to: "/",
            });
        },
        //* Rander input
        rander(id) {
            document.getElementById(id).click();
        },

        download: function() {
            axios({
                url: "/api/download/file",
                headers: {
                    // Accept: "application/json",
                    "content-type": "multipart/form-data",
                    Authorization: `Bearer ${localStorage.getItem("token")}`
                },
                method: "GET",
                responseType: "blob"
            }).then(response => {
                var fileURL = window.URL.createObjectURL(
                    new Blob([response.data])
                );
                var fileLink = document.createElement("a");

                fileLink.href = fileURL;
                fileLink.setAttribute("download", "file.pdf");
                document.body.appendChild(fileLink);

                fileLink.click();
            });
        },
        // Overlly
        isBusy: function(fetcher) {
            try {
                return this.$store.getters.expected(fetcher).status == "busy"
                    ? true
                    : false;
            } catch (error) {
                return false;
            }
        }
    },
    watch: {
        expected() {
            {
                let expected = this.$store.getters.expected("do-edit");
                if (expected != undefined) {
                    if (expected.status === "success") {
                        this.$store.commit(
                            "FETCH_GUARD",
                            expected.result.subMessage
                        );
                        this.$dialog.notify.success(
                            "'Les informations de profil ont été mises à jour avec succès 👍'",
                            {
                                position: "top-right",
                                timeout: 5000
                            }
                        );

                        this.isEditing = false;
                    }
                    if (expected.status === "error") {
                        for (const [key, value] of Object.entries(
                            expected.result.subMessage
                        )) {
                            this.$dialog.notify.warning(value[0], {
                                position: "top-right",
                                timeout: 5000
                            });
                        }
                    }
                }
            }
        }
    }
};
</script>

<style lang="scss" scoped></style>
