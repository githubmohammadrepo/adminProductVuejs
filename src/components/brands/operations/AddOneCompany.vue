<template>
  <div>
      <AddNewCompanyLevelOne v-if="showLevelOne" />
      <AddNewCompanyLevelTwo v-if="showLevelTwo" />
      <AddNewCompanyLevelThree v-if="showLevelThree" />
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
    showLevelOne(){
      return this.$store.state.companies.AddNewCompany.levelOne.show
    },
    showLevelTwo(){
      return this.$store.state.companies.AddNewCompany.levelTwo.show
    },
    showLevelThree(){
      return this.$store.state.companies.AddNewCompany.levelThree.show
    }
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
