import { createApp } from "vue";
import App from "./App.vue";
import { createPinia } from "pinia";
import router from "./router";
import { useAuthStore } from "@/stores/authStore";
import './assets/main.css';
import "toastr/build/toastr.min.css";
import "@fontsource/plus-jakarta-sans/400.css";
import "@fontsource/plus-jakarta-sans/500.css";
import "@fontsource/plus-jakarta-sans/700.css";
import { info, success, error, warning } from "@/utils/alerts.js";
import { library } from "@fortawesome/fontawesome-svg-core";
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";
import { faUser, faHome, faSignOutAlt } from "@fortawesome/free-solid-svg-icons"; 
import "@fortawesome/fontawesome-free/css/all.css";
library.add(faUser, faHome, faSignOutAlt);
import Toast from "vue-toastification";
import "vue-toastification/dist/index.css";
import "flatpickr/dist/flatpickr.css";
import "vue-multiselect/dist/vue-multiselect.css";

const toastOptions = {
    position: "top-right",
    timeout: 4000,
    closeOnClick: true,
    pauseOnHover: true,
    draggable: true,
    showCloseButtonOnHover: false,
    hideProgressBar: false,
    icon: true,
};
 

const app = createApp(App);
const pinia = createPinia();

app.use(Toast, toastOptions);
app.use(pinia);
app.use(router);
app.component("font-awesome-icon", FontAwesomeIcon);
app.config.globalProperties.$info = info;
app.config.globalProperties.$success = success;
app.config.globalProperties.$error = error;
app.config.globalProperties.$warning = warning;

const authStore = useAuthStore();
authStore.initializeAuth();

app.mount("#app");