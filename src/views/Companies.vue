<template>
  <div class="home">
    <h2>{{msg}}</h2>
    <ShwoBrands/>
    <showBrandPagination />
    <OperationCompanyModal />
  </div>
</template>

<script lang="ts">
import ShwoBrands from '@/components/brands/TableShowBrands.vue'; // @ is an alias to /src
import showBrandPagination from '@/components/brands/ShowbrandsPagination.vue'; // @ is an alias to /src
import OperationCompanyModal from '@/components/brands/operations/OperationCompanyModal'; // @ is an alias to /src

import Vuex from 'vuex'
export default{
  data(){
    return {
      msg:"اصلاح اطلاعات شرکت",

    }
  },
  methods:{
    
  },
  components:{
    ShwoBrands,
    showBrandPagination,
    OperationCompanyModal
  },
  created(){
  }
}
</script>
