<template>
  <div class="ma-6">
    <sheet title="List des livreurs"></sheet>

    <v-data-table
      :headers="headers"
      :items="user"
      class="elevation-1"
      click:row="tt()"
    >
      <template v-slot:item.avatar="{ item }">
        <v-avatar>
          <img src="https://cdn.vuetifyjs.com/images/john.jpg" alt="John" />
        </v-avatar>
      </template>
      <template v-slot:item.status="{ item }">
        <v-chip color="green" v-if="item.approuver">
          {{ `approuvé` }}
        </v-chip>
        <v-chip color="red" v-else>
          {{ `desapprouvé` }}
        </v-chip>
      </template>

      <template v-slot:item.approuver="{ item }">
        <v-chip color="primary" v-if="item.status">
          <v-switch v-model="item.approuver" color="primary"></v-switch>
        </v-chip>
      </template>
    </v-data-table>
  </div>
</template>

<script>
import Sheet from "../clue/Sheet";
export default {
  components: {
    Sheet,
  },
  data() {
    return {
      scrollInvoked: 0,
      headers: [
        {
          value: "id",
          text: "ID",
        },
        {
          value: "avatar",
          text: "",
        },
        {
          value: "name",
          text: "NOM ET PRÉNOM",
        },
        {
          value: "status",
          text: "STATUS",
        },
        {
          value: "approuver",
          text: "APPROUVER",
        },
        {
          value: "email",
          text: "EMAIL",
        },
        {
          value: "tel",
          text: "TÉLÉPHONE",
        },
      ],
      user: [
        {
          id: 1,
          name: "Stive Jobs",
          avatar: "wwww",
          status: "Approvee",
          approuver: true,
          email: "99stive@mail.com",
          tel: "+1-334-42323",
        },
      ],
    };
  },
  methods: {
    tt() {
      alert("jdjd");
    },
  },
};
</script>

<style lang="scss" scoped>
</style>