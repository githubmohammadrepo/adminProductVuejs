<template>  
  <div>
    <b-nav tabs align="center" class="bg-dark">
      <b-nav-item  class="order-1 ml-sm-auto bg-dark">
        <b-button variant="info" class="">افزودن محصول جدید</b-button>
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

            <b-form-input type="search" v-model="searchInput"  @input="SearchingProducts" placeholder="Enter your name"></b-form-input>
          </b-input-group>
      </b-nav-item>

      <b-nav-item class="order-2 order-md-3">
           <b-form-select v-model="filterNumberRowsProduct" :options="options" class="mb-3 my-auto dir-rtl">
              <!-- This slot appears above the options from 'options' prop -->
              <template #first>
                <b-form-select-option :value="null" disabled>25</b-form-select-option>
              </template>

              <!-- These options will appear after the ones from 'options' prop -->
          </b-form-select>
      </b-nav-item>
    </b-nav>
  </div>
</template>

<script>
import axios from 'axios';
export default {
  data(){
    return {
      //important
      selectedSearchType: '',
      searchInput:'',
      filterNumberRowsProduct:25,
      //requirement
      options: [
        { value: '25', text: '25' },
        { value: '100', text: '100' },
        { value: '200', text: '200' },
        { value: '500', text: '500' },
        { value: '800', text: '800' }
      ],
      searchOptions:[
        {value:'category',text:'دسته بندی'},
        {value:'brand',text:'نام برند'}
      ]
    }
  },
  methods: {
  SearchingProducts(value) {
    let that = this;
    console.log({"offset":(that.currentPage-1)*that.countPerPage,
      "count":that.countPerPage,
      "searchProductCategory":true,
      "search":that.searchInput.toString(),
      "typeSearch":that.selectedSearchType.toString()})
    axios
    .post("http://fishopping.ir/serverHypernetShowUnion/adminProduct/webservices/products/categoryProducts/Searching.php",{
      "offset":(that.currentPage-1)*that.countPerPage,
      "count":that.countPerPage,
      "searchProductCategory":true,
      "search":that.searchInput.toString(),
      "typeSearch":that.selectedSearchType.toString()
    })
    .then(response => {
        if(response.data && response.data.status){
          //set new data to products state object
          that.$store.state.products.categoriesProductPaginations.products = response.data.products
        }else{
          //error
          that.$store.state.products.categoriesProductPaginations.products = Array()
        }
    })
    .catch(error => {
        that.$store.state.products.categoriesProductPaginations.products = Array()
      console.log(error)
    })
  }
  },
  computed: {
     pages:{
      get(){
        return this.$store.state.products.categoriesProductPaginations.pages
      },
      set(newValue){
        this.$store.state.products.categoriesProductPaginations.pages =newValue
      }
    },
    products:{
      get(){
        return this.$store.state.products.categoriesProductPaginations.products
      },
      set(newValue){
        this.$store.state.products.categoriesProductPaginations.products = newValue
      }
    },
    currentPage:{
      get(){
        return this.$store.state.products.categoriesProductPaginations.currentPage
      },
      set(newValue){
        this.$store.state.products.categoriesProductPaginations.currentPage = newValue
      }
    },
    countPerPage:{
      get(){
        return this.$store.state.products.categoriesProductPaginations.countPerPage
      },
      set(newValue){
        this.$store.state.products.categoriesProductPaginations.countPerPage = newValue
      }
    },
  },
  components:{

  }
}
</script>

<style lang="scss" scoped>
  .dir-rtl{
    direction:rtl !important;
  }
</style>