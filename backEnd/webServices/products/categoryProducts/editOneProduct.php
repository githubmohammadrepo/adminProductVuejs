<?php
error_reporting(E_ALL);
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
require_once('./../../../../connection.php');
require_once('./../HelperTrait.php');

class GetAllCategoryProducts
{
  use Helpers;

  protected $last_ordering= 0;
  protected $last_product_id = 0;
  protected $resonsed=false;
  protected $conn;
  private $oldProductImage = '';
  protected $productImageError = '';
  public function __construct(mysqli $conn)
  {
      $this->conn = $conn;
  }

  /**
   * is product exist
   *
   * @param string $product_code
   * @return boolean
   */
  protected function isProductExist(string $product_id):bool{
    $sql = "SELECT product_id FROM pish_hikashop_product WHERE product_id = '$product_id'";
    $result = $this->select($sql);
    if(count($result)){
      return true;
    }else{
      return false;
    }
  }


  /**
   * insert new proudct into table
   *
   * @param string $product_name
   * @param [type] $product_code
   * @param [type] $product_type
   * @param [type] $product_sort_price
   * @param [type] $product_msrp
   * @param [type] $product_checkout_method
   * @param [type] $product_number_in_package
   * @param [type] $product_description_chechout
   * @param [type] $product_sale_type
   * @param [type] $user_id
   * @param [type] $product_package_type
   * @param [type] $product_weight
   * @param [type] $product_delivery_time
   * @param [type] $product_parent_id
   * @param [type] $cat_id
   * @return boolean
   */
  protected function updateNewProduct(int $product_id,string $product_name,$product_code,$product_type,$product_sort_price,$product_msrp,$product_checkout_method,$product_number_in_package,$product_description_chechout,$product_sale_type,$product_package_type,$product_weight,$product_delivery_time,$product_parent_id,$cat_id):bool{
    if($product_id==0 || $product_id<0){
      return false;
    }
    $sql = "UPDATE pish_hikashop_product 
    SET product_name='$product_name', product_code='$product_code', product_published=1, product_tax_id=11,
     product_type='$product_type', product_sort_price='$product_sort_price', product_msrp='$product_msrp',
      product_checkout_method='$product_checkout_method',
      product_number_in_package='$product_number_in_package', product_description_checkout='$product_description_chechout',
       product_sale_type='$product_sale_type',
       product_package_type='$product_package_type', product_weight= '$product_weight',
        product_delivery_time= '$product_delivery_time', product_parent_id='$product_parent_id',
         product_manufacturer_id='$cat_id'  WHERE product_id = $product_id";

    $result= $this->update($sql);
    if($result==true){
      return true;
    }else{
      return false;
    }
  }
  

  /**
   * get last product id inserted
   *
   * @return integer
   */
  protected function getLastProductIdInserted(){
    $sql = "SELECT product_id from pish_hikashop_product ORDER BY `pish_hikashop_product`.`product_id` DESC LIMIT 1";
    $result = $this->select($sql,true);
    if(count($result)){
      $this->last_product_id = $result[0]['product_id'];
    }else{
      $this->last_product_id = 0;
    }
  }


  /**
   * get last ordering number form productCategory
   *
   * @param integer $category_id
   * @return void
   */
  protected function getLastProductCategoryOrderingNumber(int $category_id):void{
    $sql = "SELECT ordering FROM pish_hikashop_product_category WHERE category_id = '$category_id' ORDER BY ordering DESC LIMIT 1";
    $result = $this->select($sql,true);
    if(count($result)){
      $this->last_ordering = $result[0]['ordering'];
    }else{
      $this->last_ordering = 0;
    }
  }



