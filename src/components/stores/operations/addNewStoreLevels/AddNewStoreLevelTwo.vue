<template>
  <div>
    <h5 class="text-center mt-3">مرحله ی دوم ثبت نام فروشگاه</h5>

      <!-- start select box select province -->
      {{selectedValues.selectedProvince}}
    <hr class="w-50">
    <!-- store title -->
    <b-form class="form-editCompany pb-3" @submit.stop.prevent>

      <label for="address">استان فروشگاه</label>
      <b-form-select
        v-model="selectedValues.selectedProvince"
        :options="provinces"
        @change="getProvinceCities"
        size="8"
        class="mb-3"
        :state="provinceValidation"
      >
        <!-- This slot appears above the options from 'options' prop -->
        <template #first>
          <b-form-select-option :value="null" disabled
            >نام استان را انتخاب کنید</b-form-select-option
          >
        </template>
      </b-form-select>
      <b-form-invalid-feedback :show="!provinceValidation">
        باید یک استان را انتخاب بکنید
      </b-form-invalid-feedback>
      <b-form-valid-feedback :show="provinceValidation">
        تایید شد
      </b-form-valid-feedback>
      <!-- end select box select province -->

      <!-- start select box select provinceCities -->

      <label for="city" >شهر فروشگاه</label>
       <b-form-select  :state="cityProvinceValidation" v-model="selectedValues.selectedCity" :options="cities" @change="getCitySelectedRegions" class="mb-3">
          <!-- This slot appears above the options from 'options' prop -->
          <template #first>
            <b-form-select-option :value="null" disabled
              >نام شهر را انتخاب کنید</b-form-select-option
            >
          </template>
        </b-form-select>
      <b-form-invalid-feedback  :show="!cityProvinceValidation">
        باید یک شهر را انتخاب بکنید
      </b-form-invalid-feedback>
      <b-form-valid-feedback  :show="cityProvinceValidation">
        تایید شد
      </b-form-valid-feedback>
      <!-- end select box select provinceCities -->

      <!-- start select box select region -->
      <div class="w-100" v-if="showRegionsSelect">

      <label for="address">منطقه فروشگاه</label>
       <b-form-select v-model="selectedValues.selectedRegion" @change="getUnregisteredStores" :options="regions" class="mb-3">
          <!-- This slot appears above the options from 'options' prop -->
          <template #first>
            <b-form-select-option :value="null" disabled
              >نام منطقه را انتخاب کنید</b-form-select-option
            >
          </template>
        </b-form-select>
      </div>

        <!-- start select box select province -->
      <span v-if="showUnregisteredStoreSelectBox">
        <label for="selectStore">انتخاب از میان فروشگاه های موجود</label>
        <b-form-select
          v-model="selectedStore"
          :options="fetchedData.unRegisteredStores"
          size="8"
          class=""
          :state="true"
          id="selectStore"
        >
          <!-- This slot appears above the options from 'options' prop -->
          <template #first>
            <b-form-select-option :value="null" disabled
              >انتخاب از میان فروشگاه های موجود</b-form-select-option >
          </template>
        </b-form-select>
        <b-form-valid-feedback :show="true">
          در صورت انتخاب، فیلد های زیر اپدیت خواهند شد
        </b-form-valid-feedback>
      </span>
      <!-- end select box select province -->


      <!-- store ShopName -->
      <label for="ShopName">نام فروشگاه</label>
      <b-form-input
        v-model="store.ShopName"
        :state="ShopNameValidation"
        id="ShopName"
        required
        type="text"
      ></b-form-input>
      <b-form-invalid-feedback :show="!ShopNameValidation">
        تعداد کارکتر ها نباید کمتر از 3 کارکتر باشد
      </b-form-invalid-feedback>
      <b-form-valid-feedback :show="ShopNameValidation">
        تایید شد
      </b-form-valid-feedback>
      <!-- end store ShopName -->

      <!-- store phone -->
      <label for="phone">تلفن فروشگاه</label>
      <b-form-input
        v-model="store.phone"
        :state="phoneValidation"
        id="phone"
        required
        type="text"
      ></b-form-input>
      <b-form-invalid-feedback :show="!phoneValidation">
        فقط باید عدد وارد شود
      </b-form-invalid-feedback>
      <b-form-valid-feedback :show="phoneValidation">
        تایید شد
      </b-form-valid-feedback>
      <!-- end store phone -->

      <!-- store ManagerName -->
      <label for="ManagerName">نام مدیر فروشگاه</label>
      <b-form-input
        v-model="store.ManagerName"
        :state="ManagerNameValidation"
        id="ManagerName"
        required
        type="text"
      ></b-form-input>
      <b-form-invalid-feedback :show="!ManagerNameValidation">
        باید بشتر از 3 کارکتر وارد کنید
      </b-form-invalid-feedback>
      <b-form-valid-feedback :show="ManagerNameValidation">
        تایید شد
      </b-form-valid-feedback>
      <!-- end store ManagerName -->

      <!-- store MobilePhone -->
      <label for="MobilePhone">موبایل صاحب فروشگاه</label>
      <b-form-input
        v-model="store.MobilePhone"
        :state="MobilePhoneValidation"
        id="MobilePhone"
        required
        type="text"
      ></b-form-input>
      <b-form-invalid-feedback :show="!MobilePhoneValidation">
        فقط باید عدد وارد شود
      </b-form-invalid-feedback>
      <b-form-valid-feedback :show="MobilePhoneValidation">
        تایید شد
      </b-form-valid-feedback>
      <!-- end store MobilePhone -->

      <!-- store address -->
      <label for="address">آدرس فروشگاه</label>
      <b-form-input
        v-model="store.address"
        :state="addressValidation"
        id="address"
        required
        type="text"
      ></b-form-input>
      <b-form-invalid-feedback :show="!addressValidation">
        باید بیشتر از 3 کارکتر وارد کنید
      </b-form-invalid-feedback>
      <b-form-valid-feedback :show="addressValidation">
        تایید شد
      </b-form-valid-feedback>
      <!-- end store address -->

      
    </b-form>
    <hr class="w-100" />

    <div class="row w-100 justify-content-center pt-3">
      <b-button variant="primary m-auto px-4" @click="saveStore" :disabled="!validationPasseed"
        >مرحله ی بعدی</b-button
      >
    </div>
  </div>
