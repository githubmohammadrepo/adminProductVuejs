<template>
  <div>
    <h5 class="text-center mb-3">مرحله ی سوم ثبت نام فروشگاه</h5>

    <div class="row justify-content-center">
      <div class="col-12 text-center">
        <label for="latStoreLocation">طول جغرافیایی</label>&nbsp;&nbsp;
        <input type="number" v-model="Geolocation.lat" name="latStoreLocation" id="latStoreLocation"  step="0.00000000001">
      </div>

      <div class="col-12 text-center">
        <label for="lngStoreLocation">عرض جغرافیایی</label>&nbsp;&nbsp;
        <input type="number" v-model="Geolocation.lng" name="lngStoreLocation" id="lngStoreLocation" step="0.00000000000001">
      </div>
    </div>

    <!-- show map -->
    <div id="map" />

    <hr class="w-50">
    
    <div class="text-center">
      <b-button variant="info" class="m-auto">مرحله ی بعدی</b-button>
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
    
  },
  computed: {
    
  },
  updated() {
  },
   mounted() {
     //create map id map
      // let mapDiv = document.createElement('div')
      // mapDiv.setAttribute('id', 'map'

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
      var marker = AddMarker(map,[51.39178988, 35.70056027],icon,true);

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