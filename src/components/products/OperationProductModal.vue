<template>
  <div>
    <b-modal v-model="modalShow" size="lg" id="OperationStoreModal" z-index="12">
      <!-- edit one Store -->
      <EditOneStore v-if="editOperation" />
      <!-- delete on Store -->
      <RemoveOneStore v-if="removeOneStore" />
      <!-- add new Store -->
      <AddOneStore v-if="addOneStore" />

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
  import EditOneStore from "@/components/stores/operations/EditOneStore.vue";
  import RemoveOneStore from "@/components/stores/operations/RemoveOneStore";
  import AddOneStore from '@/components/stores/operations/AddOneStore.vue'

  export default {
    data() {
      return {};
    },
    methods: {
     
    },
    computed: {
      modalShow: {
        get() {
          // console.log(this.$store.state.products.brandEditing)
          return this.$store.state.products.storeEditing;
        },
        set(newValue) {
          this.$store.state.products.storeEditing = !this.$store.state.products.storeEditing;
        },
        // return false;
      },
      editOperation(){
        return this.$store.state.products.storeOperation.edit
      },
      removeOneStore(){
        return this.$store.state.products.storeOperation.remove
      },
      addOneStore(){
        return this.$store.state.products.storeOperation.add
      },
    },
    components: {
      EditOneStore,
      RemoveOneStore,
      AddOneStore,
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