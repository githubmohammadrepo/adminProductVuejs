<template>
  <div>
      <!-- company name -->
      <b-form class="form-editCompany" @submit.stop.prevent>
        
        <label for="brandName">نام برند</label>
        <b-form-input
          v-model="company.brandName"
          :state="brandNameValidation"
          id="brandName"
          required
          
          type="text"
        ></b-form-input>
        <b-form-invalid-feedback :show="!brandNameValidation" >
          تعداد کارکتر ها نباید کمتر از 3 کارکتر باشد
        </b-form-invalid-feedback>
        <b-form-valid-feedback :show="brandNameValidation">
          تایید شد
        </b-form-valid-feedback>

      <!-- brand name -->
        
        <label for="userName">نام صاحب برند</label>
        <b-form-input
          v-model="company.userName"
          :state="userNameValidation"
          id="userName"
          type="text"
        ></b-form-input>

        <!-- manager fullName -->
        <div class="row w-100 m-auto justify-content-center">
          <div class="p-0 col d-block py-2">
             <b-form-checkbox
                id="checkbox-1"
                v-model="published"
                name="checkbox-1"
                value="accepted"
                unchecked-value="not_accepted"
              >
                وضعیت انتشار
            </b-form-checkbox>
          </div>
        </div>

        <!-- Styled -->
    <b-form-file
      v-model="brand_logo"
      :state="Boolean(brand_logo)"
      placeholder="Choose a file or drop it here..."
      drop-placeholder="Drop file here..."
      accept="image/jpeg, image/png, image/gif"
      size="sm"
      class="my-2"
    ></b-form-file>

      </b-form>
        <hr class="w-100">

        <div class="row justify-content-center pt-3">
          <b-button variant="primary m-auto px-4" :disabled="!validationPasseed" @click="updateCompany">ذخیره</b-button>
        </div>

  </div>
</template>

<script>
import axios from 'axios'
export default {
  data() {
    return {
      brand_logo:"",
      // modalShow: true,
      company: {
        published:false,
        brandName:"",
        userName:"",
      },
      options: [
        { text: 'وضعیت انشار', value: true },
      ],
    };
  },
  methods:{
    updateCompany(){
      //if validation passed save informations
      if(this.validationPasseed){

        console.log('update company')
      let that= this;
      console.log(that.company)
       axios
          .post("http://fishopping.ir/serverHypernetShowUnion/adminProduct/webservices/updateCompany.php", {
              insertFakeUpdateConpany:true,
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
              that.$store.state.brands.brandEditing = false;
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
      return (this.brandNameValidation);
    },
    brandNameValidation() {
      return (
        this.company.brandName!=null && this.company.brandName.length > 3 && this.company.brandName.length < 30
      );
    },

    modalShow:{
      get(){
        return this.$store.state.brands.brandEditing
      },
      set(newValue){
        this.$store.state.brands.brandEditing=!this.$store.state.brands.brandEditing;
      }
      // return false;
    }
  },
  created() {
    let obj = this.$store.state.brands.editDataObject;
    console.log('created edit object')
    console.log(obj)
    this.company = obj
  },
  updated() {
     let obj = this.$store.state.brands.editDataObject;
    console.log('mounted edit object')
    this.company = obj
    console.log(this.company)
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
