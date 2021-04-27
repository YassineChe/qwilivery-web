<template>
    <dialogCard
        :title="
            isBusy('fetch-orders')
                ? 'Chargement en cours...'
                : `(REF:${preOrderId}) Commande`
        "
    >
        <v-data-table
            dense
            :headers="headers"
            :items="orders"
            hide-default-footer
            :loading="isBusy('fetch-orders')"
            loading-text="Chargement en cours..."
        >
            <!-- Variant photo -->
            <template v-slot:[`item.photo`]="{ item }">
                <v-avatar size="30">
                    <img :src="`/images/variants/${item.variant.photo}`" />
                </v-avatar>
            </template>
            <!-- Variant name -->
            <template v-slot:[`item.name`]="{ item }">
                {{ item.variant.name }}
            </template>
            <!-- Variant description -->
            <template v-slot:[`item.description`]="{ item }">
                {{ !item.variant.description ? "-" : item.variant.description }}
            </template>
            <!-- Variant description -->
            <template v-slot:[`item.price`]="{ item }">
                {{ item.variant.price }} $
            </template>
            <!-- Qty -->
            <template v-slot:[`item.qty`]="{ item }">
                <v-chip small color="info">{{ item.qty }}</v-chip>
            </template>
        </v-data-table>
    </dialogCard>
</template>

<script>
export default {
    layout: ["default", { width: "40%" }],
    props: {
        preOrderId: { required: true }
    },
    data() {
        return {
            orders: [],
            headers: [
                { text: "Photo", value: "photo" },
                { text: "Article", value: "name" },
                { text: "Description", value: "description" },
                { text: "Prix", value: "price" },
                { text: "Quantité", value: "qty" }
            ]
        };
    },
    methods: {
        //* Init
        init: function() {
            this.$store
                .dispatch("fetchParticular", {
                    path: `/api/fetch/orders/${this.preOrderId}`,
                    related: "fetch-orders"
                })
                .then(response => {
                    this.orders = response;
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
    created() {
        this.init();
    }
};
</script>