  /**
   * insert product category
   *
   * @param integer $category_id
   * @return boolean
   */
  protected function insertProductCategoryOrdering(int $category_id):bool{
    $sql= "";
    $this->getLastProductIdInserted();
    $last_id =$this->last_product_id;

    if($last_id==0){
      return false;
    }
    $this->getLastProductCategoryOrderingNumber($category_id);
    $order =  $this->last_ordering;

    if ($order>0) {
      //insert into category table
      $newOrdering = $order+ 1;
      $sql = "INSERT INTO pish_hikashop_product_category (category_id, product_id, ordering) VALUES ('$category_id', '$last_id', '$newOrdering')";
    } else {
      //insert into category table
      $sql = "INSERT INTO pish_hikashop_product_category (category_id, product_id, ordering) VALUES ('$category_id', '$last_id', 1)";
    }

    $result = $this->insert($sql);
    if($result==true){
      return true;
    }else{
      return false;
    }
  }


  /**
   * get old product image
   *
   * @param integer $product_id
   * @return boolean
   */
  protected function getOldProductImage(int $product_id):bool{
    if($product_id<=0){
      return false;
    }
    $sql = "SELECT file_path  FROM `pish_hikashop_file` WHERE file_ref_id =$product_id";
    $result = $this->select($sql,true);
    if(count($result)){
      $this->oldProductImage = $result[0]['file_path'];
      return true;
    }else{
      return false;
    }
  }

  protected function proccessInsertNewProduct(){
    $product_id = (string)$this->getInput($_POST['product_id']);
    $category = (string)$this->getInput($_POST['category']);
    
    $subCategory = (string)$this->getInput($_POST['subCategory']);
    
    $checked = (string)$this->getInput($_POST['checked']);
    
    $name = (string)$this->getInput($_POST['name']);
    
    $consumerProductPrice = (string)$this->getInput($_POST['consumerProductPrice']);
    
    $manufacturProductPrice = (string)$this->getInput($_POST['manufacturProductPrice']);
    
    $settleType = (string)$this->getInput($_POST['settleType']);
    
    $countInCategory = (string)$this->getInput($_POST['countInCategory']);
    
    $packageType = (string)$this->getInput($_POST['packageType']);
    
    $weightPackageType = (string)$this->getInput($_POST['weightPackageType']);
    
    $deliverTime = (string)$this->getInput($_POST['deliverTime']);
    
    $deliveDescription = (string)$this->getInput($_POST['deliveDescription']);
    
    $productName = (string)$this->getInput($_POST['productName']);
    
    $productCode = (string)$this->getInput($_POST['productCode']);
    
    $orderType = (string)$this->getInput($_POST['orderType']);
    
    $brand = (string)$this->getInput($_POST['brand']);
    
    $fileType = "product";
    $product_type = "main";
    $insertNewProductCategory = (string)$this->getInput($_POST['insertNewProductCategory']);
    

    //is proudct exist
    $isProductExist = $this->isProductExist((int)$product_id);
    if($isProductExist==false){
      //product exist show error
      return false;
    }else{
      //continue insert product
      $insertProductStatus = $this->updateNewProduct(
        $product_id,
        $productName,
        $productCode,
        $product_type,
        $consumerProductPrice,
        $manufacturProductPrice,
        $settleType,
        $countInCategory,
        $deliveDescription,
        $orderType,
        $packageType,
        $weightPackageType,
        $deliverTime,
        $subCategory,
        $brand);
        if($insertProductStatus==true){

          //continue
          //continue upload image
          $statusUploadImage = $this->uploadImage();
          if($statusUploadImage['status']==true){
          $fileName= $statusUploadImage['file_name'];
            //uploaded

            //save old product image to remvoe it 
            $productImageExist = $this->getOldProductImage((int)$product_id);
            if($productImageExist){
              //update
              if(strlen($fileName)){
                $statusUpdateProductImage = $this->updateProductImage((int)$product_id,$fileName);
                if($statusUpdateProductImage){
                  //remvoe before image
                  if(strlen($this->oldProductImage)){
                    unlink('/home/fishopp2/public_html/images/com_hikashop/upload/thumbnails/200x200f/'+$this->oldProductImage);
                  }
                  return true;
                }else{
                  return false;
                }
              }else{
                return true;
              }
            }else{
              //insert new product image
              $statusUpdateProductImage = $this->insertProductImage((int)$product_id,$fileName);
              if($statusUpdateProductImage){
                //remvoe before image
                if(strlen($this->oldProductImage)){
                  unlink('/home/fishopp2/public_html/images/com_hikashop/upload/thumbnails/200x200f/'+$this->oldProductImage);
                }
                return true;
              }else{
                return false;
              }
            }
          }else{
            $this->productImageError = $statusUploadImage['error'];
            //show error
            return false;
          }
        }else{
          //shoe error
          return false;
        }
    }

  }


