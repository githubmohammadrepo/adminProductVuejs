<template>
  <div>
    <div class="mb-2">
      <b-form-checkbox v-model="stickyHeader" inline>Sticky header</b-form-checkbox>
      <b-form-checkbox v-model="noCollapse" inline>No border collapse</b-form-checkbox>
    </div>
    <b-table
      :sticky-header="stickyHeader"
      :no-border-collapse="noCollapse"
      responsive
      :items="items"
      :fields="fields"
    >
      <!-- We are using utility class `text-nowrap` to help illustrate horizontal scrolling -->
      <template #head(id)="scope">
        <div class="text-nowrap">Row ID</div>
      </template>
      <template #head()="scope">
        <div class="text-nowrap">
          {{ scope.label }}
        </div>
      </template>
    </b-table>
  </div>
</template>

<script>
import axios from 'axios';

  export default {
    data() {
      return {
        getAllBrandOffset:0,
        stickyHeader: true,
        noCollapse: false,
        fields: [
          { key: 'id', stickyColumn: true, isRowHeader: true, variant: 'primary' },
          { key: 'operation',label:'عملیات', stickyColumn: true, variant: 'warning' },
          {key:'a',label:'نام شرکت'},
          {key:'b',label:'نام برند'},
          {key:'c',label:'مدیر شرکت'},
          {key:'d',label:'تلفن همراه مدیر شرکت'},
          {key:'e',label:'تلفن شرکت'},
          {key:'f',label:'آدرس شرکت'},
        ],
        items: [
          { id: 1,operation:1, a: 0, b: 1, c: 2, d: 3, e: 4, f: 5 },
          { id: 2,operation:1, a: 0, b: 1, c: 2, d: 3, e: 4, f: 5 },
          { id: 3,operation:1, a: 0, b: 1, c: 2, d: 3, e: 4, f: 5 },
          { id: 4,operation:1, a: 0, b: 1, c: 2, d: 3, e: 4, f: 5 },
          { id: 5,operation:1, a: 0, b: 1, c: 2, d: 3, e: 4, f: 5 },
          { id: 6,operation:1, a: 0, b: 1, c: 2, d: 3, e: 4, f: 5 },
          { id: 7,operation:1, a: 0, b: 1, c: 2, d: 3, e: 4, f: 5 },
          { id: 8,operation:1, a: 0, b: 1, c: 2, d: 3, e: 4, f: 5 },
          { id: 9,operation:1, a: 0, b: 1, c: 2, d: 3, e: 4, f: 5 },
          { id: 10,operation:1, a: 0, b: 1, c: 2, d: 3, e: 4, f: 5 }
        ]
      }
    },
    created() {
      let that = this;
      axios
                .post("http://fishopping.ir/serverHypernetShowUnion/showBrands.php", {
                    offset: that.getAllBrandOffset,
                    typeAction: "select"
                })
                .then(function(response) {
                  console.log('getAllBrandOffset: '+that.getAllBrandOffset)
                    that.getAllBrandOffset += 10;
                    console.log(response)
                    
                })
                .catch(function(error) {
                    console.log(error)
                })

    },
  }
</script>


<style lang="scss" scoped>
 
</style>