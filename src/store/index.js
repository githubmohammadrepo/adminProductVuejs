import Vue from 'vue'
import Vuex from 'vuex'
import companies from './companies'
import brands from './brands'
import stores from './store'
import products from './products'
Vue.use(Vuex)


export default new Vuex.Store({
    state: {
        errorNotification: {
            show: false,
            message: "خطا دراجرای عملیات"
        },
        successNotification: {
            show: false,
            message: "خطا دراجرای عملیات",
            bodyContent: ''
        },
        shwoConfirmSms: false
    },
    mutations: {
        showError(state) {
            state.errorNotification.show = !state.errorNotification.show;
        },
        showSuccess(state) {
            state.successNotification.show = !state.successNotification.show;
        }
    },
    actions: {},
    modules: {
        companies: companies,
        brands: brands,
        stores: stores,
        products: products,
    }
})