  public function showResultInsertNewStore(){
    $status = $this->transactionProccess('proccessInsertNewProduct');
    if($status){
      $this->resultJsonEncode(['inserted'=>true,'status'=>true]);
    }else{
      $this->resultJsonEncode(['inserted'=>false,'status'=>false]);
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
  protected function transactionProccess($proccessStringName){
    /* Start transaction */
    $this->conn->begin_transaction();
      try {
          $reusltStatus=call_user_func(array(__CLASS__, $proccessStringName));
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
   * update category logo
   *
   * @param integer $category_id
   * @param [type] $fullFileName
   * @return boolean
   */
  protected function updateProductImage(int $product_id,$fullFileName):bool{
    if($product_id<=0){
      return false;
    }
    $vowels = array(".jpg", ".png", ".jpeg");
    $file_name = str_replace($vowels, "", $fullFileName);
    $file_path = $fullFileName;

    $sql = "UPDATE pish_hikashop_file SET file_name='$file_name',file_path='$file_path', file_type='product', file_ref_id='$product_id' WHERE file_ref_id = $product_id";
    $result = $this->update($sql);
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
  protected function insertProductImage(int $product_id,$fullFileName):bool{
    $vowels = array(".jpg", ".png", ".jpeg");
    $file_name = str_replace($vowels, "", $fullFileName);
    $file_path = $fullFileName;

    $sql = "INSERT INTO pish_hikashop_file (file_name,file_path, file_type, file_ref_id) VALUES ('$file_name','$file_path', 'manufacturer', '$product_id')";
    $result = $this->conn->query($sql);
    if($result){
      return true;
    }else{
      return false;
    }
  }

  /**
   * upload image
   *
   * @return array
   */
  protected function uploadImage():array
  {
    $result = Array(
      'error'=>'',
      'status'=>false,
      'isFileExist'=>false,
      'file_name'=>''
    );

    if (count($_FILES) && isset($_FILES['productImage'])) {
      $errors = array();
      $file_name = $_FILES['productImage']['name'];
      $file_size = $_FILES['productImage']['size'];
      $file_tmp = $_FILES['productImage']['tmp_name'];
      $file_type = $_FILES['productImage']['type'];
      $file_ext = strtolower(end(explode('.', $_FILES['productImage']['name'])));

      $extensions = array("jpeg", "jpg", "png");
      
      if(strlen($file_name)){
        $result['isFileExist'] =true;
        $result['file_name'] = $file_name;
      }else{
        $result['status'] = true;
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
        $s = move_uploaded_file($file_tmp, "/home/fishopp2/public_html/images/com_hikashop/upload/thumbnails/200x200f/" . $file_name);
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
    $result['status']=true;
    return $result;
  }


}//end removeCompnay class


class ShowResult extends GetAllCategoryProducts {

  public function __construct($conn)
  {
    parent::__construct($conn);
  }

  public function addNewProduct(){
    $this->showResultInsertNewStore();
  }

  public function __destruct()
  {
    $this->showLastResponse();
  }
}


//create init from ShowResult
$showGetCategoris = new ShowResult($conn);
$showGetCategoris->addNewProduct();

