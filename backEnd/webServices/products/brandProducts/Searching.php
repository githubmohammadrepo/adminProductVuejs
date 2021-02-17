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
   * search base category name
   *
   * @param string $like_category_name
   * @param integer $offset
   * @param integer $count
   * @return array
   */
  protected function searchBaseOnCategoryName(string $like_category_name,int $offset,int $count):array
  {
    $sql = "SELECT new.*,pish_hikashop_product.*,pish_hikashop_category.category_name as brand_name , pish_hikashop_category.category_id as brand_id FROM(SELECT category_id,category_name from pish_hikashop_category WHERE category_name like '%$like_category_name%' AND category_type = 'product' ORDER BY category_id ASC) AS new
      INNER JOIN pish_hikashop_product
      ON new.category_id= pish_hikashop_product.product_parent_id 
      LEFT JOIN pish_hikashop_category
      ON new.category_id = pish_hikashop_category.category_id
      LIMIT $offset,$count";
    
    $findedCategory = $this->select($sql);
    if(count($findedCategory)){
      return $findedCategory;
    }else{
      return Array();
    }
  }

  /**
   * get Count finded bysearch
   *
   * @param string $like_category_name
   * @param integer $offset
   * @param integer $count
   * @return array
   */
  protected function searchBaseOnCategoryNameCount(string $like_category_name,int $offset,int $count):int
  {
    $sql = "SELECT COUNT(*) as count FROM(SELECT new.*,pish_hikashop_product.* FROM(SELECT category_id,category_name from pish_hikashop_category WHERE category_name like '%$like_category_name%' AND category_type = 'product' ORDER BY category_id ) AS new
    INNER JOIN pish_hikashop_product
    ON new.category_id= pish_hikashop_product.product_parent_id)AS result";
    
    $findedProducts = $this->select($sql);
    if(count($findedProducts)){
      return $findedProducts[0]['count'];      
    }else{
      return 0;
    }
  }

  /**
   * serch base brand name
   *
   * @param string $like_brand_name
   * @param integer $offset
   * @param integer $count
   * @return array
   */
  protected function searchBaseOnBrandName(string $like_brand_name,int $offset, int $count):array {
    $sql = "SELECT new.*,pish_hikashop_product.*,pish_hikashop_category.category_name as brand_name , pish_hikashop_category.category_id as brand_id FROM(SELECT category_id,category_name from pish_hikashop_category WHERE category_name like '%$like_brand_name%' AND category_type = 'manufacturer' ORDER BY category_id ASC) AS new
      INNER JOIN pish_hikashop_product
      ON new.category_id= pish_hikashop_product.product_manufacturer_id
      LEFT JOIN pish_hikashop_category
      ON new.category_id = pish_hikashop_category.category_id
      LIMIT $offset,$count";

    $findedProducts = $this->select($sql);
    if(count($findedProducts)){
      return $findedProducts;
    }else{
      return Array();
    }
  }

  /**
   * search base on brand name count
   *
   * @param string $like_brand_name
   * @param integer $offset
   * @param integer $count
   * @return integer
   */
  protected function searchBaseOnBrandNameCount(string $like_brand_name,int $offset,int $count):int{
    $sql= "SELECT COUNT(*) as count FROM(SELECT new.*,pish_hikashop_product.* FROM(SELECT category_id,category_name from pish_hikashop_category WHERE category_name like '%$like_brand_name%' AND category_type = 'manufacturer' ORDER BY category_id) AS new
    INNER JOIN pish_hikashop_product
    ON new.category_id= pish_hikashop_product.product_manufacturer_id)AS result";

    $result = $this->select($sql);
    if(count($result)){
      return $result[0]['count'];
    }else{
      return 0;
    }
  }

}//end removeCompnay class


class ShowResult extends GetAllCategoryProducts {

  public function __construct($conn)
  {
    parent::__construct($conn);
  }

  public function showAllFindedProducts(){

    $postedData = $this->postedData();
    if(isset($postedData['searchProductCategory'])){
      $offset =(int)$this->getPostRequestField('offset','int',-1);
      $offset = $this->getInput($offset);

      $count =(int)$this->getPostRequestField('count','int',-1);  
      $count= $this->getInput($count);

      $search =(string)$this->getPostRequestField('search','string',-1);  
      $search = $this->getInput(($search));

      $sarchType =(string)$this->getPostRequestField('typeSearch','string',-1);  
      $sarchType= $this->getInput($sarchType);
      
      if($offset!=-1 && $count!=-1 && $search !=-1 && $sarchType !=-1){
        if($sarchType=='category'){
          //get products base category
          $findedProduct = $this->searchBaseOnCategoryName($search,$offset,$count);
          if(count($findedProduct)>0){
            $count= $this->searchBaseOnCategoryNameCount($search,$offset,$count);
            $this->resultJsonEncode(['products'=>$findedProduct,'count'=>$count,'status'=>true]);
          }
        }else if($sarchType=='brand'){
          //get products base on like brand_name
          $findedProducts = $this->searchBaseOnBrandName($search,$offset,$count);

          if(count($findedProducts)){
            
            $count= $this->searchBaseOnBrandNameCount($search,$offset,$count);
            $this->resultJsonEncode(['products'=>$findedProducts,'count'=>$count,'status'=>true]);

          }else{

          }
        }else{

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
$showGetCategoris->showAllFindedProducts();