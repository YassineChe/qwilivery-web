<template>
  <v-app-bar
    :height="height"
    color="primary"
    :app="app"
    :elevate-on-scroll="elevateOnScroll"
    :fixed="fixed"
    :flat="flat"
    v-if="user"
  >
    <h1>kzkzkz</h1>
    <!-- This slot to bind drawer status on/off -->
    <slot name="appBarNavIcon"></slot>

    <!-- The Spacer -->
    <v-spacer></v-spacer>
    <!-- profil -->
    <v-menu bottom min-width="200px" rounded offset-y>
      <template v-slot:activator="{ on }">
        <v-btn icon x-large v-on="on">
          <v-avatar color="brown" size="37">
            <span class="white--text headline">{{
              user.first_name.charAt(0)
            }}</span>
          </v-avatar>
        </v-btn>
      </template>
      <v-card>
        <v-list-item-content class="justify-center">
          <div class="mx-auto text-center">
            <h5>ADMIN</h5>
            <v-avatar color="brown">
              <span class="white--text headline">{{
                user.first_name.charAt(0)
              }}</span>
            </v-avatar>

            <h3>{{ user.first_name + " " + user.last_name }}</h3>
            <p class="caption mt-1">
              {{ user.email }}
            </p>
            <v-divider class="my-3"></v-divider>
            <v-btn depressed rounded text :to="{ name: 'profile' }">
              Modifier le compte
            </v-btn>
            <v-divider class="my-3"></v-divider>
            <v-btn depressed rounded text @click="signOut()">
              Se déconnecter
            </v-btn>
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
    app: {
      type: Boolean,
      default: false,
    },
    elevateOnScroll: {
      type: Boolean,
      default: false,
    },
    fixed: {
      type: Boolean,
      default: false,
    },
    flat: {
      type: Boolean,
      default: false,
    },
    height: {
      default: "70px",
    },
  },
  data: () => ({
    guardInfos: [],
  }),
  computed: {
    ...mapState(["guard"]),
    isMobile() {
      return this.$vuetify.breakpoint.xsOnly;
    },
    user: function () {
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