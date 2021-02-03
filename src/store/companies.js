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
                            newObject.operation = `
                            <span @click="editOneCompanie(${value.id})">ذخیره</span>
                            <span @click="editOneCompanie(${value.id})">ویرایش</span>
                            `

                            newObject.companyName = value.ShopName || 'تعریف نشده'
                            newObject.brandName = value.category_name || 'تعریف نشده'
                            newObject.managerName = value.ManagerName || 'تعریف نشده'
                            newObject.mobile = value.MobilePhon || 'تعریف نشده'
                            newObject.phone = value.phone || 'تعریف نشده'
                            newObject.address = value.Address || 'تعریف نشده'
                            newObject.brandImage = value.file_path || 'تعریف نشده'
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