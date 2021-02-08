<?php

 if(isset($_FILES['brand_logo'])){
      $errors= array();
      $file_name = $_FILES['brand_logo']['name'];
      $file_size =$_FILES['brand_logo']['size'];
      $file_tmp =$_FILES['brand_logo']['tmp_name'];
      $file_type=$_FILES['brand_logo']['type'];
      $file_ext=strtolower(end(explode('.',$_FILES['brand_logo']['name'])));
      
      $extensions= array("jpeg","jpg","png");
      
      if(in_array($file_ext,$extensions)=== false){
         $errors[]="extension not allowed, please choose a JPEG or PNG file.";
      }
      
      if($file_size > 12582912){
         $errors[]='File size must be excately 6 MB';
      }
      
      if(empty($errors)==true){
         $s = move_uploaded_file($file_tmp,"./brand_logos/".$file_name);
         echo "Success";
      }else{
         print_r($errors);
      }
   }