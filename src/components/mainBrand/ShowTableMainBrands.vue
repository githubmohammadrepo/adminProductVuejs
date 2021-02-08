<template>
  <div>
    <div class="row ml-auto mb-3 col">
        <b-button variant="primary" @click="ButtonClickAddOneBrand">افزودن برند جدید</b-button>

      <!-- <b-form-checkbox v-model="stickyHeader" inline>Sticky header</b-form-checkbox>
      <b-form-checkbox v-model="noCollapse" inline>No border collapse</b-form-checkbox> -->
    </div>
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
        getAllBrandOffset:0,
        stickyHeader: true,
        noCollapse: false,
        fields: [
          { key: 'operation',label:'عملیات' ,stickyColumn: true, isRowHeader: true, variant: 'primary',class:"px-0 mx-0" },
          { key: 'category_id',label:'ایدی برند'},
          {key:'category_name',label:'نام برند'},
          {key:'user_name',label:'نام صاحب برند'},
          {key:'category_published',label:'وصعیت انتشار'},
          {key:'brand_logo',label:'لوگوی برند'},
        ],
        
      }
    },
    methods: {
      change(){
        alert('chagne')
      },
      clickButton(home){
        console.log(home)
        alert('clicked button')
      },
      getAllbrands(){ 
      },
      editOneBrand(index){
        console.log(index)
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
        console.log(index)
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
        console.log(index)
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
      items(){
        return this.$store.state.brands.items
      }
    },
    created() {
     this.getAllbrands()
    },
    components:{
      OperationBrandModal
    }
  }
</script>


<style lang="scss" scoped>
 
</style>