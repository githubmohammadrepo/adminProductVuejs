import Vue from 'vue'
import Vuex from 'vuex'
import companies from './companies'
import brands from './brands'
Vue.use(Vuex)


export default new Vuex.Store({
    state: {
        paginationObject: {
            show: false,
            Countpages: 0,
            currentPageNumber: 0
        }
    },
    mutations: {},
    actions: {},
    modules: {
        companies: companies,
        brands: brands
    }
})