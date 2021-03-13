import axios from 'axios';
const companies = {
    namespaced: true,
    state: () => ({
        paginationObject: {
            show: false,
            pages: 25,
            currentPage: 1,
            countPerPage: 25,
        },
        items: Array(),
        productEditing: false,
        editDataObject: {},
        productOperation: {
            show: {
                show: false,
                addNew: false,
                categoryInfo: {},
                products: {},
                subCategoryies: {},
                showProductDetails: {
                    products: {
                        show: true,
                    },
                    subCategories: {
                        show: false,
                    }
                }
            },
            edit: {
                show: false,
                editProduct: {}
            },
            remove: {
                show: false,
                removeProduct: {}
            },
            add: {
                show: false,

            }
        },
        productShowComponents: {
            categoryProducts: {
                show: true,
            },
            brandProducts: {
                show: false
            }
        },
        // categories product paginations
        categoriesProductPaginations: {
            pages: 25,
            currentPage: 1,
            countPerPage: 25,
            products: Array(),
            errors: false,
            loading: false
        },
        categoriesSubCategorisPagination: {
            pages: 25,
            currentPage: 1,
            countPerPage: 25,
            categories: Array(),
            errors: false,
        },
        //searching base on category_nmae
        searchingBaseOnCategoryName: {
            searching: false,
            searchInput: '',
            selectedSearchType: '',
        },
        mainBrandEditing: {},
        BrandProductOperation: false,
        brandProductNavigations: {
            showBrandProducts: true,
            showSubBrands: false
        },
        BrandProductEditingFromInfos: {},
        BrandModalProductOperations: {
            show: false
        }

    }),
    mutations: {
        saveEditProduct(state, payload) {
            //saerch product
            let product = state.categoriesProductPaginations.products.map(product => {
                    if (product.product_id == payload) {
                        return product;
                    }
                })
                //save prouct
            state.productOperation.edit.editProduct = null;
            state.productOperation.edit.editProduct = product[0];
        },
        saveRemoveProduct(state, payload) {
            //saerch product
            let product = state.categoriesProductPaginations.products.filter(product => {
                    if (product.product_id == payload) {
                        return true;
                    } else {
                        return false;
                    }
                })
                //save prouct
            state.productOperation.remove.removeProduct = null;
            state.productOperation.remove.removeProduct = product[0];
        },
        // clear category products
        clearCategoryProducts(state) {
            state.categoriesProductPaginations.products = Array()
        },
        // clear category products
        clearCategorySubCategories(state) {
            state.categoriesSubCategorisPagination.categories = Array()
        },
        //payload fromat = {key:'',value:true/false}
        changeShowProductCategory(state, payload) {
            //hide all 
            state.productShowComponents.categoryProducts.show = false;
            state.productShowComponents.brandProducts.show = false;
            //show one
            state.productShowComponents[payload.key].show = payload.value;

        },
        /**
         * save category infos for show cateogry product by cateogry_id
         * @param {*} state 
         * @param {*} payload 
         */
        saveCategoryInfoShowByCategoryId(state, payload) {
            let category = state.items.filter(category => {
                if (category.category_id == payload) {
                    return true;
                } else {
                    return false;
                }
            });
            if (category && category.length) {

                state.productOperation.show.categoryInfo = category;
            }
        },
        saveSubCategoryInfoShowByCategoryId(state, payload) {
            let category = state.categoriesSubCategorisPagination.categories.filter(category => {
                if (category.category_id == payload) {
                    return true;
                } else {
                    return false;
                }
            });
            state.productOperation.show.categoryInfos = category;
        },
        /**
         * change show product details section hide all and show one
         * format payload=> {key:'',value:''}
         */
        ChangeShowProductDetails(state, payload = null) {
            //hide all
            state.productOperation.show.showProductDetails.products.show = false;
            state.productOperation.show.showProductDetails.subCategories.show = false;

            //show one
            state.productOperation.show.showProductDetails[payload.key].show = payload.value;

        },

        hideAllProductOperations(state, payload) {
            state.productOperation.show.addNew = false;
            state.productOperation.edit.show = false;
            state.productOperation.remove.show = false;
            if (payload == 'show') {
                state.productOperation[payload].addNew = true;
            } else {
                if (payload) {
                    state.productOperation[payload].show = true;
                } else {
                    //do not any thing
                }
            }
            state.productEditing = true;
        },

        /**
         * save main brand editing
         */
        saveMainBrandEditing(state, payload) {
            state.mainBrandEditing = payload;

            console.log('mainBrandEditing')
            console.log(state.mainBrandEditing)
        }

    },
    actions: {
        // گرفتن تمام دسته بندی های اصلی
        getAllCategoryProducts({ commit, dispatch, getters, rootGetters, rootState, state }) {
            state.items = []

            axios
                .post("http://fishopping.ir/serverHypernetShowUnion/adminProduct/webservices/products/categoryProducts/getAllMainCategories.php", {
                    offset: state.paginationObject.currentPage,
                    count: state.paginationObject.countPerPage,
                    getAllCategories: true
                })
                .then(response => {
                    if (response.data && response.data.status) {
                        //save fetched data into state
                        state.items = response.data.categories

                        //set pagination count
                        state.paginationObject.pages = Math.ceil(response.data.count / state.paginationObject.countPerPage)

                    } else {
                        //error fetch data
                        //show error notification
                        rootState.errorNotification = {
                            show: true,
                            message: "خطا در نایش اطلاعات دسته بندی ها"
                        }
                    }
                })
                .catch(error => {
                    console.log(error)
                })
        },
        // گرفتن محصولات یک دسته بندی
        getCategoriesProduct({ commit, dispatch, getters, rootGetters, rootState, state }, payload = null) {
            state.searchingBaseOnCategoryName.searching = false;
            state.categoriesProductPaginations.loading = true;
            commit('clearCategoryProducts');

            //save category infos
            let category_id;
            if (payload) {
                commit('saveCategoryInfoShowByCategoryId', payload);
                category_id = payload;
            } else {
                category_id = state.productOperation.show.categoryInfo[0].category_id
            }
            console.log({
                "getAllCategoryProducts": true,
                "offset": (state.categoriesProductPaginations.currentPage - 1) * state.categoriesProductPaginations.countPerPage,
                "count": state.categoriesProductPaginations.countPerPage,
                "category_id": category_id
            })
            axios
                .post("http://fishopping.ir/serverHypernetShowUnion/adminProduct/webservices/products/categoryProducts/getAllProducts.php", {
                    "getAllCategoryProducts": true,
                    "offset": (state.categoriesProductPaginations.currentPage - 1) * state.categoriesProductPaginations.countPerPage,
                    "count": state.categoriesProductPaginations.countPerPage,
                    "category_id": category_id
                })
                .then(response => {
                    console.log(response)
                    state.categoriesProductPaginations.loading = false

                    if (response.data && response.data.status) {
                        state.categoriesProductPaginations.pages = Math.ceil(response.data.count / state.categoriesProductPaginations.countPerPage)
                        state.categoriesProductPaginations.products = response.data.products
                    } else {
                        //show error product not exist
                    }
                })
                .catch(error => {
                    //show error
                    state.categoriesProductPaginations.loading = false

                    console.log(error)
                })
        },
        //گرفتن تمام زیر دسته بندی ها
        getAllSubCategories({ commit, dispatch, getters, rootGetters, rootState, state }, payload) {
            state.categoriesSubCategorisPagination.categories = []

            commit('clearCategorySubCategories')

            let that = this;
            let category_id;
            if (payload) {
                category_id = payload
            } else {
                category_id = state.productOperation.show.categoryInfo[0].category_id
            }

            axios
                .post("http://fishopping.ir/serverHypernetShowUnion/adminProduct/webservices/products/categoryProducts/getAllSubCategories.php", {
                    "offset": (state.categoriesSubCategorisPagination.currentPage - 1) * state.categoriesSubCategorisPagination.countPerPage,
                    "count": state.categoriesSubCategorisPagination.countPerPage,
                    "category_id": category_id,
                    "getAllSubCategories": true
                })
                .then(response => {
                    if (response.data && response.data.status) {
                        state.categoriesSubCategorisPagination.pages = Math.ceil(response.data.count / state.categoriesSubCategorisPagination.countPerPage)
                        state.categoriesSubCategorisPagination.categories = response.data.categories;
                    } else {
                        //show error subCategories not exist
                    }
                })
                .catch(error => {
                    //show error
                    console.log(error)
                })
        },
        //جستجوکردن محصولات بر اساس نام دسته بندی
        SearchingProducts({ commit, dispatch, getters, rootGetters, rootState, state }, payload) {
            state.searchingBaseOnCategoryName.searching = true
            if (state.searchingBaseOnCategoryName.searchInput.lenth < 2) {
                return false;
            }
            state.categoriesProductPaginations.loading = true;
            let that = this;

            axios
                .post("http://fishopping.ir/serverHypernetShowUnion/adminProduct/webservices/products/categoryProducts/Searching.php", {
                    "offset": (state.categoriesProductPaginations.currentPage - 1) * state.categoriesProductPaginations.countPerPage,
                    "count": state.categoriesProductPaginations.countPerPage,
                    "searchProductCategory": true,
                    "search": state.searchingBaseOnCategoryName.searchInput.toString(),
                    "typeSearch": state.searchingBaseOnCategoryName.selectedSearchType.toString()
                })
                .then(response => {
                    state.categoriesProductPaginations.loading = false;
                    if (response.data && response.data.status) {
                        //set new data to products state object
                        state.categoriesProductPaginations.products = response.data.products
                        state.categoriesProductPaginations.pages = Math.ceil(response.data.count / state.categoriesProductPaginations.countPerPage)

                    } else {
                        //error
                        state.categoriesProductPaginations.products = Array()
                    }
                })
                .catch(error => {
                    state.categoriesProductPaginations.loading = false;

                    state.categoriesProductPaginations.products = Array()
                    console.log(error)
                })
        },
        //حذف یک محصول
        RmoveOneProduct({ commit, dispatch, getters, rootGetters, rootState, state }, payload) {
            let that = this;
            //prepare data
            let product_id = payload
            let data = new FormData();
            data.append("productId", product_id)
            data.append("removeOneProduct", true)

            axios
                .post(
                    "http://fishopping.ir/serverHypernetShowUnion/adminProduct/webservices/products/categoryProducts/removeOneProduct.php",
                    data, {
                        headers: {
                            'Content-Type': 'multipart/form-data'
                        }
                    }
                )
                .then(function(response) {
                    if (response.data && response.data.status == true) {
                        //show success notification
                        rootState.successNotification = {
                            show: true,
                            message: "محصول با موفقیت حذف شد",
                        };
                        //close edit modal
                        rootState.brands.brandEditing = false;
                        //open comfirm smsCode

                        //remove product from ui
                        state.categoriesProductPaginations.products = state.categoriesProductPaginations.products.filter(product => {
                            if (product.product_id == payload) {
                                return false;
                            } else {
                                return true;
                            }
                        })

                    } else {
                        rootState.errorNotification = {
                            show: true,
                            message: "خطا، محصول حذف نشد",
                        };
                    }
                })
                .catch(function(error) {
                    rootState.errorNotification = {
                        show: true,
                        message: "خطا، محصول حذف نشد",
                    };
                    console.log(error);
                });
        }

    },
    getters: {
        savedCategoryInfo(state) {
            return state.productOperation.show.categoryInfo
        }
    }
}


export default companies;