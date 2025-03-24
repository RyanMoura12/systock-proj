<template>
  <user-form
    @reload-list="reload"
    @close-dialog="closeDialogForm"
    @alert="alertShow"
    :dialog-active="dialogForm"
    :operation="operation"
    :data="user"
  ></user-form>
  <DialogDelete
    title="Deseja excluir o Usuário?"
    @close-dialog="closeDialogDelete"
    @reload-list="reload"
    @alert="alertShow"
    :loadDialog="dialogDelete"
    :ID="dataID"
    :apiUrl="'v1/users'"
  ></DialogDelete>

  <v-card color="#1e1e1e" class="pa-5 rounded-lg">
    <v-container>
      <v-card-title class="text-h5 text-white">Lista de usuários</v-card-title>
      <Alert ref="alert" />
      <v-row>
        <v-col cols="10">
          <v-text-field
            density="compact"
            v-model="search"
            label="Pesquisar"
            hide-details
            variant="outlined"
          ></v-text-field>
        </v-col>
        <v-col cols="2" class="text-right">
          <v-btn color="primary" @click="newItem" class="ml-auto">
            <v-icon class="mr-2">mdi-plus</v-icon>Novo
          </v-btn>
        </v-col>
      </v-row>
      <v-data-table
        class="elevation-1 mt-5"
        :headers="(headers as any)"
        density="compact"
        item-value="name"
        :items="rowData"
        :search="search"
        :items-per-page="5"
        dark
      >
        <template v-slot:item.actions="{ item }">
          <v-icon
            small
            class="mr-2 cursor-pointer"
            title="Editar"
            color="success"
            @click="editItem(item)"
            >mdi-square-edit-outline</v-icon
          >
          <v-icon
            @click="deleteItem(item)"
            small
            color="error"
            class="cursor-pointer"
            title="Deletar"
            >mdi-delete-outline</v-icon
          >
        </template>
      </v-data-table>
    </v-container>
  </v-card>
</template>

<script setup lang="ts">
import { ref, onMounted } from "vue";
import UserForm from "./UserForm";
import api from "@/plugins/axios";
import type Alert from "@/components/Alert.vue";
import DialogDelete from "@/components/DialogDelete.vue";

//interface
interface User {
  id: string;
  name: string;
  tax_id: string;
  email: string;
  password: string;
  current_password: string;
}

//alerts
const alert = ref<InstanceType<typeof Alert> | null>(null);

//variables
const dataID = ref(0);
const dialogDelete = ref(false);
const search = ref("");
const rowData = ref([]);
const dialogForm = ref(false);
const operation = ref("");
const user = ref<User>(Object.assign({}));
const headers = [
  { title: "Nome", align: "left", key: "name" },
  { title: "CPF", align: "left", key: "tax_id" },
  { title: "E-mail", align: "left", key: "email" },
  { title: "Ações", key: "actions", sortable: false },
];

//Methods
const fetchUsers = async () => {
  try {
    const response = await api.get("/v1/users");
    rowData.value = response.data;
  } catch (error: any) {
    console.log(error);
  }
};
function newItem() {
  operation.value = "create";
  dialogForm.value = true;
}

async function editItem(item: any) {
  try {
    const response = await api.get(`v1/users/${item.id}`);
    const userData = response.data;

    operation.value = "update";
    dialogForm.value = true;
    user.value = userData;
  } catch (error) {
    console.log("Erro ao buscar usuário:");
  }
}

async function reload() {
  fetchUsers();
}
function closeDialogForm() {
  dialogForm.value = false;
  user.value = Object.assign({});
}
function alertShow(message: string, model: string) {
  alert.value?.show(message, model);
}
function closeDialogDelete() {
  dialogDelete.value = false;
}

function deleteItem(item: any) {
  dataID.value = item.id;
  dialogDelete.value = true;
}

onMounted(() => {
  fetchUsers();
});
</script>
