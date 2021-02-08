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
  public function __construct(mysqli $conn)
  {
    $this->conn = $conn;
  }

   /**
   * get last brand by category_id or brnad_id
   *
   * @param integer $category_id
   * @return array
   */
  public function getAllCategoriesByCategoryId(int $category_id): array
  {
      $dev = array();

      $sql = "SELECT * FROM pish_hikashop_category where category_parent_id = $category_id ORDER BY category_ordering DESC LIMIT 1";
      $result = $this->conn->query($sql);
      if ($result) {
          $count = mysqli_num_rows($result);
          while ($row = $result->fetch_assoc()) {
              $dev[] = $row;
          }
      }
      return $dev;
  }

  /**
   * update just one brand
   *
   * @param integer $brandId
   * @param string $brandName
   * @return boolean
   */
  private function insertOneBrand(string $title,$published,$catOrdering, $catLeft, $catRight, $category_namekey):bool {
    // main action
    $sql = "INSERT INTO pish_hikashop_category (user_id, category_parent_id, category_type, category_name, category_published, category_ordering, category_left, category_right, category_depth, category_namekey)
    VALUES (Null, 10, 'manufacturer', '$title', $published, '$catOrdering', '$catLeft', '$catRight', 2, '$category_namekey')";
    $result = $this->conn->query($sql);
    if ($result) {
        return mysqli_affected_rows($this->conn) > 0 ? true : false;
    }
    return false;
  }



  /**
   * update category logo
   *
   * @param integer $category_id
   * @param [type] $fullFileName
   * @return boolean
   */
  private function insertCategoryLogo(int $category_id,$fullFileName):bool{
    $vowels = array(".jpg", ".png", ".jpeg");
    $file_name = str_replace($vowels, "", $fullFileName);
    $file_path = $fullFileName;

    $sql = "INSERT INTO pish_hikashop_file (file_name,file_path, file_type, file_ref_id) VALUES ('$file_name','$file_path', 'manufacturer', '$category_id')";
    $result = $this->conn->query($sql);
    if($result){
      return true;
    }else{
      return false;
    }
  }

  /**
   * get last brand inserted  or get last category_id with parent_id =10
   *
   * @return integer
   */
  private function getLastInsertedId():int{
    $sql= "SELECT category_id FROM `pish_hikashop_category` WHERE category_parent_id=10 AND category_type='manufacturer' ORDER by category_id desc limit 1";
    $result = $this->conn->query($sql);
    if($result){
      $count = mysqli_num_rows($result);
      if($count){
        $row= mysqli_fetch_assoc($result);
        return $row['category_id'];
      }
    }
    return 0;
  }
  /**
   * update informations
   *
   * @param string $file_name
   * @return void
   */
 private function insertInformations($catOrdering, $catLeft, $catRight, $category_namekey,$fullFileName)
 {
   //get data
   $brand_name = (string)($this->getInput(isset($_POST['brand_name']) ? $_POST['brand_name'] : ''));
   $published = (string)($this->getInput(isset($_POST['published']) ? $_POST['published'] : ''));

    //insert brand
    $status_insertBrand = $this->insertOneBrand($brand_name,$published,$catOrdering, $catLeft, $catRight, $category_namekey);
    $category_id  = $this->getLastInsertedId();
    //insert brand logo in database
    $status_insertBrandLogo = $this->insertCategoryLogo($category_id,$fullFileName);
    if($status_insertBrand==true && $status_insertBrandLogo==true){
      return true;
    }else{
      return false;
    }

 }


 /**
  * capsulate some action in just one transaction 
  * show result UpdateInfromations
  *
  * @param string $file_name
  * @return void
  */
 public function showResultUpdateInformations(string $fullFileName){
  $dev = $this->getAllCategoriesByCategoryId(10);

  if (count($dev)) {
      $catOrdering = $dev[0]['category_ordering'] + 1;
      $catLeft = $dev[0]['category_right'] + 1;
      $catRight = $catLeft + 1;

      $time = time();
      $random = rand(1000000, 10000000);
      $category_namekey = "manufacturer_${time}_${random}";

      //insert brand proccess
      return $status = $this->transactionProccess('insertInformations',$catOrdering, $catLeft, $catRight, $category_namekey,$fullFileName);
      // $this->resultJsonEncode(['status'=>$status]);
  // return $status = $this->transactionProccess('insertInformations',$file_name);
  // $this->resultJsonEncode(['status'=>$status]);
  }
}




/**
 * transaction proccess
 *
 * @param [type] $proccessStringName
 * @param [type] $catOrdering
 * @param [type] $catLeft
 * @param [type] $catRight
 * @param [type] $category_namekey
 * @return void
 */
  private function transactionProccess($proccessStringName,$catOrdering, $catLeft, $catRight, $category_namekey,$fullFileName){
    /* Start transaction */
    $this->conn->begin_transaction();
      try {
          $reusltStatus=call_user_func(array(__CLASS__, $proccessStringName),$catOrdering, $catLeft, $catRight, $category_namekey,$fullFileName);
          
          if($reusltStatus){

            $this->conn->commit();
            return true;
          }else{
            
            $this->conn->rollback();
            return false;
          }
      } catch (mysqli_sql_exception $exception) {
        
        $this->conn->rollback();
          return false;
      }

  }//end transaxtion proccess

  /**
   * get and show all brands
   *
   * @return void
   */
  public function updateBrand(): void
  {

    $uploaded_image= false;
    $errors = Array();
    if (isset($_POST['insertOneBrand'])) {
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
