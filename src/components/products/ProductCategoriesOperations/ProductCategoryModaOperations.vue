<template>
  <div>
    <b-modal v-model="modalShow" size="lg" id="OperationStoreModal" z-index="12">

        <!-- add new product -->
      <AddNewProduct v-if="showAddProduc" />
      <RemoveOneProduct v-else-if="showRemoveProduct" />
      <EditOneProduct v-else-if="showEditProduct" />
      <span v-else></span>

      
      <template #modal-footer>
        <div class="w-100">
          <!-- <p class="float-left">Modal Footer Content</p> -->
          <b-button variant="danger" size="sm" class="float-right" @click="modalShow=false">
            Close
          </b-button>
        </div>
      </template>
    </b-modal>
  </div>
</template>

<script>
  import axios from "axios";
  import AddNewProduct from '@/components/products/ProductCategoriesOperations/productOperations/AddNewProduct.vue';
  import RemoveOneProduct from '@/components/products/ProductCategoriesOperations/productOperations/RemoveOneProduct.vue'
  import EditOneProduct from '@/components/products/ProductCategoriesOperations/productOperations/EditOneProduct.vue'
  export default {
    data() {
      return {};
    },
    methods: {
     
    },
    computed: {
      modalShow: {
        get() {
          return this.$store.state.products.productEditing
        },
        set(newValue) {
          this.$store.state.products.productEditing =newValue;
        },
        // return false;
      },
      showAddProduc:{
        get(){
          return this.$store.state.products.productOperation.show.addNew
        },
        set(newValue){
          this.$store.state.products.productOperation.show.addNew=newValue
        }
      },
      showEditProduct:{
        get(){
          return this.$store.state.products.productOperation.edit.show
        },
        set(newValue){
          this.$store.state.products.productOperation.edit.show=newValue;
        }
      },
      showRemoveProduct:{
        get(){
          return this.$store.state.products.productOperation.remove.show;
        },
        set(newValue){
          this.$store.state.products.productOperation.remove.show=newValue;
        }
      }
      
    },
    components: {
      AddNewProduct,
      RemoveOneProduct,
      EditOneProduct
    },
  };
</script>

<style lang="scss">
  .form-editStore * {
    direction: rtl !important;
    float: right;
    text-align: right;
  }
  
</style>