<template>
  <div>
    <b-row>
      <b-col class="mt-2">
        <h5 class="text-center pb-0 pt-2">نمایش برند های اصلی</h5>
        <hr class="w-25 mt-0 pt-0 bg-info">
      </b-col>
    </b-row>
      <!-- <b-form-checkbox v-model="stickyHeader" inline>Sticky header</b-form-checkbox>
      <b-form-checkbox v-model="noCollapse" inline>No border collapse</b-form-checkbox> -->
      <!-- @row-dblclicked="editOneBrand" -->
    <div v-if="loading">
      <font-awesome-icon icon="spinner" pulse ></font-awesome-icon>
    </div>
    <div v-else>
      <b-table
        :sticky-header="stickyHeader"
        :no-border-collapse="noCollapse"
        responsive
        :items="items"
        :fields="fields"
      >
        <template #cell(operation)="data">
          <div class="min-width-90px">
              <b-button  :variant="data.value.category_product_count!=0 ? 'info' : 'danger'" class="m-0 mx-1" @click="showSubBrands(data.value.category_id)" >نمایش محصولات</b-button>
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

      <div class="row justify-content-center">
        <div class="mt-3">
          <paginate :pages="pages" :value="currentPage" @changed="updateCurrentPage" />
        </div>
      </div>
    </div>  

  </div>
</template>

<script>
import axios from "axios";
import paginate from '@/components/general/Pagination.vue'

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
        { key: "category_parent_name", label: "نام دسته بندی پدر" },
        { key: "category_product_count", label: "تعداد محصولات" },
      ],
        paginationObject:{
          currentPage:1,
          pages:25,
          countPerPage:25
        },
        brands:[],
        loading:false,
    };
    
  },
  methods: {

    getAllMainBrands() {
      this.loading= true;
      let that = this;
        axios
          .post("http://fishopping.ir/serverHypernetShowUnion/adminProduct/webservices/products/brandProducts/getAllMainBrands.php", {
              offset: (that.paginationObject.currentPage-1)*that.paginationObject.countPerPage,
              count: that.paginationObject.countPerPage,
              getAllCategories: true
          })
          .then(response => {
            that.loading = false;
            console.log('response')
            console.log(response.data)
              if (response.data && response.data.status) {
                  that.brands = response.data.categories;
                  that.paginationObject.pages = Math.ceil(response.data.count/that.paginationObject.countPerPage)

              } else {
                  //error fetch data
                  //show error notification
                  that.$store.state.errorNotification = {
                      show: true,
                      message: "خطا در نایش اطلاعات دسته بندی ها"
                  }
              }
          })
          .catch(error => {
            that.loading = false;
            console.log(error)
          })
    },
    showSubBrands(category_id){
      //Goal => save editing info
      //compose brand info
      let brandEditing = this.brands.filter(brand => {
        if(brand.category_id==category_id){
          return true;
        } else{
          return false;
        }
      })

      //save brandEditing
      this.$store.commit('products/saveMainBrandEditing',brandEditing[0])

      //show brnad prducts components
      this.$store.state.products.BrandProductOperation = true;
    },
    updateCurrentPage(currentPage){
  
      if(this.currentPage.toString()==currentPage.toString()){

      }else{
        this.currentPage = currentPage;
        this.getAllMainBrands()
      }
    }

  },
  computed: {
    items() {
      return this.brands.map(category =>{
        return {
          ...category,
          operation:{category_id:category.category_id,category_product_count:category.category_product_count}
        }
      });
    },
    currentPage:{
      get(){
        return this.paginationObject.currentPage;
      },
      set(value){
        this.paginationObject.currentPage = value
      }
    },
    pages:{
      get(){
        return this.paginationObject.pages;
      },
      set(value){
        this.pagnityObject.pages = value;
      }
    }
  },
  created() {
    this.getAllMainBrands()
  },
  components: {
    paginate

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
    