<?php
require_once('./../../../connection.php');
require_once('./HelperTrait.php');
// error_reporting(E_ALL);
// ini_set('error_reporting', E_ALL);
// ini_set('display_errors', 1);

class UpdateBrand
{
  use Helpers;

  private $resonsed = false;
  private $conn;
  public function __construct(mysqli $conn)
  {
    $this->conn = $conn;
  }


  /**
   * remove one brand
   *
   * @param integer $category_id
   * @return boolean
   */
  private function removeOneBrand(int $category_id):bool
  {
    $sql = "DELETE  FROM `pish_hikashop_category` WHERE category_id = $category_id";
    $result = $this->conn->query($sql);
    if($result){
      return true;
    }else{
      return false;
    }
  }


  /**
   * remove one brand logo
   *
   * @param integer $category_id
   * @return boolean
   */
  private function removeOneBrandLogo(int $category_id):bool {
    $sql = "DELETE FROM pish_hikashop_file WHERE file_ref_id = $category_id";
    $result = $this->conn->query($sql);
    if($result){
      return true;
    }else{
      return false;
    }
  }


  /**
   * remove brand proccess
   *
   * @return void
   */
  public function removeBrandProccess()
  {
    $postedData = $this->postedData();
    if(isset($postedData['removeBrand'])){
      $category_id = (int)($this->getInput(isset($postedData['category_id']) ? $postedData['category_id'] : 0));
      $status_removeBrand = $this->removeOneBrand($category_id);
      $status_removeBrandLogo = $this->removeOneBrandLogo($category_id);

      //validation remove
      if($status_removeBrand==true && $status_removeBrandLogo==true){
        return true;
      }else{
        return false;
      }

    }
  }

 /**
  * capsulate some action in just one transaction 
  * show result UpdateInfromations
  *
  * @param string $file_name
  * @return void
  */
 public function showResultUpdateInformations(){
  $status = $this->transactionProccess('removeBrandProccess');
  $this->resultJsonEncode(['status'=>$status]);
}




  /**
   * doing transaction proccess
   *
   * @param [type] $proccessStringName
   * @return void
   */
  private function transactionProccess($proccessStringName){
    /* Start transaction */
    $this->conn->begin_transaction();
      try {
          $reusltStatus=call_user_func(array(__CLASS__, $proccessStringName),(string)$file_name);
          
          if($reusltStatus){

            $this->conn->commit();
            return true;
          }else{
            $this->conn->rollback();
            return false;
          }
      } catch (mysqli_sql_exception $exception) {
        $this->conn->rollback();
          return false;
      }

  }//end transaxtion proccess




  public function __destruct()
  {
    $this->showLastResponse();
  }
} //end removeCompnay class


//create init from class
$removeCompnay = new UpdateBrand($conn);
$removeCompnay->showResultUpdateInformations();
