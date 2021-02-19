<template>
  <div class="row justify-content-center">
    <!-- just for text show modal developing -->
    <paginate :pages="pages" :value="currentPage" @changed="updateCurrentPage" />
  </div>
</template>

<script>
import paginate from '@/components/general/Pagination.vue'
export default {
    data() {
      return {
        myName:"mohammad"
      }
    },
    methods: {
      show(newShow){
        //dispatch action
        this.$store.dispatch('companies/getAllCompanies')
      },
      goToTwo(){
        this.currentPage += 1;
        this.myName='Heideh'
      },
      updateCurrentPage(currentPage){
        this.currentPage = currentPage;
      },
      
    },
    computed: {
      pages:{
        get(){
          return this.$store.state.companies.paginationObject.pages;
        },
        set(newValue){
          this.$store.state.companies.paginationObject.pages = newValue;
        }
      },
      currentPage:{
        get(){
          return this.$store.state.companies.paginationObject.currentPage;
        },
        set(newValue){
          this.show(newValue)
          this.$store.state.companies.paginationObject.currentPage=newValue
        }
      }
    },
    components:{
      paginate
    }
  }
</script>