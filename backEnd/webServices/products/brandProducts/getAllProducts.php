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
  protected function getAllProductsCount(int $offset,int $count,int $category_id):int{
    $sql = "SELECT COUNT(*) as count FROM `pish_hikashop_product` WHERE product_manufacturer_id = $category_id";
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
   * @param integer $category_id
   * @return array
   */
  protected function getAllProducts(int $offset,int $count,int $category_id):array{
    $sql = "SELECT newResult.*,pish_hikashop_category.category_id as category_category_id,pish_hikashop_category.category_parent_id as category_category_parent_id FROM (SELECT result.*,pish_hikashop_file.file_path ,pish_hikashop_category.category_name as brand_name FROM(SELECT pish_hikashop_product.*,pish_hikashop_category.category_id,pish_hikashop_category.category_name,pish_hikashop_category.category_parent_id
    FROM pish_hikashop_product
    INNER JOIN pish_hikashop_category
    ON pish_hikashop_product.product_manufacturer_id = pish_hikashop_category.category_id
    WHERE pish_hikashop_category.category_id = $category_id
    ORDER BY pish_hikashop_category.category_id LIMIT $offset,$count) as result
    LEFT JOIN pish_hikashop_file
      ON result.product_id = pish_hikashop_file.file_ref_id
    LEFT JOIN pish_hikashop_category
    ON result.product_manufacturer_id = pish_hikashop_category.category_id)as newResult
LEFT JOIN pish_hikashop_category
ON newResult.product_parent_id = pish_hikashop_category.category_id";
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

  public function showAllProducts(){

    $postedData = $this->postedData();
    if(isset($postedData['getAllCategoryProducts'])){
      $offset =(int)$this->getPostRequestField('offset','int',-1);
      $count =(int)$this->getPostRequestField('count','int',-1);  
      $category_id =(int)$this->getPostRequestField('category_id','int',-1);  
      if($offset!=-1 && $count!=-1 && $category_id !=-1){
        $products = $this->getAllProducts($offset,$count,$category_id);
        if(count($products)){
          $productsCount = $this->getAllProductsCount($offset,$count,$category_id);
          $this->resultJsonEncode(['products'=>$products,'count'=>$productsCount,'status'=>true]);
        }else{
          $this->resultJsonEncode(['products'=>$products,'status'=>false]);
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
$showGetCategoris->showAllProducts();