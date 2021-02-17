<template>
  <div>
      <showBrandsProductNavigations v-if="brandProductOperation" />
      <showAllBrands v-else/>
  </div>
</template>


<script>
import showAllBrands from '@/components/products/ProductBrandsOperations/showAllBrands';
import showBrandsProductNavigations from '@/components/products/ProductBrandsOperations/showBrandsProductNavigations.vue';
export default {
  data(){
    return {

    }
  },
  methods: {
    
  },
  computed: {
    brandProductOperation(){
      return this.$store.state.products.BrandProductOperation
    }
  },
  components:{
    showAllBrands,
    showBrandsProductNavigations
  }
}
</script>