</template>

<script>
import axios from 'axios'
export default {
  data() {
    return {
      // modalShow: true,
      store: {
        title:"",
        ShopName:"",
        phone:"",
        MobilePhone:"",
        ManagerName:"",
        address:"",
        regions:"",
        cities:"",
        provinces:"",
      },
      showRegionsSelect:true,
      selectedStore: null,
      options: [
        { text: 'وضعیت انشار', value: true },
      ],
      fetchedData:{
        provinces:Array(),
        cities:Array(),
        Regions:Array(),
        unRegisteredStores:Array(),
      },
      selectedValues:{
        selectedProvince: null,
        selectedCity: null,
        selectedRegion: null,
      },
      showUnregisteredStoreSelectBox:true
    };
  },
  methods:{
    saveStore(){
      //if validation passed save informations
      if (this.validationPasseed) {
        //save infos goto level three
        this.$store.commit('stores/showAddNewStoreLevel',{key:'levelTwo',value:false,formData:{...this.selectedValues,selectedStore:this.selectedStore,...this.store}})
        this.$store.commit('stores/showAddNewStoreLevel',{key:'levelThree',value:true,formData:{},cancelInsert:true})
        
      }
    

    },
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
                  // that.selectedValues.selectedProvince = this.$store.getters['stores/getFormDataInserNewStoreLevels'].selectedProvince
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
                ...that.selectedValues,
                province_id: province_id
            })
            .then(function(response) {
                if (response.data && response.data.provinceCities && response.data.provinceCities.length) {
                   that.fetchedData.cities = response.data.provinceCities
                   that.selectedValues.selectedCity = this.$store.getters['stores/getFormDataInserNewStoreLevels'].selectedCity
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
                that.showRegionsSelect = true
                  
                  that.fetchedData.Regions = response.data.CityRegions
                  
              } else {
                // show modal error fetching provinceCities
                that.fetchedData.Regions = Array()
                //get unregistered stores
                that.getUnRegisteredStores_ajax();

                //hide region select box
                that.showRegionsSelect = false
              }

          })
          .catch(function(error) {
              console.log(error)
          })
    },
    /**
     * get all unregistered stores
     */
    getUnRegisteredStores_ajax() {
      let that =this;
        axios
          .post("http://fishopping.ir/serverHypernetShowUnion/adminProduct/webservices/stores/getUnRegisteredStores.php", {
              getAllUnregisteredStores: true,
              ...that.selectedValues
          })
          .then(function(response) {
              if (response.data && response.data.stores.length) {
                that.showUnregisteredStoreSelectBox= true

                  that.fetchedData.unRegisteredStores = response.data.stores.map(store => {
                    return {
                      value:store.id,
                      text:store.ShopName
                    }
                  });
                  
              } else {
                // show modal error fetching provinceCities
                that.fetchedData.Regions = Array()
                //dispable selextBox_unregisteredStores
                that.showUnregisteredStoreSelectBox= false
              }

          })
          .catch(function(error) {
              console.log(error)
          })
    },

    getUnregisteredStores(value){
      this.selectedValues.Regions = value;
      this.getUnRegisteredStores_ajax();
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
    },
    validationPasseed(){
      return (
        this.ShopNameValidation &&
        this.phoneValidation &&
        this.MobilePhoneValidation &&
        this.ManagerNameValidation &&
        this.addressValidation &&
        this.provinceValidation &&
        this.cityProvinceValidation
      );
    },

    ShopNameValidation() {
      return (
        this.store.ShopName!=null && this.store.ShopName.length > 3 && this.store.ShopName.length < 30
      );
    },
    phoneValidation() {
      return (
        this.store.phone!=null && this.store.phone.length > 3 && this.store.phone.length < 13 && !isNaN(Number(this.store.phone))
      );
    },
    MobilePhoneValidation() {
      return (
        this.store.MobilePhone!=null && this.store.MobilePhone.length > 3 && this.store.MobilePhone.length < 13 && !isNaN(Number(this.store.MobilePhone))
      );
    },
    ManagerNameValidation() {
      return (
        this.store.ManagerName!=null && this.store.ManagerName.length > 3 && this.store.ManagerName.length < 30
      );
    },
    addressValidation() {
      return (
        this.store.address!=null && this.store.address.length > 3 && this.store.address.length < 30
      );
    },
    provinceValidation() {
      return (
        this.selectedValues.selectedProvince!=null && this.selectedValues.selectedProvince.length >0
      );
    },
    cityProvinceValidation() {
      return (
        this.selectedValues.selectedCity!=null && this.selectedValues.selectedCity.length >0
      );
    },
    
    modalShow:{
      get(){
        return this.$store.state.stores.brandEditing
      },
      set(newValue){
        this.$store.state.stores.brandEditing=!this.$store.state.stores.brandEditing;
      }
      // return false;
    }
  },
  created() {
    this.store.published= false;
    this.store.brandName="";
    this.brand_logo= null;
  },
  updated() {
     let obj = this.$store.state.stores.editDataObject;
    this.store = obj
  },
  created() {
    this.getAllProvinces_ajax();
    this.showUnregisteredStoreSelectBox=true
    this.showRegionsSelect = true;

    //get all adata
    this.store.ShopName = this.$store.getters['stores/getFormDataInserNewStoreLevels'].ShopName
    this.store.phone = this.$store.getters['stores/getFormDataInserNewStoreLevels'].phone
    this.store.MobilePhone = this.$store.getters['stores/getFormDataInserNewStoreLevels'].ShopName
    this.store.ManagerName = this.$store.getters['stores/getFormDataInserNewStoreLevels'].ManagerName
    this.store.address = this.$store.getters['stores/getFormDataInserNewStoreLevels'].address
    this.selectedValues.selectedProvince = this.$store.getters['stores/getFormDataInserNewStoreLevels'].selectedProvince

    if(this.selectedValues.selectedProvince){
      //get ajax all cities
      this.getProvinceCities(this.selectedValues.selectedProvince)
    }
    this.selectedValues.selectedCity = this.$store.getters['stores/getFormDataInserNewStoreLevels'].selectedCity
    if(this.selectedValues.selectedCity){
      //get ajax all cities
      this.getCitySelectedRegions(this.selectedValues.selectedCity)
    }

    this.selectedValues.selectedRegion = this.$store.getters['stores/getFormDataInserNewStoreLevels'].selectedRegion
    if(this.selectedValues.selectedRegion){
      //get ajax all regions
      this.getUnregisteredStores(this.selectedValues.selectedRegion)
    }

    this.selectedValues.address = this.$store.getters['stores/getFormDataInserNewStoreLevels'].address
    this.selectedStore = this.$store.getters['stores/getFormDataInserNewStoreLevels'].selectedStore

  },
  components:{
  },
};
</script>

<style lang="scss">
.form-editCompany * {
  direction: rtl !important;
  float: right;
  text-align: right;
}
</style>
