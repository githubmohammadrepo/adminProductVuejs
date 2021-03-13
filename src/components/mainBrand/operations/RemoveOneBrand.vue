<template>
  <div class="mt-4">
    <h5 class="text-center">آیا از حذف برند مورد نظر مطمئن هستین؟</h5>
    <div class="row justify-content-center mt-3 ">
      <b-button variant="info m-auto px-4" @click="removebrand">حذف برند</b-button>
    </div>
  </div>
</template>

<script>
import axios from 'axios';
export default {
  data(){
    return {
      
    }
  },
  methods: {
    removebrand(){
      
      let that= this;
       axios
          .post("http://fishopping.ir//serverHypernetShowUnion/adminProduct/webservices/mainBrands/removeOneBrand.php", {
            "removeBrand":true,
            "category_id":that.$store.state.brands.editDataObject.category_id,
          })
          .then(function(response){
            if(response.data && response.data.status==true){
              //show success notification
              that.$store.state.successNotification.message= "برند با موفقیت حذف شد شد"
              that.$store.commit('showSuccess')
              //close edit modal
              that.$store.state.brands.brandEditing = false;

              //remove editing brand or current brand from ui
              that.$store.commit('brands/removeOneCategory',that.$store.state.brands.editDataObject.category_id)
              //open comfirm smsCode
            }else{
              that.$store.state.errorNotification.message= "خطا، برند حذف نشد"
              that.$store.commit('showError')
            }
          })
          .catch(function(error){
            that.$store.state.errorNotification.message= "خطا، برند حذف نشد"
            that.$store.commit('showError')
            console.log(error)
          })
    }
  },
  computed: {
    
  },
}
</script>