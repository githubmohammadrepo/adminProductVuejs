<template>
  <div>
    <div class="mb-2">
      <b-form-checkbox v-model="stickyHeader" inline>Sticky header</b-form-checkbox>
      <b-form-checkbox v-model="noCollapse" inline>No border collapse</b-form-checkbox>
    </div>
    <b-table
    @row-dblclicked="editOneCompanie"
      :sticky-header="stickyHeader"
      :no-border-collapse="noCollapse"
      responsive
      :items="items"
      :fields="fields"
    >
      <template #cell(operation)="data">
        <b-button variant="info" v-html="data.value"></b-button>
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
  </div>
</template>

<script>
import axios from 'axios';

  export default {
    data() {
      return {
        getAllBrandOffset:0,
        stickyHeader: true,
        noCollapse: false,
        fields: [
          { key: 'id', stickyColumn: true, isRowHeader: true, variant: 'primary' },
          {key:'companyName',label:'نام شرکت'},
          {key:'brandName',label:'نام برند'},
          {key:'managerName',label:'مدیر شرکت'},
          {key:'mobile',label:'تلفن همراه مدیر شرکت'},
          {key:'phone',label:'تلفن شرکت'},
          {key:'address',label:'آدرس شرکت'},
          {key:'brandImage',label:'لوگوی برند'}
        ],
        
      }
    },
    methods: {
      getAllCompanies(){ 
        
      },
      editOneCompanie(index,event){
        this.$store.state.companies.editDataObject = index;
        this.$store.state.companies.companyEditing= !this.$store.state.companies.companyEditing;
      },
      
    },
    computed: {
      items(){
        return this.$store.state.companies.items
      }
    },
    created() {
     this.getAllCompanies()
    },
  }
</script>


<style lang="scss" scoped>
 
</style>