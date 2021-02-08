import axios from 'axios'
const brands = {
    namespaced: true,
    state: () => ({
        brandTest: 'brandTest',
        search: null,
        paginationObject: {
            show: false,
            pages: 20,
            currentPage: 0,
            countPerPage: 50
        },
        items: Array(),
        brandEditing: false,
        editDataObject: {
            id: "",
            brandName: "",
            mangerFullName: "",
            phone: "",
            mobile: "",
            address: "",
            user_id: ""
        },
        brandOperation: {
            edit: false,
            remove: false,
            add: false,
            typeOperation: ''
        },
        AddNewbrand: {
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
        setSearchValue(state, payload) {
            state.search = payload;
        },
        restCuurentPageToOne(state, payload = null) {
            if (payload == null) {
                state.paginationObject.currentPage = 0;
            } else {
                state.paginationObject.currentPage = payload;
            }
        },
        changeCountPerPage(state, payload) {
            state.paginationObject.countPerPage = payload;
        },
        hideAllOperations(state) {
            for (const key in state.brandOperation) {
                if (Object.hasOwnProperty.call(state.brandOperation, key)) {
                    state.brandOperation[key] = false;
                }
            }
        },
        editOperation(state) {
            return state.brandOperation.edit = !state.brandOperation.edit;
        },
        removeOnebrand(state) {
            return state.brandOperation.remove = !state.brandOperation.remove;
        },
        addOnebrand(state) {
            return state.brandOperation.add = !state.brandOperation.add;
        },
        getTypeOperationSave(state) {
            let typeOperation = '';
            for (const key in state.brandOperation) {
                if (Object.hasOwnProperty.call(state.brandOperation, key)) {
                    if (state.brandOperation[key] == true) {
                        typeOperation = key;
                    }
                }
            }
            state.brandOperation.typeOperation = typeOperation
        },
        /**
         * hide all createNewbrand components
         */
        hideAllCreateNewCompnay(state) {
            for (const key in state.AddNewbrand) {
                if (Object.hasOwnProperty.call(state.AddNewbrand, key)) {
                    state.AddNewbrand[key].show = false
                }
            }
        },
        showbrandLevels(state, payload) {
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
            state.AddNewbrand[levelName].show = !state.AddNewbrand[levelName].show;
        },
        removeOneCategory(state, category_id) {
            state.items = state.items.filter(brand => {
                if (brand.category_id == category_id) {
                    return false;
                } else {
                    return true;
                }
            })
        }

    },
    actions: {
        /*
         * get all brands by offset
         */
        getAllBrands(context) {
            context.state.items = Array();
            axios
                .post("http://fishopping.ir//serverHypernetShowUnion/adminProduct/webservices/mainBrands/showAllBrands.php", {
                    offset: parseInt(context.state.paginationObject.countPerPage) * parseInt(context.state.paginationObject.currentPage),
                    "getAllBrands": true,
                    count: context.state.paginationObject.countPerPage,
                    search: context.state.search
                })
                .then(function(response) {
                    console.log(response.data)
                    if (response.data.brands && response.data.brands.length) {
                        response.data.brands.forEach(function(value, index) {
                            let newObject = {};
                            newObject.operation = value.category_id
                            newObject.category_id = value.category_id

                            newObject.user_id = value.user_id;
                            newObject.user_name = value.name;
                            newObject.brand_logo = value.file_path;

                            newObject.category_name = (value.category_name ? (value.category_name.toString().length ? (value.category_name) : ('')) : (''))

                            newObject.category_published = (value.category_published ? true : false);

                            context.state.items.push(newObject)
                        })

                    }
                    if (response.data.count) {
                        context.state.paginationObject.pages = Math.ceil(response.data.count / parseInt(context.state.paginationObject.countPerPage));
                    }

                })
                .catch(function(error) {
                    console.log(error)
                })
        },

    },
    getters: {
        getCountPerPagePagination(state) {
            return state.paginationObject.countPerPage;
        }
    }
}

export default brands;