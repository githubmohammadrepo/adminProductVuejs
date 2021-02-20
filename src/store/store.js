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
        },
        addNewStoreShowLevels: {
            levelOne: {
                formData: {
                    userName: null,
                    password: null
                },
                show: true,
            },
            levelTwo: {
                formData: {

                },
                show: false
            },
            levelThree: {
                formData: {

                },
                show: false
            },
            finalLevel: {
                formData: {

                },
                show: false,
            }
        }

    }),
    mutations: {
        //payload format {key:levelName,value:true/false,formData=null}
        showAddNewStoreLevel(state, payload) {
            //hide all levels
            state.addNewStoreShowLevels.levelOne.show = false;
            state.addNewStoreShowLevels.levelTwo.show = false;
            state.addNewStoreShowLevels.levelThree.show = false;
            state.addNewStoreShowLevels.finalLevel.show = false;

            //show level
            state.addNewStoreShowLevels[payload.key].show = payload.value;

            //update form data
            if (payload.cancelInsert) {

            } else {
                state.addNewStoreShowLevels[payload.key].formData = payload.formData;
            }


        },
        saveFindedStores(state, payload) {
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

        saveOneStoreIntoState(state, payload) {
            state.items.map((value) => {
                if (value.id == payload.id) {
                    return payload;
                } else {
                    return value;
                }
            })
        },
        removeOneStoreFromState(state, payload) {
            state.items = state.items.filter(store => {
                if (store.id == payload) {
                    return false;
                } else {
                    return true;
                }
            })
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
            state.storeShowComponents.SearchStore.selectedProvince = payload.selectedProvince,
                state.storeShowComponents.SearchStore.selectedCity = payload.selectedCity,
                state.storeShowComponents.SearchStore.selectedRegion = payload.selectedRegion
                // state.storeShowComponents.SearchStore = payload
        },
        computePagesPaginations(state, payload) {
            state.paginationObject.pages = Math.ceil(payload / state.paginationObject.countPerPage)
            console.log('pagination vues')
            console.log(state.paginationObject.pages)
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

            axios
                .post("http://fishopping.ir/serverHypernetShowUnion/adminProduct/webservices/stores/ShowStoreInfos.php", {
                    ...state.storeShowComponents.SearchStore,
                    offset: parseInt(state.paginationObject.currentPage) * (parseInt(state.paginationObject.countPerPage)),
                    count: state.paginationObject.countPerPage,
                    showALlStoreInfos: true,
                })
                .then(response => {
                    if (response.data && response.data.stores) {

                        commit('stores/makeSearchAsFiltered', true)

                        //save info in store
                        commit('saveFindedStores', response.data.stores)
                            //close filtered store component
                        commit('showCompoenetByName', 'showFindedStores')
                        commit('computePagesPaginations', response.data.count)

                    } else {}
                })
                .catch(error => {
                    console.log(error)
                })
        },
        getAllStores({ state, commit }) {
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
                    } else {}
                })
                .catch(error => {
                    console.log(error)
                })
        }

    },
    getters: {
        getFormDataInserNewStoreLevels(state) {
            return {
                // formData level one
                ...state.addNewStoreShowLevels.levelOne.formData,

                // formData level two
                ...state.addNewStoreShowLevels.levelTwo.formData,

                //formData level three
                ...state.addNewStoreShowLevels.levelThree.formData,

                //formData findal level
                ...state.addNewStoreShowLevels.finalLevel.formData,


            }
        }
    }
}


export default companies;