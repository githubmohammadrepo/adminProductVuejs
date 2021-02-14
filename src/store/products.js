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
        productEditing: false,
        editDataObject: {},
        productOperation: {
            show: {
                show: false,
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

            },
            remove: {
                show: false,

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
        },
        categoriesSubCategorisPagination: {
            pages: 25,
            currentPage: 1,
            countPerPage: 25,
            categories: Array(),
            errors: false,
        }




    }),
    mutations: {
        // clear category products
        clearCategoryProducts(state) {
            alert('clear categoryProducts')
            state.categoriesProductPaginations.products = Array()
        },
        // clear category products
        clearCategorySubCategories(state) {
            alert('clear subCategiries')
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
                console.log('saveCategory categoryInfo')
                console.log(state.productOperation.show.categoryInfo)
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
            console.log('saveSubCategory categoryInfo')
            console.log(state.productOperation.show.categoryInfos)
        },
        /**
         * change show product details section hide all and show one
         * format payload=> {key:'',value:''}
         * @param {*} state 
         * @param {*} payload 
         */
        ChangeShowProductDetails(state, payload) {
            //hide all
            state.productOperation.show.showProductDetails.products.show = false;
            state.productOperation.show.showProductDetails.subCategories.show = false;

            //show one
            state.productOperation.show.showProductDetails[payload.key].show = payload.value;

        },

    },
    actions: {
        // گرفتن تمام دسته بندی های اصلی
        getAllCategoryProducts({ commit, dispatch, getters, rootGetters, rootState, state }) {
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
        getCategoriesProduct({ commit, dispatch, getters, rootGetters, rootState, state }, payload) {
            commit('clearCategoryProducts')

            //save category infos

            commit('saveCategoryInfoShowByCategoryId', payload)
            console.log(state.productOperation.show.categoryInfo)
            let category_id = payload
            console.log({
                "getAllCategoryProducts": true,
                "offset": 0,
                "count": 25,
                "category_id": category_id
            })
            axios
                .post("http://fishopping.ir/serverHypernetShowUnion/adminProduct/webservices/products/categoryProducts/getAllProducts.php", {
                    "getAllCategoryProducts": true,
                    "offset": 0,
                    "count": 25,
                    "category_id": category_id
                })
                .then(response => {
                    console.log(response.data)
                    if (response.data && response.data.status) {
                        alert(Math.ceil(response.data.count / state.categoriesProductPaginations.countPerPage))
                        state.categoriesProductPaginations.pages = Math.ceil(response.data.count / state.categoriesProductPaginations.countPerPage)
                        state.categoriesProductPaginations.products = response.data.products
                    } else {
                        //show error product not exist


                    }

                })
                .catch(error => {

                    //show error
                    console.log(error)
                })
        },
        //گرفتن تمام زیر دسته بندی ها
        getAllSubCategories({ commit, dispatch, getters, rootGetters, rootState, state }, payload) {
            commit('clearCategorySubCategories')

            let that = this;
            let category_id;
            if (payload) {
                category_id = payload
            } else {
                category_id = state.productOperation.show.categoryInfo[0].category_id
            }
            console.log('subcategory')
            console.log({
                "offset": (state.categoriesSubCategorisPagination.currentPage - 1) * state.categoriesSubCategorisPagination.countPerPage,
                "count": state.categoriesSubCategorisPagination.countPerPage,
                "category_id": category_id,
                "getAllSubCategories": true
            });
            axios
                .post("http://fishopping.ir/serverHypernetShowUnion/adminProduct/webservices/products/categoryProducts/getAllSubCategories.php", {
                    "offset": (state.categoriesSubCategorisPagination.currentPage - 1) * state.categoriesSubCategorisPagination.countPerPage,
                    "count": state.categoriesSubCategorisPagination.countPerPage,
                    "category_id": category_id,
                    "getAllSubCategories": true
                })
                .then(response => {
                    console.log(response.data)
                    if (response.data && response.data.status) {
                        state.categoriesSubCategorisPagination.pages = Math.ceil(response.data.count / state.categoriesSubCategorisPagination.countPerPage)
                        state.categoriesSubCategorisPagination.categories = response.data.categories;
                        console.log(state.categoriesSubCategorisPagination.categories)
                    } else {
                        //show error subCategories not exist
                    }

                })
                .catch(error => {

                    //show error
                    console.log(error)
                })
        }

    },
    getters: {
        savedCategoryInfo(state) {
            return state.productOperation.show.categoryInfo
        }
    }
}


export default companies;