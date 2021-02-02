<template>
  <div class="home">
    <h2>{{msg}}</h2>
    <ShwoBrands/>
  </div>
</template>

<script lang="ts">
import ShwoBrands from '@/components/brands/TableShowBrands.vue'; // @ is an alias to /src

export default{
  data(){
    return {
      msg:"اصلاح اطلاعات شرکت"
    }
  },
  components:{
    ShwoBrands
  }
}
</script>
