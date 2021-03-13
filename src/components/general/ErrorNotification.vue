<template>
  <div id="errorNofication">
    <b-modal  v-model="showModal" id="bv-modal-ErrorNotification" z-index="9999999999999999" hide-footer>
      <template #modal-header-close >
        <button type="button" aria-label="Close" class="close ml-0">×</button>
      </template>
      <template #modal-title>
      </template>
      <div class="d-block text-center">
        <h6 class="text-danger">{{message}}</h6>
      </div>
    </b-modal>
  </div>
</template>

<script>
export default {
  
  data(){
    return {
      showModal:false,
    }
  },
  methods:{
    showErrorNoticationModal() {
      $bvModal.show('bv-modal-ErrorNotification')
    },
    hideErrorNotificationModal(){
      $bvModal.hide('bv-modal-ErrorNotification')
    }
  },
  computed: {
    errorNoticationShow:{
      get(){
        return this.$store.state.errorNotification.show
      },
      set(value){
        this.$store.state.errorNotification.show=value;
      }
    },
    message(){
      return this.$store.state.errorNotification.message
    },
    title(){
      return this.$store.state.errorNotification.title
    }
  },
  watch: {
    errorNoticationShow(o,v){
     this.showModal = true;
    }
  },
}
</script>

<style lang="scss" scoped>

  
  *{

    direction:ltr;
  }
  .text-pink{
    color:orangered;
  }
   #bv-modal-ErrorNotification___BV_modal_outer_{
     background:red !important;
    z-index:99999999!important;
  }
</style>