<?php
require_once('./../../../connection.php');
require_once('./HelperTrait.php');
// error_reporting(E_ALL);
// ini_set('error_reporting', E_ALL);
// ini_set('display_errors', 1);

class UpdateBrand
{
  use Helpers;

  private $resonsed = false;
  private $conn;
  private $brandHasLogoFile= false;
  public function __construct(mysqli $conn)
  {
    $this->conn = $conn;
  }


  /**
   * update just one brand
   *
   * @param integer $brandId
   * @param string $brandName
   * @return boolean
   */
  private function updateOneBrand(int $brandId,string $brandName,bool $published):bool {
    $sql = "UPDATE pish_hikashop_category SET category_name = '$brandName',category_published	=$published WHERE category_id = $brandId and category_type = 'manufacturer'";
    $result = $this->conn->query($sql);
    if($result){
      return true;
    }else{
      return false;
    }
  }

  private function updateUser(int $user_id,string $userName):bool{
    $sql = "UPDATE pish_users SET pish_users.name = '$userName' WHERE pish_users.id = $user_id";
    $result = $this->conn->query($sql);
    if($result){
      return true;
    }else{
      return false;
    }
  }


  /**
   * update category logo
   *
   * @param integer $category_id
   * @param [type] $fullFileName
   * @return boolean
   */
  private function updateCategoryLogo(int $category_id,$fullFileName):bool{
    $vowels = array(".jpg", ".png", ".jpeg");
    $file_name = str_replace($vowels, "", $fullFileName);
    $file_path = $fullFileName;
    if(strlen($fullFileName)==0){
      return true;
    }
    $sql = "";
    if($this->brandHasLogoFile==true){
      //update brnad logo
      $sql= "UPDATE pish_hikashop_file SET file_path= '$file_path', file_name = '$file_name' WHERE file_ref_id= $category_id";
    }else{
      //inser brand logo
      $sql = "INSERT INTO pish_hikashop_file (file_name,file_path, file_type, file_ref_id) VALUES ('$file_name','$file_path', 'manufacturer', '$category_id')";
    }
    $result = $this->conn->query($sql);
    if($result){
      return true;
    }else{
      return false;
    }
  }

  /**
   * update informations
   *
   * @param string $file_name
   * @return void
   */
 private function updateInformations(string $file_name)
 {
   //get data
   $brandId = (string)($this->getInput(isset($_POST['brandId']) ? $_POST['brandId'] : ''));
   $brandName = (string)($this->getInput(isset($_POST['brandName']) ? $_POST['brandName'] : ''));
   $userName = (string)($this->getInput(isset($_POST['userName']) ? $_POST['userName'] : ''));
   $user_id = (int)($this->getInput(isset($_POST['user_id']) ? $_POST['user_id'] : 0));
   $published = (bool)($this->getInput(isset($_POST['published']) ? $_POST['published'] : ''));

   if($user_id){  
    //update user
    $status_updateUser = $this->updateUser($user_id,$userName);
    //update brand
    $status_updateBrand = $this->updateOneBrand($brandId,$brandName,$published);

    //update brand logo in database
    $status_updateBrandLogo = $this->updateCategoryLogo((int)$brandId,(string)$file_name);
    if($status_updateUser==true && $status_updateBrand==true && $status_updateBrandLogo==true){
      return true;
    }else{
      return false;
    }

   }else{
     //update brand
    $status_updateBrand = $this->updateOneBrand($brandId,$brandName,$published);
    $status_updateBrandLogo = $this->updateCategoryLogo((int)$brandId,(string)$file_name);

    if($status_updateBrand==true && $status_updateBrandLogo==true){
      return true;
    }else{
      return false;
    }
   }
 }


 /**
  * capsulate some action in just one transaction 
  * show result UpdateInfromations
  *
  * @param string $file_name
  * @return void
  */
 public function showResultUpdateInformations(string $file_name){
  return $status = $this->transactionProccess('updateInformations',$file_name);
  // $this->resultJsonEncode(['status'=>$status]);
}




