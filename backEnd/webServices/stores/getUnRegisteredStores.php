<?php
require_once('./../../../connection.php');
require_once('./HelperTrait.php');
error_reporting(E_ALL);
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);

class ShowAllUnRegisteredStores
{
  use Helpers;

  private $resonsed=false;
  private $conn;
  public function __construct(mysqli $conn)
  {
      $this->conn = $conn;
  }

  /**
   * get all unregestered stores
   *
   * @return void
   */
  private function getAllUnRegisteredStores(int $province_id,int $city_id,int $region_id){
    $dev_array = Array();
    if($province_id !=-1 && $city_id != -1 && $region_id !=-1 ){
      $sql = "SELECT id, RegionID, ShopName FROM pish_phocamaps_marker_store WHERE user_id IS NULL AND province = '$province_id' AND city = '$city_id' AND RegionID = '$region_id'  ORDER BY ShopName ASC";
    }else if($province_id != -1 && $city_id !=-1){
      $sql = "SELECT id, RegionID, ShopName FROM pish_phocamaps_marker_store WHERE user_id IS NULL AND province = '$province_id' AND city = '$city_id'  ORDER BY ShopName ASC";
    }else if($province_id != -1){
      $sql = "SELECT id, RegionID, ShopName FROM pish_phocamaps_marker_store WHERE user_id IS NULL AND province = '$province_id'  ORDER BY ShopName ASC";
    }else{
      $sql = "SELECT id, RegionID, ShopName FROM pish_phocamaps_marker_store WHERE user_id IS NULL ORDER BY ShopName ASC";
    }
    
    $dev_array = $this->select($sql);
    return $dev_array;    
  }

  /**
   * show all unregistered stores
   *
   * @return void
   */
  public function ShowAllUnRegisteredStores()
  {
    $postedData = $this->postedData();

    if(isset($postedData['getAllUnregisteredStores'])){
      $province_id = (int)($this->getInput(isset($postedData['selectedProvince']) ? $postedData['selectedProvince'] : -1));
      $city_id = (int)($this->getInput(isset($postedData['selectedCity']) ? $postedData['selectedCity'] : -1));
      $region_id = (int)($this->getInput(isset($postedData['selectedRegion']) ? $postedData['selectedRegion'] : -1));

      $unregisteredStores = $this->getAllUnRegisteredStores((int)$province_id,(int)$city_id,(int)$region_id);
      $this->resultJsonEncode(['stores'=>$unregisteredStores,'status'=>true]);
    }
  }


  public function __destruct()
  {
    $this->showLastResponse();
  }

}//end removeCompnay class


//create init from class
$showFilterFormInfo = new ShowAllUnRegisteredStores($conn);
$showFilterFormInfo->ShowAllUnRegisteredStores();
