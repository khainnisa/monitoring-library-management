import "./bootstrap";
// Import global CSS (Tailwind + app styles)
import "../css/app.css";
import { createApp } from "vue";
import { createPinia } from "pinia";
import router from "./router";
import App from "./App.vue";
import { useAuthStore } from "./stores/auth"; // ← TAMBAHKAN INI

console.log("🚀 Starting Vue app...");

const app = createApp(App);
const pinia = createPinia();

app.use(pinia);
app.use(router);

const authStore = useAuthStore();
authStore.initializeAuth();

app.mount("#app");

console.log("✅ Vue app mounted");