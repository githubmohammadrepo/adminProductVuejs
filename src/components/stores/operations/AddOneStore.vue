<template>
  <div>
    <addNewStoreLevelOne v-if="showLevelOne" />
    <addNewStoreLevelTwo v-if="showLevelTwo" />
    <addNewStoreLevelThree v-if="showLevelThree" />
    <AddNewStoreFinalLevel v-if="showFinalLevel" />
  </div>
</template>


<script>
import addNewStoreLevelOne from '@/components/stores/operations/addNewStoreLevels/AddNewStoreLevelOne.vue';
import addNewStoreLevelTwo from '@/components/stores/operations/addNewStoreLevels/AddNewStoreLevelTwo.vue';
import addNewStoreLevelThree from '@/components/stores/operations/addNewStoreLevels/AddNewStoreLevelThree.vue';
import AddNewStoreFinalLevel from '@/components/stores/operations/addNewStoreLevels/AddNewStoreFinalLevel.vue';

export default {
  data() {
    return {
      addNewStore:{
      }
    }
  },
  methods: {
    // showAddNewStoreLevel
  },
  computed: {
    showLevelOne:{
      get(){
        return this.$store.state.stores.addNewStoreShowLevels.levelOne.show
      },
      set(newValue){
        this.$store.commit('stores/showAddNewStoreLevel',{key:'levelOne',value:true,formData:null})
      }
    },
    showLevelTwo:{
      get(){
        return this.$store.state.stores.addNewStoreShowLevels.levelTwo.show
      },
      set(newValue){
        this.$store.commit('stores/showAddNewStoreLevel',{key:'levelTwo',value:true,formData:null})
      }
    },
    showLevelThree:{
      get(){
        return this.$store.state.stores.addNewStoreShowLevels.levelThree.show
      },
      set(newValue){
        this.$store.commit('stores/showAddNewStoreLevel',{key:'levelThree',value:true,formData:null})
      }
    },
    showFinalLevel:{
      get(){
        return this.$store.state.stores.addNewStoreShowLevels.finalLevel.show
      },
      set(newValue){
        this.$store.commit('stores/showAddNewStoreLevel',{key:'finalLevel',value:true,formData:null})
      }
    },
  },
  components:{
    addNewStoreLevelOne,
    addNewStoreLevelTwo,
    addNewStoreLevelThree,
    AddNewStoreFinalLevel
  }
}
</script>