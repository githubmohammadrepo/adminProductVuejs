<template>
  <div>
    <b-row>
      <b-col class="mt-2">
        <h5 class="text-center pb-0 pt-2">
          نمایش محصولات برند {{ category_name }}
        </h5>
        <hr class="w-25 mt-0 pt-0 bg-info" />
      </b-col>
    </b-row>

    <!-- start show navbar -->
      <b-nav tabs align="center" class="bg-dark">
      <b-nav-item  class="order-1 ml-sm-auto bg-dark">
        <b-button variant="info" class="" @click="showAddNewProduct">افزودن محصول جدید</b-button>
      </b-nav-item>
      <b-nav-item class="order-3 order-md-2 ">
          <b-input-group class="my-auto">
            <!-- <template #prepend>
              <b-input-group-text >Username</b-input-group-text>
            </template> -->
            <template #append>
              <b-form-select v-model="selectedSearchType" :options="searchOptions" class="mb-3 my-auto dir-rtl">
                  <!-- This slot appears above the options from 'options' prop -->
                  <template #first>
                    <b-form-select-option :value="null" disabled>جستجو بر اساس</b-form-select-option>
                  </template>

                  <!-- These options will appear after the ones from 'options' prop -->
              </b-form-select>
            </template>

            <b-form-input type="search" v-model="searchInput"  @keyup.enter="SearchingProducts(searchInput)" placeholder="Enter your name"></b-form-input>
          </b-input-group>
      </b-nav-item>

      <b-nav-item class="order-2 order-md-3">
           <b-form-select v-model="paginationObject.countPerPage" :options="options" class="mb-3 my-auto dir-rtl">
              <!-- This slot appears above the options from 'options' prop -->
              <template #first>
                <b-form-select-option :value="null" disabled>25</b-form-select-option>
              </template>

              <!-- These options will appear after the ones from 'options' prop -->
          </b-form-select>
      </b-nav-item>
    </b-nav>
    <!-- end show navbar -->

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
          <b-pagination-nav
            v-model="paginationObject.currentPage"
            :number-of-pages="paginationObject.pages"
            base-url="#"
            last-number
            @input="changeCurrentPage"
          ></b-pagination-nav>
        </div>
      </div>
    </div>
    <div v-else class="p-3">
      <h6 class="text-danger font-weight-bold">هیچ محصولی پیدا نشد</h6>
    </div>
    </div>

    <!-- start child operation components -->
      
      <!-- addNewProduct with modal inside -->
      <addNewProduct v-if="showProductOperations.add.show" />

      <!-- editOneProduct with modal inside -->
      <editOneProduct v-if="showProductOperations.edit.show" />


      <!-- removeOneProduct with modal inside -->
      <removeOneProduct v-if="showProductOperations.remove.show" />

    <!-- end child operation components -->

  </div>
</template>

