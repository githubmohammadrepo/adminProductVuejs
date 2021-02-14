<template>
  <div>
    <h5 class="text-center"> ویرایش فروشگاه </h5>
    <hr class="w-50">
    <!-- store title -->
    <b-form class="form-editCompany pb-3" @submit.stop.prevent>
      <label for="title">عنوان فروشگاه</label>
      <b-form-input
        v-model="store.title"
        :state="titleValidation"
        id="title"
        required
        type="text"
      ></b-form-input>
      <b-form-invalid-feedback :show="!titleValidation">
        تعداد کارکتر ها نباید کمتر از 3 کارکتر باشد
      </b-form-invalid-feedback>
      <b-form-valid-feedback :show="titleValidation">
        تایید شد
      </b-form-valid-feedback>
      <!-- end store title -->

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

      <!-- store ManagerName -->
      <label for="ManagerName">نام صاحب فروشگاه</label>
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

      <!-- start select box select province -->
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
      <label for="address">شهر فروشگاه</label>
       <b-form-select :state="cityProvinceValidation" v-model="selectedValues.selectedCity" :options="cities" @change="getCitySelectedRegions" class="mb-3">
          <!-- This slot appears above the options from 'options' prop -->
          <template #first>
            <b-form-select-option :value="null" disabled
              >نام شهر را انتخاب کنید</b-form-select-option
            >
          </template>
        </b-form-select>
      <b-form-invalid-feedback :show="!cityProvinceValidation">
        باید یک شهر را انتخاب بکنید
      </b-form-invalid-feedback>
      <b-form-valid-feedback :show="cityProvinceValidation">
        تایید شد
      </b-form-valid-feedback>
      <!-- end select box select provinceCities -->

      <!-- start select box select region -->
      <label for="address">شهر فروشگاه</label>
       <b-form-select v-model="selectedValues.selectedRegion" :options="regions" class="mb-3">
          <!-- This slot appears above the options from 'options' prop -->
          <template #first>
            <b-form-select-option :value="null" disabled
              >نام منطقه را انتخاب کنید</b-form-select-option
            >
          </template>
        </b-form-select>
      <!-- end select box select province -->
    </b-form>
    <hr class="w-100" />

    <div class="row w-100 justify-content-center pt-3">
      <b-button 
        variant="primary m-auto px-4" 
        :disabled="!validationPasseed"
        @click="updateOneStore">آپدیت</b-button
      >
    </div>
  </div>
</template>

<script>
import axios from 'axios'
export default {
  data() {
    return {
      brand_logo:"",
      // modalShow: true,
      store: {
        title:"",
        ShopName:"",
        phone:"",
        MobilePhone:"",
        lant:"",
        lng:"",
        ManagerName:"",
        address:"",
        regions:"",
        cities:"",
        provinces:"",
      },
      options: [
        { text: 'وضعیت انشار', value: true },
      ],
      fetchedData:{
        provinces:Array(),
        cities:Array(),
        Regions:Array(),
      },
      selectedValues:{
        selectedProvince: null,
        selectedCity: null,
        selectedRegion: null,
      },
    };
  },
  methods:{
    updateOneStore(){
      //if validation passed save informations
      let that = this;
      if (this.validationPasseed) {

        axios
          .post("http://fishopping.ir/serverHypernetShowUnion/adminProduct/webservices/stores/editOneStore.php",{
            ...that.selectedValues,
            ...that.store,
            updateOneStore:true
          })
          .then(function (response) {
            console.log(response);
            if (response.data && response.data.status == true) {
              //close edit modal
              that.$store.commit('stores/showModalOperation',{key:'edit',editing:false});

              //save updatedStore into stats
              that.$store.commit('stores/saveOneStoreIntoState',response.data.store)
              
              //show success notification
              that.$store.state.successNotification = {
                show: true,
                message: "فروشگاه با موفقیت اپدیت شد",
              };
              //close edit modal
              that.$store.state.stores.brandEditing = false;
              //open comfirm smsCode
            } else {
              that.$store.state.errorNotification = {
                show: true,
                message: "خطا، فروشگاه اپدیت نشد",
              };
            }
          })
          .catch(function (error) {
            that.$store.state.errorNotification = {
              show: true,
              message: "خطا، فروشگاه اپدیت نشد",
            };
            console.log(error);
          });
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
                } else {
                  that.fetchedData.provinces = Array();
                  //show error fetching provinces
                }

                //select province
                // this.selectedValues.provinceSelected
                

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
        return this.fetchedData.provinces.map((province)=>{
          return {
            key:province.id,
            text:province.name,
            selected: province.id = this.store.province
          }
        })
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
      return (this.titleValidation);
    },
    titleValidation() {
      return (
        this.store.title!=null && this.store.title.length > 3 && this.store.title.length < 30
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
    this.getAllProvinces_ajax()

  },
  updated() {


    
  },
  components:{
  },
  mounted() {
    let obj = this.$store.state.stores.editDataObject;
    this.store = obj
    this.selectedValues.selectedProvince= obj.province
    //get provinceCities
    this.getProvinceCities(obj.province)
    this.selectedValues.selectedCity= obj.city

    //get cityRegions
    this.getCitySelectedRegions(obj.city)
    this.selectedValues.selectedRegion = obj.RegionID
  }
};
</script>

<style lang="scss">
.form-editCompany * {
  direction: rtl !important;
  float: right;
  text-align: right;
}
</style>