  /**
   * doing transaction proccess
   *
   * @param [type] $proccessStringName
   * @return void
   */
  private function transactionProccess($proccessStringName,$file_name){
    /* Start transaction */
    $this->conn->begin_transaction();
      try {
          $reusltStatus=call_user_func(array(__CLASS__, $proccessStringName),(string)$file_name);
          
          if($reusltStatus){

            $this->conn->commit();
            return true;
          }else{
            echo 'rolledBack';
            $this->conn->rollback();
            return false;
          }
      } catch (mysqli_sql_exception $exception) {
        echo 'exception mysqli';  
        $this->conn->rollback();
          return false;
      }

  }//end transaxtion proccess

  /**
   * is brand has logo file saved?
   *
   * @param integer $brandId
   * @return boolean
   */
  private function isBransHasLogoFile(int $brandId):bool{
    $sql= "SELECT *  FROM `pish_hikashop_file` WHERE `file_ref_id` = $brandId";
    $result = $this->conn->query($sql);
    if($result){
      $count = mysqli_num_rows($result);
      return $count ? true : false;
    }
    return false;
  }

  /**
   * get and show all brands
   *
   * @return void
   */
  public function updateBrand(): void
  {

    $uploaded_image= false;
    $errors = Array();
    if (isset($_POST['editOneBrand'])) {
      //upload image
      $errors=$statusUploadImage = $this->uploadImage();
      if($statusUploadImage['status']){
        //image exist and uploaded
        $uploaded_image = true;
      }else if($statusUploadImage['status']==false){
        if($statusUploadImage['isFileExist']){
          //file exist and not uploaded
          $uploaded_image = false;          
        }else{
          $uploaded_image= true;
        }
      }else{
        $uploaded_image = false;
      }

    }
    $brandId = (int)($this->getInput(isset($_POST['brandId']) ? $_POST['brandId'] : ''));

    $this->brandHasLogoFile = $this->isBransHasLogoFile((int)$brandId);

    if($uploaded_image==true){
      //insert extra informations
      $status_updateInformations = $this->showResultUpdateInformations((string)$errors['file_name']);
      if($status_updateInformations==true){
        $this->resultJsonEncode(['status'=>true]);

      }else{
        if($errors['isFileExist']){
          unlink('./brand_logos/'.$errors['file_name']);
        }
        $this->resultJsonEncode(['status'=>false]);
        //remove file

      }
    }else{
      //show fail response
      $this->resultJsonEncode(['status'=>false,'errors'=>$errors]);
    }
  }



  /**
   * upload image
   *
   * @return array
   */
  private function uploadImage():array
  {
    $result = Array(
      'error'=>'',
      'status'=>false,
      'isFileExist'=>false,
      'file_name'=>''
    );

    if (count($_FILES) && isset($_FILES['brand_logo'])) {
      $errors = array();
      $file_name = $_FILES['brand_logo']['name'];
      $file_size = $_FILES['brand_logo']['size'];
      $file_tmp = $_FILES['brand_logo']['tmp_name'];
      $file_type = $_FILES['brand_logo']['type'];
      $file_ext = strtolower(end(explode('.', $_FILES['brand_logo']['name'])));

      $extensions = array("jpeg", "jpg", "png");
      
      if(strlen($file_name)){
        $result['isFileExist'] =true;
        $result['file_name'] = $file_name;
      }else{
        return $result;
      }
      if (in_array($file_ext, $extensions) === false) {
        $errors[] = "extension not allowed, please choose a JPEG or PNG file.";
        $result['error'] = 'نوع فایل فقط عکس باشد ';
        $result['status'] = false;
        return $result;
      }

      if ($file_size > 12582912) {
        $errors[] = 'File size must be excately 6 MB';
        $result['error'] = 'حجم فایل نباید بیشتر از 6 مگابایت باشد';
        $result['status'] = false;
        return $result;
      }

      if (empty($errors) == true) {
        $s = move_uploaded_file($file_tmp, "./../../../../../images/com_hikashop/upload/thumbnails/200x200f/" . $file_name);
        if($s){
          $result['status'] =true;
          return $result;
        }else{
          $result['error'] = 'خطا، فایل اپلود نشد';
          $result['status'] =false;
          return $result;

        }
      }
    }
    return $result;
  }

  public function __destruct()
  {
    $this->showLastResponse();
  }
} //end removeCompnay class


//create init from class
$removeCompnay = new UpdateBrand($conn);
$removeCompnay->updateBrand();
