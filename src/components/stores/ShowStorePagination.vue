<template>
  <div class="row justify-content-center">
    <div class="mt-3">
      <paginate :pages="pages" :value="currentPage" @changed="updateCurrentPage" ref="hi"/>      
    </div>
  </div>
</template>

<script>

import paginate from '@/components/general/Pagination.vue'
import axios from 'axios'
export default {
    data() {
      return {
      }
    },
    methods: {
     
      show(newShow){
        if(this.$store.state.stores.storeShowComponents.SearchStore.filtered){
          //get filtered
          this.searchStores()
        }else{
          //dispatch action
          this.$store.dispatch('stores/getAllStores')
        }
      },
      updateCurrentPage(currentPage){
        this.currentPage = currentPage;
      },
      searchStores(){
      let that = this;
      axios
        .post("http://fishopping.ir/serverHypernetShowUnion/adminProduct/webservices/stores/ShowStoreInfos.php",{
          ...that.$store.state.stores.storeShowComponents.SearchStore,
          offset:that.currentPage,
          paginationCount:25,
          showALlStoreInfos:true
        })
        .then(response =>{
          console.log('filter pagination')
          console.log(response.data)
          if(response.data && response.data.stores){
            
            this.$store.commit('stores/makeSearchAsFiltered',true)
            //save info in store
            this.$store.commit('stores/saveFindedStores',response.data.stores)
            //close filtered store component
            this.$store.commit('stores/showCompoenetByName','showFindedStores')
            this.pages= response.data.count
            //save searched value province,city,region
            this.$store.commit('stores/saveSearchedFilters',...that.$store.state.stores.storeShowComponents.SearchStore,)
          }else{
          }
        })
        .catch(error =>{
          console.log(error)
        })
    },
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
    components:{
      paginate
    },
    created() {
    },
    
  }
</script>