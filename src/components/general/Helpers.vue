<template>
<div>
  <ErrorNotification />
  <SuccessNotification />
</div>
</template>

<script>
import ErrorNotification from '@/components/general/ErrorNotification.vue';
import SuccessNotification from '@/components/general/SuccessNotification.vue';

export default {
  data(){
    return {

    }
  },
  methods: {
    
  },
  computed: {
    
  },
  components:{
    ErrorNotification,
    SuccessNotification
  }
}
</script>