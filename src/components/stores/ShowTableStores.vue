<template>
  <div>
    <b-row>
      <b-col class="mt-2">
        <h5 class="text-center pb-0 pt-2">نمایش اطلاعات فروشگاه ها</h5>
        <hr class="w-25 mt-0 pt-0 bg-info">
      </b-col>
    </b-row>

      <!-- <b-form-checkbox v-model="stickyHeader" inline>Sticky header</b-form-checkbox>
      <b-form-checkbox v-model="noCollapse" inline>No border collapse</b-form-checkbox> -->
    <b-table
      @row-dblclicked="editOneBrand"
      :sticky-header="stickyHeader"
      :no-border-collapse="noCollapse"
      responsive
      :items="items"
      :fields="fields"
    >
      <template #cell(operation)="data">
        <div class="min-width-200px">
            <b-button variant="info" class="m-0 mx-1" @click="ButtonClickEditOneStore(data.value)" >ویرایش</b-button>
            <b-button variant="danger" class="m-0 mx-1" @click="ButtonClickRemoveOneBrand(data.value)" >حذف</b-button>
        </div>

      </template>

      <!-- <template #cell(brand_logo)="data" class="m-0 p-0">
          <b-img left :src="'http://fishopping.ir/images/com_hikashop/upload/thumbnails/200x200f/'+data.value" rounded="circle" height="80px" class="m-0 p-0" alt="لوگوی برند"></b-img>
      </template> -->

      <!-- We are using utility class `text-nowrap` to help illustrate horizontal scrolling -->
      <template #head(id)="scope">
        <div class="text-nowrap p-0 m-0">Row ID</div>
      </template>

      <template #head()="scope">
        <div class="text-nowrap">
          {{ scope.label }}
        </div>
      </template>

      <template #cell(Address)="data" class="m-0 p-0">
          <div class="min-width-300px">
            {{data.value}}
          </div>
      </template>
      <template #cell(region_title)="data" class="m-0 p-0">
          <div class="min-width-200px">
            {{data.value}}
          </div>
      </template>
    </b-table>


    <ShowStorePagination />

  </div>
</template>

<script>
import axios from "axios";
import ShowStorePagination from '@/components/stores/ShowStorePagination.vue';

export default {
  data() {
    return {
      options: [
        { value: 20, text: 20 },
        { value: 50, text: 50 },
        { value: 100, text: 100 },
        { value: 500, text: 500 },
        { value: 1000, text: 1000 },
      ],
      getAllBrandOffset: 0,
      stickyHeader: true,
      noCollapse: false,
      fields: [
        {
          key: "operation",
          label: "عملیات",
          stickyColumn: true,
          isRowHeader: true,
          variant: "primary",
          class: "px-0 mx-0",
        },
        { key: "id", label: "ایدی فروشگاه" },
        { key: "title", label: "عنوان فروشگاه" },
        { key: "ShopName", label: "نام فروشگاه" },
        { key: "ManagerName", label: "نام مدیر فروشگاه" },
        { key: "phone", label: "شماره تلفن" },
        { key: "MobilePhone", label: "شماره موبایل" },
        { key: "latitude", label: "طول جغرافیایی" },
        { key: "longitude", label: "عرض جغرافیایی" },
        { key: "Address", label: "آدرس فروشگاه" },
        { key: "city_name", label: "نام شهر" },
        { key: "province_name", label: "نام استان" },
        { key: "region_title", label: "نام منطقه" },
      ],
    };
  },
  methods: {
    editOneBrand(index) {
      this.$store.state.stores.editDataObject = index;
      this.$store.state.stores.storeEditing = !this.$store.state.stores

      //reset addNewCompnay to level one
      //hide all
      this.$store.commit('stores/showModalOperation',{key:'edit',editing:true});
     
    },
    ButtonClickEditOneStore(store_id) {
      let index = {};
      for (const key in this.$store.state.stores.items) {
        if (Object.hasOwnProperty.call(this.$store.state.stores.items, key)) {
          const element = this.$store.state.stores.items[key];
          if (element.id == store_id) {
            index = element;
          }
        }
      }
      this.$store.state.stores.editDataObject = index;
      this.$store.commit('stores/showModalOperation',{key:'edit',editing:true});

      //reset addNewCompnay to level one
      //hide all
      //show level three
    },
    ButtonClickRemoveOneBrand(store_id) {
      let index = {};
      for (const key in this.$store.state.stores.items) {
        if (Object.hasOwnProperty.call(this.$store.state.stores.items, key)) {
          const element = this.$store.state.stores.items[key];
          if (element.id == store_id) {
            index = element;
          }
        }
      }
      this.$store.state.stores.editDataObject = index;
      this.$store.state.stores.brandEditing = !this.$store.state.stores
      
      this.$store.commit('stores/showModalOperation',{key:'remove',editing:true});
      //reset addNewCompnay to level one
      //hide all
      //show level three
    },
  },
  computed: {
    items() {
      return this.$store.state.stores.items.map(store =>{
        return {
          ...store,
          operation:store.id
        }
      });
    },
  },
  created() {},
  components: {
    ShowStorePagination,

  },
};
</script>


<style lang="scss" scoped>
.min-width-200px {
  width: 150px !important;
  display: block !important;
}

.min-width-300px {
  width: 250px !important;
  display: block !important;
}
table tr th {
  display: contents;
}
</style>