<template>
  <v-dialog v-model="loadDialogLocal">
    <v-form ref="formRef" @submit.prevent="submitForm">
      <v-card class="mx-auto" width="50%">
        <v-card-title class="pa-2 bg-primary">
          <span class="title text-white ml-2">{{ formTitle }} </span>
        </v-card-title>

        <v-card-text class="pa-2">
          <v-container class="pb-1">
            <v-row>
              <v-col class="py-1" cols="12">
                <v-text-field
                  v-model="user.name"
                  label="Nome"
                  :error-messages="errors.name"
                  outlined
                />
              </v-col>

              <v-col class="py-1" cols="12">
                <v-text-field
                  v-model="user.email"
                  label="Email"
                  :error-messages="errors.email"
                  outlined
                />
              </v-col>

              <v-col class="py-1" cols="12">
                <v-text-field
                  v-model="user.tax_id"
                  label="CPF"
                  outlined
                  :error-messages="errors.tax_id"
                  v-mask="'###.###.###-##'"
                />
              </v-col>

              <!-- Só exibe "Senha" no modo de criação -->
              <v-col class="py-1" cols="12" v-if="!isEdit">
                <v-text-field
                  v-model="user.password"
                  label="Senha"
                  :error-messages="errors.password"
                  :type="showPassword ? 'text' : 'password'"
                  outlined
                >
                  <template v-slot:append-inner>
                    <v-btn
                      icon
                      @click="showPassword = !showPassword"
                      x-small
                      class="mt-n1"
                    >
                      <v-icon small>{{
                        showPassword ? "mdi-eye" : "mdi-eye-off"
                      }}</v-icon>
                    </v-btn>
                  </template>
                </v-text-field>
              </v-col>

              <v-col class="py-1" cols="12" v-if="props.operation === 'update'">
                <v-text-field
                  v-model="user.current_password"
                  label="Confirmar Senha"
                  :error-messages="errors.current_password"
                  :type="showCurrentPassword ? 'text' : 'password'"
                  outlined
                >
                  <template v-slot:append-inner>
                    <v-btn
                      icon
                      @click="showCurrentPassword = !showCurrentPassword"
                      x-small
                      class="mt-n1"
                    >
                      <v-icon small>{{
                        showCurrentPassword ? "mdi-eye" : "mdi-eye-off"
                      }}</v-icon>
                    </v-btn>
                  </template>
                </v-text-field>
              </v-col>
            </v-row>
          </v-container>
        </v-card-text>

        <v-card-actions class="pa-2">
          <v-spacer></v-spacer>
          <v-btn color="error" @click.stop="loadDialogLocal = false" dark
            >Cancelar</v-btn
          >
          <v-btn color="primary" type="submit" variant="elevated">{{
            buttonTitle
          }}</v-btn>
        </v-card-actions>
      </v-card>
    </v-form>
  </v-dialog>
</template>

<script setup lang="ts">
import { ref, computed, inject, onMounted } from "vue";
import { Form } from "vee-validate";
import { z } from "zod";
import api from "@/plugins/axios";
import validateCPF from "@/validators/cpf";

// Interface
interface User {
  id: string;
  name: string;
  tax_id: string;
  email: string;
  password: string;
  current_password: string;
}

//Props
const props = defineProps({
  dialogActive: {
    type: Boolean,
    required: true,
  },
  data: {
    type: Object as () => User,
    required: true,
  },
  operation: {
    type: String,
    required: true,
  },
});

//Emits
const emit = defineEmits(["closeDialog", "reloadList", "alert"]);

// Computed
const loadDialogLocal = computed({
  get() {
    setLocalData();
    return props.dialogActive;
  },
  set(val) {
    if (!val) {
      emit("closeDialog");
    }
  },
});

const formTitle = computed(() => {
  return props.operation === "create" ? "Novo Usuário" : "Alterar Usuário";
});

const method = computed(() => {
  return props.operation === "create" ? createUser : updateUser;
});

const buttonTitle = computed(() => {
  return props.operation === "create" ? "Salvar" : "Alterar";
});

// Variables
const showPassword = ref(false);
const user = ref<User>(Object.assign({}));
const errors = ref<any>({
  name: "",
  email: "",
  tax_id: "",
  password: "",
});
const isEditing = ref(false);
const showCurrentPassword = ref(false);
const isEdit = ref(false);
const userID = ref(null);
const schema = z.object({
  name: z.string().min(1, { message: "Preencha o campo nome!" }),
  email: z
    .string()
    .email({ message: "Por favor, insira um email válido!" })
    .min(1, { message: "Preencha o campo email!" }),
  tax_id: z
    .string()
    .min(1, { message: "Preencha o campo CPF!" })
    .refine((value) => validateCPF(value) === true, {
      message: "CPF inválido!",
    }),
  password: z
    .string()
    .min(6, { message: "A senha deve ter no mínimo 6 caracteres!" })
    .min(1, { message: "Preencha o campo senha!" }),
});

//Methods

const submitForm = async () => {
  errors.value = {
    name: "",
    email: "",
    tax_id: "",
    password: "",
  };

  try {
    schema.parse(user.value);
    if (props.operation === "update") {
      await updateUser();
    } else {
      await createUser();
    }
    loadDialogLocal.value = false;
  } catch (error) {
    if (error instanceof z.ZodError) {
      error.errors.forEach((err) => {
        errors.value[err.path[0]] = err.message;
      });
    }
  }
};
function setLocalData() {
  user.value = Object.assign({}, props.data);
}

const createUser = () => {
  api
    .post("/v1/users", user.value)
    .then((response) => {
      console.log("Usuário criado:", response.data);
      emit("reloadList");
      emit("alert", "Usuário criado com sucesso!!", "success");
    })
    .catch((error) => {
      emit("alert", error.response.data.message, "error");
    });
};

const updateUser = () => {
  api
    .put(`/v1/users/${user.value.id}`, user.value)
    .then((response) => {
      emit("reloadList");
      emit("alert", "Usuário Alterado com sucesso!!", "info ");
    })
    .catch((error: any) => {
      emit("alert", "erro ao alterar o usuário", "error");
    });
};
</script>
