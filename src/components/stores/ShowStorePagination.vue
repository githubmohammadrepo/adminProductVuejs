<template>
  <div class="row justify-content-center">
    <div class="mt-3">
      <b-pagination-nav
        v-model="currentPage"
        :number-of-pages="pages"
        base-url="#"
        last-number
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
      show(newShow){
        alert('show')
        //dispatch action
        this.$store.dispatch('stores/getAllStores')
      }
    },
    computed: {
      pages:{
        get(){
          return this.$store.state.stores.paginationObject.pages;
        },
        set(newValue){
          this.$store.state.stores.paginationObject.pages = newValue;
        }
      },
      currentPage:{
        get(){
          return this.$store.state.stores.paginationObject.currentPage;
        },
        set(newValue){
          this.show(newValue)
          this.$store.state.stores.paginationObject.currentPage=newValue
        }
      }
    },
  }
</script>