<template>
  <div class="home">
    <h2>{{msg}}</h2>
    <ShwoBrands/>
    <showBrandPagination />
    <EditOneCompany />
  </div>
</template>

<script lang="ts">
import ShwoBrands from '@/components/brands/TableShowBrands.vue'; // @ is an alias to /src
import showBrandPagination from '@/components/brands/ShowbrandsPagination.vue'; // @ is an alias to /src
import EditOneCompany from '@/components/brands/EditOneCompany.vue'; // @ is an alias to /src

import Vuex from 'vuex'
export default{
  data(){
    return {
      msg:"اصلاح اطلاعات شرکت"
    }
  },
  methods:{
    
  },
  components:{
    ShwoBrands,
    showBrandPagination,
EditOneCompany
  },
  created(){
  }
}
</script>
