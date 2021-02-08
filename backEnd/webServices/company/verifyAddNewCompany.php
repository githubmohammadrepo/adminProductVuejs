<?php
require_once('./../../connection.php');
require_once('./HelperTrait.php');
error_reporting(E_ALL);
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);

class VerifyCompany
{
  use Helpers;

  private $resonsed=false;
  private $conn;
  public function __construct(mysqli $conn)
  {
      $this->conn = $conn;
  }
  
  /**
   * make user as active
   *
   * @param integer $user_id
   * @return boolean
   */
  private function makeUserAsActive(int $user_id):bool{
    $sql = "UPDATE pish_users set activation=0, block=0 WHERE id= $user_id";
    $result =$this->conn->query($sql);
    if($result){
      $count= mysqli_affected_rows($this->conn);
      return $count ? true : false;
    }
    return false;
  }

  /**
   * insert user as memberShop subscriber
   *
   * @param integer $user_id
   * @param float $price
   * @return boolean
   */
  private function insertUserAsSubscriberMemberShip(int $user_id,float $price):bool{
    $unit = 'تومان';
    $sql = "INSERT INTO pish_rsmembership_membership_subscribers (user_id, price, currency, status)
      VALUES ('$user_id', '$price', '$unit', 1)";

    $result = $this->conn->query($sql);
    if($result){
      $count= mysqli_affected_rows($this->conn);
      return $count ? true : false;
    }
    return false;
  }

  private function getFakeCompanyInfo(int $user_id,int $brand_id,$brand_name):array{
    $dev =Array();
    $sql = "";
    if($brand_id && $brand_id>0){
      //when brand_name is  selected from select box
      $sql = "SELECT * FROM pish_phocamaps_marker_fake WHERE brandSelectedname = '$brand_name'";
    }else{
      //when brand_name is not selected
      $sql = "SELECT * FROM pish_phocamaps_marker_fake WHERE user_id = '$user_id'";
    }
    
    $result = $this->conn->query($sql);
    if($result){
      $count = mysqli_num_rows($result);
      if($count){
        for ($i = 0; $i < $result->num_rows; $i++) {
          $dev[$i] = $result->fetch_assoc();
        }
      }
    }
    return $dev;
  }

  private function isCompanyExist(int $user_id):bool{
    $sql = "SELECT * FROM pish_phocamaps_marker_company WHERE user_id = $user_id";
    $result = $this->conn->query($sql);
    if($result){
      $count = mysqli_num_rows($result);
      return $count ? true : false;
    }
    return false;
  }

  private function InsertNewCompany(array $fakeCompanyInfo):bool {
    //prepare data what is need it
    $myBrandSelectedId = $fakeCompanyInfo[0]['brandSelectedname'];
    $myUserId = $fakeCompanyInfo[0]['user_id'];
    $title = $fakeCompanyInfo[0]['title'];
    $ShopName = $fakeCompanyInfo[0]['ShopName'];
    $phone = $fakeCompanyInfo[0]['phone'];
    $MobilePhone = $fakeCompanyInfo[0]['MobilePhone'];
    $OwnerName = $fakeCompanyInfo[0]['OwnerName'];
    $Fax = $fakeCompanyInfo[0]['Fax'];
    $Email = $fakeCompanyInfo[0]['Email'];
    $ShopKind = $fakeCompanyInfo[0]['ShopKind'];
    $sms_confirmed = $fakeCompanyInfo[0]['sms_confirmed'];
    $marketer_user_id = $fakeCompanyInfo[0]['marketer_user_id'];

    $sql = "INSERT INTO pish_phocamaps_marker_company (user_id, title, ShopName, phone, MobilePhone, OwnerName, Fax, Email)
      VALUES ('$myUserId', '$title', '$ShopName', '$phone', '$MobilePhone', '$OwnerName', '$Fax', '$Email')";
    $result = $this->conn->query($sql);
    if($sql){
      $count = mysqli_affected_rows($this->conn);
      return $count ? true : false;
    }
    return false;
  }


