<?php
error_reporting(E_ALL);
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
require_once('./../../../../connection.php');
require_once('./../HelperTrait.php');

class GetAllBrands
{
  use Helpers;

  protected $resonsed=false;
  protected $conn;
  public function __construct(mysqli $conn)
  {
      $this->conn = $conn;
  }



  /**
   * get all brands
   *
   * @return void
   */
  protected function getAllBrands():array{
    $sql = "SELECT category_id,category_name FROM pish_hikashop_category WHERE category_type='manufacturer' ORDER BY category_id ASC";
    $result = $this->select($sql);
    if(count($result)){
      return $result;
    }else{
      return Array();
    }
  }



}//end removeCompnay class


class ShowResult extends GetAllBrands {

  public function __construct($conn)
  {
    parent::__construct($conn);
  }

  public function showAllBrands(){

    $postedData = $this->postedData();
    if(isset($postedData['getAllBrands'])){
      $brands = $this->getAllBrands();
      if(count($brands)){
        $this->resultJsonEncode(['brands'=>$brands,'status'=>true]);
      }else{
        $this->resultJsonEncode(['status'=>false]);
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
$showGetCategoris->showAllBrands();