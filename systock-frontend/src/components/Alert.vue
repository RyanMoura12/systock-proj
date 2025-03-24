<template>
  <v-alert
    v-if="alert"
    v-model="alert"
    class="ma-1 pa-1"
    closable
    :type="type"
    variant="outlined"
  >
    {{ message }}
  </v-alert>
</template>

<script setup lang="ts">
import { ref } from "vue";

const message = ref("");
const type = ref<any>(undefined);

const alert = ref(false);

function time() {
  setTimeout(() => {
    alert.value = false;
  }, 4000);
}

function show(text: string, model: string) {
  message.value = text;
  type.value = model;
  alert.value = true;
  time();
}

function showSuccess(text: string) {
  message.value = text;
  alert.value = true;
  time();
}

function showInfo(text: string) {
  message.value = text;
  type.value = "info";
  alert.value = true;
  time();
}

function showError(text: string) {
  message.value = text;
  type.value = "error";
  alert.value = true;
  time();
}

function showWarning(text: string) {
  message.value = text;
  type.value = "warning";
  alert.value = true;
  time();
}

defineExpose({
  show,
  showSuccess,
  showInfo,
  showError,
  showWarning,
});
</script>
