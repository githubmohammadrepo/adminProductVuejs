<template>
  <div>
    <h5>level Three</h5>
      <!-- user name -->
      <b-form class="form-editCompany" @submit.stop.prevent>
        <h5>level Two</h5>
        <label for="userName">نام کاربری</label>
        <b-form-input
          v-model="company.userName"
          :state="userNameValidation"
          id="userName"
          required
          type="text"
        ></b-form-input>
        <b-form-invalid-feedback :show="!userNameValidation" >
          تعداد کارکتر ها نباید کمتر از 3 کارکتر باشد
        </b-form-invalid-feedback>
        <b-form-valid-feedback :show="userNameValidation">
          تایید شد
        </b-form-valid-feedback>

      <!-- password -->
        <label for="password">رمز عبور شرکت</label>
        <b-form-input
          v-model="company.password"
          :state="passwordValidation"
          id="password"
          required
          
          type="text"
        ></b-form-input>
        <b-form-invalid-feedback :show="passwordValidation==false" >
          تعداد کارکتر ها نباید کمتر از 8 کارکتر باشد
        </b-form-invalid-feedback>
        <b-form-valid-feedback :show="passwordValidation==true">
          تایید شد
        </b-form-valid-feedback>

        <!-- retypePassword -->
        
        <label for="retypePassword">تکرار رمز عبور شرکت</label>
        <b-form-input
          v-model="company.retypePassword"
          :state="retypePasswordValidation"
          id="retypePassword"
          required
          
          type="text"
        ></b-form-input>
        <b-form-invalid-feedback :show="!retypePasswordValidation">
         رمز عبورو تکرار رمز عبور باید با هم برابر باشند
        </b-form-invalid-feedback>
        <b-form-valid-feedback :show="retypePasswordValidation">
          تایید شد
        </b-form-valid-feedback>

      </b-form>

        <div class="row justify-content-center">
          <b-button variant="primary m-auto px-4" :disabled="!validationPasseed" @click="saveCompany">مرحله ی بعدی</b-button>
        </div>

  </div>
</template>

<script>
import axios from 'axios'
export default {
  data() {
    return {
      // modalShow: true,
      company: {
        userName: "",
        password: "",
        retypePassword: "",
      },
    };
  },
  methods:{
    saveCompany(){
      //if validation passed save informations
      if(this.validationPasseed){

      let that= this;
      console.log(that.company)
       axios
          .post("http://fishopping.ir/serverHypernetShowUnion/adminProduct/webservices/InsertNewCompany.php", {
              addNewCompany:true,
              ...that.company
          })
          .then(function(response){
            console.log(response)
            if(response.data && response.data.status==true){
              //show success notification
              that.$store.state.successNotification = {
                show: true,
                message: "شرکت با موفقیت اپدیت شد",
              }
              that.$store.state.shwoConfirmSms = true;
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
      }else{

      }

    }
  },
  computed: {
    validationPasseed(){
      return (
        JSON.parse((this.userNameValidation && this.passwordValidation && this.retypePasswordValidation).toString()) ? true : false
      );
    },
    userNameValidation() {
      return (
        JSON.parse((this.company.userName!=null && this.company.userName.length > 3 && this.company.userName.length < 30).toString()) ? true: false
      );
    },
    passwordValidation() {
      return (
        JSON.parse((this.company.password!=null && this.company.password.toString().length > 7 &&  this.company.password.toString().length < 43).toString()) ? true : false
      );
    },

    retypePasswordValidation() {
      return (
        JSON.parse((this.company.retypePassword!=null && this.company.password.toString() ==this.company.retypePassword.toString() ).toString()) ? true : false
      );
    },

  },
  components:{
  }
};
</script>

<style lang="scss">
.form-editCompany * {
  direction: rtl !important;
  float: right;
  text-align: right;
}
</style>