  private function updateCuurrentCompany(array $fakeCompanyInfo):bool{
    //prepare data what is need it
    $myBrandSelectedId = $fakeCompanyInfo[0]['brandSelectedname'];
    $myUserId = $fakeCompanyInfo[0]['user_id'];
    $title = $fakeCompanyInfo[0]['title'];
    $ShopName = $fakeCompanyInfo[0]['ShopName'];
    $phone = $fakeCompanyInfo[0]['phone'];
    $MobilePhone = $fakeCompanyInfo[0]['MobilePhone'];
    $OwnerName = $fakeCompanyInfo[0]['OwnerName'];
    $Fax = $fakeCompanyInfo[0]['Fax'];
    $Email = $fakeCompanyInfo[0]['Email'];
    $ShopKind = $fakeCompanyInfo[0]['ShopKind'];
    $sms_confirmed = $fakeCompanyInfo[0]['sms_confirmed'];
    $marketer_user_id = $fakeCompanyInfo[0]['marketer_user_id'];

    $sql = "UPDATE  pish_phocamaps_marker_company SET user_id='$myUserId', title = '$title'
      , ShopName = '$ShopName' , phone = '$phone' ,
      MobilePhone = '$MobilePhone' , OwnerName = '$OwnerName' , Fax = '$Fax' 
      , Email = '$Email' WHERE user_id = '$myBrandSelectedId'";
    $result = $this->conn->query($sql);
    if($result){
      $count = mysqli_affected_rows($this->conn);
      return $count ? true : false;
    }
    return false;
  }


  private function insertHikamarketVendor(array $fakeCompanyInfo,string $brand_name){
    $myUserId = $fakeCompanyInfo[0]['user_id'];
      $title = $fakeCompanyInfo[0]['title'];
      $ShopName = $fakeCompanyInfo[0]['ShopName'];
      $phone = $fakeCompanyInfo[0]['phone'];
      $MobilePhone = $fakeCompanyInfo[0]['MobilePhone'];
      $OwnerName = $fakeCompanyInfo[0]['OwnerName'];
      $Fax = $fakeCompanyInfo[0]['Fax'];
      $Email = $fakeCompanyInfo[0]['Email'];
      $ShopKind = $fakeCompanyInfo[0]['ShopKind'];
      $sms_confirmed = $fakeCompanyInfo[0]['sms_confirmed'];
      $marketer_user_id = $fakeCompanyInfo[0]['marketer_user_id'];
    $sql = "INSERT INTO pish_hikamarket_vendor (vendor_id,vendor_admin_id, vendor_name, vendor_alias, vendor_canonical, vendor_image, vendor_template_id, vendor_site_id) VALUES ('$brand_name','$myUserId', '$title', '$ShopName', '', '', '', '')";
    $result = $this->conn->query($sql);
    if($result){
      $count = mysqli_affected_rows($this->conn);
      return $count ? true : false;
    }
    return false;
  }


  private function getCategory(string $title):array{
    $dev_array = Array();
    $sql = "SELECT * FROM pish_hikashop_category WHERE category_name = '$title'";
    $result = $this->conn->query($sql);
    if($result){
      $count= mysqli_num_rows($result);
     if($count){
       while($row = mysqli_fetch_assoc($result)){
         $dev_array[] =$row;
       }
     }
    }
    return $dev_array;
  }


  private function getLastOrderingBrandCategory(){
    $dev_array = Array();
    $sql = "SELECT * FROM pish_hikashop_category where category_parent_id = 10 ORDER BY category_ordering DESC LIMIT 1";
    $result = $this->conn->query($sql);
    if($result){
      $count = mysqli_num_rows($result);
      if($count){
        while($row= mysqli_fetch_assoc($result)){
          $dev_array[] = $row;
        }
      }
    }
    return $dev_array;
  }

  /**
   * update one category by user_id and category_name
   *
   * @param integer $user_id
   * @param string $title
   * @return boolean
   */
  private function updateCategory(int $user_id,string $title):bool{
    $sql = "UPDATE pish_hikashop_category SET user_id = '$user_id' WHERE category_name = '$title'";
    $result = $this->conn->query($sql);
    if($result){
      $count = mysqli_affected_rows($this->conn);
      return $count ? true : false;
    }
    return false;
  }

