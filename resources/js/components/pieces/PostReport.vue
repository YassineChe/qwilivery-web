<template>
    <validation-observer ref="observer" v-slot="{ invalid }">
        <dialogCard title="Reporter!" :actions="actions(invalid)">
            <v-row>
                <!-- Subject -->
                <v-col cols="12">
                    <validation-provider
                        v-slot="{
                            errors
                        }"
                        name="Sujet"
                        rules="required"
                    >
                        <v-text-field
                            dense
                            :error-messages="errors"
                            hide-details="auto"
                            outlined
                            label="Sujet"
                            v-model="report.subject"
                        ></v-text-field>
                    </validation-provider>
                </v-col>
                <!-- Description -->
                <v-col cols="12">
                    <validation-provider
                        v-slot="{
                            errors
                        }"
                        name="Description"
                        rules="required|min:100|max:1000"
                    >
                        <v-textarea
                            dense
                            outlined
                            rows="6"
                            hide-details="auto"
                            :counter="1000"
                            label="Description"
                            :error-messages="errors"
                            v-model="report.description"
                            prepend-inner-icon="mdi-text"
                        ></v-textarea>
                    </validation-provider>
                </v-col>
            </v-row>
        </dialogCard>
    </validation-observer>
</template>

<script>
export default {
    layout: ["default", { width: 600 }],
    data() {
        return {
            report: {
                subject: "",
                description: ""
            }
        };
    },
    methods: {
        actions: function(invalid) {
            return {
                post: {
                    text: "Envoyer",
                    disabled: invalid,
                    color: "primary",
                    rounded: true,
                    handle: () => {
                        return this.$store.dispatch("postData", {
                            path: "/api/add/report",
                            data: this.report,
                            related: "add-report",
                            returned: true
                        });
                    }
                }
            };
        }
    }
};
</script>
