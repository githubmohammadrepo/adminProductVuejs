<template>
  <div>
    <b-button @click="modalShow = !modalShow">Open Modal</b-button>

    <b-modal v-model="modalShow">
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
        <b-form-invalid-feedback :show="!brandNameValidation" >
          تعداد کارکتر ها نباید کمتر از 3 کارکتر باشد
        </b-form-invalid-feedback>
        <b-form-valid-feedback :show="brandNameValidation">
          تایید شد
        </b-form-valid-feedback>

        <!-- manager fullName -->
        
        <label for="mangerFullName">نام و نام خانوادگی مدیر شرکت</label>
        <b-form-input
          v-model="company.mangerFullName"
          :state="managerFullNameValidation"
          id="mangerFullName"
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
    </b-modal>
  </div>
</template>

<script>
export default {
  data() {
    return {
      modalShow: true,
      company: {
        companyName: "",
        brandName:"",
        mangerFullName: "",
        phone:"",
        mobile:"",
        address:""
      },
    };
  },
  computed: {
    companyNameValidation() {
      return (
        this.company.companyName.length > 3 &&
        this.company.companyName.length < 30
      );
    },
    managerFullNameValidation() {
      return (
        this.company.mangerFullName.length > 4 &&
        this.company.mangerFullName.length < 43
      );
    },

    phoneValidation() {
      return (
        this.company.phone.length > 4 &&
        this.company.phone.length < 43
      );
    }, 
    mobileValidation() {
      return (
        this.company.mobile.length > 4 &&
        this.company.mobile.length < 43
      );
    },
    addressValidation() {
      return (
        this.company.address.length > 4 &&
        this.company.address.length < 43
      );
    },
    brandNameValidation() {
      return (
        this.company.brandName.length > 4 &&
        this.company.brandName.length < 43
      );
    },
  },
};
</script>

<style lang="scss">
.form-editCompany * {
  direction: rtl !important;
  float: right;
  text-align: right;
}
</style>
