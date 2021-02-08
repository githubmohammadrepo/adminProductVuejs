<template>
  <div>
    <div class="row mb-3 ">
      <div class="col text-right">
        <b-button variant="primary" @click="ButtonClickAddOneBrand">افزودن برند جدید</b-button>
      </div>

      <!-- search brand name -->
      <div class="col-4 mr-auto text-left">
        <b-form-input v-model="searchBrandName" @input="searchBrandByInput" type="search" placeholder="نام برند ...."></b-form-input>
      </div>

      <!-- fileter count per page -->
      <div class="col-4 mr-auto text-left">
        {{paginatinCountPerPage}}
        <b-form-select v-model="paginatinCountPerPage" @change="getAllBrandWithNewCount" :options="options"></b-form-select>
      </div>
    </div>


      <!-- <b-form-checkbox v-model="stickyHeader" inline>Sticky header</b-form-checkbox>
      <b-form-checkbox v-model="noCollapse" inline>No border collapse</b-form-checkbox> -->
    <b-table
      @row-dblclicked="editOneBrand"
      :sticky-header="stickyHeader"
      :no-border-collapse="noCollapse"
      responsive
      :items="items"
      :fields="fields"
    >
      <template #cell(operation)="data">
        <b-button variant="info" class="m-0 mx-1" @click="ButtonClickEditOneBrand(data.value)" >ویرایش</b-button>
        <b-button variant="danger" class="m-0 mx-1" @click="ButtonClickRemoveOneBrand(data.value)" >حذف</b-button>
      </template>

      <template #cell(brand_logo)="data" class="m-0 p-0">
          <b-img left :src="'http://fishopping.ir/images/com_hikashop/upload/thumbnails/200x200f/'+data.value" rounded="circle" height="80px" class="m-0 p-0" alt="لوگوی برند"></b-img>
      </template>

        <template #cell(category_published)="row" variant="info">
          <!-- As `row.showDetails` is one-way, we call the toggleDetails function on @change -->
          <p>{{row.value ? 'منتشر شده' : 'منتشر نشده'}}</p>
      </template>

      <!-- We are using utility class `text-nowrap` to help illustrate horizontal scrolling -->
      <template #head(id)="scope">
        <div class="text-nowrap p-0 m-0">Row ID</div>
      </template>

      <template #head()="scope">
        <div class="text-nowrap">
          {{ scope.label }}
        </div>
      </template>
    </b-table>

    <OperationBrandModal />
  </div>
</template>

<script>
import axios from 'axios';
import OperationBrandModal from '@/components/mainBrand/OperationBrandModal.vue'
  export default {
    data() {
      return {
        searchBrandName:null,
         options: [
          { value: 20, text: 20 },
          { value: 50, text: 50 },
          { value: 100, text: 100 },
          { value: 500, text: 500 },
          { value: 1000, text: 1000}
        ],
        getAllBrandOffset:0,
        stickyHeader: true,
        noCollapse: false,
        fields: [
          { key: 'operation',label:'عملیات' ,stickyColumn: true, isRowHeader: true, variant: 'primary',class:"px-0 mx-0" },
          { key: 'category_id',label:'ایدی برند'},
          {key:'category_name',label:'نام برند'},
          {key:'category_published',label:'وصعیت انتشار'},
          {key:'brand_logo',label:'لوگوی برند'},
        ],
        
      }
    },
    methods: {
      searchBrandByInput(value){
        if(value && value.toString().length>0){
          this.$store.commit('brands/restCuurentPageToOne')
          this.$store.commit('brands/setSearchValue',value)
          this.$store.dispatch('brands/getAllBrands')
        }
      },
      getAllBrandWithNewCount(value){
        this.$store.commit('brands/restCuurentPageToOne')
        this.$store.commit('brands/changeCountPerPage',value)
        this.$store.dispatch('brands/getAllBrands')
      },      

      editOneBrand(index){
        this.$store.state.brands.editDataObject = index;
        this.$store.state.brands.brandEditing= !this.$store.state.brands.brandEditing;
        //reset addNewCompnay to level one
        //hide all
        this.$store.commit('brands/hideAllOperations')
        //show level three
        this.$store.commit('brands/editOperation')
        
      },
      ButtonClickEditOneBrand(category_id){
        let index = {};
        for (const key in this.$store.state.brands.items) {
          if (Object.hasOwnProperty.call(this.$store.state.brands.items, key)) {
            const element = this.$store.state.brands.items[key];
            if(element.category_id==category_id){
              index = element;
            }
            
          }
        }
        this.$store.state.brands.editDataObject = index;
        this.$store.state.brands.brandEditing= !this.$store.state.brands.brandEditing;
        this.$store.commit('brands/hideAllOperations')
        //show level three
        this.$store.commit('brands/editOperation')
        //reset addNewCompnay to level one
        //hide all
        //show level three
      },
      ButtonClickRemoveOneBrand(category_id){
        let index = {};
        for (const key in this.$store.state.brands.items) {
          if (Object.hasOwnProperty.call(this.$store.state.brands.items, key)) {
            const element = this.$store.state.brands.items[key];
            if(element.category_id==category_id){
              index = element;
            }
            
          }
        }
        this.$store.state.brands.editDataObject = index;
        this.$store.state.brands.brandEditing= !this.$store.state.brands.brandEditing;
        this.$store.commit('brands/hideAllOperations')
        //show level three
        this.$store.commit('brands/removeOnebrand')
        //reset addNewCompnay to level one
        //hide all
        //show level three
      },
      ButtonClickAddOneBrand(){
        
        this.$store.state.brands.brandEditing= !this.$store.state.brands.brandEditing;
        this.$store.commit('brands/hideAllOperations')
        //show level three
        this.$store.commit('brands/addOnebrand')
        //reset addNewCompnay to level one
        //hide all
        //show level three
      },
      
    },
    computed: {
      paginatinCountPerPage:{
        get(){
          return this.$store.state.brands.paginationObject.countPerPage;
        },
        set(newValue){
          this.$store.commit('brands/changeCountPerPage',newValue)
        }
      },
      items(){
        return this.$store.state.brands.items
      },
    },
    created() {
      this.searchBrandName = this.$store.state.brands.search;
    },
    components:{
      OperationBrandModal
    }
  }
</script>


<style lang="scss" scoped>
 
</style>