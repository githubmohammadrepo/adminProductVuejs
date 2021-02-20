<template>
  <div>
     <b-button-group class="">

      <!-- start start -->
      <b-button @click="showSelf(values.start)" class="bg-white text-primary" :disabled="!shows.start">
        <font-awesome-icon :icon="['fas', 'angle-double-right']" />
      </b-button>
      <!-- end start -->
      
      <!-- start continue -->
      <b-button  class="bg-white text-primary" v-show="shows.startContinue">...</b-button>
      <!-- end continue -->

      <b-button @click="showSelf(values.one)" class="" v-show="shows.one" :class="{'text-white bg-primary':currentPage ==1,'bg-white text-primary':this.currentPage !=1}">{{values.one}}</b-button>

      <!-- changing section -->
      <b-button @click="showSelf(values.two)" :class="{'text-white bg-primary':currentPage !=1 && currentPage!=pageCount,'bg-white text-primary': (currentPage ==pageCount || currentPage ==1)}" v-show="shows.two">{{values.two}}</b-button>
      <b-button @click="showSelf(values.three)" :class="{'bg-primary text-white':currentPage==pageCount,'bg-white text-primary':currentPage != pageCount}" v-show="shows.three">{{values.three}}</b-button>
      <!-- end changing section -->

      <!-- show continue -->
      <b-button class="bg-white text-primary" v-show="shows.endContinue">...</b-button>
      <!-- end continue -->
      
      <!-- start show goal -->
      <b-button @click="showSelf(values.goal)" class="bg-white text-primary" v-show="shows.goal">{{values.goal}}</b-button>
      <!-- end show goal -->

      <!-- start end -->
      <b-button @click="showSelf(values.end)" class="bg-white text-primary" :disabled="!shows.end">
        <font-awesome-icon :icon="['fas', 'angle-double-left']" />
      </b-button>
      <!-- edn end  -->


    </b-button-group>
  </div>
</template>

<script>

export default {
  props:{
    pages:{
      type:Number,
      required:true
    },
    value:{
      type:Number,
      required:true
    }
  },
 data(){
   return {
     newCurrentPage:1,
     shows:{
       one:false,
       two:false,
       three:false,
       start:false,
       end:false,
       startContinue:false,
       endContinue:false,
       goal:false,
     },
     values:{
       one:-1,
       two:-1,
       three:-1,
       start:-1,
       end:-1,
       goal:-1
     }
   }
 },
 methods: {
  showSelf(value,refresh=false){
 
    //hide all
    this.hideAll()
    if(this.pageCount<=1){
      //just show one
      this.shows.one = true;
      this.values.one =1
    }else if(this.pageCount==2){
      //show one and
      this.shows.one = true;
      this.values.one =1;

      //show two
      this.shows.two = true;
      this.values.two = 2;
    }else if(this.pageCount==3){
      //show one and
      this.shows.one = true;
      this.values.one =1;

      //show two and
      this.shows.two = true;
      this.values.two =2;

      //show three
      this.shows.three = true;
      this.values.three =3;

    }else if(this.pageCount==4){
      //show one and
      this.shows.one = true;
      this.values.one =1;

      //show two and
      this.shows.two = true;
      this.values.two =2;

      //show three
      this.shows.three = true;
      this.values.three =3;

      // show end
      this.shows.goal = true;
      this.values.goal =4;
    }else{

      
      ///show one and
      this.shows.one = true;
      this.values.one =value-1;

      let add = 0;
      if(this.values.one<=0){
        this.values.one = 1;
        add = 1;
      }
      //show two and
      this.shows.two = true;
      this.values.two =value+add;

      //show three
      this.shows.three = true;
      this.values.three =value+1+add;

      // show goal

      this.shows.goal = true;
      this.values.goal =this.pageCount;
      //show end
      this.shows.end = true;
      this.values.end =this.pageCount;

      // show continue end and
      this.shows.endContinue = true;
      this.values.endContinue =this.pageCount;

      //show start
      this.shows.start=true;
      this.values.start = 1;

      //do some operations
      if(this.values.three >this.pageCount){
        //less one number from all one and two and three
        this.values.one-=1;
        this.values.two -=1;
        this.values.three -=1;
        //hide goal
        this.shows.goal =false;

        //hide endContinue
        this.shows.endContinue =false;
      }

    }
    this.currentPage = value;
    if(refresh==false){
      this.OuputCurrentPage(value)
    }

    if(value>2){
      //show startContinue
      this.shows.startContinue =true;

      //show begain
    }else{
      //hide startContinue
      this.shows.startContinue =false;

      //hide begain
    }
  },
  OuputCurrentPage(value){
    this.$emit('changed',value)
  },
  hideAll(){
    for (const key in this.shows) {
      if (Object.hasOwnProperty.call(this.shows, key)) {
        this.shows[key]=false
      }
    }
  }
 },
 computed: {
   currentPage:{
     get(){
       return parseInt(this.value);
     },
     set(value){
       this.newCurrentPage= value;
     }
   },
   pageCount:{
     get(){
       if(window.sessionStorage.getItem('page')!=this.pages.toString()){
         window.sessionStorage.setItem('page',this.pages.toString());
          this.showSelf(this.currentPage)
       }
       return parseInt(this.pages);
     },

   }
  
 },
 created() {
  this.showSelf(this.currentPage)
 },
}
</script>

<style>

</style>