<template>
  <div>
    <NavBar></NavBar>
    <router-view></router-view>
  </div>
</template>

<script>
import NavBar from "./clue/NavBar.vue";
export default {
  components: { NavBar },
  mounted() {
    console.log("Component mounted.");
  },
};
</script>
