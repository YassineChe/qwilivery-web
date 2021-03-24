<template>
    <div>
        <div :class="headlineClasses">
            {{ headline }}
        </div>
        <span v-if="subheadline">{{ subheadline }}</span>
        <slot name="subheadline"></slot>
    </div>
</template>

<script>
export default {
    props: {
        headline: {
            type: String,
            required: true
        },
        headlineClasses: {
            type: Array,
            required: true
        },
        subheadline: {
            type: String,
            required: false
        }
    }
};
</script>
