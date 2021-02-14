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


  /**
   * get all categoris Count
   *
   * @param integer $offset
   * @param integer $count
   * @return array
   */
  protected function getAllSubCategoriesCount(int $offset,int $count,int $category_id):int{
    $sql = "SELECT COUNT(*) as count FROM `pish_hikashop_category`  WHERE category_parent_id =$category_id  ORDER BY category_id ASC LIMIT $offset,$count";
    $Categories = $this->select($sql);
    if(count($Categories)){
      return $Categories[0]['count'];
    }else{
      return 0;
    }
  }

  /**
   * get all categories
   *
   * @param integer $offset
   * @param integer $count
   * @return array
   */
  protected function getAllSubCategories(int $offset,int $count,int $category_id):array{
    $sql = "SELECT *  FROM `pish_hikashop_category`  WHERE category_parent_id =$category_id ORDER BY category_id ASC LIMIT $offset,$count";
    $Categories = $this->select($sql);
    if(count($Categories)){
      return $Categories;
    }else{
      return Array();
    }
  }





}//end removeCompnay class


class ShowResult extends GetAllCategoryProducts {

  public function __construct($conn)
  {
    parent::__construct($conn);
  }

  public function showAllSubCategories(){

    $postedData = $this->postedData();
    if(isset($postedData['getAllSubCategories'])){
      $offset =(int)$this->getPostRequestField('offset','int',-1);
      $count =(int)$this->getPostRequestField('count','int',-1);  
      $category_id =(int)$this->getPostRequestField('category_id','int',-1);  

      if($offset!=-1 && $count!=-1 && $category_id!=-1){
        $categories = $this->getAllSubCategories($offset,$count,$category_id);
        if(count($categories)){
          $categoriesCount = $this->getAllSubCategoriesCount($offset,$count,$category_id);
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
$showGetCategoris->showAllSubCategories();