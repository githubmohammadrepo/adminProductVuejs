<?php
error_reporting(E_ALL);
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
require_once ('./../../connection.php');

class ShowBrands {

  private $conn;
  public function __construct(mysqli $conn){
    $this->conn = $conn;
  }

  /**
   * show all brands
   *
   * @param integer $offfset
   * @param integer $count
   * @return array
   */
  private function getAllBrands(int $offfset = 0,int $count = 25):array{
    $result_array = Array();
    $sql= "SELECT brands.*, pish_hikashop_file.file_path as brand_image, COUNT(pish_hikashop_product.product_id) as productsCount FROM( SELECT pish_hikashop_category.category_id, pish_hikashop_category.category_name, pish_hikashop_category.user_id FROM pish_hikashop_category WHERE pish_hikashop_category.category_type = 'manufacturer' AND pish_hikashop_category.category_parent_id = 10 LIMIT $offfset,$count)as brands LEFT JOIN pish_hikashop_file ON pish_hikashop_file.file_ref_id = brands.category_id AND pish_hikashop_file.file_type = 'category' LEFT JOIN pish_hikashop_product ON pish_hikashop_product.product_manufacturer_id = brands.category_id GROUP BY brands.category_id";
    $result = $this->conn->query($sql);
    if($result){
      $count = mysqli_num_rows($result);
      if($count){
        while($row= mysqli_fetch_assoc($result)){
          $result_array[]=$row;
        }
      }
    }
    return $result_array;
  }
  


  public function showResult(){
    $postedData = $this->postedData();
    $offset = (int)($this->getInput($postedData['offset']));
    $count = (int)($this->getInput($postedData['count']));

    $brands = $this->getAllBrands($offset,$count);
    //endoce and show brands
    $this->resultJsonEncode(['brands'=>$brands,'status'=>true]);
  }


   /**
   * return array post requested data to this page
   *
   * @return array
   */
  public function postedData(): array
  {
    $arrayJsonData = file_get_contents('php://input', true);
    $post = json_decode($arrayJsonData, JSON_UNESCAPED_UNICODE);
    $this->recivecData = $post;
    return $post;
  }
  
  /**
   * section just show output methods
   */
  private function resultJsonEncode($data)
  {
      $this->resonsed = true;
      if (gettype($data) == 'array') {
          echo json_encode($data, JSON_UNESCAPED_UNICODE);
      } else {
          echo json_encode([$data], JSON_UNESCAPED_UNICODE);
      }
  }


  private function showLastResponse(){
    if($this->resonsed==false){
      $this->resultJsonEncode(['status'=>false]);
    }
  }

    /**
   * get input and make sure it secure
   */
  protected function getInput($input)
  {
      $result = htmlspecialchars(strip_tags($input));
      if (preg_match('/<>;:\$^/', $result)) {
          return;
      } else {
          return $result;
      }
  }

  public function __destruct(){
    $this->showLastResponse();
  }
}


//create init
$brands = new ShowBrands($conn);
$brands->showResult();


