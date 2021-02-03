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
        //dispatch action
        this.$store.dispatch('companies/getAllCompanies')
      }
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
  }
</script>