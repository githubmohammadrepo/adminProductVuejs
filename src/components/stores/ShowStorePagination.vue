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
        if(this.$store.state.stores.storeShowComponents.SearchStore.searchInput.status){
        let count = (this.$store.state.stores.storeShowComponents.SearchStore.searchInput.count);

        }else if(this.$store.state.stores.storeShowComponents.SearchStore.filtered){
          this.searchStores();
        
          //get filtered
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
          offset:((that.currentPage-1)<0 ? 0 : that.currentPage-1)*that.countPerPage,
          paginationCount:that.countPerPage,
          showALlStoreInfos:true
        })
        .then(response =>{
          if(response.data && response.data.stores){
            
            that.$store.commit('stores/makeSearchAsFiltered',true)
            //save info in store
            that.$store.commit('stores/saveFindedStores',response.data.stores)
            //close filtered store component
            that.$store.commit('stores/showCompoenetByName','showFindedStores')
            that.pages= Math.ceil(response.data.count/that.countPerPage)
            //save searched value province,city,region
            that.$store.commit('stores/saveSearchedFilters',...that.$store.state.stores.storeShowComponents.SearchStore,)
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
          this.$store.state.stores.paginationObject.currentPage=newValue
          this.show(newValue)
        }
      },
      countPerPage:{
        get(){
          return this.$store.state.stores.paginationObject.countPerPage;
        },
        set(newValue){
          this.show(newValue)
          this.$store.state.stores.paginationObject.countPerPage=newValue
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