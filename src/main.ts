import '@babel/polyfill'
import 'mutationobserver-shim'
import Vue from 'vue'
import './plugins/bootstrap-vue'
import App from './App.vue'
import './registerServiceWorker'
import router from './router'
import store from './store'
import axios from 'axios'
import VueAxios from 'vue-axios'
import Vuex from 'vuex'
import { BootstrapIconsPlugin } from 'bootstrap-icons-vue';
Vue.config.productionTip = false

Vue.use(VueAxios, axios,Vuex,BootstrapIconsPlugin)

new Vue({
  router,
  store,
  render: h => h(App)
}).$mount('#app')
