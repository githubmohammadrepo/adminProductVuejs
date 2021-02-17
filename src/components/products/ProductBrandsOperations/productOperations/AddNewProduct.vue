<template>
  <div>
    <b-modal v-model="modalShow" size="lg" id="OperationStoreModal" z-index="12">
      <h5 class="text-center">افزودن محصول جدید</h5>
      <!-- start form -->
      <b-form @submit="onSubmit($event)" @reset="onReset" v-if="show">
        <!-- select product category -->
        <b-form-group
          id="selectProductCategory"
          label="دسته بندی محصول را انتخاب کنید:"
          label-for="selectCategory"
          description="دسته بندی محصول را انتخاب بکنید"
        >
          <b-form-select
            id="selectCategory"
            v-model="newProductInfos.category"
            :options="productCategories"
            required
            @change="getProductSubCategory"
          ></b-form-select>
        </b-form-group>
        <!-- end product category -->

        <!-- select product subCategory -->
        <b-form-group
          id="selectProductSubCategory"
          label="زیر دسته بندی محصول را انتخاب کنید:"
          label-for="selectSubCategory"
          description="زیر دسته بندی محصول را انتخاب بکنید"
        >
          <b-form-select
            id="selectSubCategory"
            v-model="newProductInfos.subCategory"
            :options="productSubCategories"
            required
          ></b-form-select>
        </b-form-group>
        <!-- end select product subCategory -->


        <!-- select product brand -->
        <b-form-group
          id="selectProductbrand"
          label="برند محصول را انتخاب کنید:"
          label-for="selectbrand"
          description="برند محصول را انتخاب بکنید"
        >
          <b-form-select
            id="selectbrand"
            v-model="newProductInfos.brand"
            :options="productBrands"
            required
          ></b-form-select>
        </b-form-group>
        <!-- end select product brand -->

        <!-- start productNmae -->
        <b-form-group
          id="productName"
          label="نام محصول:"
          label-for="productName"
        >
          <b-form-input
            id="productName"
            v-model="newProductInfos.productName"
            placeholder="نام محصول ..."
            required
          ></b-form-input>
        </b-form-group>
        <!-- end productNmae -->

        <!-- start productCode -->
        <b-form-group
          id="productCode"
          label="کد محصول:"
          label-for="productCode"
        >
          <b-form-input
            id="productCode"
            v-model="newProductInfos.productCode"
            placeholder="کد محصول ..."
            required
          ></b-form-input>
        </b-form-group>
        <!-- end productCode -->
        
        <!-- start consumerProductPrice -->
        <b-form-group
          id="consumerProductPrice"
          label="قیمت مصرف کننده:"
          label-for="consumerProductPrice"
        >
          <b-form-input
            id="consumerProductPrice"
            v-model="newProductInfos.consumerProductPrice"
            placeholder="قیمت مصرف کننده ..."
            required
          ></b-form-input>
        </b-form-group>
        <!-- end consumerProductPrice -->

      <!-- start manufacturProductPrice -->
        <b-form-group
          id="manufacturProductPrice"
          label="قیمت کارخانه:"
          label-for="manufacturProductPrice"
        >
          <b-form-input
            id="manufacturProductPrice"
            v-model="newProductInfos.manufacturProductPrice"
            placeholder="قیمت کارخانه ..."
            required
          ></b-form-input>
        </b-form-group>
        <!-- end manufacturProductPrice -->


        <!-- start settleType -->
        <b-form-group
          id="settleType"
          label="نحوه تسویه حساب:"
          label-for="settleType"
        >
          <b-form-input
            id="settleType"
            v-model="newProductInfos.settleType"
            placeholder="نحوه تسویه حساب ..."
            required
          ></b-form-input>
        </b-form-group>
        <!-- end settleType -->

        <!-- start countInCategory -->
        <b-form-group
          id="countInCategory"
          label="تعداد در دسته بندی:"
          label-for="countInCategory"
        >
          <b-form-input
            id="countInCategory"
            v-model="newProductInfos.countInCategory"
            placeholder="تعداد در دسته بندی ..."
            required
          ></b-form-input>
        </b-form-group>
        <!-- end countInCategory -->


        <!-- start packageType -->
        <b-form-group
          id="packageType"
          label="نوع دسته بندی:"
          label-for="packageType"
        >
          <b-form-input
            id="packageType"
            v-model="newProductInfos.packageType"
            placeholder="نوع دسته بندی ..."
            required
          ></b-form-input>
        </b-form-group>
        <!-- end packageType -->


        <!-- start weightPackageType -->
        <b-form-group
          id="weightPackageType"
          label="وزن بسته بندی:"
          label-for="weightPackageType"
        >
          <b-form-input
            id="weightPackageType"
            v-model="newProductInfos.weightPackageType"
            placeholder="وزن بسته بندی ..."
            required
          ></b-form-input>
        </b-form-group>
        <!-- end weightPackageType -->

        <!-- start deliverTime -->
        <b-form-group
          id="deliverTime"
          label="مدت زمان تحویل کال:"
          label-for="deliverTime"
        >
          <b-form-input
            id="deliverTime"
            v-model="newProductInfos.deliverTime"
            placeholder="مدت زمان تحویل کال ..."
            required
          ></b-form-input>
        </b-form-group>
        <!-- end deliverTime -->

        <!-- start deliveDescription -->
        <b-form-group
          id="deliveDescription"
          label="توضیح برای نحوه تسویه حساب:"
          label-for="deliveDescription"
        >
          <b-form-input
            id="deliveDescription"
            v-model="newProductInfos.deliveDescription"
            placeholder="توضیح برای نحوه تسویه حساب ..."
            required
          ></b-form-input>
        </b-form-group>
        <!-- end deliveDescription -->

        <!-- start orderType -->
        <b-form-group
          id="orderType"
          label="نحوه سفارش کالا:"
          label-for="orderType"
        >
          <b-form-input
            id="orderType"
            v-model="newProductInfos.orderType"
            placeholder="نحوه سفارش کالا ..."
            required
          ></b-form-input>
        </b-form-group>
        <!-- end orderType -->

        <!-- Styled -->
        <b-form-file
          v-model="newProductInfos.productImage"
          :state="true"
          placeholder="عکس محصول را انتخاب کنید..."
          drop-placeholder="Drop file here..."
          accept="image/jpeg, image/png, image/gif"
          size="sm"
          class="my-2"
          @change="onChangeFileUpload"
          id="uploadProductImage"
        ></b-form-file>
        <b-row>
          <b-col>
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
          </b-col>
        </b-row>
        <hr class="pt-3">
        <b-row class="text-center">
          <b-col>
            <b-button type="submit" class="mx-2" variant="primary" @click="saveNewProduct">ذخیره محصول جدید</b-button>
            <b-button type="reset" class="mx-2" variant="danger">پاک کردن فرم</b-button>
          </b-col>
        </b-row>
      </b-form>

      <!-- end form -->
    <template #modal-footer>
        <div class="w-100">
          <!-- <p class="float-left">Modal Footer Content</p> -->
          <b-button variant="danger" size="sm" class="float-right" @click="modalShow=false">
            Close
          </b-button>
        </div>
      </template>
    </b-modal>
  </div>
