<template>
  <div>
      <AddNewCompanyLevelOne />
      <AddNewCompanyLevelTwo />
      <AddNewCompanyLevelThree />
  </div>
</template>

<script>
import AddNewCompanyLevelOne from '@/components/brands/operations/addNewCompany/AddNewCompanyLevelOne.vue'
import AddNewCompanyLevelTwo from '@/components/brands/operations/addNewCompany/AddNewCompanyLevelTwo.vue'
import AddNewCompanyLevelThree from '@/components/brands/operations/addNewCompany/AddNewCompanyLevelThree.vue'
export default {
  data() {
    return {
      
    };
  },
  methods:{
  
  },
  computed: {
    
  },
  components:{
    AddNewCompanyLevelOne,
    AddNewCompanyLevelTwo,
    AddNewCompanyLevelThree
  }
};
</script>

<style lang="scss">
.form-editCompany * {
  direction: rtl !important;
  float: right;
  text-align: right;
}
</style>
