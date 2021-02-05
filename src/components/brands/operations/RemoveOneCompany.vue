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
      console.log(that.company)
       axios
          .post("http://fishopping.ir/serverHypernetShowUnion/adminProduct/webservices/InsertNewCompany.php", {
              addNewCompany:true,
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
              that.$store.state.companiescompanyEditing = false;
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
    }
  },
  computed: {
    
  },
}
</script>