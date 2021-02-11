<template>
  <div>
      <h5 class="text-center mt-3">مرحله ی اول ثبت نام فروشگاه</h5>

      <!-- user name -->
      <b-form class="form-editCompany" @submit.stop.prevent>
        
        <label for="userName">نام کاربری</label>
        <b-form-input
          v-model="store.userName"
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
          v-model="store.password"
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
          v-model="store.retypePassword"
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
      store: {
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
        //save info and go to level two
        this.$store.commit('stores/showAddNewStoreLevel',{key:'levelOne',value:false,formData:{...this.store}})
        this.$store.commit('stores/showAddNewStoreLevel',{key:'levelTwo',value:true,formData:{},cancelInsert:true})

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
        JSON.parse((this.store.userName!=null && this.store.userName.length > 3 && this.store.userName.length < 30).toString()) ? true: false
      );
    },
    passwordValidation() {
      return (
        JSON.parse((this.store.password!=null && this.store.password.toString().length > 7 &&  this.store.password.toString().length < 43).toString()) ? true : false
      );
    },

    retypePasswordValidation() {
      return (
        JSON.parse((this.store.retypePassword!=null && this.store.password.toString() ==this.store.retypePassword.toString() ).toString()) ? true : false
      );
    },

  },
  created(){

    this.store.userName = this.$store.getters['stores/getFormDataInserNewStoreLevels'].userName
    this.store.password = this.$store.getters['stores/getFormDataInserNewStoreLevels'].password
    this.store.retypePassword = this.$store.getters['stores/getFormDataInserNewStoreLevels'].retypePassword
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
