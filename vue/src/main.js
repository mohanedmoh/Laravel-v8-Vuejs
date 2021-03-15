import Vue from "vue";
import App from "./App.vue";
import router from "./router";
import store from "./core/services/store";
import ApiService from "./core/services/api.service";
import { VERIFY_AUTH } from "./core/services/store/auth.module";
import { RESET_LAYOUT_CONFIG } from "@/core/services/store/config.module";
import VueScrollTo from "vue-scrollto";

ApiService.init();
ApiService.setHeader();



import VueToast from "vue-toast-notification";
import "vue-toast-notification/dist/theme-sugar.css";
Vue.use(VueToast, {
  position: "top",
  dismissible: true,
});

Vue.config.productionTip = false;

// Global 3rd party plugins
import "popper.js";
import "tooltip.js";
import PerfectScrollbar from "perfect-scrollbar";
window.PerfectScrollbar = PerfectScrollbar;
import ClipboardJS from "clipboard";
window.ClipboardJS = ClipboardJS;

// Vue 3rd party plugins
import i18n from "./core/plugins/vue-i18n";
import vuetify from "./core/plugins/vuetify";
import "./core/plugins/portal-vue";
import "./core/plugins/bootstrap-vue";
import "./core/plugins/perfect-scrollbar";
import "./core/plugins/highlight-js";
import "./core/plugins/inline-svg";
import "./core/plugins/apexcharts";
import "./core/plugins/metronic";
import "@mdi/font/css/materialdesignicons.css";
import Acl from "vue-browser-acl";

import VueConfirmDialog from "vue-confirm-dialog";

Vue.use(VueConfirmDialog);
Vue.component("vue-confirm-dialog", VueConfirmDialog.default);

import BootstrapVue from "bootstrap-vue";
import {
  ValidationObserver,
  ValidationProvider,
  extend,
  localize,
} from "vee-validate";
import en from "vee-validate/dist/locale/en.json";
import * as rules from "vee-validate/dist/rules";

import "bootstrap/dist/css/bootstrap.css";
import "bootstrap-vue/dist/bootstrap-vue.css";

// Install VeeValidate rules and localization
Object.keys(rules).forEach((rule) => {
  extend(rule, rules[rule]);
});

localize("en", en);

// Install VeeValidate components globally
Vue.component("ValidationObserver", ValidationObserver);
Vue.component("ValidationProvider", ValidationProvider);

Vue.use(BootstrapVue);
Vue.use(VueScrollTo);

// API service init

Vue.prototype.$ApiService = ApiService;


router.beforeEach((to, from, next) => {
  // reset config to initial state
  store.dispatch(RESET_LAYOUT_CONFIG);

  // Ensure we checked auth before each page load.
  Promise.all([store.dispatch(VERIFY_AUTH)]).then(next);

  // Scroll page to top on every route change
  setTimeout(() => {
    window.scrollTo(0, 0);
  }, 100);
});

new Vue({
  router,
  store,
  i18n,
  vuetify,
  ApiService,
  render: (h) => h(App),
}).$mount("#app");

