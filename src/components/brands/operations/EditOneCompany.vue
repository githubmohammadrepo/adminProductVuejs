<template>
  <div>
      <!-- company name -->
      <b-form class="form-editCompany" @submit.stop.prevent>
        
        <label for="companyName">نام شرکت</label>
        <b-form-input
          v-model="company.companyName"
          :state="companyNameValidation"
          id="companyName"
          required
          
          type="text"
        ></b-form-input>
        <b-form-invalid-feedback :show="!companyNameValidation" >
          تعداد کارکتر ها نباید کمتر از 3 کارکتر باشد
        </b-form-invalid-feedback>
        <b-form-valid-feedback :show="companyNameValidation">
          تایید شد
        </b-form-valid-feedback>

      <!-- brand name -->
        
        <label for="brandName">نام برند</label>
        <b-form-input
          v-model="company.brandName"
          :state="brandNameValidation"
          id="brandName"
          required
          
          type="text"
        ></b-form-input>
        <b-form-invalid-feedback :show="brandNameValidation==false" >
          تعداد کارکتر ها نباید کمتر از 3 کارکتر باشد
        </b-form-invalid-feedback>
        <b-form-valid-feedback :show="brandNameValidation==true">
          تایید شد
        </b-form-valid-feedback>

        <!-- manager fullName -->
        
        <label for="managerName">نام و نام خانوادگی مدیر شرکت</label>
        <b-form-input
          v-model="company.managerName"
          :state="managerFullNameValidation"
          id="managerName"
          required
          
          type="text"
        ></b-form-input>
        <b-form-invalid-feedback :show="!managerFullNameValidation">
          تعداد کارکتر ها نباید کمتر از 3 کارکتر باشد
        </b-form-invalid-feedback>
        <b-form-valid-feedback :show="managerFullNameValidation">
          تایید شد
        </b-form-valid-feedback>


        <!-- phone company -->
        
        <label for="phone">تلفن شرکت</label>
        <b-form-input
          v-model="company.phone"
          :state="phoneValidation"
          id="phone"
          required
          
          type="text"
        ></b-form-input>
        <b-form-invalid-feedback :show="!phoneValidation">
          تعداد کارکتر ها نباید کمتر از 3 کارکتر باشد
        </b-form-invalid-feedback>
        <b-form-valid-feedback :show="phoneValidation">
          تایید شد
        </b-form-valid-feedback>

         <!-- mobile company -->
        
        <label for="mobile">شماره همراه مدیر شرکت</label>
        <b-form-input
          v-model="company.mobile"
          :state="mobileValidation"
          id="mobile"
          required
          
          type="text"
        ></b-form-input>
        <b-form-invalid-feedback :show="!mobileValidation">
          تعداد کارکتر ها نباید کمتر از 3 کارکتر باشد
        </b-form-invalid-feedback>
        <b-form-valid-feedback :show="mobileValidation">
          تایید شد
        </b-form-valid-feedback>

        
         <!-- address company -->
        
        <label for="address">آدرس شرکت</label>
        <b-form-input
          v-model="company.address"
          :state="addressValidation"
          id="address"
          required
          type="text"
        ></b-form-input>
        <b-form-invalid-feedback :show="!addressValidation">
          تعداد کارکتر ها نباید کمتر از 3 کارکتر باشد
        </b-form-invalid-feedback>
        <b-form-valid-feedback :show="addressValidation">
          تایید شد
        </b-form-valid-feedback>
      </b-form>

        <div class="row justify-content-center">
          <b-button variant="primary m-auto px-4" :disabled="!validationPasseed" @click="updateCompany">ذخیره</b-button>
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
        address: "",
        brandImage: "",
        brandName: "",
        companyName: "",
        id: "",
        managerName: "",
        mobile: "",
        operation: "",
        phone: "",
      },
    };
  },
  methods:{
    updateCompany(){
      //if validation passed save informations
      if(this.validationPasseed){

      let that= this;
       axios
          .post("http://fishopping.ir/serverHypernetShowUnion/adminProduct/webservices/updateCompany.php", {
              insertFakeUpdateConpany:true,
              ...that.company
          })
          .then(function(response){
            if(response.data && response.data.status==true){
              //show success notification
              that.$store.state.successNotification.message= "شرکت با موفقیت اپدیت شد"
              that.$store.commit('showSuccess')
              that.$store.state.shwoConfirmSms = true;
              //close edit modal
              that.$store.state.companiescompanyEditing = false;
              //open comfirm smsCode
            }else{
              that.$store.errorNotification.message= "خطا، شرکت اپدیت نشد"
              that.$store.commit('showError')
            }
          })
          .catch(function(error){
            that.$store.errorNotification.message= "خطا، شرکت اپدیت نشد"
            that.$store.commit('showError')
            console.log(error)
          })
      }else{

      }

    }
  },
  computed: {
    validationPasseed(){
      return (this.companyNameValidation && this.managerFullNameValidation && this.phoneValidation && this.mobileValidation && this.addressValidation && this.brandNameValidation);
    },
    companyNameValidation() {
      return (
        this.company.companyName!=null && this.company.companyName.length > 3 && this.company.companyName.length < 30
      );
    },
    managerFullNameValidation() {
      return (
        this.company.managerName!=null && this.company.managerName.toString().length > 4 &&  this.company.managerName.toString().length < 43
      );
    },

    phoneValidation() {
      return (
        this.company.phone!=null && this.company.phone.toString().length > 4 && this.company.phone.toString().length < 43
      );
    }, 
    mobileValidation() {
      return (
        this.company.mobile!=null && this.company.mobile.toString().length > 4 && this.company.mobile.toString().length < 43
      );
    },
    addressValidation() {
      return (
        this.company.address!=null && this.company.address.toString().length > 4 && this.company.address.toString().length < 43
      );
    },
    brandNameValidation() {
      return (
        this.company.brandName!=null && this.company.brandName.toString().length > 4 && this.company.brandName.toString().length < 43
      );
    },
    modalShow:{
      get(){
        return this.$store.state.companies.companyEditing
      },
      set(newValue){
        this.$store.state.companies.companyEditing=!this.$store.state.companies.companyEditing;
      }
      // return false;
    }
  },
  created() {
    let obj = this.$store.state.companies.editDataObject;
    this.company = obj
  },
  updated() {
     let obj = this.$store.state.companies.editDataObject;
    this.company = obj
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
