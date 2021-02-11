<?php
error_reporting(E_ALL);
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
require_once('./../../../connection.php');
require_once('./HelperTrait.php');
class InsertNewCompany
{
  use Helpers;

  private $conn;
  private $resonsed = false;
  private $last_id;
  public function __construct(mysqli $conn)
  {
    $this->conn = $conn;
  }



  /**
   * update one store
   *
   * @param integer $selectedStore
   * @param integer $selectedRegion
   * @param integer $selectedProvince
   * @param integer $selectedCity
   * @param string $ShopName
   * @param string $ManagerName
   * @param string $phone
   * @param string $MobilePhone
   * @param string $Address
   * @param string $latitude
   * @param string $longitude
   * @return boolean
   */
  private function updateRealNewStore(
    int $selectedStore,
    int $selectedRegion,
    int $selectedProvince,
    int $selectedCity,
    string $ShopName,
    string $ManagerName,
    string $phone,
    string $MobilePhone,
    string $Address,
    string $latitude,
    string $longitude
  ):bool{
    //prepare data
    if($selectedCity ==0){
      return false;
    }
    $sql = "";
    
    if($selectedProvince && $selectedCity && $selectedRegion){
      $sql = "UPDATE pish_phocamaps_marker_store SET user_id='$this->last_id', RegionID = '$selectedRegion', 
      province = '$selectedProvince',city = '$selectedCity',
      title='$ShopName', ShopName = '$ShopName' , phone = '$phone' ,
      MobilePhone = '$MobilePhone' , OwnerName = '$ManagerName' , Address = '$Address' 
      WHERE id = '$selectedStore'";
    }else if($selectedProvince && $selectedCity){
      $sql = "UPDATE pish_phocamaps_marker_store SET user_id='$this->last_id', RegionID = '$selectedRegion', 
      province = '$selectedProvince',city = '$selectedCity',
      title='$ShopName', ShopName = '$ShopName' , phone = '$phone' ,
      MobilePhone = '$MobilePhone' , OwnerName = '$ManagerName' , Address = '$Address' 
      WHERE id = '$selectedStore'";
    }else if($selectedProvince){
      $sql = "UPDATE pish_phocamaps_marker_store SET user_id='$this->last_id', RegionID = '$selectedRegion', 
      province = '$selectedProvince',city = '$selectedCity',
      title='$ShopName', ShopName = '$ShopName' , phone = '$phone' ,
      MobilePhone = '$MobilePhone' , OwnerName = '$ManagerName' , Address = '$Address' 
      WHERE id = '$selectedStore'";
    }else{
      $sql = "UPDATE pish_phocamaps_marker_store SET user_id='$this->last_id', RegionID = '$selectedRegion', 
      province = '$selectedProvince',city = '$selectedCity',
      title='$ShopName', ShopName = '$ShopName' , phone = '$phone' ,
      MobilePhone = '$MobilePhone' , OwnerName = '$ManagerName' , Address = '$Address' 
      WHERE id = '$selectedStore'";
    }
    //prepare sql
    $result = $this->conn->query($sql);
    if($result){
      return true;
    }else{
      return false;
    }
  }


  /**
   * getUpdatedStore array
   *
   * @param integer $sotre_id
   * @return array
   */
  private function getUpdatedStore(int $sotre_id):array{
    $sql= "SELECT *  FROM `pish_phocamaps_marker_store` WHERE `id` = $sotre_id limit 1";
    $store= $this->select($sql);
    if(count($store)){
      return $store[0];
    }else{
      return Array();
    }
  }

  public function showResulUpdateOneStore(){
    $postedData = $this->postedData();
    if(isset($postedData['updateOneStore'])){
      //prepare data
      $selectedStore = (int)$this->getInput(isset($postedData['id']) ? $postedData['id'] : '');
      $selectedRegion = (int)$this->getInput(isset($postedData['selectedRegion']) ? $postedData['selectedRegion'] : '');
      $selectedProvince = (int)$this->getInput(isset($postedData['selectedProvince']) ? $postedData['selectedProvince'] : '');
      $selectedCity = (int)$this->getInput(isset($postedData['selectedCity']) ? $postedData['selectedCity'] : '');
      $ShopName = (string)$this->getInput(isset($postedData['ShopName']) ? $postedData['ShopName'] : '');
      $ManagerName = (string)$this->getInput(isset($postedData['ManagerName']) ? $postedData['ManagerName'] : '');
      $phone = (string)$this->getInput(isset($postedData['phone']) ? $postedData['phone'] : '');
      $MobilePhone = (string)$this->getInput(isset($postedData['MobilePhone']) ? $postedData['MobilePhone'] : '');
      $Address = (string)$this->getInput(isset($postedData['address']) ? $postedData['address'] : '');
      $latitude = (string)$this->getInput(isset($postedData['latitude']) ? $postedData['latitude'] : '');
      $longitude = (string)$this->getInput(isset($postedData['longitude']) ? $postedData['longitude'] : '');

      //validate data
      if(
        empty($ShopName) &&
        empty($ManagerName) &&
        empty($phone) &&
        empty($MobilePhone) &&
        empty($Address) &&
        empty($latitude) &&
        empty($longitude)
      ){
        $this->resultJsonEncode(['invalidInput'=>true,'status'=>false]);
      }else{
        //update proccess
        $status_updateStore = $this->updateRealNewStore(
          $selectedStore,
          $selectedRegion,
          $selectedProvince,
          $selectedCity,
          $ShopName,
          $ManagerName,
          $phone,
          $MobilePhone,
          $Address,
          $latitude,
          $longitude
        );
        //show properiate response
        if($status_updateStore){
          $updatedStore= $this->getUpdatedStore($selectedStore);
          $this->resultJsonEncode(['updated'=>true,'store'=>$updatedStore,'status'=>true]);
        }else{
          $this->resultJsonEncode(['updated'=>false,'status'=>false]);
        }

      }
    }
  }

  

 /**
   * section just show output methods
   */
  public function resultJsonEncode($data)
  {
    if($this->resonsed){
      return;
    }
    $this->resonsed = true;
    if (gettype($data) == 'array') {
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    } else {
        echo json_encode([$data], JSON_UNESCAPED_UNICODE);
    }
  }

  public function __destruct()
  {
    $this->showLastResponse();
  }

}

/**
 * create init from class
 */
$updateCompany = new InsertNewCompany($conn);
$updateCompany->showResulUpdateOneStore();

