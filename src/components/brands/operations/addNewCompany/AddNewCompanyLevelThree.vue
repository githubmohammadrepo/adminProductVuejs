<template>
  <div>
    <h5 class="text-center mt-3">مرحله ی نهایی فعال کردن شرکت</h5>
      <!-- user name -->
      <h6 class="text-center mb-3 mt-3" v-if="!saveCompanyCompleted">برای عضویت شرکت باید مبلغ {{priceRegisterCompany}} تومان را پرداخت نمایید.</h6>
      <h6 class="text-center mb-3 mt-3 text-success" v-if="saveCompanyCompleted">شرکت با موفقیت ثبت شد</h6>

      <div class="row justify-content-center mt-2">
        <b-button variant="primary m-auto px-4"  @click="saveCompany" v-if="!saveCompanyCompleted">فعال کردن</b-button>
        <b-button variant="primary m-auto px-4"  @click="gotToAddNewCompnayLevelOne" v-if="saveCompanyCompleted">افزودن شرکت جدید</b-button>
      </div>

  </div>
</template>

<script>
import axios from 'axios'
export default {
  data() {
    return {
      // modalShow: true,
      priceRegisterCompany:null,
      saveCompanyCompleted:false,
    };
  },
  methods:{
    saveCompany(){
      let that = this;
      
        
      let brandSelectedId = this.$store.state.companies.AddNewCompany.levelThree.brandSelectedId
      axios
      .post("http://fishopping.ir/serverHypernetShowUnion/adminProduct/webservices/verifyAddNewCompany.php",{
        "verifyCompany":true,
        "user_id":that.user_id,
        "code":"#&#(&#^(#&@!$7423974hfiehfe#$@!aife",
        "price":that.priceRegisterCompany,
        "brand_name":that.$store.state.companies.AddNewCompany.levelThree.brand_name,
        "brand_id":isNaN(that.$store.state.companies.AddNewCompany.levelThree.brand_id) ? 0 : that.$store.state.companies.AddNewCompany.levelThree.brand_id
      }).then(response => {
        
        if(response.data && response.data.status==true){
          that.saveCompanyCompleted=true;
          
        }else{
          that.$store.errorNotification={
            show: true,
            message: "خطا !!، اطلاعات شرکت ذخیره نشد"
          }
        }
      })
      .catch(error =>{
        that.$store.errorNotification={
          show: true,
          message: "خطا !!، اطلاعات شرکت ذخیره نشد"
        }
        console.log(error)
      })
    },
    getPricePayToVerifyCompany(){
      //if validation passed save informations

      let that= this;
       axios
          .post("http://fishopping.ir/serverHypernetShowUnion/adminProduct/webservices/InsertNewCompany.php",{
            getRegisterCompanyPrice:true
          })
          .then(function(response){
            if(response.data && response.data.status==true){
              that.priceRegisterCompany = response.data.price
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
      

    },
    gotToAddNewCompnayLevelOne(){
      //hide all
      this.$store.commit('companies/hideAllCreateNewCompnay')
      //show level three
      this.$store.commit('companies/showCompanyLevels','levelOne')
    }
  },
  computed: {
    user_id(){
      return this.$store.state.companies.AddNewCompany.levelTwo.userId
    }
  },
  components:{
  },
  created(){
    this.getPricePayToVerifyCompany()
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
