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
            console.log(response.data);
            if(response.data && response.data.status==true){
              //show success notification
              that.$store.state.successNotification.message= "شرکت با موفقیت حذف شد شد"
              that.$store.commit('showSuccess');
              that.$store.state.shwoConfirmSms = true;
              //close edit modal
              that.$store.state.companiescompanyEditing = false;
              //open comfirm smsCode

              //remove company from ui ram
              that.$store.state.companies.items = that.$store.state.companies.items.filter(item=>{
                if(item.id ==that.$store.state.companies.editDataObject.id){
                  return false;
                }else{
                  return true;
                }
              })
            }else{
              that.$store.state.errorNotification.message= "خطا، شرکت حذف نشد"
              that.$store.commit('showError')
            }
          })
          .catch(function(error){
            that.$store.state.errorNotification.message= "خطا، شرکت حذف نشد"
            that.$store.commit('showError')
            console.log(error)
          })
    }
  },
  computed: {
    
  },
}
</script>