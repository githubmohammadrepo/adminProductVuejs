<template>
  <div id="errorNofication">

    <b-modal  v-model="errorNoticationShow" id="bv-modal-ErrorNotification" hide-footer>
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
    errorNoticationShow(){
      return this.$store.state.errorNotification.show
    },
    message(){
      return this.$store.state.errorNotification.message
    },
    title(){
      return this.$store.state.errorNotification.title
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
</style>