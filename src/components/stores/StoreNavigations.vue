<template>
   <div>
   <b-navbar toggleable="lg" type="light" variant="light">
     <b-navbar-toggle target="nav-collapse"></b-navbar-toggle>
        <b-nav-item class="btn btn-sm" active @click="addOneStore" href="#">افزودن فروشگاه</b-nav-item>

    <b-collapse id="nav-collapse" is-nav>

      <b-navbar-nav class="col-sm-4">
        <b-nav-item class="btn btn-sm" @click="ShowFileterStore" href="#">فیلتر فروشگاه</b-nav-item>
        <b-nav-item @click="showFindedStores" class="" href="#" >نمایش فروشگاه ها</b-nav-item>
      </b-navbar-nav>

      <!-- center aligned nav items -->
      <b-navbar-nav class="m-auto col-sm-4">
        <!-- <b-nav-form class="my-2 w-100 bg-info"> -->
          <b-form-input type="search" v-if="storeLength" v-model="searchStore" size="md" @input="findStoreBySearchInput" class="w-100" placeholder="Search"></b-form-input>
        <!-- </b-nav-form> -->
      </b-navbar-nav>

       <!-- Right aligned nav items -->
      <b-navbar-nav class="col-sm-4 col-md-3 m-auto mr-lg-auto">
        <!-- <b-nav-form class="d-flex bg-primary m-0"> -->
         <!-- select regions -->
            <b-form-select  v-model="selectCountPerPage" :options="filterNumbers" @change="changeCountPaginationPerPage" class="m-auto row col-12">
              <!-- This slot appears above the options from 'options' prop -->
              <template #first>
                <b-form-select-option :value="null" disabled
                  >نمایش تعداد فروشگاه</b-form-select-option
                >
              </template>
            </b-form-select>
        <!-- </b-nav-form> -->
      </b-navbar-nav>
          </b-collapse>

  </b-navbar>
</div> 
</template>

<script>
import axios from 'axios'
export default {
  props:{
    navNumber:{
      type:Number,
    }
  },
  data(){
    return {
      searchStore:'',
      filterNumbers:[
        {value:25,text:25},
        {value:50,text:50},
        {value:100,text:100},
        {value:500,text:500},
        {value:1000,text:1000},
      ]
    }
  },
  methods:{
    addOneStore(){
      //show modal one store
      this.$store.commit('stores/showModalOperation',{key:'add',editing:true});
    },
    ShowFileterStore(){
      this.$store.commit('stores/showCompoenetByName','filterSearch')
    },
    showFindedStores(){
      this.$store.commit('stores/showCompoenetByName','showFindedStores')
      this.$store.commit('stores/makeSearchAsFiltered',true)
    },
    changeCountPaginationPerPage(value){
      //change store perapge number
      this.$store.commit('stores/changeCountPerPagePagnitionation',value)

      //dispatch action StoreSearch a gain
      if(this.searchStore.length){
        //dispatch search by input
        this.findStoreBySearchInput(this.searchStore);
      }
      else if(this.filteredSearchValue==true){
        this.$store.dispatch('stores/searchStores')
      }else{
        this.$store.dispatch('stores/getAllStores')
      }

      
    },
    findStoreBySearchInput(value){
      this.$store.state.stores.storeShowComponents.SearchStore.searchInput.status=true;
      let that = this;
      let province_id = this.$store.state.stores.storeShowComponents.SearchStore.selectedProvince;
      let city_id = this.$store.state.stores.storeShowComponents.SearchStore.selectedCity;
      let region_id = this.$store.state.stores.storeShowComponents.SearchStore.selectedRegion;

      console.log({
        offset: isNaN(parseInt(that.selectCountPerPage) * (parseInt(that.$store.state.stores.paginationObject.currentPage))) ? 0 : parseInt(that.selectCountPerPage) * (parseInt(that.$store.state.stores.paginationObject.currentPage)),
                count: that.$store.state.stores.paginationObject.currentPage==0 ? 25 : that.$store.state.stores.paginationObject.currentPage,
                search:value,
                searchStoreBySearchInput: true,
                province_id:province_id,
                city_id: city_id,
                region_id: region_id
      })
        axios
            .post("http://fishopping.ir/serverHypernetShowUnion/adminProduct/webservices/stores/ShowStoreInfos.php", {
                offset: isNaN(parseInt(that.selectCountPerPage) * (parseInt(that.$store.state.stores.paginationObject.currentPage))) ? 0 : parseInt(that.selectCountPerPage) * (parseInt(that.$store.state.stores.paginationObject.currentPage)),
                count: that.$store.state.stores.paginationObject.currentPage==0 ? 25 : that.$store.state.stores.paginationObject.currentPage,
                search:value,
                province_id:province_id,
                city_id: city_id,
                region_id: region_id,
                searchStoreBySearchInput: true
            })
            .then(response => {
              console.log('searchInput response')
              console.log(response.data)
                if (response.data && response.data.stores) {
                    that.$store.commit('stores/computePagesPaginations', response.data.count)
                        //save info in store
                    that.$store.commit('stores/saveFindedStores', response.data.stores)
                        //close filtered store component
                    that.$store.commit('stores/showCompoenetByName', 'showFindedStores')
                } else {
                }
            })
            .catch(error => {
                console.log(error)
            })
    },
    storeLength(){
      this.showSearchBox = Object.keys(this.$store.state.stores.items).length>0 ? false : true;
    },
    
    
  },
  computed:{
    navigationActiveNumber(){
      
    },
    selectCountPerPage:{
      get(){
        return this.$store.state.stores.paginationObject.countPerPage
      },
      set(newValue){
        this.$store.state.stores.paginationObject.countPerPage = newValue;
      }
    },
    filteredSearchValue:{
      get(){
        return this.$store.state.stores.storeShowComponents.SearchStore.filtered 
      },
      set(newValue){
        this.$store.state.stores.storeShowComponents.SearchStore.filtered =newValue
      }
    },
    storeLength(){
      // return Object.keys(this.$store.state.stores.items).length;
      return this.$store.state.stores.storeShowComponents.SearchStore.filtered
    }
    // searchStore:{
    //   get(){
    //     return;
    //   },
    //   set(newSearchValue){

    //   }
    // }
    
  },
  created(){
    console.log('items')
    let length = Object.keys(this.$store.state.stores.items).length>0 ? false : true;
    console.log(length)

  },
  mounted() {
    
  },
}
</script>