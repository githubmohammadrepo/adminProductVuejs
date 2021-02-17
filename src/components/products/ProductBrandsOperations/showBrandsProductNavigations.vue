<template>
  <div>
    <!-- show navigations -->
      <div>
        <b-nav tabs align="center">
          <b-nav-item :active="showBrandProducts" @click="activeProucts">محصولات</b-nav-item>
          <b-nav-item :active="showSubBrands" @click="activeSubBrands">زیر برند ها</b-nav-item>
        </b-nav>
      </div>
    <!-- end show navigations -->



    <!-- show subbrands -->
    <showSubBrands v-if="showSubBrands" />

    <!-- show brand products -->
    <showBrandProducts v-if="showBrandProducts" />
  </div>
</template>

<script>
import showSubBrands from '@/components/products/ProductBrandsOperations/showSubBrands.vue'
import showBrandProducts from '@/components/products/ProductBrandsOperations/showBrandProducts'
export default {
  data() {
    return {

    }
  },
  methods: {
    activeProucts(){
      //hide all
      this.$store.state.products.brandProductNavigations.showSubBrands = false;
      //show porduct
      this.$store.state.products.brandProductNavigations.showBrandProducts =true
    },
    activeSubBrands(){
      //hide all
      this.$store.state.products.brandProductNavigations.showBrandProducts = false;

      //show product
      this.$store.state.products.brandProductNavigations.showSubBrands= true;
    }
  },
  computed: {
    showBrandProducts(){
      return this.$store.state.products.brandProductNavigations.showBrandProducts;
    },
    showSubBrands(){
      return this.$store.state.products.brandProductNavigations.showSubBrands;
    }
  },
  components:{
    showSubBrands,
    showBrandProducts 
  }
}
</script>