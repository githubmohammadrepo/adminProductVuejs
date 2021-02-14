<template>
  <div>
    <showProductNavigation />
    <showCategoryProductDetails v-if="showProducts" />
    <showCategorySubCategoryDetails v-if="showBrands" />

  </div>
</template>

<script>
import showProductNavigation from '@/components/products/ProductCategoriesOperations/ShowProductNavigation.vue';
import showCategoryProductDetails from '@/components/products/ProductCategoriesOperations/showCategoryProductDetails.vue';
import showCategorySubCategoryDetails from '@/components/products/ProductCategoriesOperations/showCategorySubCategoryDetails.vue'
export default {
  data(){
    return {

    }
  },
  methods: {
    
  },
  computed: {
    showProducts:{
      get(){
        return this.$store.state.products.productOperation.show.showProductDetails.products.show
      },
      set(newValue){
        return this.$store.state.products.productOperation.show.showProductDetails.products.show = newValue
      }
    },
    showBrands:{
      get(){
        return this.$store.state.products.productOperation.show.showProductDetails.subCategories.show
      },
      set(newValue){
        return this.$store.state.products.productOperation.show.showProductDetails.subCategories.show=newValue
      }
    }
  },
  components:{
    showProductNavigation,
    showCategoryProductDetails,
    showCategorySubCategoryDetails
  },
  created(){
  }
}
</script>