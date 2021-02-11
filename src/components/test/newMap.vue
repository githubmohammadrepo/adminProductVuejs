<template>
  <div>
    <h5 class="text-center mb-3">مرحله ی سوم ثبت نام فروشگاه</h5>
    <b-form style="direction:rtl !important; text-align:right !important;">
      <!-- store lat -->
      <label for="latStoreLocation">طول جغرافیایی</label>
      <b-form-input
        v-model="Geolocation.lat"
        required
        type="text"
        id="latStoreLocation"
        :state="latValidation"
      ></b-form-input>
      <b-form-invalid-feedback :show="!latValidation">
        این فیلد الزامی است
      </b-form-invalid-feedback>
      <b-form-valid-feedback :show="latValidation">
        تایید شد
      </b-form-valid-feedback>
      <!-- end lat -->


      <!-- start lng -->
      <label for="lngStoreLocation" class="mt-1">عرض جغرافیایی</label>
      <b-form-input
        v-model="Geolocation.lng"
        required
        type="text"
        id="lngStoreLocation"
        :state="lngValidation"

      ></b-form-input>
       <b-form-invalid-feedback :show="!lngValidation">
        این فیلد الزامی است
      </b-form-invalid-feedback>
      <b-form-valid-feedback :show="lngValidation">
        تایید شد
      </b-form-valid-feedback>
      <!-- end store lat -->
    </b-form>
    <div class="mt-2"></div>
    <div class="row justify-content-center">

    </div>
    
    <!-- show map -->
    <div id="map" />

    <hr class="w-50">
    
    <div class="text-center">
      <b-button variant="info" class="m-auto" @click="saveStore">مرحله ی بعدی</b-button>
    </div>


  </div>
</template>

<script>

export default {

  data(){
    return {
      Geolocation:{
        lat:null,
        lng:null
      }
    }
  },
  methods: {
    saveStore(){
      this.Geolocation.lat = document.getElementById('latStoreLocation').value
      this.Geolocation.lng = document.getElementById('lngStoreLocation').value
      
      if(this.latValidation && this.lngValidation){  
        setTimeout(() => {
          this.$store.commit('stores/showAddNewStoreLevel',{key:'levelThree',value:false,formData:{},cancelInsert:true})
          this.$store.commit('stores/showAddNewStoreLevel',{key:'finalLevel',value:true,formData:{...this.Geolocation}})
        }, 700);
      }

    },
    getLatValue(e){
    }
  },
  computed: {
  latValidation(){
    return (this.Geolocation && this.Geolocation.lat && this.Geolocation.lat.toString().length) ? true : false
  },
  lngValidation(){
    return (this.Geolocation && this.Geolocation.lng && this.Geolocation.lng.toString().length) ? true : false
  }
  },
  updated() {
  },
   mounted() {
     //create map id map
      // let mapDiv = document.createElement('div')
      // mapDiv.setAttribute('id', 'map'
        var that = this;
        //vuejs 
        
     //add map script
      let mapScript = document.createElement('script')
      mapScript.setAttribute('src', 'http://192.168.1.35:8080/js/store/map.js')
      document.head.appendChild(mapScript)
      

      //using map script
      var myVar = setInterval(()=>{
        if(document.getElementById('map')){
                //add map styles
        let link = document.createElement('link')
        link.setAttribute('href', 'http://192.168.1.35:8080/css/store/map.css')
        link.setAttribute('rel', 'stylesheet')
        document.head.appendChild(link)
          //add script
        var map = initMap('map');
      
      // Change Center
      map.setCenter([51.39, 35.70]); 
      
      //Add Custom Marker
      var icon = createIcon('http://192.168.1.35:8080/img/store/map/marker-48.png',48,48);
      let lng = document.getElementById("lngStoreLocation").value;
      let lat = document.getElementById("latStoreLocation").value;
      var marker = AddMarker(map,[,lng,lat],icon,true);

      function onDragEnd() 
      {
        var lngLat = marker.getLngLat();
        document.getElementById("lngStoreLocation").value = lngLat.lng;
        document.getElementById("latStoreLocation").value = lngLat.lat;
      }
      marker.on('dragend', onDragEnd);
          //clear interval
          myStopFunction()
        }
      
      }, Infinity);


      function myStopFunction() {
        clearInterval(myVar);
      }


   
    },
    created() {
      this.Geolocation.lat = this.$store.getters['stores/getFormDataInserNewStoreLevels'].lat
      if(this.Geolocation.lat){

      }else{
        this.Geolocation.lat= 35.70056027
      }


      this.Geolocation.lng = this.$store.getters['stores/getFormDataInserNewStoreLevels'].lng
      if(this.Geolocation.lng){

      }else{
        this.Geolocation.lng = 51.39178988;
      }
    },

}
</script>


<style lang="scss">
  .mapboxgl-canvas{
        position:relative !important;
        width:100%;
        height:300px;
      }

      
</style>
