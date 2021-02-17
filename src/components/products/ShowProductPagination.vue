<template>
  <div class="row justify-content-center">
    <div class="mt-3">
      <b-pagination-nav
        v-model="currentPage"
        :number-of-pages="pages"
        base-url="#"
        last-number
        @input= "changeCurrentPage"
      ></b-pagination-nav>
    </div>
  </div>
</template>

<script>
export default {
    data() {
      return {
      }
    },
    methods: {
      changeCurrentPage(value){
        this.currentPage = value;
        this.$store.dispatch('products/getAllCategoryProducts')
      },
      show(newShow){
        // alert('newShow')
        //dispatch action
        // this.$store.dispatch('products/getAllCategoryProducts')
      }
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
    }
  }
</script>