<?php
require_once('./../../../connection.php');
require_once('./HelperTrait.php');
error_reporting(E_ALL);
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);

class VerifyCompany
{
  use Helpers;

  private $resonsed=false;
  private $conn;
  public function __construct(mysqli $conn)
  {
      $this->conn = $conn;
  }
  
  /**
   * get all brand count
   *
   * @return integer
   */
  private function getAllBrandsCount():int{
    $brandCount = 0;
    $postedData = $this->postedData();
    $sql = "";
    if(isset($postedData['search'])){
      $search = ($postedData['search']);
      $sql = "SELECT COUNT(*) as count FROM `pish_hikashop_category` WHERE category_type = 'manufacturer' AND category_parent_id= 10 AND category_name like '%$search%' ORDER BY category_id";
    }else{
      $sql = "SELECT COUNT(*) as count FROM `pish_hikashop_category` WHERE category_type = 'manufacturer' AND category_parent_id= 10 ORDER BY category_id";
    }
    $result = $this->conn->query($sql);
    if($result){
      $count = mysqli_num_rows($result);
      if($count){
        $row = mysqli_fetch_assoc($result);
        $brandCount= $row['count'];
      }
    }
    return $brandCount;

  }

  /**
   * get and show all brands
   *
   * @return void
   */
  private function getAllBrands():void
  {
    $brandCount = $this->getAllBrandsCount();
    $postedData = $this->postedData();
    
    if(isset($postedData['getAllBrands'])){
      $offset= 0;
      if(isset($postedData['offset'])){
        $offset = (int)($this->getInput($postedData['offset']));
      }else{
        $offset = settype($postedData['offset'],'string')=="0" ? 0 : null;
      }
      $count = (int)($this->getInput(isset($postedData['count']) ? $postedData['count'] : 0));
      if ($offset!==null) {
          $dev_array = array();
          $sql = "SELECT category.*,pish_users.id,pish_users.name,pish_hikashop_file.file_path,pish_hikashop_file.file_ref_id FROM(SELECT category_id,user_id,category_name,category_published FROM `pish_hikashop_category` WHERE category_type = 'manufacturer' AND category_parent_id= 10 ORDER BY category_id LIMIT $offset,$count)as category
            LEFT JOIN pish_users ON category.user_id = pish_users.id
            LEFT JOIN pish_hikashop_file ON category.category_id = pish_hikashop_file.file_ref_id";
          $result = $this->conn->query($sql);
          if ($result) {
              $count= mysqli_num_rows($result);
              if ($count) {
                  while ($row = mysqli_fetch_assoc($result)) {
                      $dev_array[] = $row;
                  }

                  $this->resultJsonEncode(['brands'=>$dev_array,'count'=>$brandCount,'status'=>true]);
              }
          }
      }
    }

  }

  /**
   * search brand like
   */
  private function searchBrandLike():void
  {
    $brandCount = $this->getAllBrandsCount();
    $postedData = $this->postedData();
    if(isset($postedData['getAllBrands'])){
      $offset= 0;
      if(isset($postedData['offset'])){
        $offset = (int)($this->getInput($postedData['offset']));
      }else{
        $offset = settype($postedData['offset'],'string')=="0" ? 0 : null;
      }
      $count = (int)($this->getInput(isset($postedData['count']) ? $postedData['count'] : 0));
      $search = (string)($this->getInput(isset($postedData['search']) ? $postedData['search'] : ''));
      $search = ($search);
      if ($offset!==null) {
          $dev_array = array();
          $sql = "SELECT category.*,pish_users.id,pish_users.name,pish_hikashop_file.file_path,pish_hikashop_file.file_ref_id FROM(SELECT category_id,user_id,category_name,category_published FROM `pish_hikashop_category` WHERE category_type = 'manufacturer' AND category_parent_id= 10 AND category_name LIKE '%$search%' ORDER BY category_id LIMIT $offset,$count)as category
            LEFT JOIN pish_users ON category.user_id = pish_users.id
            LEFT JOIN pish_hikashop_file ON category.category_id = pish_hikashop_file.file_ref_id";
          $result = $this->conn->query($sql);
          if ($result) {
              $count= mysqli_num_rows($result);
              if ($count) {
                  while ($row = mysqli_fetch_assoc($result)) {
                      $dev_array[] = $row;
                  }

                  $this->resultJsonEncode(['brands'=>$dev_array,'count'=>$brandCount,'status'=>true]);
              }
          }
      }
    }

  }


  public function showResult(){
    $postedData = $this->postedData();
    if(isset($postedData['search'])){
      //get search
      $this->searchBrandLike();
    }else{
      //get normal brands 
      $this->getAllBrands();
    }
  }

  public function __destruct()
  {
    $this->showLastResponse();
  }

}//end removeCompnay class


//create init from class
$removeCompnay = new VerifyCompany($conn);
$removeCompnay->showResult();
// $removeCompnay->searchBrandLike();