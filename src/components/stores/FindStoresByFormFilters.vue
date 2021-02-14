<template>
  <div>
    <h5 class="text-center mt-2">جتسجوی فروشگاه ها</h5>
    <hr class="w-50" />
    <b-row class="justify-content-center m-auto">
      <b-form class=" col-md-8 col-lg-7 col-xl-6 col-sm-10">
        <b-row class="p-4  card bg-secondary">
          <!-- select province -->
          <b-col class="col-12">
            <b-form-select v-model="selectedValues.selectedProvince" :options="provinces" @change="getProvinceCities" size="8" class="mb-3">
              <!-- This slot appears above the options from 'options' prop -->
              <template #first>
                <b-form-select-option :value="null" disabled
                  >نام استان را انتخاب کنید</b-form-select-option
                >
              </template>
            </b-form-select>
          </b-col>
          <!-- end select province -->

          <!-- select city -->
          <b-col class="col-12">
            <b-form-select v-model="selectedValues.selectedCity" :options="cities" @change="getCitySelectedRegions" class="mb-3">
              <!-- This slot appears above the options from 'options' prop -->
              <template #first>
                <b-form-select-option :value="null" disabled
                  >نام شهر را انتخاب کنید</b-form-select-option
                >
              </template>
            </b-form-select>
          </b-col>
          <!-- end select city -->

          <!-- select regions -->
          <b-col class="col-12">
            <b-form-select v-model="selectedValues.selectedRegion" :options="regions" class="mb-3">
              <!-- This slot appears above the options from 'options' prop -->
              <template #first>
                <b-form-select-option :value="null" disabled
                  >نام منطقه را انتخاب کنید</b-form-select-option
                >
              </template>
            </b-form-select>
          </b-col>
          <!-- end select regions -->

          <!-- submit button -->
          <b-col class="col-12">
              <b-button variant="info" @click="searchStores">جستجوی فروشگاه</b-button>
          </b-col>
        </b-row>
      </b-form>
    </b-row>
  </div>
</template>

<script>
import axios from 'axios'
export default {
  data() {
    return {
      selectedValues:{
        selectedProvince: null,
        selectedCity: null,
        selectedRegion: null,
      },
      fetchedData:{
        provinces:Array(),
        cities:Array(),
        Regions:Array(),
      },
      options: [
        { value: "A", text: "Option A (from options prop)" },
        { value: "B", text: "Option B (from options prop)" },
      ],
    };
  },
  methods: {
    getProvinceCities(value){
      //search value
      let provinceSelected = {};
      let allProvinces= this.getProvinces;
      for(let i=0;i<allProvinces.length;i++){
        if(parseInt(allProvinces[i].id) == value){
          provinceSelected = allProvinces[i];
          break;
        }
      }
    
    //set province_id to selected city
    //dispatch action get all provinceCities
    this.getProvinceCities_ajax(value)
    },
    getCitySelectedRegions(value){
       //search value
      let CitySelected = {};
      let allCities= this.getCities;
      for(let i=0;i<allCities.length;i++){
        if(parseInt(allCities[i].id) == value){
          CitySelected = allCities[i];
          break;
        }
      }
    
    //dispatch action get all provinceCities
    this.getCityRegions_ajax(value);

    //just for test show 
    },
    /**
     * search stoes
     */
    searchStores(){
      let that = this;
      axios
        .post("http://fishopping.ir/serverHypernetShowUnion/adminProduct/webservices/stores/ShowStoreInfos.php",{
          ...that.selectedValues,
          offset:0,
          count:25,
          showALlStoreInfos:true
        })
        .then(response =>{
          if(response.data && response.data.stores){
            
            this.$store.commit('stores/makeSearchAsFiltered',true)
            //save info in store
            this.$store.commit('stores/saveFindedStores',response.data.stores)
            //close filtered store component
            this.$store.commit('stores/showCompoenetByName','showFindedStores')

            //save searched value province,city,region
            this.$store.commit('stores/saveSearchedFilters',this.selectedValues)
          }else{
          }
        })
        .catch(error =>{
          console.log(error)
        })
    },



    /**
     * get all provinces
     */
    getAllProvinces_ajax() {
      let that = this;
        axios
            .post("http://fishopping.ir/serverHypernetShowUnion/adminProduct/webservices/stores/showFormFilterStore.php", {
                selectProvince: true
            })
            .then(function(response) {
                if (response.data && response.data.provinces.length) {
                    that.fetchedData.provinces = response.data.provinces
                } else {
                  that.fetchedData.provinces = Array();
                  //show error fetching provinces
                }

            })
            .catch(function(error) {
                console.log(error)
            })
    },
    /**
     * get all province cities
     */
    getProvinceCities_ajax(province_id) {
      let that  =this;
        axios
            .post("http://fishopping.ir/serverHypernetShowUnion/adminProduct/webservices/stores/showFormFilterStore.php", {
                selectProvinceCities: true,
                province_id: province_id
            })
            .then(function(response) {
                if (response.data && response.data.provinceCities && response.data.provinceCities.length) {
                   that.fetchedData.cities = response.data.provinceCities
                   
                } else {
                  // show modal error fetching provinceCities
                  that.fetchedData.cities = Array()
                }

            })
            .catch(function(error) {
                console.log(error)
            })
    },

    /**
     * get all regions
     */
    getCityRegions_ajax(city_id) {
      let that =this;
        axios
          .post("http://fishopping.ir/serverHypernetShowUnion/adminProduct/webservices/stores/showFormFilterStore.php", {
              selectCityRegions: true,
              cityId: city_id
          })
          .then(function(response) {
              if (response.data && response.data.CityRegions && response.data.CityRegions.length) {
                  that.fetchedData.Regions = response.data.CityRegions
                  
              } else {
                // show modal error fetching provinceCities
                that.fetchedData.Regions = Array()
              }

          })
          .catch(function(error) {
              console.log(error)
          })
    }
  },
  computed: {
    provinces(){
      return this.fetchedData.provinces.map((value,index)=>{
        return {
          text:value.name,
          value:value.id,
        }
      })
    },
    cities(){
      return this.fetchedData.cities.map((value,index)=>{
        return {
          text:value.name,
          value:value.id
        }
      })
    },
    regions(){
      return this.fetchedData.Regions.map((value,index)=>{
        return {
          text:value.title,
          value:value.id
        }
      })
    },

    getProvinces() {
        return this.fetchedData.provinces
    },
    getCities() {
        return this.fetchedData.cities
    },
    getRegions() {
        return this.fetchedData.Regions
    },
    getStoreSearchObject() {
        return this.storeShowComponents.SearchStore;
    }
  },
  created() {
    this.getAllProvinces_ajax()
  },
  mounted() {
    
  },
  components: {},
};
</script>

<style lang="scss" scoped>
</style>