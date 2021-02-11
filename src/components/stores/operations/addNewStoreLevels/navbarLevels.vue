<template>
    <div>
      <b-nav tabs align="center">
        <b-nav-item  @click="showLevelOne" :disabled="levelOneDisabled" :active="levelOneShow">مرحله اول</b-nav-item>
        <b-nav-item @click="showLevelTwo" :disabled="levelTwoDisabled" :active="levelTwoShow">مرحله دوم</b-nav-item>
        <b-nav-item @click="showLevelThree" :disabled="levelThreeDisabled" :active="levelThreeShow">مرحله سوم</b-nav-item>
        <b-nav-item @click="showLevelFour" :disabled="levelFourDisabled" :active="levelFourShow">مرحله ی نهایی</b-nav-item>
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
    showLevelOne(){
        this.$store.commit('stores/showAddNewStoreLevel',{key:'levelOne',value:true,formData:{},cancelInsert:true})
    },
    showLevelTwo(){
        this.$store.commit('stores/showAddNewStoreLevel',{key:'levelTwo',value:true,formData:{},cancelInsert:true})
    },
    showLevelThree(){
        this.$store.commit('stores/showAddNewStoreLevel',{key:'levelThree',value:true,formData:{},cancelInsert:true})
    },
    showLevelFour(){
        this.$store.commit('stores/showAddNewStoreLevel',{key:'finalLevel',value:true,formData:{},cancelInsert:true})
    },
  },
  computed: {
    levelOneDisabled(){
      return Object.keys(this.$store.state.stores.addNewStoreShowLevels.levelOne.formData).length ==0
    },
    levelTwoDisabled(){
      return Object.keys(this.$store.state.stores.addNewStoreShowLevels.levelTwo.formData).length ==0
    },
    levelThreeDisabled(){
      return Object.keys(this.$store.state.stores.addNewStoreShowLevels.levelThree.formData).length ==0
    },
    levelFourDisabled(){
      return this.levelOneDisabled || this.levelTwoDisabled || this.levelThreeDisabled
    },
    levelOneShow(){
      return this.$store.state.stores.addNewStoreShowLevels.levelOne.show
    },
    levelTwoShow(){
      return this.$store.state.stores.addNewStoreShowLevels.levelTwo.show
    },
    levelThreeShow(){
      return this.$store.state.stores.addNewStoreShowLevels.levelThree.show
    },
    levelFourShow(){
      return this.$store.state.stores.addNewStoreShowLevels.finalLevel.show
    }
    
  },
}
</script>