<template>
    <validation-observer ref="observer" v-slot="{ invalid }">
        <v-card
            flat
            :loading="isBusy('edit-profile')"
            :disabled="isBusy('edit-profile')"
        >
            <v-card-text v-if="guard">
                <v-row justify="center" align="center">
                    <v-col cols="12" sm="4" md="4" lg="4" align="center">
                        <v-avatar
                            size="150"
                            color="grey lighten-2"
                            @click.native="triggerMe()"
                        >
                            <img
                                :src="
                                    tempAvatar
                                        ? tempAvatar
                                        : `/images/avatars/${guard.avatar}`
                                "
                            />
                        </v-avatar>
                        <validation-provider
                            v-slot="{
                                errors
                            }"
                            name="image"
                            rules="required|ext:png,jpg"
                        >
                            <v-file-input
                                v-show="false"
                                ref="triggerMe"
                                hide-details="auto"
                                :error-messages="errors"
                                truncate-length="15"
                                @change.native="imageChanged"
                            ></v-file-input>
                        </validation-provider>
                        <!-- Another trigger here -->
                        <v-btn small text class="mt-1" @click="triggerMe()"
                            >Changer photo de profile
                        </v-btn>
                    </v-col>
                    <!-- Last name -->
                    <v-col cols="12" sm="8" lg="8" md="8">
                        <v-row>
                            <v-subheader>Nom complet</v-subheader>
                            <v-col cols="12">
                                <validation-provider
                                    v-slot="{
                                        errors
                                    }"
                                    name="Nom"
                                    rules="required|max:50"
                                >
                                    <v-text-field
                                        v-model="guard.last_name"
                                        dense
                                        outlined
                                        prepend-inner-icon="mdi-account"
                                        :error-messages="errors"
                                        hide-details="auto"
                                        label="Nom"
                                    ></v-text-field>
                                </validation-provider>
                            </v-col>
                            <!-- First name -->
                            <v-col cols="12">
                                <validation-provider
                                    v-slot="{
                                        errors
                                    }"
                                    name="Prénom"
                                    rules="required|max:50"
                                >
                                    <v-text-field
                                        v-model="guard.first_name"
                                        dense
                                        outlined
                                        prepend-inner-icon="mdi-account"
                                        :error-messages="errors"
                                        hide-details="auto"
                                        label="Prénom"
                                    ></v-text-field>
                                </validation-provider>
                            </v-col>
                            <!-- Phone number -->
                            <v-subheader>Contact information</v-subheader>
                            <v-col cols="12">
                                <validation-provider
                                    v-slot="{
                                        errors
                                    }"
                                    name="Téléphone"
                                    rules="required|min:10|max:10"
                                >
                                    <v-text-field
                                        v-model="guard.phone_number"
                                        dense
                                        outlined
                                        prepend-inner-icon="mdi-phone"
                                        :error-messages="errors"
                                        hide-details="auto"
                                        label="Téléphone"
                                    ></v-text-field>
                                </validation-provider>
                            </v-col>
                            <!-- Email -->
                            <v-col cols="12">
                                <validation-provider
                                    v-slot="{
                                        errors
                                    }"
                                    name="E-mail"
                                    rules="required|max:50"
                                >
                                    <v-text-field
                                        v-model="guard.email"
                                        dense
                                        outlined
                                        prepend-inner-icon="mdi-email"
                                        :error-messages="errors"
                                        hide-details="auto"
                                        label="Email"
                                    ></v-text-field>
                                </validation-provider>
                            </v-col>
                            <!-- How many years of experience -->
                            <v-subheader>Experience</v-subheader>
                            <v-col cols="12">
                                <validation-provider
                                    v-slot="{
                                        errors
                                    }"
                                    name="Expérience(s)"
                                    rules="required|numeric|min:0|max:2"
                                >
                                    <v-text-field
                                        prepend-inner-icon="mdi-calendar"
                                        :error-messages="errors"
                                        hide-details="auto"
                                        label="Combien d'années d'expérience ?"
                                        type="number"
                                        v-model="guard.experience"
                                        outlined
                                        dense
                                    ></v-text-field>
                                </validation-provider>
                            </v-col>
                        </v-row>
                    </v-col>
                </v-row>
            </v-card-text>
            <v-card-actions>
                <v-btn small text class="mt-1" outlined>Permis actuel </v-btn>
                <v-spacer></v-spacer>
                <v-btn
                    outlined
                    rounded
                    text
                    color="primary"
                    :disabled="invalid"
                    @click="updateProfileInfo()"
                    >Valider</v-btn
                >
            </v-card-actions>
        </v-card>
    </validation-observer>
</template>

<script>
import { mapState } from "vuex";
export default {
    data() {
        return {
            tempAvatar: "",
            profile: []
        };
    },
    computed: {
        ...mapState(["expected"]),
        guard: function() {
            return this.$store.getters.guard;
        }
    },
    methods: {
        triggerMe: function() {
            this.$refs.triggerMe.$refs.input.click();
        },
        //* If image change load it as avatar (UI)
        imageChanged: function(e) {
            var fileReader = new FileReader();
            fileReader.readAsDataURL(e.target.files[0]);
            var extension = e.target.files[0].name.split(".")[1];

            if (
                extension.toLowerCase() !== "png" &&
                extension.toLowerCase() !== "jpg" &&
                extension.toLowerCase() !== "jpeg"
            ) {
                this.$dialog.notify.error(
                    "Seuls les formats png, jpg sont autorisés",
                    {
                        position: "top-right",
                        timeout: 3000
                    }
                );
                return;
            }
            fileReader.onload = e => {
                this.tempAvatar = e.target.result;
            };
        },
        //* Update profile info
        updateProfileInfo: function() {
            //Prepare consts
            const image = this.$refs.triggerMe.$refs.input;
            const formData = new FormData();
            //Append image

            formData.append("avatar", image.files[0]);
            //Append to form data
            for (const [key, value] of Object.entries(this.guard)) {
                formData.append(key, value);
            }

            this.$store.dispatch("postData", {
                path: "/api/edit/delivery/profile",
                data: formData,
                related: "edit-profile"
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
        }
    },
    watch: {
        expected() {
            {
                this.$callback.handler(
                    this.$dialog,
                    this.$store.getters.expected("edit-profile")
                );
            }
        }
    }
};
</script>
