<?php
error_reporting(E_ALL);
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
require_once('./../../../../connection.php');
require_once('./../HelperTrait.php');

class GetAllCategoryProducts
{
  use Helpers;

  protected $resonsed=false;
  protected $conn;
  public function __construct(mysqli $conn)
  {
      $this->conn = $conn;
  }


  protected function removeOneProduct(int $product_id):bool{
    $sql = "";
  }





}//end removeCompnay class


class ShowResult extends GetAllCategoryProducts {

  public function __construct($conn)
  {
    parent::__construct($conn);
  }

  public function showAllCategories(){

    $postedData = $this->postedData();
    if(isset($postedData['getAllCategories'])){
      $offset =(int)$this->getPostRequestField('offset','int',-1);
      $count =(int)$this->getPostRequestField('count','int',-1);  
      if($offset!=-1 && $count!=-1){
        $categories = $this->getAllCategories($offset,$count);
        if(count($categories)){
          $categoriesCount = $this->getAllCategoriesCount($offset,$count);
          $this->resultJsonEncode(['categories'=>$categories,'count'=>$categoriesCount,'status'=>true]);
        }else{
          $this->resultJsonEncode(['categories'=>$categories,'status'=>false]);
        }
      }
    }
  }

  public function __destruct()
  {
    $this->showLastResponse();
  }
}


//create init from ShowResult
$showGetCategoris = new ShowResult($conn);
$showGetCategoris->showAllCategories();