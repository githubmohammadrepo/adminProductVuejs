<template>
  <div class="home">
    <h2>{{msg}}</h2>
    <hr class="mb-3"

    <ShowMainBrandsTable />
    <ShowbrandsPagination />
  </div>
</template>

<script>

import ShowMainBrandsTable from '@/components/mainBrand/ShowTableMainBrands.vue'
import ShowbrandsPagination from '@/components/mainBrand/ShowBrandsPagination.vue'

export default {
  data(){
    return {
      msg:"اطلاح برندها"
    }
  },
  methods:{

  },
  computeds:{

  },
  components:{
    ShowMainBrandsTable,
    ShowbrandsPagination
  }
  
}
</script>
