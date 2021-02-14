<template>
  <div>
    <b-row>
      <b-col class="mt-2">
        <h5 class="text-center pb-0 pt-2">نمایش دسته بندی محصولات</h5>
        <hr class="w-25 mt-0 pt-0 bg-info">
      </b-col>
    </b-row>

      <!-- <b-form-checkbox v-model="stickyHeader" inline>Sticky header</b-form-checkbox>
      <b-form-checkbox v-model="noCollapse" inline>No border collapse</b-form-checkbox> -->
      <!-- @row-dblclicked="editOneBrand" -->
    <b-table
      :sticky-header="stickyHeader"
      :no-border-collapse="noCollapse"
      responsive
      :items="items"
      :fields="fields"
    >
      <template #cell(operation)="data">
        <div class="min-width-90px">
            <b-button  :variant="data.value.category_product_count!=0 ? 'info' : 'danger'" class="m-0 mx-1" @click="ShowCategoryProducts(data.value.category_id)" >نمایش محصولات</b-button>
        </div>

      </template>

      <!-- <template #cell(brand_logo)="data" class="m-0 p-0">
          <b-img left :src="'http://fishopping.ir/images/com_hikashop/upload/thumbnails/200x200f/'+data.value" rounded="circle" height="80px" class="m-0 p-0" alt="لوگوی برند"></b-img>
      </template> -->

      <!-- We are using utility class `text-nowrap` to help illustrate horizontal scrolling -->
      <template #head(category_id)="scope">
        <div class="text-nowrap p-0 m-0">Row ID</div>
      </template>

      <template #head()="scope">
        <div class="text-nowrap">
          {{ scope.label }}
        </div>
      </template>

      <!-- <template #cell(Address)="data" class="m-0 p-0">
          <div class="min-width-300px">
            {{data.value}}
          </div>
      </template> -->

    </b-table>


    <ShowProductPagination />

  </div>
</template>

<script>
import axios from "axios";
import ShowProductPagination from '@/components/products/ShowProductPagination.vue';

export default {
  data() {
    return {
      options: [
        { value: 20, text: 20 },
        { value: 50, text: 50 },
        { value: 100, text: 100 },
        { value: 500, text: 500 },
        { value: 1000, text: 1000 },
      ],
      getAllBrandOffset: 0,
      stickyHeader: true,
      noCollapse: false,
      fields: [
        {
          key: "operation",
          label: "عملیات",
          stickyColumn: true,
          isRowHeader: true,
          variant: "light",
          class: "px-0 mx-0",
        },
        { key: "category_id", label: "ایدی دسته بندی" },
        { key: "category_name", label: "نام دسته بندی" },
        { key: "category_published", label: "وضعیت انتشار" },
        { key: "category_parent_id", label: "نام دسته بندی پدر" },
        { key: "category_product_count", label: "تعداد محصولات" },

      ],
    };
  },
  methods: {
    ShowCategoryProducts(category_id) {
      //dispatch action get all categoryProducts
      //show component
      this.$store.state.products.productOperation.show.show=true;
      // this.$store.commit('products/changeShowProductCategory',{key:'categoryProducts',value:true})


      //console.log category saved
      this.$store.dispatch('products/getCategoriesProduct',category_id)
      this.$store.dispatch('products/getAllSubCategories',category_id)
      
    }
  },
  computed: {
    items() {
      return this.$store.state.products.items.map(category =>{
        return {
          ...category,
          operation:{category_id:category.category_id,category_product_count:category.category_product_count}
        }
      });
    },
  },
  created() {},
  components: {
    ShowProductPagination,

  },
};
</script>


<style lang="scss" scoped>
.danger{
  background: red !important;
}
table tr th {
  display: contents;
}
</style>