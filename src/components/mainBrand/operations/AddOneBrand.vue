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
      <b-form-invalid-feedback :show="!brandNameValidation">
        تعداد کارکتر ها نباید کمتر از 3 کارکتر باشد
      </b-form-invalid-feedback>
      <b-form-valid-feedback :show="brandNameValidation">
        تایید شد
      </b-form-valid-feedback>

      <!-- brand name -->

      <!-- manager fullName -->
      <div class="row w-100 m-auto justify-content-center">
        <div class="p-0 col d-block py-2">
          <b-form-checkbox
            id="checkbox-1"
            v-model="company.published"
            name="checkbox-1"
            :value="true"
            :unchecked-value="false"
          >
            وضعیت انتشار
          </b-form-checkbox>
        </div>
      </div>

      <!-- Styled -->
      <b-form-file
        v-model="brand_logo"
        :state="true"
        placeholder="Choose a file or drop it here..."
        drop-placeholder="Drop file here..."
        accept="image/jpeg, image/png, image/gif"
        size="sm"
        class="my-2"
        @change="onChangeFileUpload"
      ></b-form-file>

      <div class="width-200px height-200px">
        <b-card-text> پیش نمایش عکس </b-card-text>
        <b-card
          :img-src="previewImageUrl"
          rounded="0"
          img-alt="Card image"
          img-top
        >
        </b-card>
      </div>
    </b-form>
    <hr class="w-100" />

    <div class="row w-100 justify-content-center pt-3">
      <b-button
        variant="primary m-auto px-4"
        :disabled="!validationPasseed"
        @click="saveCompnay"
        >ذخیره</b-button
      >
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
      },
      imageUrl:null,
      options: [
        { text: 'وضعیت انشار', value: true },
      ],
    };
  },
  methods:{
     onChangeFileUpload(e) {
      // const reader = new FileReader()
      // reader.readAsDataURL(this.brand_logo)
      // reader.onload = e => {
      //     this.image = e.target.result
      //     console.log(this.image)
      // }
      //create image url
      
      // best approch
      const file = e.target.files[0];
      this.brand_logo = file;
      this.imageUrl = URL.createObjectURL(file);
    },
    saveCompnay(){
      //if validation passed save informations
      if (this.validationPasseed) {
        console.log("update company");
        let that = this;
        console.log(that.company);

        //prepare data
        let data = new FormData();
        data.append('insertOneBrand', true);
        data.append('brand_name', this.company.brandName);
        data.append('published', this.company.published);
        data.append('brand_logo', this.brand_logo);
        axios
          .post(
            "http://fishopping.ir/serverHypernetShowUnion/adminProduct/webservices/mainBrands/insertNewBrand.php",
            data,
           {
              headers: {
                'Content-Type': 'multipart/form-data'
              }
           }
          )
          .then(function (response) {
            console.log(response);
            if (response.data && response.data.status == true) {
              //show success notification
              that.$store.state.successNotification = {
                show: true,
                message: "شرکت با موفقیت برند شد",
              };
              //close edit modal
              that.$store.state.brands.brandEditing = false;
              //open comfirm smsCode
            } else {
              that.$store.state.errorNotification = {
                show: true,
                message: "خطا، شرکت برند نشد",
              };
            }
          })
          .catch(function (error) {
            that.$store.state.errorNotification = {
              show: true,
              message: "خطا، شرکت برند نشد",
            };
            console.log(error);
          });
      }
    

    }
  },
  computed: {
    previewImageUrl(){
      if(this.imageUrl && this.imageUrl.toString().length){
        return this.imageUrl;
      }else{
        let mainPath= 'http://fishopping.ir/images/com_hikashop/upload/thumbnails/200x200f/'
        return mainPath+this.$store.state.brands.editDataObject.brand_logo
      }
    },
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
    this.company.published= false;
    this.company.brandName="";
    this.brand_logo= null;
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
