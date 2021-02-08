import axios from 'axios'
const brands = {
    namespaced: true,
    state: () => ({
        brandTest: 'brandTest',
        paginationObject: {
            show: false,
            pages: 20,
            currentPage: 0,
            countPerPage: 10
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
        hideAllOperations(state) {
            console.log('brand operation')
            console.log(state.brandOperation)
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
                })
                .then(function(response) {
                    console.log('brands info')
                    console.log(response.data.brands)
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

export default brands;