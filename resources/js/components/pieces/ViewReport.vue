<template>
    <dialogCard :title="report.subject">
        <Headline :headline-classes="['text-h6', 'black--text']">
            <template #subheadline>
                <p class="black--text text-justify">{{ report.description }}</p>
            </template>
        </Headline>
    </dialogCard>
</template>
<script>
import Headline from "./Headline";
export default {
    layout: ["default", { width: 600 }],
    components: {
        Headline
    },
    props: {
        report: {
            required: true
        }
    }
};
</script>
