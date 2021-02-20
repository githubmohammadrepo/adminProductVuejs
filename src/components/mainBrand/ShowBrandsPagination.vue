<template>
  <div class="row justify-content-center">
    <div class="mt-3">
    <paginate :pages="pages" :value="currentPage" @changed="updateCurrentPage" />
      
    </div>
  </div>
</template>

<script>
import paginate from '@/components/general/Pagination.vue'

export default {
    data() {
      return {
      }
    },
    methods: {
      show(newShow){
        //dispatch action
        this.$store.dispatch('brands/getAllBrands')
      },
      updateCurrentPage(currentPage){
        this.currentPage = currentPage;
      },
    },
    computed: {
      pages:{
        get(){
          return this.$store.state.brands.paginationObject.pages;
        },
        set(newValue){
          this.$store.state.brands.paginationObject.pages = newValue;
        }
      },
      currentPage:{
        get(){
          return this.$store.state.brands.paginationObject.currentPage;
        },
        set(newValue){
          this.show(newValue)
          this.$store.state.brands.paginationObject.currentPage=newValue
        }
      }
    },
    components:{
      paginate
    }
  }
</script>