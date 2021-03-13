<template>
  <div>
        <!-- brand name -->
    <b-form class="form-editCompany" @submit.stop.prevent>
      <div class="form" v-if="!loading">
              <label for="brandName">نام برند</label>
              <b-form-input
                v-model="brandName"
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

              <!-- userName or brand owenername -->
              <label for="userName">نام صاحب برند</label>
              <b-form-input v-model="userName" :disabled="userId" id="userName" type="text"></b-form-input>

              <!-- published brands -->
              <div class="row w-100 m-auto justify-content-center">
                <div class="p-0 col d-block py-2">
                  <b-form-checkbox
                    id="checkbox-1"
                    v-model="published"
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
                <b-card-text>
                پیش نمایش عکس
                </b-card-text>
                <b-card :img-src="previewImageUrl" rounded="0"  img-alt="Card image" img-top>
              </b-card>
              </div>

            <hr class="w-100" />
      </div>
            </b-form>
      <div class="loading text-center" v-if="loading">
        <font-awesome-icon icon="spinner" class="h1" spin />
        <p class="text-warning">درحال درج اطلاعات...</p>
      </div>

    <div class="row col justify-content-center pt-3">
      <b-button
        variant="primary m-auto px-4"
        :disabled="!validationPasseed"
        @click="updateBrand"
        >ذخیره</b-button
      >
    </div>

      
  </div>
</template>

<script>
import axios from "axios";
export default {
  data() {
    return {
      brand_logo: null,
      // modalShow: true,
      imageUrl: null,
      data: null,
      loading:false
    };
  },
  methods: {
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
    updateBrand() {
      //if validation passed save informations
      if (this.validationPasseed) {
        let that = this;
        that.loading = true;
        //prepare data
        let data = new FormData();
        data.append('editOneBrand', true);
        data.append('brandName', this.brandName);
        data.append('fuserNameile', this.userName);
        data.append('user_id', this.$store.state.brands.editDataObject.user_id);
        data.append('brandId', this.$store.state.brands.editDataObject.category_id);
        data.append('published', this.published);
        data.append('brand_logo', this.brand_logo);
        axios
          .post(
            "http://fishopping.ir/serverHypernetShowUnion/adminProduct/webservices/mainBrands/editOneBrand.php",
            data,
           {
              headers: {
                'Content-Type': 'multipart/form-data'
              }
           }
          )
          .then(function (response) {
              that.loading = false;

            if (response.data && response.data.status == true) {
              //show success notification
              that.$store.state.successNotification.message= "شرکت با موفقیت اپدیت شد"
              that.$store.commit('showSuccess')
              //close edit modal
              that.$store.state.brands.brandEditing = false;
              //open comfirm smsCode
            } else {
              that.$store.errorNotification.message= "خطا، شرکت اپدیت نشد"
              that.$store.commit('showError')
              
            }
          })
          .catch(function (error) {
              that.loading = false;

            that.$store.errorNotification.message= "خطا، شرکت اپدیت نشد"
              that.$store.commit('showError')
            
            console.log(error);
          });
      } else {
      }
    },
    userId(){
      return this.$store.state.brands.editDataObject.userId
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
    validationPasseed() {
      return this.brandNameValidation;
    },
    brandNameValidation() {
      return (
        this.brandName != null &&
        this.brandName.length > 3 &&
        this.brandName.length < 30
      );
    },

    modalShow: {
      get() {
        return this.$store.state.brands.companyEditing;
      },
      set(newValue) {
        this.$store.state.brands.companyEditing = !this.$store.state.brands
          .companyEditing;
      },
      // return false;
    },
    published: {
      get() {
        return this.$store.state.brands.editDataObject.category_published
          ? true
          : false;
      },
      set(newVal) {
        this.$store.state.brands.editDataObject.category_published = newVal;
      },
    },
    brandName: {
      get() {
        return this.$store.state.brands.editDataObject.category_name;
      },
      set(newVal) {
        this.$store.state.brands.editDataObject.category_name = newVal;
      },
    },
    userName: {
      get() {
        this.$store.state.brands.editDataObject.user_name;
      },
      set(newVal) {
        this.$store.state.brands.editDataObject.user_name = newVal;
      },
    },
  },
  created() {
    let obj = this.$store.state.brands.editDataObject;
    this.company = obj;
  },
  updated() {
    let obj = this.$store.state.brands.editDataObject;
    this.company = obj;
  },
  components: {},
};
</script>

<style lang="scss">
.form-editCompany * {
  direction: rtl !important;
  float: right;
  text-align: right;
}

.width-200px{
  width:200px;
} 
.height-200px{
  height:200px;
}
</style>