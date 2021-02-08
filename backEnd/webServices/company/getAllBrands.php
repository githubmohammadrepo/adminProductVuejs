<?php
error_reporting(E_ALL);
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
require_once('./../../connection.php');
require_once('./HelperTrait.php');
class GetAllBrands
{
  use Helpers;

  private $conn;
  private $resonsed = false;

  public function __construct(mysqli $conn)
  {
    $this->conn = $conn;
  }

  public function getAllBrands():void
  {
    $dev_array = Array();
    $method= $this->getRequestType();
    if($method=='GET'){
      $sql = "SELECT  category_id,category_name
        FROM `pish_hikashop_category`
        WHERE `user_id` IS NULL 
        AND `category_type` = 'manufacturer' 
        AND `category_name` != 'manufacturer'
        ORDER BY `category_name` ASC";
      $result = $this->conn->query($sql);
      if($result){
        $count = mysqli_num_rows($result);
        if($count){
          while($row = mysqli_fetch_assoc($result)){
            $dev_array[] = $row;
          }
          $this->resultJsonEncode(['brands'=>$dev_array,'status'=>true]);
          return;
        }
      }
    }
    $this->resultJsonEncode(['brands'=>$dev_array,'status'=>false]);


  }

  /**
   * return request method type
   *
   * @return void
   */
  public function getRequestType(){
    $methodType= $_SERVER['REQUEST_METHOD'];
    return $methodType;
  }

}

/**
 * create init from class
 */
$getBrands = new GetAllBrands($conn);
$getBrands->getAllBrands();
















