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
  private $regCode;
  private $resonsed = false;
  private $last_id;
  public function __construct(mysqli $conn)
  {
    $this->conn = $conn;
  }

  private function makeUserActive():bool{
    $sql= "UPDATE pish_users set activation=0, block=0 WHERE id= $this->last_id";
    $result = $this->insert($sql);
    return $result ? true : false;
  }

  /**
   * if user exist
   *
   * @param string $brandusername
   * @return boolean
   */
  private function IsUserExist(string $brandusername):bool{
    $brandusername = $this->getInput($brandusername);
    $sql = "SELECT * FROM pish_users WHERE username = '$brandusername' AND block= '0'";
    $result = $this->conn->query($sql);
    if($result){
      $count= mysqli_num_rows($result);
      return $count ? true : false;
    }
    return false;
  }

  /**
   * insert new user group
   *
   * @param integer $last_id
   * @return boolean
   */
  private function insertNewUserGroup():bool{
    $last_id = $this->getInput($this->last_id);

    $sql2 = "INSERT INTO pish_user_usergroup_map (user_id, group_id) VALUES ('$last_id', '11')";
    $result = $this->conn->query($sql2);
    if($result){
      $count = mysqli_affected_rows($this->conn);
      return $count ? true : false;
    }
    return false;
  }


  private function insertNewUser(string $brandusername,string $brandpass):bool{
    $brandusername = $this->getInput($brandusername);
    $brandpass = md5($this->getInput($brandpass));

    $sql1 = "INSERT INTO pish_users (username, password, block,activation) VALUES ('$brandusername', '$brandpass', '1', '1')";
    $result = $this->conn->query($sql1);
    if($result){
      $count = mysqli_affected_rows($this->conn);
      return $count ? true : false;
    }
    return false;
  }

  private function getLastId():bool{
    //get last user
    $sql="SELECT id FROM pish_users ORDER BY id DESC LIMIT 1";
    $result = $this->conn->query($sql);
    if($result){
      $count= mysqli_num_rows($result);
      if($count){
        $row= mysqli_fetch_assoc($result);
        $this->last_id= $row['id'];
        mysqli_free_result($result);
        return true;
      }      
    }
    return false;
  }
  
  private function insertHikashopUser(string $brandusername):bool 
  {
    $last_id = $this->getInput($this->last_id);
    $time = time();
    $email = $brandusername . '@hypernetshow.com';
    $sql3 = "INSERT INTO `pish_hikashop_user`(`user_cms_id`, `user_email`, `user_created`) VALUES ('$last_id', '$email', '$time')";
    $result = $this->conn->query($sql3);
    if($result){
      $count= mysqli_affected_rows($this->conn);
      return $count ? true : false;
    }
    return false;
  }
  
  /**
   * insert new Store Level One
   *
   * @return boolean
   */
  private function insertNewStoreLevelOne():bool
  {
      $postedDatat = $this->postedData();
      //prepare data
      $userName = isset($postedDatat['userName']) ? $postedDatat['userName'] : null;
      $password= isset($postedDatat['password'])  ? $postedDatat['password'] : null;
      if($userName==null || $password==null || strlen($userName)<4 || strlen($password)<8){
        return false;
      }
      //is user exist
      if($this->IsUserExist($userName)==false){
        //inser new users
        if($this->insertNewUser($userName,$password)){
            if($this->getLastId()){
              if($this->insertNewUserGroup()){
                if($this->insertHikashopUser($userName)){
                  return true;
                }
            }
          }
        }
      }
      $this->resultJsonEncode(['username'=>true,'status'=>false]);
      return false;
  }

  /**
   * check is brand mobile is exist
   *
   * @param string $mobile
   * @return boolean
   */
  private function isCompanyMobileExist(string $mobile):bool{
    $mobile = $this->getInput($mobile);
    $sql = "SELECT * FROM  pish_phocamaps_marker_store WHERE  MobilePhone = '$mobile'";
    $result = $this->conn->query($sql);
    if($result){
      if ($result->num_rows > 0) {
        return true;
      }      
    }
    return false;
  }

  private function isMobileDublicateForBlockUser(string $brandmobile):bool{
    $status_reslt = false;
    $sql = "SELECT title FROM pish_phocamaps_marker_store INNER JOIN pish_users ON pish_phocamaps_marker_store.user_id=pish_users.id 
    WHERE pish_phocamaps_marker_store.MobilePhone = '$brandmobile' AND pish_phocamaps_marker_store.user_id IS NOT NULL AND pish_users.block= 0";
    $result = $this->conn->query($sql);

    if($result){
      if ($result->num_rows > 0) {
        $status_reslt = true;   
      }
    }

    return $status_reslt;
  }



  private function insertRealNewStore(
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
    if($selectedStore !=0){
      return false;
    }

    $sql= "";
    //prepare data
    if($selectedProvince && $selectedRegion && $selectedCity){
      $sql = "INSERT INTO pish_phocamaps_marker_store (user_id, RegionID,province,city,title,ShopName, phone, MobilePhone, OwnerName, Address, latitude, longitude)
      VALUES ('$this->last_id', '$selectedRegion','$selectedProvince','$selectedCity', '$ShopName','$ShopName', '$phone', '$MobilePhone', '$ManagerName', '$Address', '$latitude', '$longitude')";
    }else if($selectedProvince && $selectedCity){
      $sql = "INSERT INTO pish_phocamaps_marker_store (user_id, province,city,title,ShopName, phone, MobilePhone, OwnerName, Address, latitude, longitude)
      VALUES ('$this->last_id', '$selectedProvince','$selectedCity', '$ShopName','$ShopName', '$phone', '$MobilePhone', '$ManagerName', '$Address', '$latitude', '$longitude')";
    }else if($selectedProvince){
      $sql = "INSERT INTO pish_phocamaps_marker_store (user_id, province,title,ShopName, phone, MobilePhone, OwnerName, Address, latitude, longitude)
      VALUES ('$this->last_id', '$selectedProvince','$ShopName', '$phone', '$MobilePhone', '$ManagerName', '$Address', '$latitude', '$longitude')";
    }else{
      $sql = "INSERT INTO pish_phocamaps_marker_store (user_id,title,ShopName, phone, MobilePhone, OwnerName, Address, latitude, longitude)
      VALUES ('$this->last_id','$ShopName','$ShopName', '$phone', '$MobilePhone', '$ManagerName', '$Address', '$latitude', '$longitude')";
    }
    //prepare sql
    $result = $this->conn->query($sql);
    if($result){
      return true;
    }else{
      return false;
    }
  }

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
      , latitude = '$latitude' , longitude = '$longitude' WHERE id = '$selectedStore'";
    }else if($selectedProvince && $selectedCity){
      $sql = "UPDATE pish_phocamaps_marker_store SET user_id='$this->last_id', RegionID = '$selectedRegion', 
      province = '$selectedProvince',city = '$selectedCity',
      title='$ShopName', ShopName = '$ShopName' , phone = '$phone' ,
      MobilePhone = '$MobilePhone' , OwnerName = '$ManagerName' , Address = '$Address' 
      , latitude = '$latitude' , longitude = '$longitude' WHERE id = '$selectedStore'";
    }else if($selectedProvince){
      $sql = "UPDATE pish_phocamaps_marker_store SET user_id='$this->last_id', RegionID = '$selectedRegion', 
      province = '$selectedProvince',city = '$selectedCity',
      title='$ShopName', ShopName = '$ShopName' , phone = '$phone' ,
      MobilePhone = '$MobilePhone' , OwnerName = '$ManagerName' , Address = '$Address' 
      , latitude = '$latitude' , longitude = '$longitude' WHERE id = '$selectedStore'";
    }else{
      $sql = "UPDATE pish_phocamaps_marker_store SET user_id='$this->last_id', RegionID = '$selectedRegion', 
      province = '$selectedProvince',city = '$selectedCity',
      title='$ShopName', ShopName = '$ShopName' , phone = '$phone' ,
      MobilePhone = '$MobilePhone' , OwnerName = '$ManagerName' , Address = '$Address' 
      , latitude = '$latitude' , longitude = '$longitude' WHERE id = '$selectedStore'";
    }
    
    //prepare sql
    $result = $this->conn->query($sql);
    if($result){
      return true;
    }else{
      return false;
    }
  }

  private function insertRememberShipSubscriber():bool{
    $sql = "INSERT INTO pish_rsmembership_membership_subscribers (user_id, price, currency, status)
    VALUES ('$this->last_id', '0', 'تومان', 1)";
    $status_insert = $this->insert($sql);
    return $status_insert ? true : false;
  }
  
  private function saveNewStore(
    int $selectedStore,
    int $selectedRegion,
    int $selectedProvince,
    int $selectedCity,
    string $ShopName,
    string $ManagerName,
    string $phone,
    string $MobilePhone,
    string $address,
    string $lat,
    string $lng):bool{
    if($this->isMobileDublicateForBlockUser($MobilePhone)==false){
      //remvoe before like company with hikahsop user_id inseted before
      //make user as axtive
      if($this->makeUserActive()){

        //insert remembership subscriber
        if($this->insertRememberShipSubscriber()){
            if($selectedStore==0){
                //insert real store
                $inserted_store = $this->insertRealNewStore(
                $selectedStore,
                $selectedRegion,
                $selectedProvince,
                $selectedCity,
                $ShopName,
                $ManagerName,
                $phone,
                $MobilePhone,
                $address,
                $lat,
                $lng
              );
              if($inserted_store){
                return true; 
              }else{
                return false;
              }
            }else{
              //udpate

              $status_updateStore= $this->updateRealNewStore( $selectedStore,
              $selectedRegion,
              $selectedProvince,
              $selectedCity,
              $ShopName,
              $ManagerName,
              $phone,
              $MobilePhone,
              $address,
              $lat,
              $lng);
              //insert fake company
              if($status_updateStore){
                return true;
              }else{
                return false;
              }
            }
            
        }else{
          return false;
        }
      }else{
        return false;
      }
      
      

    }else{
      $this->resultJsonEncode(['mobile'=>true,'status'=>false]);

    }
  }

  public function insertNewStoreLevelTwo():bool{
      $postedDatat = $this->postedData();
      //prepare data

      $selectedStore = (int)$this->getInput(isset($postedDatat['selectedStore']) ? $postedDatat['selectedStore'] : null);
      $selectedRegion = (int)$this->getInput(isset($postedDatat['selectedRegion']) ? $postedDatat['selectedRegion'] : null);
      $selectedProvince = (int)$this->getInput(isset($postedDatat['selectedProvince']) ? $postedDatat['selectedProvince'] : null);
      $selectedCity = (int)$this->getInput(isset($postedDatat['selectedCity']) ? $postedDatat['selectedCity'] : null);
      $ShopName = (string)$this->getInput(isset($postedDatat['ShopName']) ? $postedDatat['ShopName'] : null);
      $ManagerName = (string)$this->getInput(isset($postedDatat['ManagerName']) ? $postedDatat['ManagerName'] : null);
      $phone = (string)$this->getInput(isset($postedDatat['phone']) ? $postedDatat['phone'] : null);
      $MobilePhone = (string)$this->getInput(isset($postedDatat['MobilePhone']) ? $postedDatat['MobilePhone'] : null);
      $address = (string)$this->getInput(isset($postedDatat['address']) ? $postedDatat['address'] : null);
      $lat = (string)$this->getInput(isset($postedDatat['lat']) ? $postedDatat['lat'] : null);
      $lng= (string)$this->getInput(isset($postedDatat['lng']) ? $postedDatat['lng'] : null);

      //check mobile
      if($selectedStore==0){
        //insert new store
        //phone exist
        if($this->isCompanyMobileExist($MobilePhone)){

          $this->resultJsonEncode(['mobile'=>true,'status'=>false]);
          return false;
        }else{

          //insert new store
          if($this->saveNewStore($selectedStore,$selectedRegion,$selectedProvince,$selectedCity,$ShopName,$ManagerName,$phone,$MobilePhone,$address,$lat,$lng)){
            
            return true;

          }else{

            return false;
          }
        }
      }else{
        //upate store informations
        if($this->saveNewStore($selectedStore,$selectedRegion,$selectedProvince,$selectedCity,$ShopName,$ManagerName,$phone,$MobilePhone,$address,$lat,$lng)){
          return true;

        }else{

          return false;
        }
      }
      //save company
  }


  public function getPriceRegisterCompany(){
    $postedDatat= $this->postedData();
    if(isset($postedDatat['getRegisterCompanyPrice'])){
      $sql = "SELECT price FROM pish_rsmembership_memberships WHERE id = 3";
      $result = $this->conn->query($sql);
      $dev = array();
      if ($result->num_rows > 0) {

          // output data of each row
          for ($i = 0; $i < $result-> num_rows; $i++)
          {
              $dev[$i] = $result->fetch_assoc();
          }

          $price = $dev[0]['price'];
          $price = $price / 10;
          $this->resultJsonEncode(['price'=>$price,'status'=>true]);
      }else{
        $this->resultJsonEncode(['status'=>false]);
      }
    }
  }

  private function postCurlVerifyInsertNewCompany(int $user_id,float $price,string $brandSelectedId):bool{
    // $url = "http://fishopping.ir/serverHypernetShowUnion/SavePayerInfo(changed).php";
    $url = "http://fishopping.ir/serverHypernetShowUnion/adminProduct/webservices/verifyInsertNewStore.php";
    $post = [
        'userid' => $user_id,
        'price' => $price,
        'unit' => 'تومان',
        'storeidselected' => $brandSelectedId
    ];

    $ch = curl_init(); 
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    
    $output = curl_exec($ch);
    if($output=='ok'){
      return true;
    }
    return false;
  }

  public function verifyInsertNewCompanyLevelThree(){
    $postedData = $this->postedData();
    if(isset($postedData['verifyInsertNewCOmpanyLevelThree'])){
      $Orivinalcode = '#&#(&#^(#&@!$7423974hfiehfe#$@!aife';
      $code = isset($postedData['code']) ? $postedData['code'] : null;
      $user_id = isset($postedData['user_id']) ? $postedData['user_id'] : null;
      $price = isset($postedData['price']) ? $postedData['price'] : null;
      $brandSelectedId = isset($postedData['brandSelectedId']) ? $postedData['brandSelectedId'] : null;

      //if validation passes
      if($code ==null || (string)$code !==(string)$Orivinalcode || $user_id==null || $price==null || $brandSelectedId==null){
        $this->resultJsonEncode(['inserted'=>false,'status'=>false]);
      }else{
        $statusInsert = $this->postCurlVerifyInsertNewCompany((int)$user_id,(float)$price,(string)$brandSelectedId);
        if($statusInsert==true){
          $this->resultJsonEncode(['inserted'=>true,'status'=>true]);
        }else{
          $this->resultJsonEncode(['inserted'=>false,'status'=>false]);
        }
      }

    }
  }


  /**
   * doing proccess insert new stoer
   */
  protected function proccessInsertNewStore():bool{

    //insert level one
    $status_insert_levelOne = $this->insertNewStoreLevelOne();
    if($status_insert_levelOne){
      //insert level two
      $status_insert_levelTwo = $this->insertNewStoreLevelTwo();

      if($status_insert_levelTwo){
        return true; 
      }else{
        return false;
      }

    }else{
      return false;
    }
  }


  public function showResultInsertNewStore(){
    $status = $this->transactionProccess('proccessInsertNewStore');
    if($status){
      $this->resultJsonEncode(['inserted'=>true,'status'=>true]);
    }else{
      $this->resultJsonEncode(['inserted'=>false,'status'=>false]);
    }
  }

  

  /**
   * transaction proccess
   *
   * @param [type] $proccessStringName
   * @param [type] $catOrdering
   * @param [type] $catLeft
   * @param [type] $catRight
   * @param [type] $category_namekey
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
$updateCompany->showResultInsertNewStore();