<script>
import axios from "axios";
import addNewProduct from '@/components/products/ProductBrandsOperations/productOperations/AddNewProduct.vue';
import editOneProduct from '@/components/products/ProductBrandsOperations/productOperations/EditOneProduct.vue';
import removeOneProduct from '@/components/products/ProductBrandsOperations/productOperations/RemoveOneProduct.vue';

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
        { key: "category_name", label: "نام برند" },
        { key: "product_msrp", label: "قیمت مصرف کننده" },
        { key: "product_sort_price", label: "قیمت فروشگاه" },
        { key: "product_package_type ", label: "نوع برند" },
        { key: "product_number_in_package", label: "" },
        { key: "product_counting_uni", label: "واحد شمارش محصول" },
        { key: "file_path", label: "عکس محصول" },
      ],
      paginationObject:{
        currentPage:1,
        pages:25,
        countPerPage:25
      },
      options:[
        25,50,100,300,500
      ],
      searchOptions:[
        {value:'brand',text:"بر اساس برند"},
        {value:'category',text:"دسته بندی"}
      ],
      brandProducts:[],
      loading:false,
      searchInput:'',
      selectedSearchType:'',
      showProductOperations:{
        add:{
          show:false,
          addNewProduct:{

          }
        },
        edit:{
          show:false,
          editProduct:{

          }
        },
        remove:{
          show:false,
          removeProduct:{

          }
        }
      }
    };
  },
  methods: {
    changeCurrentPage(value) {
      this.currentPage = value;
      this.getCategoriesProduct()
    },
    // remove one product
    showRemoveProducts(product_id){
      this.$store.state.products.BrandProductEditingFromInfos ={}

      //save productEditing in state
      let productInfo = this.brandProducts.filter(product=>{
        if(product.product_id == product_id) {
          return true;
        }else{
          return false;
        }
      })
      this.$store.state.products.BrandProductEditingFromInfos =productInfo[0]

      //show editProduct component
      //hide all
      this.showProductOperations.add.show = false;
      this.showProductOperations.remove.show = false;
      this.showProductOperations.edit.show = false;

      //show edit
      this.showProductOperations.remove.show = true;
      this.$store.state.products.BrandModalProductOperations.show=true
    },
    // edit one product
    ShowEditProducts(product_id){
      this.$store.state.products.BrandProductEditingFromInfos ={}

      //save productEditing in state
      let productInfo = this.brandProducts.filter(product=>{
        if(product.product_id == product_id) {
          return true;
        }else{
          return false;
        }
      })
      this.$store.state.products.BrandProductEditingFromInfos =productInfo[0]

      //show editProduct component
      //hide all
      this.showProductOperations.add.show = false;
      this.showProductOperations.remove.show = false;
      this.showProductOperations.edit.show = false;

      //show edit
      this.showProductOperations.edit.show = true;
      this.$store.state.products.BrandModalProductOperations.show=true

    },
    SearchingProducts(searchInput){
      this.loading = true;
      let that = this;

      axios
          .post("http://fishopping.ir/serverHypernetShowUnion/adminProduct/webservices/products/brandProducts/Searching.php", {
              "offset": (that.paginationObject.currentPage - 1) * that.paginationObject.countPerPage,
              "count": that.paginationObject.countPerPage,
              "searchProductCategory": true,
              "search": that.searchInput.toString(),
              "typeSearch": that.selectedSearchType.toString()
          })
          .then(response => {
              console.log('searching products');
              console.log(response.data)
              that.loading = false;

              that.loading = false;
              if (response.data && response.data.status) {
                  //set new data to products state object
                  that.brandProducts = response.data.products
                  that.paginationObject.pages = Math.ceil(response.data.count / that.paginationObject.countPerPage)

              } else {
                that.loading = false;
                  //error
                  that.brandProducts = Array()

              }
          })
          .catch(error => {
              that.loading = false;

              that.products = Array()
              console.log(error)
          })
    },
    // گرفتن محصولات یک برند
    getCategoriesProduct() {
      this.loading = true;
      let that = this;
      that.brandProducts =[]
      let category_id =this.$store.state.products.mainBrandEditing.category_id;
      axios
          .post("http://fishopping.ir/serverHypernetShowUnion/adminProduct/webservices/products/brandProducts/getAllProducts.php", {
              "getAllCategoryProducts": true,
              "offset": (that.paginationObject.currentPage - 1) * that.paginationObject.countPerPage,
              "count": that.paginationObject.countPerPage,
              "category_id": category_id
          })
          .then(response => {
              this.loading = false;

              console.log('brand proucts')
              console.log(response.data)

              if (response.data && response.data.status) {
                  that.paginationObject.pages = Math.ceil(response.data.count / that.paginationObject.countPerPage)
                  that.brandProducts = response.data.products
              } else {
                  //show error product not exist
              }
          })
          .catch(error => {
              this.loading = false;
              //show error
              that.loading = false

              console.log(error)
          })
    },
    // add new product===show add new Component
    showAddNewProduct(){
      //hide all
      this.showProductOperations.add.show = false;
      this.showProductOperations.remove.show = false;
      this.showProductOperations.edit.show = false;

      //show add
      this.showProductOperations.add.show = true;
      this.$store.state.products.BrandModalProductOperations.show=true
      
    }
    
  },
  computed: {
    items() {
      if (this.brandProducts && this.brandProducts.length) {
        return this.brandProducts.map((product) => {
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
    category_name(){
      return this.$store.state.products.mainBrandEditing.category_name
    },
  },
  components:{
    addNewProduct,
    editOneProduct,
    removeOneProduct
  },
  created() {
    this.getCategoriesProduct()
  },
}
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
