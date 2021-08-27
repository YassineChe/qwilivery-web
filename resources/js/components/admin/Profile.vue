<template>
    <div>
        <v-row>
            <v-col>
                <!-- HeadLine -->
                <Headline
                    headline="Profile"
                    subheadline="Modifier les informations de profile, le mot de passe et plus"
                    :headline-classes="[
                        'text-h5',
                        'primary--text',
                        'font-weight-bold',
                        'text-uppercase'
                    ]"
                />
            </v-col>
        </v-row>

        <v-card class="mt-5">
            <v-tabs :vertical="!isMobile">
                <v-tab>
                    <v-icon left> mdi-account </v-icon>
                    profile
                </v-tab>
                <v-tab>
                    <v-icon left> mdi-lock </v-icon>
                    Sécurité
                </v-tab>

                <v-tab-item>
                    <ProfileInfo />
                </v-tab-item>
                <v-tab-item>
                    <ProfileSecurity />
                </v-tab-item>
            </v-tabs>
        </v-card>
    </div>
</template>

<script>
import Headline from "../pieces/Headline";
import ProfileInfo from "./profile/ProfileInfo";
import ProfileSecurity from "./profile/ProfileSecurity";
export default {
    components: {
        Headline,
        ProfileInfo,
        ProfileSecurity
    },
    computed: {
        //* Is mobile
        isMobile() {
            return this.$vuetify.breakpoint.xsOnly;
        }
    }
};
</script>
