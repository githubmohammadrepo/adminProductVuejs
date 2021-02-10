<template>
  <div>
    <h5 class="text-center">مرحله ی نهایی ثبت شرکت</h5>

    <div class="text-center">
      <b-button variant="info" @click="SaveCompleteAddNewStore" class="mt-2">ثبت شرکت</b-button>
    </div>
  </div>
</template>

<script>
export default {
  data(){
    return {

    }
  },
  methods:{
    SaveCompleteAddNewStore(){
      //get all infos about add new store
      alert('complete')
      
      //ajax request by axios
    }
  },
  computed: {
    
  },
}
</script>