<template>
    <v-row align="center" justify="center">
        <v-col cols="8">
            <!-- HeadLine -->
            <Headline
                headline="This functionality disabled for now! It Will be available when google approve mobile App"
                :headline-classes="[
                    'accent--text',
                    'mt-10',
                    'text-center',
                    'display-1',
                    'font-weight-black'
                ]"
            >
                <template #subheadline>
                    <p class="text-center mt-5">
                        Spoveup IT will be at your disposal.. Thank you!
                    </p>
                </template>
            </Headline>
        </v-col>
    </v-row>
</template>

<script>
import Headline from "../pieces/Headline";
export default {
    components: {
        Headline
    }
};
</script>
