<template>
    <validation-observer ref="observer" v-slot="{ invalid }">
        <dialogCard title="Chatter" :actions="actions(invalid)">
            <v-row>
                <v-col cols="12">
                    <validation-provider
                        v-slot="{
                            errors
                        }"
                        name="Message"
                        rules="required"
                    >
                        <v-textarea
                            outlined
                            v-model="message"
                            hide-details="auto"
                            :error-messages="errors"
                            rows="3"
                            placeholder="message..."
                            row
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
    props: {
        guard: { required: true },
        guard_id: { required: true }
    },
    data() {
        return {
            message: ""
        };
    },
    methods: {
        actions: function(invalid) {
            return {
                close: {
                    text: "Fermer",
                    color: "error",
                    rounded: true
                },
                add: {
                    text: "Envoyer",
                    color: "primary",
                    disabled: invalid,
                    rounded: true,
                    handle: () => {
                        return this.$store.dispatch("postData", {
                            path: "/api/admin/send/msg/out/msger",
                            data: {
                                guard: this.guard,
                                guard_id: this.guard_id,
                                message: this.message
                            },
                            related: "send-message",
                            returned: true
                        });
                    }
                }
            };
        }
    }
};
</script>
