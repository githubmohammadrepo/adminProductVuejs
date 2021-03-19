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
      console.log('new store infos')
      console.log(addNewStoreInfos)
      //insert NewStore
      axios
        .post("http://fishopping.ir/serverHypernetShowUnion/adminProduct/webservices/stores/InserNewStore.php", {
            ...that.newStoreInfos,
            insertNewStore: true
        })
        .then(response => {
          console.log('response insert new stoer')
          console.log(response.data)
            if (response.data && response.data.status) {
              //show success notification
              that.$store.state.successNotification.message= "فروشگاه با موفقیت ذخیره شد"
              that.$store.commit('showSuccess')	
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
                //error user name level one
                that.$store.state.errorNotification.message= "نام کاربری تکراری است"
                that.$store.commit('showError')
              }else if(response.data.mobile){
                //error mobile dublicate level two
                that.$store.state.errorNotification.message= "شماره موبایل تکراری است"
                that.$store.commit('showError')

              }else{

                that.$store.state.errorNotification.message= "خطا، فروشگاه ذخیره نشد"
                that.$store.commit('showError')
              }
            }
        })
        .catch(error => {
            that.$store.state.errorNotification.message= "خطا، فروشگاه ذخیره نشد"
            that.$store.commit('showError')
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