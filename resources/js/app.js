import "./bootstrap";
// Import global CSS (Tailwind + app styles)
import "../css/app.css";
import { createApp } from "vue";
import { createPinia } from "pinia";
import router from "./router";
import App from "./App.vue";

console.log("🚀 Starting Vue app...");

const app = createApp(App);
const pinia = createPinia();

app.use(pinia);
app.use(router);
app.mount("#app");

console.log("✅ Vue app mounted");