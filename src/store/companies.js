import axios from 'axios';
import { mixinState, mixinMutations, mixinActions } from '@/assets/mixins/companies/operationMixin.js'
const companies = {
    namespaced: true,
    state: () => ({
        ...mixinState(),
        paginationObject: {
            show: false,
            pages: 20,
            currentPage: 0,
            countPerPage: 10
        },
        items: Array(),
        companyEditing: false,
        editDataObject: {
            id: "",
            companyName: "",
            brandName: "",
            mangerFullName: "",
            phone: "",
            mobile: "",
            address: "",
            user_id: ""
        },
        companyOperation: {
            edit: false,
            remove: false,
            add: false,
            typeOperation: ''
        },
        AddNewCompany: {
            levelOne: {
                show: true,
                userName: "",
                password: "",
            },
            levelTwo: {
                show: false,
                userId: false,

            },
            levelThree: {
                show: false,
                price: null,
                brand_name: "",
                brand_id: "",
                brandSelectedId: null

            }
        }

    }),
    mutations: {
        ...mixinMutations(),
        hideAllOperations(state) {

            for (const key in state.companyOperation) {
                if (Object.hasOwnProperty.call(state.companyOperation, key)) {
                    state.companyOperation[key] = false;
                }
            }
        },
        editOperation(state) {
            return state.companyOperation.edit = !state.companyOperation.edit;
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
        hideAllCreateNewCompnay(state) {
            for (const key in state.AddNewCompany) {
                if (Object.hasOwnProperty.call(state.AddNewCompany, key)) {
                    state.AddNewCompany[key].show = false
                }
            }
        },
        showCompanyLevels(state, payload) {
            let levelName = '';
            if (payload.toString() == 'levelOne') {
                levelName = 'levelOne';
            } else if (payload.toString() == 'levelTwo') {
                levelName = 'levelTwo';
            } else if (payload.toString() == 'levelThree') {
                levelName = 'levelThree';
            } else {
                //don othing
            }
            state.AddNewCompany[levelName].show = !state.AddNewCompany[levelName].show;
        }

    },
    actions: {
        ...mixinActions(),
        /**
         * get all companies by offset
         */
        getAllCompanies(context) {
            context.state.items = Array();
            axios
                .post("http://fishopping.ir/serverHypernetShowUnion/showBrands.php", {
                    offset: parseInt(context.state.paginationObject.countPerPage) * parseInt(context.state.paginationObject.currentPage),
                    typeAction: "select"
                })
                .then(function(response) {
                    if (response.data.companies && response.data.companies.length) {
                        response.data.companies.forEach(function(value, index) {
                            let newObject = {};
                            newObject.id = value.id


                            newObject.companyName = (value.ShopName ? (value.ShopName.toString().length ? (value.ShopName) : ('')) : (''))
                            newObject.user_id = value.user_id;
                            newObject.id = value.id;

                            newObject.brandName = (value.category_name ? (value.category_name.toString().length ? (value.category_name) : ('')) : (''))

                            newObject.managerName = (value.ManagerName ? (value.ManagerName.toString().length ? (value.ManagerName) : ('')) : (''))

                            newObject.mobile = (value.MobilePhon ? (value.MobilePhon.toString().length ? (value.MobilePhon) : ('')) : (''))

                            newObject.phone = (value.phone ? (value.phone.toString().length ? (value.phone) : ('')) : (''))

                            newObject.address = (value.Address ? (value.Address.toString().length ? (value.Address) : ('')) : (''))

                            newObject.brandImage = (value.file_path ? (value.file_path.toString().length ? (value.file_path) : ('')) : (''))

                            context.state.items.push(newObject)
                        })

                    }
                    if (response.data.count) {
                        context.state.paginationObject.pages = Math.ceil(response.data.count / 10);
                    }

                })
                .catch(function(error) {
                    console.log(error)
                })
        }

    },
    getters: {

    }
}


export default companies;