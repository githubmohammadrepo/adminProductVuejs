<template>
  <div>
    <b-row>
      <b-col class="mt-2">
        <h5 class="text-center pb-0 pt-2">
          نمایش محصولات دسته بندی {{ category_name }}
        </h5>
        <hr class="w-25 mt-0 pt-0 bg-info" />
      </b-col>
    </b-row>

    <ProductNavigation />
    <div v-if="loading">
      <font-awesome-icon icon="spinner" pulse ></font-awesome-icon>
    </div>
    <div v-else>
      <div v-if="items.length">
      <b-table
        :sticky-header="stickyHeader"
        :no-border-collapse="noCollapse"
        responsive
        :items="items"
        :fields="fields"
      >
        <template #cell(operation)="data">
          <div class="min-width-250px">
            
            <b-button
              class="m-0 bg-danger mx-1"
              @click="showRemoveProducts(data.value.product_id)"
              >حذف</b-button
            >
            <b-button
              class="m-0 bg-info mx-1"
              @click="ShowEditProducts(data.value.product_id)"
              >ویرایش</b-button
            >
          </div>
        </template>

        <!-- We are using utility class `text-nowrap` to help illustrate horizontal scrolling -->
        <template #head(category_id)="scope">
          <div class="text-nowrap p-0 m-0">Row ID</div>
        </template>

        <template #head()="scope">
          <div class="text-nowrap">
            {{ scope.label }}
          </div>
        </template>

        <template #cell(product_name)="data">
          <div class="min-width-140px">
            {{ data.value }}
          </div>
        </template>
        <template #cell(file_path)="data">
          <div>
            <b-img :src="'https://fishopping.ir/images/com_hikashop/upload/thumbnails/200x200f/'+data.value" fluid ></b-img>
          </div>
        </template>
      </b-table>

      <!-- pagination section -->
      <div class="row justify-content-center">
        <div class="mt-3">
         
         <paginate :pages="pages" :value="currentPage" @changed="updateCurrentPage" />

        </div>
      </div>
    </div>
    <div v-else class="p-3">
      <h6 class="text-danger font-weight-bold">هیچ محصولی پیدا نشد</h6>
    </div>
    </div>

    <!-- add modal operation product category -->
    <ProductCategoryModaOperations />
  </div>
</template>

<script>
import axios from "axios";
import ShowProductPagination from "@/components/products/ShowProductPagination.vue";
import ProductNavigation from "@/components/products/ProductCategoriesOperations/ProductNavigation.vue";
import ProductCategoryModaOperations from '@/components/products/ProductCategoriesOperations/ProductCategoryModaOperations.vue'
import paginate from '@/components/general/Pagination.vue'

export default {
  data() {
    return {
      getAllBrandOffset: 0,
      stickyHeader: true,
      noCollapse: false,
      searching:false,
      fields: [
        {
          key: "operation",
          label: "عملیات",
          stickyColumn: true,
          isRowHeader: true,
          variant: "light",
          class: "px-0 mx-0",
        },
        { key: "product_id", label: "ایدی محصول" },
        { key: "product_description", label: "توضیحات محصول" },
        { key: "product_name", label: "نام محصول" },
        { key: "brand_name", label: "نام برند" },
        // { key: "store_name", label: "نام فروشگاه" },
        { key: "product_published", label: "وضعیت انتشار" },
        { key: "category_name", label: "نام دسته بندی" },
        { key: "product_msrp", label: "قیمت مصرف کننده" },
        { key: "product_sort_price", label: "قیمت فروشگاه" },
        { key: "product_package_type ", label: "نوع دسته بندی" },
        { key: "product_number_in_package", label: "" },
        { key: "product_counting_uni", label: "واحد شمارش محصول" },
        { key: "file_path", label: "عکس محصول" },
      ],
    };
  },
  methods: {
    updateCurrentPage(currentPage){
      if(this.currentPage.toString()==currentPage.toString()){
      }else{
        this.currentPage = currentPage;
        if(this.$store.state.products.searchingBaseOnCategoryName.searching==false){
          this.$store.dispatch('products/getCategoriesProduct',window.sessionStorage.getItem('subCategory_id'))
        }else{
          //searcing procces
          this.$store.dispatch('products/SearchingProducts')
        }
      }
    },

    showRemoveProducts(product_id) {
      //save product infos
      this.$store.commit('products/saveRemoveProduct',product_id)
      this.$store.commit('products/hideAllProductOperations','remove')
    },
    ShowEditProducts(product_id){
      //save product info
      this.$store.commit('products/saveEditProduct',product_id)
      this.$store.commit('products/hideAllProductOperations','edit')

    },
    //get products of category selected in previous section
    getCategoriesProduct() {
      // let that = this;
      // let category_id = that.$store.getters['products/savedCategoryInfo'][0].category_id
      // axios
      //   .post("http://fishopping.ir/serverHypernetShowUnion/adminProduct/webservices/products/categoryProducts/getAllProducts.php",{
      //       "getAllCategoryProducts":true,
      //       "offset":(that.currentPage-1)*that.countPerPage,
      //       "count":that.countPerPage,
      //       "category_id":category_id
      //   })
      //   .then(response =>{
      //     console.log(response.data)
      //     if(response.data && response.data.status){
      //       that.pages = Math.ceil(response.data.count/that.countPerPage)
      //       that.products = response.data.products
      //     }else{
      //       //show error product not exist
      //     }
      //   })
      //   .catch(error =>{
      //     //show error
      //     console.log(error)
      //   })
    },
  },
  computed: {
    category_name() {
      return (
        "(" +
        this.$store.state.products.productOperation.show.categoryInfo[0]
          .category_name +
        ")"
      );
    },
    items() {
      if (this.products && this.products.length) {
        return this.products.map((product) => {
          return {
            ...product,
            operation: {
              product_id: product.product_id,
              product_product_count: product.product_product_count,
            },
          };
        });
      } else {
        return Array();
      }
    },
    pages: {
      get() {
        return this.$store.state.products.categoriesProductPaginations.pages;
      },
      set(newValue) {
        this.$store.state.products.categoriesProductPaginations.pages = newValue;
      },
    },
    products: {
      get() {
        return this.$store.state.products.categoriesProductPaginations.products;
      },
      set(newValue) {
        this.$store.state.products.categoriesProductPaginations.products = newValue;
      },
    },
    currentPage: {
      get() {
        return this.$store.state.products.categoriesProductPaginations.currentPage;
      },
      set(newValue) {
        this.$store.state.products.categoriesProductPaginations.currentPage = newValue;
      },
    },
    countPerPage: {
      get() {
        return this.$store.state.products.categoriesProductPaginations
          .countPerPage;
      },
      set(newValue) {
        this.$store.state.products.categoriesProductPaginations.countPerPage = newValue;
      },
    },
    loading: {
      get() {
        return this.$store.state.products.categoriesProductPaginations.loading;
      },
      set(newValue) {
        this.$store.state.products.categoriesProductPaginations.loading = newValue;
      },
    },
  },
  components: {
    ShowProductPagination,
    ProductNavigation,
    ProductCategoryModaOperations,
    paginate
  },
  created() {
    this.getCategoriesProduct();
  },
};
</script>


<style lang="scss" scoped>
.danger {
  background: red !important;
}
table tr th {
  display: contents;
}
.min-width-250px {
  min-width: 140px !important;
}

.min-width-140px {
  min-width: 210px;
  color: rgb(15, 134, 128);
}
.width-150-150 img{
  background: red;
  width:150px !important;
  height:100px !important;
  overflow: hidden;
}
</style>
