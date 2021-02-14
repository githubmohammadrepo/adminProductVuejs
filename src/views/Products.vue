<template>
  <div>
    <ProductNavigations />
    <CategoryProducts v-if="showProductCategory" />
    <BrandCategory v-if="showProductBrand" />
  </div>
</template>

<script>
import ProductNavigations from '@/components/products/ProductsNavigaions.vue';
import CategoryProducts from '@/components/products/CategoryProducts.vue';
import BrandCategory from '@/components/products/BrandProducts.vue';
export default {
  data(){
    return {

    }
  },
  methods: {
    
  },
  computed: {
    showProductCategory(){
      return this.$store.state.products.productShowComponents.categoryProducts.show
    },
    showProductBrand(){
      return this.$store.state.products.productShowComponents.brandProducts.show
    },
  },
  components:{
    ProductNavigations,
    CategoryProducts,
    BrandCategory
  }
}
</script>
