<template>
  <v-app-bar
    :height="height"
    color="white"
    :app="app"
    :class="classes"
    :elevate-on-scroll="elevateOnScroll"
    :elevation="elevation"
    :fixed="fixed"
    :flat="flat"
    v-if="delivery"
    rounded
  >
    <!-- This slot to bind drawer status on/off -->
    <slot v-if="isMobile" name="appBarNavIcon"></slot>
    <!-- The Spacer -->
    <v-spacer></v-spacer>
    <v-badge color="error" content="6" overlap class="mr-9">
      <v-icon color="grey lighten-1" medium> mdi-message-outline</v-icon>
    </v-badge>
    <v-badge color="error" content="6" overlap class="mr-9">
      <v-icon color="grey lighten-1" medium> mdi-bell-outline </v-icon>
    </v-badge>
    <!-- profil -->
    <v-menu bottom min-width="200px" rounded offset-y>
      <template v-slot:activator="{ on }">
        <v-btn icon x-large v-on="on">
          <v-avatar size="45">
            <img :src="`/images/avatar.png`" />
          </v-avatar>
        </v-btn>
        <div>
          <v-layout class="mr-3 mt-5">
            <v-flex>
              <h4 class="grey--text">Elon</h4>
              <p>Musk</p>
            </v-flex>
          </v-layout>
        </div>
      </template>
      <v-card>
        <v-list-item-content class="justify-center">
          <div class="mx-auto text-center">
            <v-btn depressed text :to="{ name: 'profile' }">
              Modifier le compte
            </v-btn>
            <v-divider class="my-3"></v-divider>
            <v-btn depressed text @click="signOut()"> Se déconnecter </v-btn>
          </div>
        </v-list-item-content>
      </v-card>
    </v-menu>
  </v-app-bar>
</template>

<script>
import { mapState } from "vuex";
export default {
  props: {
    app: { type: Boolean, default: false },
    elevateOnScroll: { type: Boolean, default: false },
    fixed: { type: Boolean, default: false },
    elevation: { default: 0 },
    flat: { type: Boolean, default: false },
    height: { default: "70px" },
    classes: { default: "" },
  },
  data: () => ({
    guardInfos: [],
  }),
  computed: {
    ...mapState(["guard"]),
    isMobile() {
      return this.$vuetify.breakpoint.mdAndDown;
    },
    delivery: function () {
      try {
        return this.$store.getters.guard;
      } catch (error) {
        return [];
      }
    },
  },
  methods: {
    signOut: function () {
      this.$auth.signOut();
    },
  },
};
</script>
