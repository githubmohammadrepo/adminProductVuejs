<template>
  <div class="about">
    <StoreNavigations />
    <showAllRegions v-if="showAllRegions" />
    <FindStoresByFormFilters v-if="findByFormFilter"/>
    <ShowTableStores v-else />
    <OperationStoreModal />

  </div>
</template>

<script>
import showAllRegions from '@/components/stores/ShowAllRegions.vue'
import FindStoresByFormFilters from '@/components/stores/FindStoresByFormFilters.vue'
import StoreNavigations from '@/components/stores/StoreNavigations.vue';
import ShowTableStores from '@/components/stores/ShowTableStores.vue'
import OperationStoreModal from "@/components/stores/OperationStoreModal.vue";

export default {
  data(){
    return {
    }
  },
  methods:{

  },
  computed: {
    showAllRegions(){
      return this.$store.state.stores.storeShowComponents.showAllRegoins.show;
    },
    showTableFindedStore(){
      return this.$store.state.stores.storeShowComponents.showTableFindedStore.show;
    },
    findByFormFilter(){
      return  this.$store.state.stores.storeShowComponents.findByFormFilter.show ;
    }
  },
  components:{
    showAllRegions,
    FindStoresByFormFilters,
    StoreNavigations,
    ShowTableStores,
    OperationStoreModal,
    
  },
  created() {
    
  },
  mounted() {
    
  },
}
</script>
