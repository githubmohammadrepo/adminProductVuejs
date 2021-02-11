<template>
  <div>
    <h5 class="text-center mt-3">مرحله ی نهایی ثبت شرکت</h5>

    <div class="text-center">
      <b-button variant="info" @click="SaveCompleteAddNewStore" class="mt-2"
        >ثبت شرکت</b-button
      >
    </div>
  </div>
</template>

<script>
import axios from 'axios'
export default {
  data(){
    return {

    }
  },
  methods:{
    SaveCompleteAddNewStore(){
      let that = this;
      //get all infos about add new store
      let addNewStoreInfos = this.$store.getters['stores/getFormDataInserNewStoreLevels']
      //ajax request by axios

      //insert NewStore
      axios
        .post("http://fishopping.ir/serverHypernetShowUnion/adminProduct/webservices/stores/InserNewStore.php", {
            ...that.newStoreInfos,
            insertNewStore: true
        })
        .then(response => {
          console.log(response.data)
            if (response.data && response.data.status) {
              that.$store.state.successNotification = {
                show: true,
                message: "فروشگاه با موفقیت برند شد",
              };
              //clear  level one
              this.$store.commit('stores/showAddNewStoreLevel',{key:'levelOne',value:false,formData:{}})

              //clear  level two
              this.$store.commit('stores/showAddNewStoreLevel',{key:'levelTwo',value:false,formData:{}})

              //clear  level three
              this.$store.commit('stores/showAddNewStoreLevel',{key:'levelThree',value:false,formData:{}})
              
              //show level one
              this.$store.commit('stores/showAddNewStoreLevel',{key:'levelOne',value:true,formData:{}})
              

            } else {
              if(response.data.username){
                alert("username")
                //error user name level one
                that.$store.state.errorNotification = {
                  show: true,
                  message: "نام کاربری تکراری است",
                };
              }else if(response.data.mobile){
                alert('mobile')
                //error mobile dublicate level two
                console.log('before')
                console.log(that.$store.state.errorNotification)
                that.$store.state.errorNotification = {
                  show: true,
                  message: "شماره موبایل تکراری است",
                };

                console.log('before')
                console.log(that.$store.state.errorNotification )
              }else{

                that.$store.state.errorNotification = {
                  show: true,
                  message: "خطا، فروشگاه ذخیره نشد",
                };
              }
            }
        })
        .catch(error => {
            that.$store.state.errorNotification = {
              show: true,
              message: "خطا، فروشگاه ذخیره نشد",
            };
            console.log(error)
        })
    }
    
  },
  computed: {
    newStoreInfos(){
      return this.$store.getters['stores/getFormDataInserNewStoreLevels'];
    }
  },
}
</script>