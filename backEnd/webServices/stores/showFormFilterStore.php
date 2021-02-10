<?php
require_once('./../../../connection.php');
require_once('./HelperTrait.php');
error_reporting(E_ALL);
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);

class ShowFormFitlerInfos
{
  use Helpers;

  private $resonsed=false;
  private $conn;
  public function __construct(mysqli $conn)
  {
      $this->conn = $conn;
  }

  /**
   * show all provinces
   *
   * @return array
   */
  public function showAllProvince():void{
    $postedData = $this->postedData();

    if(isset($postedData['selectProvince'])){

      $sql = "SELECT * FROM `pish_province` ORDER BY name ASC";
      $provinces = $this->select($sql);
      if(count($provinces)){
        $this->resultJsonEncode(['provinces'=>$provinces,'status'=>true]);
      }else{
        $this->resultJsonEncode(['status'=>false]);
      }
    }
  }



  /**
   * show all provincecityes by province_id
   *
   * @return void
   */
  public function showProvinceCities():void{
    $postedData = $this->postedData();
    if(isset($postedData['selectProvinceCities'])){
      $province_id= (int)($this->getInput($postedData['province_id']));
      $sql = "SELECT * FROM `pish_city` WHERE province_id = $province_id ORDER BY name ASC";
      $provincesCities = $this->select($sql);
      if(count($provincesCities)){
        $this->resultJsonEncode(['provinceCities'=>$provincesCities,'status'=>true]);
      }else{
        $this->resultJsonEncode(['status'=>false]);
      }
    }
  }



  /**
   * show all city regions by city_id
   *
   * @return void
   */
  public function showCityRigions():void{
    $postedData = $this->postedData();
    if(isset($postedData['selectCityRegions'])){
      $cityId= (int)($this->getInput($postedData['cityId']));
      $sql = "SELECT id,title FROM pish_region WHERE city_id =$cityId ORDER BY id ASC";
      $provincesCities = $this->select($sql);
      if(count($provincesCities)){
        $this->resultJsonEncode(['CityRegions'=>$provincesCities,'status'=>true]);
      }else{
        $this->resultJsonEncode(['status'=>false]);
      }
    }
  }
  



  public function __destruct()
  {
    $this->showLastResponse();
  }

}//end removeCompnay class


//create init from class
$showFilterFormInfo = new ShowFormFitlerInfos($conn);
$showFilterFormInfo->showAllProvince();
$showFilterFormInfo->showProvinceCities();
$showFilterFormInfo->showCityRigions();
