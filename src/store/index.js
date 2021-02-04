import Vue from 'vue'
import Vuex from 'vuex'
import companies from './companies'
import brands from './brands'
Vue.use(Vuex)


export default new Vuex.Store({
    state: {
        errorNotification: {
            show: false,
            message: "خطا دراجرای عملیات"
        },
        successNotification: {
            show: true,
            message: "خطا دراجرای عملیات",
            bodyContent: ''
        },
        shwoConfirmSms: true
    },
    mutations: {},
    actions: {},
    modules: {
        companies: companies,
        brands: brands
    }
})