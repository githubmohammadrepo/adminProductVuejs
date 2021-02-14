import '@babel/polyfill'
import 'mutationobserver-shim'
import '@/assets/css/main.css'
import Vue from 'vue'
import './plugins/bootstrap-vue'
import App from './App.vue'
import './registerServiceWorker'
import router from './router'
import store from './store'
import axios from 'axios'
import VueAxios from 'vue-axios'
import Vuex from 'vuex'
import 'bootstrap-icons-vue';
import { BootstrapIconsPlugin } from 'bootstrap-icons-vue';
import { BootstrapVue, IconsPlugin  } from 'bootstrap-vue'

Vue.config.productionTip = false

Vue.use(VueAxios, axios,Vuex,BootstrapIconsPlugin,BootstrapVue,IconsPlugin)

new Vue({
  router,
  store,
  render: h => h(App)
}).$mount('#app')
