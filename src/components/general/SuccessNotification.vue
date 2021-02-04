<template>
  <div id="errorNofication">

    <b-modal v-model="successNoticationShow"  id="bv-modal-successNotification" hide-footer>
      <div class="d-block text-center">
        <h6 class="text-success">{{message}}</h6>
      </div>
        <confirmSms v-if="shwoConfirmSms" />

    </b-modal>
  </div>
</template>

<script>
import confirmSms from '@/components/brands/confirmSms.vue';
  export default {
    data() {
      return {
      }
    },
    methods: {
      showSuccessNoticationModal() {
        $bvModal.show('bv-modal-successNotification')
      },
      hideSuccessNotificationModal() {
        $bvModal.hide('bv-modal-successNotification')
      },
      
    },
    computed: {
      successNoticationShow: {
        get() {

          return this.$store.state.successNotification.show
        },
        set(newValue) {
          this.$store.state.successNotification.show = newValue
        }
      },
      message() {
        return this.$store.state.successNotification.message
      },
      title() {
        return this.$store.state.successNotification.title
      },
      successBodyContent(){
        let bodyContent = this.$store.state.successNotification.bodyContent
        console.log(bodyContent)
        return bodyContent;
      },
      shwoConfirmSms(){
        return this.$store.state.shwoConfirmSms;
      }
    },
    components:{
      confirmSms
    }
  }
</script>

<style lang="scss" scoped>
  .text-ligthGreen {
    color: lightseagreen !important;
  }
</style>