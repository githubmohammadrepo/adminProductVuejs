<template>
  <div class="mt-4">
    <h5 class="text-center">آیا از حذف شرکت مورد نظر مطمئن هستین؟</h5>
    <div class="row justify-content-center mt-3 ">
      <b-button variant="info m-auto px-4" @click="removeCompany">حذف شرکت</b-button>
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
    removeCompany(){
      
      let that= this;
      
       axios
          .post("http://fishopping.ir/serverHypernetShowUnion/adminProduct/webservices/removeOneCompany.php", {
            "removeCompnay":true,
            "code":"#&#(&#^(#&@%1!$7423974hfiehfe#$@!aife",
            "user_id":that.$store.state.companies.editDataObject.user_id
          })
          .then(function(response){
            if(response.data && response.data.status==true){
              //show success notification
              that.$store.state.successNotification = {
                show: true,
                message: "شرکت با موفقیت حذف شد شد",
              }
              that.$store.state.shwoConfirmSms = true;
              //close edit modal
              that.$store.state.companiescompanyEditing = false;
              //open comfirm smsCode
            }else{
              that.$store.state.errorNotification={
                show: true,
                message: "خطا، شرکت حذف نشد"
              }
            }
          })
          .catch(function(error){
            that.$store.state.errorNotification={
                show: true,
                message: "خطا، شرکت حذف نشد"
              }
            console.log(error)
          })
    }
  },
  computed: {
    
  },
}
</script>