<template>
  <div>
    <v-row>
      <v-col>
        <!-- HeadLine -->
        <Headline
          :headline="`(${deliveries.length}) Livreur(s)`"
          subheadline="Gestion des offres publiées"
          :headline-classes="[
            'text-h5',
            'primary--text',
            'font-weight-black',
            'text-uppercase',
          ]"
        />
      </v-col>
    </v-row>
    <v-row class="mt-5" flat>
      <v-col :align="!isMobile ? 'right' : ''">
        <v-btn
          color="primary"
          outlined
          :block="isMobile"
          @click="addDelivery()"
        >
          <v-icon left>mdi-truck-delivery</v-icon>
          Ajouter Livreur
        </v-btn>
      </v-col>
    </v-row>
    <v-card class="mt-5">
      <v-toolbar flat>
        <v-text-field
          v-model="search"
          append-icon="mdi-magnify"
          label="Search"
          hide-details
          solo
          clearable
        ></v-text-field>
      </v-toolbar>

      <v-data-table
        :loading="isBusy('fetch-deliveries')"
        :headers="headers"
        :items="deliveries"
        :search="search"
        disable-sort
        item-key="id"
      >
        <template v-slot:[`item.name`]="{ item }">
          <v-icon color="error" v-if="item.blocked_at != null"
            >mdi-cancel</v-icon
          >
          <span>{{ item.first_name + " " + item.last_name }}</span>
        </template>

        <template v-slot:[`item.avatar`]="{ item }">
          <v-avatar size="40">
            <img :src="`/images/avatars/${item.avatar}`" alt="John" />
          </v-avatar>
        </template>

        <template v-slot:[`item.status`]="{ item }">
          <v-chip v-if="!item.status">
            <v-switch
              v-model="item.status"
              @change="editApprovement(item.id)"
              color="primary"
            ></v-switch>
          </v-chip>
          <v-chip color="success" v-else> approuvé(e) </v-chip>
        </template>

        <template v-slot:[`item.actions`]="{ item }">
          <v-speed-dial
            :v-model="true"
            direction="left"
            transition="slide-x-reverse-transition"
          >
            <template v-slot:activator>
              <v-btn small fab color="primary">
                <v-icon> mdi-dots-horizontal </v-icon>
              </v-btn>
            </template>

            <!-- Delete -->
            <v-tooltip top>
              <template v-slot:activator="{ on, attrs }">
                <v-btn
                  v-on="on"
                  v-bind="attrs"
                  color="error"
                  @click="deleteDelivery(item.id)"
                  fab
                  x-small
                >
                  <v-icon> mdi-delete </v-icon>
                </v-btn>
              </template>
              Supprimer
            </v-tooltip>
            <!-- Edit -->
            <v-tooltip top>
              <template v-slot:activator="{ on, attrs }">
                <v-btn
                  v-on="on"
                  v-bind="attrs"
                  color="info"
                  fab
                  x-small
                  @click="editDelivery(item)"
                >
                  <v-icon> mdi-pen </v-icon>
                </v-btn>
              </template>
              Modifier
            </v-tooltip>
            <!-- Permis de conduire -->
            <v-tooltip top>
              <template v-slot:activator="{ on, attrs }">
                <v-btn v-on="on" v-bind="attrs" color="warning" fab x-small>
                  <v-icon> mdi-card-account-details </v-icon>
                </v-btn>
              </template>
              Télécharger permis
            </v-tooltip>
            <!-- Block -->
            <!-- Block -->
            <v-tooltip top>
              <template v-slot:activator="{ on, attrs }">
                <v-btn
                  v-on="on"
                  v-bind="attrs"
                  color="error"
                  fab
                  x-small
                  @click="blockDelivery(item.id)"
                  v-if="item.blocked_at == null"
                >
                  <v-icon> mdi-cancel </v-icon>
                </v-btn>
              </template>
              Bloquer
            </v-tooltip>

            <!-- Block -->
            <v-tooltip top>
              <template v-slot:activator="{ on, attrs }">
                <v-btn
                  v-on="on"
                  v-bind="attrs"
                  color="success"
                  fab
                  x-small
                  @click="unblockDelivery(item.id)"
                  v-if="item.blocked_at != null"
                >
                  <v-icon> mdi-undo </v-icon>
                </v-btn>
              </template>
              Débloquer
            </v-tooltip>
            <!-- Chatter -->
            <v-tooltip top>
              <template v-slot:activator="{ on, attrs }">
                <v-btn v-on="on" v-bind="attrs" color="primary" fab x-small>
                  <v-icon> mdi-chat </v-icon>
                </v-btn>
              </template>
              Chatter
            </v-tooltip>
          </v-speed-dial>
        </template>
      </v-data-table>
    </v-card>
  </div>
