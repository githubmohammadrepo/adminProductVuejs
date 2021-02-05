<template>
  <div>
    <b-form class="form-editCompany" @submit.stop.prevent>
      <label for="mobile">کد تایید پیامکی</label>
        <b-form-input
          v-model="sentSms"
          :state="smsCodeConfirmValidation"
          id="mobile"
          required
          
          type="number"
        ></b-form-input>
        <b-form-invalid-feedback :show="!smsCodeConfirmValidation">
          تعداد کارکتر ها نباید کمتر از 4 کارکتر باشد
        </b-form-invalid-feedback>
        <b-form-valid-feedback :show="smsCodeConfirmValidation">
          تایید شد
        </b-form-valid-feedback>
    </b-form>

       <div class="row justify-content-center">
         <b-button class="my-3 text-center" @click="sentSmsConfirm" variant="danger">تایید</b-button>
       </div>
  </div>
</template>

<script>
import axios from 'axios'
  export default {
    data() {
      return {
        sentSms:null
      }
    },
    methods: {
       
      sentSmsConfirm(){
        alert('clicked')
        if(this.smsCodeConfirmValidation==true){
          let that = this;
           axios
          .post("http://fishopping.ir/serverHypernetShowUnion/adminProduct/webservices/updateCompany.php", {
              confirmSmsCode:true,
              regCode:that.sentSms,
              id:this.$store.state.companies.editDataObject.id,
              user_id:this.$store.state.companies.editDataObject.user_id,
              
          })
          .then(function(response){
            console.log(response)
            if(response.data && response.data.status==true){
              //show success notification
              that.$store.state.successNotification = {
                show: true,
                message: "شرکت با موفقیت اپدیت شد",
              }
              that.$store.state.shwoConfirmSms = false;
              //close edit modal
              that.$store.state.companiescompanyEditing = false;
              //open comfirm smsCode
            }else{
              that.$store.errorNotification={
                show: true,
                message: "خطا، شرکت اپدیت نشد"
              }
            }
          })
          .catch(function(error){
            that.$store.errorNotification={
              show: true,
              message: "خطا، شرکت اپدیت نشد"
            }
            console.log(error)
          })
        }
      },
    },
    computed: {
      smsCodeConfirmValidation(){
        return (this.sentSms !=null && this.sentSms.toString().length== 5);
      },
    },

  }
</script>

<style lang="scss" scoped>
  *{
    direction:rtl;
  }
</style>