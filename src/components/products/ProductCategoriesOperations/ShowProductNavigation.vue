<template>
  <div>
    <b-nav tabs align="center">
      <b-nav-item @click="showProductsEvent" :active="showProducts">محصولات</b-nav-item>
      <b-nav-item @click="showSubCategoriesEvent" :active="showBrands">زیر دسته بندی ها</b-nav-item>
      <b-nav-item>
        
      </b-nav-item>
      <b-nav-item disabled>Disabled</b-nav-item>
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
    showProductsEvent(){
      this.$store.commit('products/ChangeShowProductDetails',{key:'products',value:true})
    },
    showSubCategoriesEvent(){
      this.$store.commit('products/ChangeShowProductDetails',{key:'subCategories',value:true})
    }
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
  }
}
</script>