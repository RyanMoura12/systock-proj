/**
 * main.ts
 *
 * Bootstraps Vuetify and other plugins then mounts the App
 */

// Plugins
import { registerPlugins } from "@/plugins";

// Importando o vue-the-mask
import { mask } from "vue-the-mask";

// Components
import App from "./App.vue";

// Composables
import { createApp } from "vue";

const app = createApp(App);

registerPlugins(app);
app.directive("mask", mask); // Registrar diretiva antes de montar
app.mount("#app"); // Montar após todas as configurações
