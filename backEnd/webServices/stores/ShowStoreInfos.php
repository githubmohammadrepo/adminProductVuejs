<?php
header("Access-Control-Allow-Origin: *");
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Allow-Headers: *');


require_once('./../../../connection.php');
require_once('./HelperTrait.php');
error_reporting(E_ALL);
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);

class ShowStoreInfos
{
  use Helpers;

  private $resonsed=false;
  private $conn;
  public function __construct(mysqli $conn)
  {
      $this->conn = $conn;
  }

  /**
   * get count sotre by filter
   *
   * @param [type] $province_id
   * @param integer $city_id
   * @param integer $region_id
   * @param integer $offset
   * @param integer $count
   * @return integer
   */
  private function getStoreCountint($province_id,int $city_id,int $region_id,int $offset,int $count):int {
    $sql ="";
    if($region_id ==-1){
      //without region_id
      $sql = "SELECT COUNT(*) as count FROM pish_phocamaps_marker_store WHERE province = '$province_id' AND city = '$city_id'  ORDER BY id ASC limit $offset,$count";
    }else{
      //with region_id
      $sql = "SELECT COUNT(*) as count FROM pish_phocamaps_marker_store WHERE province = '$province_id' AND city = '$city_id' AND RegionID= '$region_id' ORDER BY id ASC limit $offset,$count";
    }

    $dev_array = $this->select($sql);
    if(count($dev_array)){
      return $dev_array[0]['count'];
    }else{
      return 0;
    }
  }

  /**
   * get all filtred stores
   *
   * @param integer $province_id
   * @param integer $city_id
   * @param integer $region_id
   * @return array
   */
  private function getAllFilteredStores(int $province_id,int $city_id,int $region_id,int $offset,int $count):array {
    $dev_array = Array();
    $sql ="";
    if($region_id ==-1){
      //without region_id
      $sql = "SELECT new.*,pish_province.id as province_id,pish_province.name as province_name,pish_city.id as city_id,pish_city.name as city_name,pish_region.id as region_id,pish_region.title as region_title FROM (SELECT id,user_id,title,ShopName,phone,MobilePhone,latitude,longitude,ManagerName,Address,RegionID,province,city FROM pish_phocamaps_marker_store WHERE province = '$province_id' AND city = '$city_id'  ORDER BY id ASC limit $offset,$count)AS new
      LEFT JOIN pish_province ON new.province = pish_province.id
      LEFT JOIN pish_city ON new.city = pish_city.id
      LEFT JOIN pish_region ON new.RegionID = pish_region.id";
    }else{
      //with region_id
      $sql = "SELECT new.*,pish_province.id as province_id,pish_province.name as province_name,pish_city.id as city_id,pish_city.name as city_name,pish_region.id as region_id,pish_region.title as region_title FROM (SELECT id,user_id,title,ShopName,phone,MobilePhone,latitude,longitude,ManagerName,Address,RegionID,province,city FROM pish_phocamaps_marker_store WHERE province = '$province_id' AND city = '$city_id' AND RegionID = '$region_id' ORDER BY id ASC limit $offset,$count)AS new
      LEFT JOIN pish_province ON new.province = pish_province.id
      LEFT JOIN pish_city ON new.city = pish_city.id
      LEFT JOIN pish_region ON new.RegionID = pish_region.id";
    }

    $dev_array = $this->select($sql);

    return $dev_array;
  }

  /**
   * show all provinces
   *
   * @return array
   */
  public function showAllStoresByFilter():void{
    $postedData = $this->postedData();

    if(isset($postedData['showALlStoreInfos'])){
      $province_id = $this->getPostRequestField('selectedProvince','int',-1);
      $city_id = $this->getPostRequestField('selectedCity','int',-1);
      $region_id = $this->getPostRequestField('selectedRegion','int',-1,null);
      $offset = $this->getPostRequestField('offset','int',-1);
      $count = $this->getPostRequestField('paginationCount','int',-1);

      if($province_id != -1 && $city_id != -1 && $offset !=-1 && $offset !=-1){
        $filteredStores = $this->getAllFilteredStores((int)$province_id,(int)$city_id,(int)$region_id,(int)$offset,(int)$count);
        $storeCount = $this->getStoreCountint((int)$province_id,(int)$city_id,(int)$region_id,(int)$offset,(int)$count);

        $this->resultJsonEncode(['stores'=>$filteredStores,'count'=>$storeCount,'status'=>true]);
      }else{
        $this->resultJsonEncode(['status'=>false]);
      }
      
    }
  }



  

  /**
   * retun posted request file with type and default value
   *
   * @param string $name
   * @param string $type
   * @param [type] $defaultValue
   * @return void
   */
  private function getPostRequestField(string $name,string $type,$defaultValue,$blackBox=-INF){
    $postedData = $this->postedData();
    if($blackBox != -INF){
      if(trim($postedData[$name]) ==$blackBox) {
        return $defaultValue;
      }
    }

    if($type=='int'){
      return (int)($this->getInput(isset($postedData[$name]) ? $postedData[$name] : $defaultValue));
    }else if($type=='string'){
      return (string)($this->getInput(isset($postedData[$name]) ? $postedData[$name] : $defaultValue));
    }else if($type=='bool'){
      return (bool)($this->getInput(isset($postedData[$name]) ? $postedData[$name] : $defaultValue));
    }else{
      return ($this->getInput(isset($postedData[$name]) ? $postedData[$name] : $defaultValue));
    }

    
  }

  

  
/**
 * start section get all stores without filters
 */

