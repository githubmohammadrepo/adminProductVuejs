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
        // alert('newShow')
        //dispatch action
        // this.$store.dispatch('products/getAllCategoryProducts')
      },
      updateCurrentPage(currentPage){
        if(this.currentPage.toString()==currentPage.toString()){

        }else{
        
          this.currentPage = currentPage;
          this.$store.dispatch('products/getAllCategoryProducts')
        }

      },
    },
    computed: {
      pages:{
        get(){
          return parseInt(this.$store.state.products.paginationObject.pages);
        },
        set(newValue){
          this.$store.state.products.paginationObject.pages = newValue;
        }
      },
      currentPage:{
        get(){
          return parseInt(this.$store.state.products.paginationObject.currentPage);
        },
        set(newValue){
          this.show(newValue)
          this.$store.state.products.paginationObject.currentPage=newValue
        }
      }
    },
    created(){
        this.$store.dispatch('products/getAllCategoryProducts')
    },
    components:{
      paginate
    }
  }
</script>