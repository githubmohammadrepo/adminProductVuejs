<template>
  <div>
    <b-modal v-model="modalShow" size="lg" @ok="saveOperationCompany">
      <!-- navbar company operations -->
      <NavbarOperation />
      <!-- edit one company -->
      <EditOneCompany v-if="editOperation" />
      <!-- delete on company -->
      <RemoveOneCompany v-if="removeOneCompany" />
      <!-- add new company -->
      <AddOneCompany v-if="addOneCompany" />

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
  import EditOneCompany from "@/components/brands/operations/EditOneCompany.vue";
  import RemoveOneCompany from "@/components/brands/operations/RemoveOneCompany.vue";
  import AddOneCompany from "@/components/brands/operations/AddOneCompany.vue";
  import NavbarOperation from '@/components/brands/operations/NavOperation.vue'

  export default {
    data() {
      return {};
    },
    methods: {
      saveOperationCompany() {
        this.$store.dispatch('companies/saveCompanyOperation');
      }
    },
    computed: {
      modalShow: {
        get() {
          return this.$store.state.companies.companyEditing;
        },
        set(newValue) {
          this.$store.state.companies.companyEditing = !this.$store.state
            .companies.companyEditing;
        },
        // return false;
      },
      editOperation: {
        get() {
          return this.$store.state.companies.companyOperation.edit;
        },
      },
      removeOneCompany: {
        get() {
          return this.$store.state.companies.companyOperation.remove;
        }
      },
      addOneCompany: {
        get() {
          return this.$store.state.companies.companyOperation.add;
        }
      },
    },
    components: {
      EditOneCompany,
      RemoveOneCompany,
      AddOneCompany,
      NavbarOperation
    },
  };
</script>

<style lang="scss">
  .form-editCompany * {
    direction: rtl !important;
    float: right;
    text-align: right;
  }
</style>