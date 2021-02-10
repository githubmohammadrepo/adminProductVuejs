import axios from 'axios';
const companies = {
    namespaced: true,
    state: () => ({
        paginationObject: {
            show: false,
            pages: 25,
            currentPage: 0,
            countPerPage: 25,
        },
        items: Array(),
        storeEditing: false,
        editDataObject: {

        },
        storeOperation: {
            edit: false,
            remove: false,
            add: false,
            typeOperation: ''
        },
        storeShowComponents: {
            findByFormFilter: {
                show: true,
                provinces: Array(),
                cities: Array(),
                regions: Array(),
            },
            showAllRegoins: {
                show: false
            },
            showTableFindedStore: {
                show: false
            },
            SearchStore: {
                province_id: null,
                city_id: null,
                region_id: null,
                filtered: false,
            }
        }

    }),
    mutations: {
        saveFindedStores(state, payload) {
            console.log(payload)
            state.items = payload;
        },
        showCompoenetByName(state, payload = null) {
            if (payload.toString() == 'filterSearch') {
                state.storeShowComponents.showTableFindedStore.show = false;
                state.storeShowComponents.findByFormFilter.show = true;
            } else if (payload.toString() == 'showFindedStores') {
                state.storeShowComponents.findByFormFilter.show = false;
                state.storeShowComponents.showTableFindedStore.show = true;
            } else {
                //error accured
            }
        },
        setSearchStoreValue(state, payLoad) {
            if (payLoad.key && payLoad.value) {
                state.SearchStore[payLoad.key] = payLoad.value;
            }
        },
        showModalOperation(state, payload) {
            state.storeOperation.add = false;
            state.storeOperation.edit = false;
            state.storeOperation.remove = false;

            //set editing modal to true
            state.storeEditing = payload.editing;
            //shwo compoennt addOneStore in modal component
            state.storeOperation[payload.key] = true;

        },
        removeOneCompany(state) {
            return state.companyOperation.remove = !state.companyOperation.remove;
        },
        addOneCompany(state) {
            return state.companyOperation.add = !state.companyOperation.add;
        },
        getTypeOperationSave(state) {
            let typeOperation = '';
            for (const key in state.companyOperation) {
                if (Object.hasOwnProperty.call(state.companyOperation, key)) {
                    if (state.companyOperation[key] == true) {
                        typeOperation = key;
                    }

                }
            }
            state.companyOperation.typeOperation = typeOperation
        },

        /**
         * hide all createNewCompany components
         */
        hideAllSstoreShowComponents(state) {
            for (const key in state.AddNewCompany) {
                if (Object.hasOwnProperty.call(state.AddNewCompany, key)) {
                    state.AddNewCompany[key].show = false
                }
            }
        },
        changeToShowComponents(state, payload) {
            let levelName = '';
            if (payload.toString() == 'findByFormFilter') {
                levelName = 'findByFormFilter';
            } else if (payload.toString() == 'showAllRegoins') {
                levelName = 'showAllRegoins';
            } else if (payload.toString() == 'showTableFindedStore') {
                levelName = 'showTableFindedStore';
            } else {
                //don othing
            }
            state.AddNewCompany[levelName].show = !state.AddNewCompany[levelName].show;
        },
        changeCountPerPagePagnitionation(state, payload) {
            state.paginationObject.countPerPage = payload;
        },
        saveSearchedFilters(state, payload) {
            state.storeShowComponents.SearchStore = payload
        },
        computePagesPaginations(state, payload) {
            state.paginationObject.pages = Math.ceil(payload / state.paginationObject.countPerPage)
        },
        makeSearchAsFiltered(state, payload) {
            state.storeShowComponents.SearchStore.filtered = payload ? true : false;
        }

    },
    actions: {
        /** 
         * get stores by filter selected from form
         * search stoes
         */
        searchStores({ state, commit }) {
            let that = this;

            console.log({
                ...state.storeShowComponents.SearchStore,
                offset: parseInt(state.paginationObject.currentPage) * (parseInt(state.paginationObject.countPerPage)),
                count: state.paginationObject.countPerPage,
                showALlStoreInfos: true,
            })
            axios
                .post("http://fishopping.ir/serverHypernetShowUnion/adminProduct/webservices/stores/ShowStoreInfos.php", {
                    ...state.storeShowComponents.SearchStore,
                    offset: parseInt(state.paginationObject.currentPage) * (parseInt(state.paginationObject.countPerPage)),
                    count: state.paginationObject.countPerPage,
                    showALlStoreInfos: true,
                })
                .then(response => {
                    console.log(response.data)
                    if (response.data && response.data.stores) {

                        commit('stores/makeSearchAsFiltered', true)

                        //save info in store
                        commit('saveFindedStores', response.data.stores)
                            //close filtered store component
                        commit('showCompoenetByName', 'showFindedStores')
                        commit('computePagesPaginations', response.data.count)

                    } else {
                        alert('status false')
                    }
                })
                .catch(error => {
                    console.log(error)
                })
        },
        getAllStores({ state, commit }) {
            alert('countPerPage' + state.paginationObject.countPerPage)
            let that = this;
            axios
                .post("http://fishopping.ir/serverHypernetShowUnion/adminProduct/webservices/stores/ShowStoreInfos.php", {
                    offset: parseInt(state.paginationObject.currentPage) * (parseInt(state.paginationObject.countPerPage)),
                    count: state.paginationObject.countPerPage,
                    getAllStoreWhoutFitler: true,
                })
                .then(response => {
                    console.log(response.data)
                    if (response.data && response.data.stores) {
                        commit('computePagesPaginations', response.data.count)
                            //save info in store
                        commit('saveFindedStores', response.data.stores)
                            //close filtered store component
                        commit('showCompoenetByName', 'showFindedStores')
                    } else {
                        alert('status false')
                    }
                })
                .catch(error => {
                    console.log(error)
                })
            alert('End_countPerPage' + state.paginationObject.countPerPage)
        }

    },
    getters: {

    }
}


export default companies;