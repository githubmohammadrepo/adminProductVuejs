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
  public function __construct(mysqli $conn)
  {
      $this->conn = $conn;
  }

  /**
   * remove one product
   *
   * @param integer $product_id
   * @return boolean
   */
  protected function RemoveOneProduct(int $product_id):bool{
    if($product_id==0 || $product_id<0){
      return false;
    }
    $sql = "DELETE FROM pish_hikashop_product WHERE product_id = '$product_id'";
    $result = $this->remove($sql);
    if($result){
      return true;
    }else{
      return false;
    }
  }


  /**
   * remove product category
   *
   * @param integer $product_id
   * @return boolean
   */
  protected function RemoveProductCategory(int $product_id):bool{
    if($product_id==0 || $product_id<0){
      return false;
    }
    $sql = "DELETE FROM pish_hikashop_product_category WHERE product_id = '$product_id'";
    $result = $this->remove($sql);
    if($result){
      return true;
    }else{
      return false;
    }
  }



  /**
   * remvoe product image
   *
   * @param integer $product_id
   * @return boolean
   */
  protected function removeProductImageDatabase(int $product_id):bool{
    if($product_id ==0 || $product_id<0){
      return false;
    }
    $sql = "DELETE FROM pish_hikashop_file WHERE file_ref_id = '$product_id'";
    $result = $this->remove($sql);
    if($result){
      return true;
    }else{
      return false;
    }
  }

  /**
   * unlink product image
   *
   * @param integer $product_id
   * @return boolean
   */
  protected function UnlinkProductImage(int $product_id):bool{
    $sql = "SELECT * FROM pish_hikashop_file WHERE file_ref_id = '$product_id'";
    $result = $this->select($sql,true);
    if(count($result)){
      //continue unlink
      $fileName = $result[0]['file_path'];
      $url = "/home/fishopp2/public_html/images/com_hikashop/upload/" . $fileName;
      unlink($url);

      return true;
    }else{
      return true;
    }
  }



  /**
   * proccess remove product
   *
   * @return void
   */
  protected function proccessRemoveProduct(){
    $productId = (string)$this->getInput($_POST['productId']);
    $fileType = "product";
    $product_type = "main";
    $removeOneProduct = (string)$this->getInput($_POST['removeOneProduct']);
    
    //continue insert product
    $removeProduct_status = $this->RemoveOneProduct((int)$productId);
    if($removeProduct_status){
      //continue
      $removeProductCategory = $this->RemoveProductCategory((int)$productId);
      if($removeProduct_status){
        //continue
        $removeProductImage_status = $this->removeProductImageDatabase((int)$productId);
        if($removeProductImage_status){
          //continue
          $unlinkProductImage = $this->UnlinkProductImage((int)$productId);
          if($unlinkProductImage){
            return true;
          }else{
            return false;
          }
        }else{
          return false;
        }
      }else{
        //show error
        return false;
      }

    }else{
      //shwo error 
      return false;
    }
    

  }


  public function showResultInsertNewStore(){
    $status = $this->transactionProccess('proccessRemoveProduct');
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

