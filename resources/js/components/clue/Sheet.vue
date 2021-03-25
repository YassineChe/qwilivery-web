<template>
  <v-layout no-gutters class="mb-6">
    <v-flex>
      <p>{{ title }}</p>
    </v-flex>
    <v-flex style="text-align: end">
      <v-btn class="mx-2" fab dark small color="primary">
        <v-icon dark> mdi-cog </v-icon>
      </v-btn>
    </v-flex>
  </v-layout>
</template>
<script>
export default {
  props: ["title"],
};
</script>