 /**
  * coutn stores without any filters
  *
  * @return void
  */
  private function getStoreCountWihoutFilter():int{
    $dev_array = Array();
    $sql = "SELECT COUNT(*) as count FROM pish_phocamaps_marker_store  ORDER BY id ASC";
    $dev_array  = $this->select($sql);
    if(count($dev_array)){
      return $dev_array[0]['count'];
    }else{
      return 0;
    }
  }

  /**
   * get all store without any filter
   *
   * @return void
   */
  private function getAllStoresWithoutFitler(int $offset,int $count):array{
    $dev_array= Array();
    $sql = "SELECT new.*,pish_province.id as province_id,pish_province.name as province_name,pish_city.id as city_id,pish_city.name as city_name,pish_region.id as region_id,pish_region.title as region_title FROM (SELECT id,user_id,title,ShopName,phone,MobilePhone,latitude,longitude,ManagerName,Address,RegionID,province,city FROM pish_phocamaps_marker_store  ORDER BY id ASC LIMIT $offset,$count)AS new
    LEFT JOIN pish_province ON new.province = pish_province.id
    LEFT JOIN pish_city ON new.city = pish_city.id
    LEFT JOIN pish_region ON new.RegionID = pish_region.id";

    $dev_array = $this->select($sql);
    return $dev_array;
  }


  /**
   * show result get stores
   *
   * @return void
   */
  public function showALlStoresWithoutFilters(){
    $postedData = $this->postedData();
    if(isset($postedData['getAllStoreWhoutFitler'])){
      $offset= $this->getPostRequestField('offset','int',-1);
      $count= $this->getPostRequestField('count','int',-1);

      $stores = $this->getAllStoresWithoutFitler((int)$offset,(int)$count);
      $count = $this->getStoreCountWihoutFilter();

      $this->resultJsonEncode(['stores'=>$stores,'count'=>$count,'status'=>true]);
    }
  }

  
 
/**
 * end section get all stores without filters
 */




 /**
  * start section search store by search input
  */



  /**
   * get count store search by input search
   *
   * @param string $search
   * @return integer
   */
  private function getCountStoreSearrchByInput(string $search):int {
    $dev_array = Array();
    echo $sql= "SELECT COUNT(*) as count FROM pish_phocamaps_marker_store WHERE ShopName like '%$search%'  ORDER BY id ASC";
    
    $dev_array = $this->select($sql);
    if(count($dev_array)){
      return $dev_array[0]['count'];
    }else{
      return 0;
    }

  }

  
  /**
   * get all store like search input by offset and count
   *
   * @param integer $offset
   * @param integer $count
   * @param string $search
   * @return array
   */
  private function getStoreLikeSearchByInput(int $offset,int $count,string $search):array{
    $dev_array = Array();
    echo $sql= "SELECT new.*,pish_province.id as province_id,pish_province.name as province_name,pish_city.id as city_id,pish_city.name as city_name,pish_region.id as region_id,pish_region.title as region_title FROM (SELECT id,user_id,title,ShopName,phone,MobilePhone,latitude,longitude,ManagerName,Address,RegionID,province,city FROM pish_phocamaps_marker_store WHERE ShopName like '%$search%'  ORDER BY id ASC LIMIT $offset,$count)AS new
    LEFT JOIN pish_province ON new.province = pish_province.id
    LEFT JOIN pish_city ON new.city = pish_city.id
    LEFT JOIN pish_region ON new.RegionID = pish_region.id";
    
    $dev_array = $this->select($sql);
    return $dev_array;
  }


  public function showSearchStoreByInput()
  {
    $postedData = $this->postedData();
    if(isset($postedData['searchStoreBySearchInput'])){
      $offset= $this->getPostRequestField('offset','int',-1);
      $countPerPage= $this->getPostRequestField('count','int',-1);
      $search = $this->getPostRequestField('search','string','');

      //get search count
      $count = $this->getCountStoreSearrchByInput((string)$search);

      //get stores
      $stores = $this->getStoreLikeSearchByInput((int)$offset,(int)$countPerPage,(string)$search);

      $this->resultJsonEncode(['stores'=>$stores,'count'=>$count,'status'=>true]);
    }
  }




  /**
   * end section search stoer by search input
   */



  public function __destruct()
  {
    $this->showLastResponse();
  }

}//end removeCompnay class


//create init from class
$showStoreInfos = new ShowStoreInfos($conn);
$showStoreInfos->showAllStoresByFilter();
$showStoreInfos->showALlStoresWithoutFilters();
$showStoreInfos->showSearchStoreByInput();