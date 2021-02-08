<template>
  <div>
    <b-modal v-model="modalShow" size="lg">
      <!-- edit one Brand -->
      <EditOneBrand v-if="editOperation" />
      <!-- delete on Brand -->
      <RemoveOneBrand v-if="removeOneBrand" />
      <!-- add new Brand -->
      <AddOneBrand v-if="addOneBrand" />

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
  import EditOneBrand from "@/components/mainBrand/operations/EditOneBrand.vue";
  import RemoveOneBrand from "@/components/mainBrand/operations/RemoveOneBrand.vue";
  import AddOneBrand from "@/components/mainBrand/operations/AddOneBrand.vue";

  export default {
    data() {
      return {};
    },
    methods: {
     
    },
    computed: {
      modalShow: {
        get() {
          console.log(this.$store.state.brands.brandEditing)
          return this.$store.state.brands.brandEditing;
        },
        set(newValue) {
          this.$store.state.brands.brandEditing = !this.$store.state.brands.brandEditing;
        },
        // return false;
      },
      editOperation(){
        return this.$store.state.brands.brandOperation.edit
      },
      removeOneBrand(){
        return this.$store.state.brands.brandOperation.remove
      },
      addOneBrand(){
        return this.$store.state.brands.brandOperation.add
      },
    },
    components: {
      EditOneBrand,
      RemoveOneBrand,
      AddOneBrand,
    },
  };
</script>

<style lang="scss">
  .form-editBrand * {
    direction: rtl !important;
    float: right;
    text-align: right;
  }
</style>