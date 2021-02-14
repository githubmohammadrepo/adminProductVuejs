<template>
  <div>
      <ShowCategoryProducts v-if="showCategoryProducts" />
      <ShowTableProducts v-else />
  </div>
</template>


<script>
import ShowTableProducts from '@/components/products/ShowTableProducts.vue';
import ShowCategoryProducts from '@/components/products/ProductCategoriesOperations/ShowProductCategory.vue';
export default {
  data(){
    return {

    }
  },
  methods: {
    
  },
  computed: {
    showCategoryProducts(){
      return  this.$store.state.products.productOperation.show.show;
    }
  },
  components:{
    ShowTableProducts,
    ShowCategoryProducts
  },

}
</script>