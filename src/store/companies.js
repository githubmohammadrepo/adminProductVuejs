import axios from 'axios';
const companies = {
    namespaced: true,
    state: () => ({
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
    }),
    mutations: {

    },
    actions: {
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
                    console.log(response.data.companies)
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