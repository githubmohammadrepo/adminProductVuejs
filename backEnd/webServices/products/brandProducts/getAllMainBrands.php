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
  protected function getAllCategoriesCount(int $offset,int $count):int{
    $sql = "SELECT COUNT(*) as count FROM `pish_hikashop_category`  WHERE category_type = 'manufacturer' AND category_parent_id = 10
    ORDER BY `pish_hikashop_category`.`category_id` ASC";
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
  protected function getAllCategories(int $offset,int $count):array{
    $sql = "
    SELECT  finalCategory.*
           ,COUNT(pish_hikashop_product.product_id) as category_product_count
    FROM(
      SELECT  new.*
             ,pish_hikashop_category.category_name AS category_parent_name
      FROM 
      (
        SELECT  *
        FROM `pish_hikashop_category`
        WHERE category_type = 'manufacturer' 
        AND category_parent_id IN(1,10)
        ORDER BY `pish_hikashop_category`.`category_id` ASC LIMIT $offset,$count
      ) AS new
      LEFT JOIN pish_hikashop_category
      ON new.category_parent_id = pish_hikashop_category.category_id
    )as finalCategory
    LEFT JOIN pish_hikashop_product
    ON finalCategory.category_id = pish_hikashop_product.product_manufacturer_id
    GROUP BY finalCategory.category_id
    ";
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