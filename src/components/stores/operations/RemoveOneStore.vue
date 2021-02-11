<template>
  <div class="mt-4">
    <h5 class="text-center">آیا از حذف فروشگاه مورد نظر مطمئن هستین؟</h5>
    <div class="row justify-content-center mt-3 ">
      <b-button variant="info m-auto px-4" @click="removeOneStore">حذف برند</b-button>
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
    removeOneStore(){
      
      let that= this;
       axios
          .post("http://fishopping.ir//serverHypernetShowUnion/adminProduct/webservices/stores/removeOneStore.php", {
            "removeOneStore":true,
            "user_id":that.$store.state.stores.editDataObject.user_id,
            "store_id":that.$store.state.stores.editDataObject.store_id,
          })
          .then(function(response){
            console.log(response)
            if(response.data && response.data.status==true){
              //close modal remove
              that.$store.commit('stores/showModalOperation',{key:'remove',editing:false});

              //remove store id from state
              that.$store.commit('stores/removeOneStoreFromState',that.$store.state.stores.editDataObject.store_id)
              
              //show success notification
              that.$store.state.successNotification = {
                show: true,
                message: "فروشگاه با موفقیت حذف شد شد",
              }
              //close edit modal
              that.$store.state.brands.brandEditing = false;

              //remove editing brand or current brand from ui
              that.$store.commit('brands/removeOneCategory',that.$store.state.brands.editDataObject.category_id)
              //open comfirm smsCode
            }else{
              that.$store.state.errorNotification={
                show: true,
                message: "خطا، فروشگاه حذف نشد"
              }
            }
          })
          .catch(function(error){
            that.$store.state.errorNotification={
                show: true,
                message: "خطا، فروشگاه حذف نشد"
              }
            console.log(error)
          })
    }
  },
  computed: {
    
  },
}
</script>