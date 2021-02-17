<?php

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
  private function getAllSubBrands(int $brand_id,int $offfset = 0,int $count = 25):array{
    $result_array = Array();
    $sql= "SELECT * FROM pish_hikashop_product WHERE pish_hikashop_product.product_manufacturer_id = $brand_id ORDER by product_id ASC LIMIT $offfset,$count";
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

    $brand_id = (int)($this->getInput($postedData['brand_id']));
    $offset = (int)($this->getInput($postedData['offset']));
    $count = (int)($this->getInput($postedData['count']));

    $brands = $this->getAllSubBrands($brand_id,$offset,$count);
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


