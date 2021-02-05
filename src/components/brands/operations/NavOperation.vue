<template>
  <div>
  <b-nav tabs align="center">
    <b-nav-item :active="editOperation" @click="showEditOneCompany" :disabled="isCompanyEditable">ویرایش</b-nav-item>
    <b-nav-item :active="removeOneCompany" @click="showRemoveOneCompany">حذف</b-nav-item>
    <b-nav-item :active="addOneCompany" @click="showAddOneCompany">افزودن شرکت جدید</b-nav-item>
  </b-nav>
</div>
</template>


<script>
export default {
  data(){
    return {

    }
  },
  methods: {
    showEditOneCompany(){
      this.$store.commit('companies/hideAllOperations');
      this.$store.commit('companies/editOperation');
    },
    showRemoveOneCompany(){
      this.$store.commit('companies/hideAllOperations');
      this.$store.commit('companies/removeOneCompany');
    },
    showAddOneCompany(){
      this.$store.commit('companies/hideAllOperations');
      this.$store.commit('companies/addOneCompany');
    }
  },
  computed: {
    isCompanyEditable(){
      let user_id =this.$store.state.companies.editDataObject.user_id;
      return JSON.parse(this.$store.state.companies.editDataObject.user_id) ? false : true
    },
    editOperation: {
      get() {
        return this.$store.state.companies.companyOperation.edit;
      },
    },
    removeOneCompany: {
      get() {
        return this.$store.state.companies.companyOperation.remove;
      }
    },
    addOneCompany: {
      get() {
        return this.$store.state.companies.companyOperation.add;
      }
    },
  },
  components:{
    
  }
}
</script>