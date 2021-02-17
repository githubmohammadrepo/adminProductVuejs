  
<template>
  <div>
    <b-modal v-model="modalShow" size="lg" id="OperationStoreModal" z-index="12">
      <!-- start form -->

      <section v-if="showSectionOne">
        <h6 class="text-center">آیا از حذف محصول مورد نظر مطمئن هستین؟</h6>
        <div class="text-center mt-3">
          <b-button variant="info" class="mx-1 px-3" @click="confirmRemoveProduct"
            >بله</b-button
          >
          <b-button variant="danger" class="mx-1 px-3" @click="hideModal"
            >خیر</b-button
          >
        </div>
      </section>

      <section v-if="showSectionTwo">
        <h6 class="text-center">تایید حذف محصول  مورد نظر</h6>
        <div class="text-center mt-3">
          <b-button variant="info" class="mx-1 px-3" @click="removeProduct"
            >حذف محصول</b-button
          >
          <b-button variant="danger" class="mx-1 px-3" @click="cancelRemoveProduct"
            >انصراف</b-button
          >
        </div>
      </section>
      <!-- end form -->
      <template #modal-footer>
        <div class="w-100">
          <!-- <p class="float-left">Modal Footer Content</p> -->
          <b-button variant="danger" size="sm" class="float-right" @click="closeModalShow">
            Close
          </b-button>
        </div>
      </template>
    </b-modal>
  </div>
</template>


<script>
export default {
  data() {
    return {
      showSectionOne: true,
      showSectionTwo: false,
    };
  },
  methods: {
    confirmRemoveProduct() {
      //show next step remove product
      this.showSectionOne = false;
      this.showSectionTwo = true;
    },
    hideModal() {
      //hide show modal edit one category product
      this.$store.commit("products/hideAllProductOperations");
      this.$store.state.products.productEditing = false;
    },
    removeProduct(){
      
      //get editing info
    let product_id = this.$store.state.products.productOperation.remove.removeProduct.product_id;
      //remove proccess
      this.$store.dispatch('products/RmoveOneProduct',product_id)
      //show notication
    },
    cancelRemoveProduct(){
      //cancel remove product
      this.hideModal()
    }
  },
  computed: {
    modalShow:{
      get(){
        return this.$store.state.products.BrandModalProductOperations.show
      },
      set(newValue){
        this.$store.state.products.BrandModalProductOperations.show=newValue
      }
    },},
  components: {},
};
</script>