<template>
    <v-card :color="color" flat :loading="loading" :disabled="loading">
        <template slot="progress">
            <v-progress-linear color="white" indeterminate></v-progress-linear>
        </template>
        <v-list-item two-line>
            <v-list-item-content>
                <v-list-item-title
                    class="headline text-h4"
                    :class="contentColor + '--text'"
                >
                    {{ text }}
                </v-list-item-title>
                <v-list-item-subtitle :class="contentColor + '--text'">
                    {{ description }}
                </v-list-item-subtitle>
            </v-list-item-content>
            <v-list-item-icon>
                <v-icon :color="contentColor" v-text="icon" size="90"></v-icon>
            </v-list-item-icon>
        </v-list-item>
    </v-card>
</template>

<script>
export default {
    props: {
        loading: { default: false },
        text: { required: true },
        description: { required: true },
        icon: { required: true },
        contentColor: { default: "white" },
        color: { required: true }
    },
    computed: {
        smAndDown() {
            return this.$vuetify.breakpoint.smAndDown;
        }
    }
};
</script>