  /**
   * inset new category
   *
   * @param array $fakeCompanyInfo
   * @param integer $user_id
   * @param string $title
   * @return boolean
   */
  private function insertNewCategory(array $fakeCompanyInfo,int $user_id,string $title):bool{
    $catOrdering = $fakeCompanyInfo[0]['category_ordering'] + 1;
    $catLeft = $fakeCompanyInfo[0]['category_right'] + 1;
    $catRight = $catLeft + 1;

    $time = time();
    $random = rand(1000000, 10000000);
    $category_namekey = "manufacturer_${time}_${random}";

    $sql = "INSERT INTO pish_hikashop_category (user_id, category_parent_id, category_type, category_name, category_published, category_ordering, category_left, category_right, category_depth, category_namekey)
      VALUES ('$user_id', 10, 'manufacturer', '$title', 1, '$catOrdering', '$catLeft', '$catRight', 2, '$category_namekey')";
    $result =  $this->conn->query($sql);
    if($result){
      $count = mysqli_affected_rows($this->conn);
      return $count ? true : false;
    }
    return false;
  }

  /**
   * insert hikashop category proccess
   *
   * @param integer $user_id
   * @param string $title
   * @return boolean
   */
  private function insertHikashopCategory(int $user_id,string $title):bool{
    //brand was selected from select box
    $categoryInfo = $this->getCategory($title);
    if(count($categoryInfo)){
      //update category
      $update_status = $this->updateCategory($user_id,$title);
      return $update_status ? true : false;

    }else{
      //get last ordering brand category
      $lastOrderingBrandCategory = $this->getLastOrderingBrandCategory();
      if(count($lastOrderingBrandCategory)){
        //insert new category or brand
        $insert_status= $this->insertNewCategory($lastOrderingBrandCategory,$user_id,$title);
        return $insert_status ? true : false;
      }else{
        return false;
      }
    }
  }


  /**
   * main function doing proccess verify one company and add it
   *
   * @return void
   */
  public function showResultVerifyCompanyProccess(){
    $status = $this->transactionProccess('verifyCompany');
    $this->resultJsonEncode(['status'=>$status]);
  }

  /**
   * all proccess verify company
   *
   * @return void
   */
  public function verifyCompany():bool{
    $postedData = $this->postedData();
    if(isset($postedData['verifyCompany'])){
      //prepare data 
      $originalCode = "#&#(&#^(#&@!$7423974hfiehfe#$@!aife";
      $user_id = (int)($this->getInput(isset($postedData['user_id']) ? $postedData['user_id'] : -1));
      $price = (float)($this->getInput(isset($postedData['price']) ? $postedData['price'] : -1));
      $code = (string)($this->getInput(isset($postedData['code']) && $postedData['code']==$originalCode ? $postedData['code'] : -1));
      $brand_id = (int)($this->getInput(isset($postedData['brand_id']) ? $postedData['brand_id'] : -1));
      $brand_name = (string)($this->getInput(isset($postedData['brand_name']) ? $postedData['brand_name'] : -1));

      if($user_id==-1 || $price ==-1 || $code == -1 || $brand_id ==-1 || $brand_name==-1){
        return false;
      }
      //make user as active
      $status_activeUser=$this->makeUserAsActive($user_id);
      //insert user as membershop_subscriber
      $status_subscriber= $this->insertUserAsSubscriberMemberShip($user_id,$price);

      //get all company_fake
      $fakeCompanyInfo = $this->getFakeCompanyInfo($user_id,$brand_id,$brand_name);
      
      //insert company_fake info to real_company
      $status_companyOperation = false;
      if($this->isCompanyExist($user_id)){
        //update company
        $status_companyOperation = $this->updateCuurrentCompany($fakeCompanyInfo);
      }else{
        //insert new company
        $status_companyOperation = $this->InsertNewCompany($fakeCompanyInfo);
      }

      //insert hikamarket_vendor
      $status_hikamarketVendor =$this->insertHikamarketVendor($fakeCompanyInfo,$brand_name);

      //insert brand category or update it
      $title = $fakeCompanyInfo[0]['title'];
      $status_insertCategory = $this->insertHikashopCategory($user_id,$title);

      //validate statuses
      if(
        $status_activeUser==false &&
        $status_subscriber==false &&
        $status_companyOperation==false &&
        $status_hikamarketVendor==false &&
        $status_insertCategory==false
        ){
          return false;
        }
        return true;
    }else{
      return false;
    }
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
          $reusltStatus=call_user_func(array(__CLASS__, $proccessStringName));
          
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

}//end verifyCompany class


//create init from class
$verifyCompany = new VerifyCompany($conn);
$verifyCompany->showResultVerifyCompanyProccess();