</template>

<script>
import axios from 'axios'
export default {
  data() {
    return {
      newProductInfos: {
        category:'',
        subCategory:'',
        checked:true,
        name:"shnia",
        productImage:null,
        consumerProductPrice:null,
        manufacturProductPrice:null,
        settleType:null,
        countInCategory:null,
        packageType:null,
        weightPackageType:null,
        deliverTime:null,
        deliveDescription:null,
        productName:null,
        productCode:null,
        orderType:null,
        brand:null
      },
      show: true,
      imageUrl:null,
      brands:Array(),
      
    };
  },
  methods:{
    onSubmit(event) {
      event.preventDefault();
      // console.log(JSON.stringify(this.newProductInfos));
      this.saveNewProduct()
    },
    onReset(event) {
      event.preventDefault();
      // Reset our form values
      this.form.email = "";
      this.form.name = "";
      this.form.food = null;
      this.form.checked = [];
      // Trick to reset/clear native browser form validation state
      this.show = false;
      this.$nextTick(() => {
        this.show = true;
      });
    },
    getProductSubCategory(category_id){
      if(category_id){
        this.$store.dispatch('products/getAllSubCategories',category_id)
      }
    },
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
      this.newProductInfos.productImage = file;
      this.imageUrl = URL.createObjectURL(file);
    },
    //productBrands
    getAllBrands(){
      //if validation passed save informations
      let that = this;

      axios
        .post(
          "http://fishopping.ir/serverHypernetShowUnion/adminProduct/webservices/products/categoryProducts/getAllBrands.php",{
            "getAllBrands":true
          })
        .then(function (response) {
          if (response.data && response.data.status == true) {
            that.brands= response.data.brands
          }
        })
        .catch(function (error) {
          console.log(error);
        });
    },
    saveNewProduct(){
      //if validation passed save informations
        let that = this;
        //prepare data
        let data = new FormData();
        data.append("category",this.newProductInfos.category)
        data.append("subCategory",this.newProductInfos.subCategory)
        data.append("checked",this.newProductInfos.checked)
        data.append("name",this.newProductInfos.name)
        data.append("productImage",this.newProductInfos.productImage)
        data.append("consumerProductPrice",this.newProductInfos.consumerProductPrice)
        data.append("manufacturProductPrice",this.newProductInfos.manufacturProductPrice)
        data.append("settleType",this.newProductInfos.settleType)
        data.append("countInCategory",this.newProductInfos.countInCategory)
        data.append("packageType",this.newProductInfos.packageType)
        data.append("weightPackageType",this.newProductInfos.weightPackageType)
        data.append("deliverTime",this.newProductInfos.deliverTime)
        data.append("deliveDescription",this.newProductInfos.deliveDescription)
        data.append("productName",this.newProductInfos.productName)
        data.append("productCode",this.newProductInfos.productCode)
        data.append("orderType",this.newProductInfos.orderType)
        data.append("brand",this.newProductInfos.brand)
        data.append("insertNewProductCategory",true);
        axios
          .post(
            "http://fishopping.ir/serverHypernetShowUnion/adminProduct/webservices/products/categoryProducts/InsertNewProduct.php",
            data,
           {
              headers: {
                'Content-Type': 'multipart/form-data'
              }
           }
          )
          .then(function (response) {
            console.log(response)
            if (response.data && response.data.status == true) {
              //show success notification
              that.$store.state.successNotification = {
                show: true,
                message: "محصول با موفقیت ذخیره شد",
              };
              //close edit modal
              that.$store.state.brands.brandEditing = false;
              //open comfirm smsCode

              //add new product to view
            } else {
              that.$store.state.errorNotification = {
                show: true,
                message: "خطا، محصول ذخیره نشد",
              };
            }
          })
          .catch(function (error) {
            that.$store.state.errorNotification = {
              show: true,
              message: "خطا، محصول ذخیره نشد",
            };
            console.log(error);
          });
      }
    

  },
  computed: {
    productCategories(){
      let categories =  this.$store.state.products.items.map((category,index)=>{
       return {
          value:category.category_id,
          text:category.category_name
       }
      })
      categories.unshift({value:null,text:'دسته بندی محصول'})
      return categories
    },
    productSubCategories(){
      let subCategories = this.$store.state.products.categoriesSubCategorisPagination.categories.map((subCategory,index)=>{
        return {
          value:subCategory.category_id,
          text:subCategory.category_name
       }
      })
      subCategories.unshift({value:null,text:'زیر دسته بندی محصول'})
      return subCategories;
    },
    previewImageUrl(){
      if(this.imageUrl && this.imageUrl.toString().length){
        return this.imageUrl;
      }
    },
    productBrands(){
      let brands = this.brands.map(brand=>{
        return {
          value:brand.category_id,
          text: brand.category_name
        }
      })
      brands.unshift({value:null,text:'برند راانتخاب کنید'})
      return brands;
    },
    modalShow:{
      get(){
        return this.$store.state.products.BrandModalProductOperations.show
      },
      set(newValue){
        this.$store.state.products.BrandModalProductOperations.show=newValue
      }
    }
    
  },
  mounted() {
    //get main categories
    this.$store.dispatch('products/getAllCategoryProducts')
    this.getAllBrands()
  },
  created() {
  },
};
</script>

<style lang="scss" scoped>
#selectProductCategory,
#selectProductSubCategory,
#uploadProductImage,
#deliveDescription,
#consumerProductPrice,
#manufacturProductPrice,
#settleType,
#countInCategory,
#packageType,
#weightPackageType,
#deliverTime,
#productName,
#productCode,
#orderType,
#selectProductbrand
{
  text-align: right !important;
}


.width-200px{
  width:200px;
}
.height-200px{
  height: 200px;
}
</style>