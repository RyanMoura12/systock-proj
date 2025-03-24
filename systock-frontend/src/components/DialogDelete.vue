<template>
  <v-dialog v-model="isLoadActiveLocal" persistent max-width="350">
    <v-card>
      <!-- Título -->
      <v-card-item class="py-1 px-4 text-white bg-error">
        <h4 class="text-h6">Deletar</h4>
      </v-card-item>

      <!-- Corpo -->
      <v-card-text class="pa-6">
        <h5 class="text-h6 title mb-2">{{ title }}</h5>

        <v-alert
          variant="outlined"
          class="text-subtitle-2"
          type="error"
          prominent
          border="top"
        >
          A operação resultará na exclusão permanente do item da lista.
        </v-alert>

        <!-- Botões -->
        <v-btn
          class="mt-4 mr-2 text-white"
          color="error"
          @click="confirmDelete"
          :disabled="isLoading"
        >
          <template v-if="isLoading">Excluindo...</template>
          <template v-else>Sim</template>
        </v-btn>

        <v-btn
          class="mt-4 text-white"
          color="#d2d6d5"
          @click.stop="isLoadActiveLocal = false"
          :disabled="isLoading"
        >
          Não
        </v-btn>
      </v-card-text>
    </v-card>
  </v-dialog>
</template>

<script setup lang="ts">
import { computed, ref } from "vue";
import api from "@/plugins/axios";

// Props
const props = defineProps({
  title: String,
  loadDialog: Boolean,
  ID: {
    type: Number,
    required: true,
  },
  apiUrl: {
    type: String,
    required: true,
  },
});

// Emits
const emit = defineEmits(["closeDialog", "reload-list", "alert"]);

// Computed
const isLoadActiveLocal = computed({
  get() {
    return props.loadDialog;
  },
  set(val) {
    if (!val) {
      emit("closeDialog");
    }
  },
});

// Estado de Loading
const isLoading = ref(false);

// Métodos
async function confirmDelete() {
  isLoading.value = true;
  try {
    const response = await api.delete(`${props.apiUrl}/${props.ID}`);

    if (response.status === 200 || response.status === 204) {
      emit("alert", "Excluído com sucesso!", "success");
      emit("reload-list");
      isLoadActiveLocal.value = false;
    } else {
      throw new Error("Falha ao excluir");
    }
  } catch (error) {
    console.error("Erro ao excluir:", error);
    emit("alert", "Erro ao excluir o item.", "error");
  } finally {
    isLoading.value = false;
  }
}
</script>
