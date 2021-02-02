<template>
  <div class="home">
    <h2>{{msg}}</h2>
    <ShwoBrands/>
  </div>
</template>

<script lang="ts">
import { Component, Vue } from 'vue-property-decorator';
import ShwoBrands from '@/components/brands/TableShowBrands.vue'; // @ is an alias to /src

export default class Brands extends Vue {
  data(){
    return {
      msg:"message"
    }
  }
}
</script>
