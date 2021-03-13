<template>
  <div>
  <b-nav tabs align="center">
    <b-nav-item :active="showProductCategory" @click="showCategoryProduct">دسته بندی ها</b-nav-item>
    <b-nav-item :active="showProductBrand" @click="showBrandProduct">برند ها</b-nav-item>
  </b-nav>
</div>
</template>

<script>
export default {
  data(){
    return {

    }
  },
  methods: {
    showCategoryProduct(){
      this.$store.commit('products/changeShowProductCategory',{key:'categoryProducts',value:true})
      this.$store.state.products.productOperation.show.show=false;
    },
    showBrandProduct(){
      this.$store.commit('products/changeShowProductCategory',{key:'brandProducts',value:true})
      this.$store.state.products.BrandProductOperation = false;

    },
    
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

  }
}
</script>
