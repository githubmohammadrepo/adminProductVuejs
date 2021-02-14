<template>
  <div>
    <b-row>
      <b-col class="mt-2">
        <h5 class="text-center pb-0 pt-2">نمایش زیر دسته بندی {{category_name}}</h5>
        <hr class="w-25 mt-0 pt-0 bg-info">
      </b-col>
    </b-row>
  items-length: {{items.length}}
    <div v-if="items.length">
      <b-table
        :sticky-header="stickyHeader"
        :no-border-collapse="noCollapse"
        responsive
        :items="items"
        :fields="fields"
      >
        <template #cell(operation)="data">
          <div class="min-width-90px">
              <b-button  :variant="data.value.category_product_count!=0 ? 'info' : 'danger'" class="m-0 mx-1" @click="ShowCategoryProducts(data.value.category_id)" >نمایش محصولات</b-button>
          </div>

        </template>

        <!-- <template #cell(brand_logo)="data" class="m-0 p-0">
            <b-img left :src="'http://fishopping.ir/images/com_hikashop/upload/thumbnails/200x200f/'+data.value" rounded="circle" height="80px" class="m-0 p-0" alt="لوگوی برند"></b-img>
        </template> -->

        <!-- We are using utility class `text-nowrap` to help illustrate horizontal scrolling -->
        <template #head(category_id)="scope">
          <div class="text-nowrap p-0 m-0">Row ID</div>
        </template>

        <template #head()="scope">
          <div class="text-nowrap">
            {{ scope.label }}
          </div>
        </template>

        <!-- <template #cell(Address)="data" class="m-0 p-0">
            <div class="min-width-300px">
              {{data.value}}
            </div>
        </template> -->

      </b-table>

      <!-- pagination section -->
      <div class="row justify-content-center">
        <div class="mt-3">
          <b-pagination-nav
            v-model="currentPage"
            :number-of-pages="pages"
            base-url="#"
            last-number
            @input= "changeCurrentPage"
          ></b-pagination-nav>
        </div>
      </div>
    </div>
        
    <div v-else class="p-3">
      <h6 class="text-danger font-weight-bold">هیچ محصولی پیدا نشد</h6>
    </div>


  </div>
</template>

<script>
import axios from "axios";
import ShowProductPagination from '@/components/products/ShowProductPagination.vue';

export default {
  data() {
    return {
      getAllBrandOffset: 0,
      stickyHeader: true,
      noCollapse: false,
      fields: [
        {
          key: "operation",
          label: "عملیات",
          stickyColumn: true,
          isRowHeader: true,
          variant: "light",
          class: "px-0 mx-0",
        },
        { key: "category_id", label: "ایدی دسته بندی" },
        { key: "category_name", label: "نام دسته بندی" },
        { key: "category_published", label: "وضعیت انتشار" },
        { key: "category_parent_id", label: "نام دسته بندی پدر" },
        { key: "category_product_count", label: "تعداد محصولات" },

      ],
    };
  },
  methods: {
    ShowCategoryProducts(category_id) {
      //show component
      this.$store.state.products.productOperation.show.show=true;

      //save category infos
      this.$store.commit('products/saveSubCategoryInfoShowByCategoryId',category_id)
      console.log('subCategoryConsole')
      console.log(this.$store.state.products.productOperation.show.categoryInfo)

      //console.log category saved
      this.$store.dispatch('products/getAllSubCategories',category_id)
      this.$store.dispatch('products/getCategoriesProduct',category_id)

      
    },
    // getCategoriesProduct(){
    //   let that = this;
    //   let category_id = that.$store.getters['products/savedCategoryInfo'][0].category_id
      
    //   axios
    //     .post("http://fishopping.ir/serverHypernetShowUnion/adminProduct/webservices/products/categoryProducts/getAllSubCategories.php",{
    //         "offset":(that.currentPage-1)*that.countPerPage,
    //         "count":that.countPerPage,
    //         "category_id":category_id,
    //         "getAllSubCategories":true
    //     })
    //     .then(response =>{
    //       if(response.data && response.data.status){
    //         that.pages = Math.ceil(response.data.count/that.countPerPage)
    //         that.categories = response.data.categories
    //       }else{
    //         //show error product not exist
    //       }
          
    //     })
    //     .catch(error =>{
    //       //show error
    //       console.log(error)
    //     })
    // }
  },
  computed: {
    category_name(){
      return '('+this.$store.state.products.productOperation.show.categoryInfo[0].category_name+')';
    },
    items() {
      return this.categories.map(category =>{
        return {
          ...category,
          operation:{category_id:category.category_id,category_product_count:category.category_product_count}
        }
      });
    },
    categories:{
      get(){
        return this.$store.state.products.categoriesSubCategorisPagination.categories
      },
      set(newValue){
        this.$store.state.products.categoriesSubCategorisPagination.categories =newValue
      }
    },
    pages:{
      get(){
        return this.$store.state.products.categoriesSubCategorisPagination.pages;
      },
      set(newValue){
        this.$store.state.products.categoriesSubCategorisPagination.pages=newValue;
      }
    },
    currentPage:{
      get(){
        return this.$store.state.products.categoriesSubCategorisPagination.currentPage
      },
      set(newValue){
        this.$store.state.products.categoriesSubCategorisPagination.currentPage = newValue;
      }
    },
    countPerPage:{
      get(){
        return this.$store.state.products.categoriesSubCategorisPagination.countPerPage;
      },
      set(newValue){
        this.$store.state.products.categoriesSubCategorisPagination.countPerPage = newValue;
      }
    }
  },
  created() {
    // this.getCategoriesProduct()
      this.$store.dispatch('products/getAllSubCategories')

  },
  components: {
    ShowProductPagination,

  },
};
</script>


<style lang="scss" scoped>
.danger{
  background: red !important;
}
table tr th {
  display: contents;
}
</style>