</template>

<script>
import Headline from "../pieces/Headline";
import HandleDelivery from "../pieces/HandleDelivery";

import { mapState } from "vuex";
export default {
  components: {
    Headline,
  },
  data() {
    return {
      search: "",
      headers: [
        {
          value: "avatar",
          text: "Photo",
        },
        {
          value: "name",
          text: "NOM ET PRÉNOM",
        },
        {
          value: "status",
          text: "APPROUVER",
        },
        {
          value: "email",
          text: "EMAIL",
        },
        {
          value: "phone_number",
          text: "TÉLÉPHONE",
        },
        {
          value: "actions",
        },
      ],
    };
  },
  computed: {
    ...mapState(["expected"]),
    //*Get no blocked delivery
    deliveries: function () {
      return this.$store.getters.deliveries;
    },
    //* Is mobile
    isMobile() {
      return this.$vuetify.breakpoint.xsOnly;
    },
  },
  methods: {
    //* Init
    init() {
      //* Get all deliveries
      this.$store.dispatch("fetchData", {
        path: "/api/fetch/deliveries",
        mutation: "FETCH_DELIVERIES",
        related: "fetch-deliveries",
      });
    },
    //* Add delivery
    addDelivery: function () {
      this.$store.commit("CLEAR_EXPECTED");

      this.$dialog.show(HandleDelivery, {
        title: "Ajouter nouveau Livreur",
        width: "40%",
      });
    },
    //* Edit delivery
    editDelivery: function (Delivery) {
      this.$store.commit("CLEAR_EXPECTED");

      this.$dialog.show(HandleDelivery, {
        deliveryToEdit: Delivery, // Props
        title: "Modifier le Livreur",
        width: "40%",
      });
    },
    // * Edit approvement delivery man.
    editApprovement(delivery_id) {
      this.$store.commit("CLEAR_EXPECTED");
      this.$store.dispatch("postData", {
        path: `/api/approved/delivery-man`,
        data: { delivery_id: delivery_id },
        related: `edit-approvement`,
      });
    },
    //* Delete delivery
    async deleteDelivery(delivery_id) {
      this.$store.commit("CLEAR_EXPECTED");
      this.$dialog.confirm({
        text: "Êtes-vous sûr de supprimer ce livreur ?",
        title: "Attention!",
        actions: {
          false: "Non!",
          true: {
            color: "red",
            text: "Je confirme",
            handle: () => {
              this.$store.dispatch("deleteData", {
                path: `/api/delete/delivery-man`,
                data: { delivery_id: delivery_id },
                related: `delete-delivery-man`,
              });
              this.dummy = delivery_id;
            },
          },
        },
      });
    },
    //* Block delivery
    blockDelivery(delivery_id) {
      this.$store.commit("CLEAR_EXPECTED");

      this.$dialog.confirm({
        text: "Êtes-vous sûr de bloquer ce livreur ?",
        title: "Attention!",
        actions: {
          false: "Non!",
          true: {
            color: "red",
            text: "Je confirme",
            handle: () => {
              this.$store.dispatch("patchData", {
                path: `/api/block/delivery-man`,
                data: { delivery_id: delivery_id },
                related: `block-delivery-man`,
                returned: true,
              });
              this.$store.commit("BLOCK_DELIVERY", delivery_id);
            },
          },
        },
      });
    },
    //* unBlock delivery
    unblockDelivery: function (delivery_id) {
      this.$store.commit("CLEAR_EXPECTED");

      if (delivery_id) {
        this.$dialog.confirm({
          text: "Êtes-vous sûr de débloquer ce livreur ?",
          title: "Attention!",
          actions: {
            false: "Non!",
            true: {
              color: "red",
              text: "Je confirme",
              handle: () => {
                // Change it in backend
                this.$store.dispatch("patchData", {
                  path: "/api/unblock/delivery-man",
                  data: { delivery_id: delivery_id },
                  related: "unblock-delivery",
                });
                //Change it in front
                this.$store.commit("UNBLOCK_DELIVERY", delivery_id);
              },
            },
          },
        });
      }
    },
    //* The famous isBusy funtion haha
    isBusy: function (fetcher) {
      try {
        return this.$store.getters.expected(fetcher).status == "busy"
          ? true
          : false;
      } catch (error) {
        return false;
      }
    },
  },
  watch: {
    expected() {
      //* Approuve delivery
      {
        let expected = this.$store.getters.expected("edit-approvement");
        if (expected != undefined) {
          if (expected.status === "success") {
            this.$dialog.notify.success(expected.result.subMessage, {
              position: "top-right",
              timeout: 3000,
            });
          }
          if (expected.status === "error") {
            this.$dialog.notify.warning(expected.result.subMessage, {
              position: "top-right",
              timeout: 3000,
            });
          }
        }
      }
      //* Delete delivery
      {
        let expected = this.$store.getters.expected("delete-delivery-man");
        if (expected != undefined) {
          if (expected.status === "success") {
            //?  DELETE DELIVERY
            this.$store.commit("DELETE_DELIVERY", this.dummy);
            this.$dialog.notify.success(expected.result.subMessage, {
              position: "top-right",
              timeout: 3000,
            });
          }
          if (expected.status === "error") {
            this.$dialog.notify.warning(expected.result.subMessage, {
              position: "top-right",
              timeout: 3000,
            });
          }
        }
      }
      //* Block delivery
      {
        let expected = this.$store.getters.expected("block-delivery-man");
        if (expected != undefined) {
          if (expected.status === "success") {
            this.$dialog.notify.success(expected.result.subMessage, {
              position: "top-right",
              timeout: 3000,
            });
          }
          if (expected.status === "error") {
            this.$dialog.notify.warning(expected.result.subMessage, {
              position: "top-right",
              timeout: 3000,
            });
          }
        }
      }
      //* Unblock delivery
      {
        let expected = this.$store.getters.expected("unblock-delivery");
        if (expected != undefined) {
          if (expected.status === "success") {
            this.$dialog.notify.success(expected.result.subMessage, {
              position: "top-right",
              timeout: 3000,
            });
          }
          if (expected.status === "error") {
            this.$dialog.notify.warning(expected.result.subMessage, {
              position: "top-right",
              timeout: 3000,
            });
          }
        }
      }
      //* Add delivery
      {
        let expected = this.$store.getters.expected("add-delivery");
        if (expected != undefined) {
          if (expected.status === "success") {
            this.$dialog.notify.success(expected.result.subMessage["msg"], {
              position: "top-right",
              timeout: 3000,
            });
            this.$store.commit(
              "ADD_DELIVERY",
              expected.result.subMessage["data"]
            );
          }
          if (expected.status === "error") {
            for (const [key, value] of Object.entries(
              expected.result.subMessage
            )) {
              this.$dialog.notify.warning(value[0], {
                position: "top-right",
                timeout: 5000,
              });
            }
          }
        }
      }

      // Edit delivery
      {
        let expected = this.$store.getters.expected("edit-delivery");
        if (expected != undefined) {
          if (expected.status === "success") {
            this.$dialog.notify.success(expected.result.subMessage["msg"], {
              position: "top-right",
              timeout: 3000,
            });
            this.$store.commit(
              "ADD_DELIVERY",
              expected.result.subMessage["data"]
            );
          }
          if (expected.status === "error") {
            for (const [key, value] of Object.entries(
              expected.result.subMessage
            )) {
              this.$dialog.notify.warning(value[0], {
                position: "top-right",
                timeout: 5000,
              });
            }
          }
        }
      }
    },
  },
  created() {
    this.$store.commit("CLEAR_EXPECTED");
    this.init();
  },
};
</script>
