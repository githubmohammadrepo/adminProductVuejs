<template>
  <div>
    <h5>تمامی مناطق</h5>
    <b-row>
      <b-card
        v-for="region in regions"
        :title="'منطقه '+region.text"
        tag="article"
        class="mb-2 d-block col-sm-12 col-md-6 col-lg-4 col-xl-3"
      >
    <b-button href="#" variant="primary">نمایش فروشگاه ها</b-button>
    </b-card>
    </b-row>
  </div>
</template>

<script>
export default {
  data() {
    return {
      regions:[
        {id:1,text:1},
        {id:2,text:2},
        {id:3,text:3},
        {id:4,text:4},
        {id:5,text:5},
        {id:6,text:6},
        {id:7,text:7},
        {id:8,text:8},
        {id:9,text:9},
      ]
    };
  },
  methods: {
  },
  computed: {},
  created() {},
  mounted() {},
};
</script>


<style lang="scss" scoped>

</style>