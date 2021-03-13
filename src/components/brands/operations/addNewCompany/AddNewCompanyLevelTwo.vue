<template>
  <div>
      <h5 class="text-center mt-3">مرحله ی دوم ثبت نام شرکت</h5>
      <b-form class="form-editCompany" @submit.stop.prevent>

        <!-- company name -->
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

      <!-- manager name -->
        <label for="managerName">نماینده یا مالک شرکت</label>
        <b-form-input
          v-model="company.managerName"
          :state="managerNameValidation"
          id="managerName"
          required
          
          type="text"
        ></b-form-input>
        <b-form-invalid-feedback :show="managerNameValidation==false" >
          تعداد کارکتر ها نباید کمتر از 8 کارکتر باشد
        </b-form-invalid-feedback>
        <b-form-valid-feedback :show="managerNameValidation==true">
          تایید شد
        </b-form-valid-feedback>

        
        <!-- company phone number -->
        <label for="companyPhone">شماره تماس شرکت</label>
        <b-form-input
          v-model="company.companyPhone"
          :state="companyPhoneValidation"
          id="companyPhone"
          required
          
          type="text"
        ></b-form-input>
        <b-form-invalid-feedback :show="!companyPhoneValidation">
         رمز عبورو تکرار رمز عبور باید با هم برابر باشند
        </b-form-invalid-feedback>
        <b-form-valid-feedback :show="companyPhoneValidation">
          تایید شد
        </b-form-valid-feedback>


      <!-- compnay mobile number -->
      <label for="companyMobile">شماره موبال شرکت</label>
        <b-form-input
          v-model="company.companyMobile"
          :state="companyMobileValidation"
          id="companyMobile"
          required
          
          type="text"
        ></b-form-input>
        <b-form-invalid-feedback :show="!companyMobileValidation">
         رمز عبورو تکرار رمز عبور باید با هم برابر باشند
        </b-form-invalid-feedback>
        <b-form-valid-feedback :show="companyMobileValidation">
          تایید شد
        </b-form-valid-feedback>


      <!-- company fax number -->
      <label for="companyFax">شماره فکس</label>
        <b-form-input
          v-model="company.companyFax"
          :state="companyFaxValidation"
          id="companyFax"
          required
          type="text"
        ></b-form-input>
        <b-form-invalid-feedback :show="!companyFaxValidation">
         رمز عبورو تکرار رمز عبور باید با هم برابر باشند
        </b-form-invalid-feedback>
        <b-form-valid-feedback :show="companyFaxValidation">
          تایید شد
        </b-form-valid-feedback>

        <!-- company email -->
        <label for="companyEmail">ایمیل شرکت</label>
        <b-form-input
          v-model="company.companyEmail"
          :state="companyEmailValidation"
          id="companyEmail"
          required
          
          type="text"
        ></b-form-input>
        <b-form-invalid-feedback :show="!companyEmailValidation">
         رمز عبورو تکرار رمز عبور باید با هم برابر باشند
        </b-form-invalid-feedback>
        <b-form-valid-feedback :show="companyEmailValidation">
          تایید شد
        </b-form-valid-feedback>

        <b-form-input 
          id="selectBrandCompanyInput"
          list="selectBrandCompany" 
          v-model="company.brandName" 
          @change="searchBrandId"
          :state="selectBrandCompanyInputValidation"
          required
           v-if="!loadingBrands"
        >
        </b-form-input>
        <div class="w-100  row text-center  justify-content-center" v-if="loadingBrands">
          <div class="text-center">
            <b-spinner type="grow" class="m-auto" label="Spinning"></b-spinner>
            <span>در حال لود کردن برند ها</span>
          </div>
        </div>

        <b-form-invalid-feedback :show="!selectBrandCompanyInputValidation">
         نام برند نباید خالی باشد
        </b-form-invalid-feedback>
        <b-form-valid-feedback :show="selectBrandCompanyInputValidation">
          تایید شد
        </b-form-valid-feedback>
           
        <datalist id="selectBrandCompany" v-if="!loadingBrands">
          <option v-for="brand in company.brands">{{ brand.category_name.toString().trim() }}</option>
        </datalist>

      </b-form>
        <div class="row justify-content-center mt-4">
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
        brand_id:null,
        brands: [],
        brandName:"",
        companyFax:"",
        companyEmail:"",
        companyMobile:"",
        companyPhone:"",
        managerName:"",
        companyName:"",
      },
      loadingBrands:false,

    };
  },
  methods:{
    searchBrandId(){
      this.company.brand_id=null;
      for(let i =0;i<this.company.brands.length;i++){
        if(this.company.brands[i].category_name.toString().trim()==this.company.brandName.toString().trim()){
          this.company.brand_id = this.company.brands[i].category_id
          break;
        }
      };
      if(this.company.brand_id !=null){
        this.$store.state.companies.AddNewCompany.levelThree.brandSelectedId = this.company.brandName ? this.company.brand_id : "";
      }
    },
    getAllBrands(){
      //if validation passed save informations
      let that= this;
      that.loadingBrands=true;
       axios
          .get("http://fishopping.ir/serverHypernetShowUnion/adminProduct/webservices/getAllBrands.php",{
             responseType: 'json', // default
          })
          .then(function(response){
            that.loadingBrands=false;

            if(response.data && response.data.status==true){
              //show success notification
              that.$store.state.successNotification.message= "مرحله ی دوم ثبت نام با موفقیت ذخیره شد"
              that.$store.commit('showSuccess');

              //close edit modal
              that.company.brands = response.data.brands;
              //open comfirm smsCode
            }else{
              that.$store.state.errorNotification.message= "خطا در گرفتن اطلاعات برند ها"
              that.$store.commit('showError');
              
            }
          })
          .catch(function(error){
            that.loadingBrands=false;
            that.$store.state.errorNotification.message= "خطا، شرکت اپدیت نشد"
            that.$store.commit('showError');
              
            console.log(error)
          })
    

    },
    saveCompany(){
      let that = this;
      axios
        .post("http://fishopping.ir/serverHypernetShowUnion/adminProduct/webservices/InsertNewCompany.php",{  
          "insertNewCompanyLeveltwo": true,
          "brand_id": parseInt(that.company.brand_id),
          "brand_name": that.company.brandName,
          "company_name": that.company.companyName,
          "manager_name": that.company.managerName,
          "company_phone": that.company.companyPhone,
          "company_mobile": that.company.companyMobile,
          "company_email": that.company.companyEmail,
          "company_fax": that.company.companyFax,
          "user_id": that.userId,
          user_name: that.userName
        }).then(response => {
          console.log(response)
          if(response && response.data && response.data.status==true){
            //hide all
            that.$store.commit('companies/hideAllCreateNewCompnay')
            //show level three
            that.$store.commit('companies/showCompanyLevels','levelThree')
            //store brand_id
            that.$store.state.companies.AddNewCompany.levelThree.brand_id=isNaN(parseInt(that.company.brand_id)) ? 0 : parseInt(that.company.brand_id)
            that.$store.state.companies.AddNewCompany.levelThree.brand_name= that.company.brandName
            //store brand_name

            that.$store.state.successNotification = {
              show: true,
              message: "مرحله ی دوم ثبت نام با موفقیت ذخیره شد",
            }
          }else{
            alert('no'+ that.$store.state.errorNotification.show)
            that.$store.state.errorNotification={
              show: true,
              message: "خطا !!، اطلاعات شرکت ذخیره نشد"
            }
          }
        })
        .catch(error=>{
          that.$store.state.errorNotification={
            show: true,
            message: "خطا !!، اطلاعات شرکت ذخیره نشد"
          }
          console.log(error)
        })
    },
    isNumberValidation(inputName){
      return inputName.length ==(String(parseInt(inputName))=="NaN" ? "": String(parseInt(inputName))).length
    },
    showError(){
      this.$store.state.errorNotification.show = !this.$store.state.errorNotification.show;
    }
  },
  computed: {
    validationPasseed(){
      return (
        JSON.parse((this.selectBrandCompanyInputValidation && this.companyNameValidation && this.companyFaxValidation && this.companyEmailValidation && this.managerNameValidation && this.companyPhoneValidation && this.companyMobileValidation).toString()) ? true : false
      );
    },

    selectBrandCompanyInputValidation(){
      return (
        JSON.parse((this.company.brandName!=null && this.company.brandName.length > 1 && this.company.brandName.length < 45).toString()) ? true: false
      );
    },

    companyNameValidation() {
      return (
        JSON.parse((this.company.companyName!=null && this.company.companyName.length > 3 && this.company.companyName.length < 30).toString()) ? true: false
      );
    },

    companyFaxValidation() {
      return (
        JSON.parse((this.company.companyFax!=null && this.company.companyFax.toString().length>3 && (this.isNumberValidation(this.company.companyFax))).toString()) ? true : false
      );
    },

    companyEmailValidation() {
      return (
        JSON.parse((this.company.companyEmail!=null && this.company.companyEmail.length > 3 && this.company.companyEmail.length < 30).toString()) ? true: false
      );
    },
    managerNameValidation() {
      return (
        JSON.parse((this.company.managerName!=null && this.company.managerName.toString().length > 7 &&  this.company.managerName.toString().length < 43).toString()) ? true : false
      );
    },
    companyPhoneValidation() {
      return (
        JSON.parse((this.company.companyPhone!=null && this.company.companyPhone.toString().length>3 && (this.isNumberValidation(this.company.companyPhone))).toString()) ? true : false
      );
    },
    companyMobileValidation() {
      return (
        JSON.parse((this.company.companyMobile!=null && this.company.companyMobile.toString().length>3 && (this.isNumberValidation(this.company.companyMobile))).toString()) ? true : false
      );
    },
    userName(){
      return this.$store.state.companies.AddNewCompany.levelOne.userName
    },
    userId(){
      return this.$store.state.companies.AddNewCompany.levelTwo.userId
    },
    

  },
  components:{
  },
  mounted(){
    //load all brands
    this.getAllBrands();
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
