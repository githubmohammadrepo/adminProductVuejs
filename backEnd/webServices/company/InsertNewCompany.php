<?php
error_reporting(E_ALL);
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
require_once('./../../connection.php');
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

  private function insertNewRealCompany(){
    
  }
  
  public function insertNewCompanyLevelOne()
  {
    $postedDatat = $this->postedData();
    if(isset($postedDatat['newCompanyLevelOne'])){
      //prepare data
      $userName = isset($postedDatat['userName']) ? $postedDatat['userName'] : null;
      $password= isset($postedDatat['password'])  ? $postedDatat['password'] : null;
      if($userName==null || $password==null || strlen($userName)<4 || strlen($password)<8){
        return;
      }
      //is user exist
      if($this->IsUserExist($userName)==false){
        //inser new users
        if($this->insertNewUser($userName,$password)){
            if($this->getLastId()){
              if($this->insertNewUserGroup()){
                if($this->insertHikashopUser($userName)){
                  $this->resultJsonEncode(['user_id'=>$this->last_id,'status'=>true]);
                  return;
                }
            }
          }
        }
      }
      $this->resultJsonEncode(['status'=>false]);
    }
  }

  /**
   * check is brand mobile is exist
   *
   * @param string $mobile
   * @return boolean
   */
  private function isCompanyMobileExist(string $mobile):bool{
    $mobile = $this->getInput($mobile);
     $sql = "SELECT * FROM  pish_phocamaps_marker_company WHERE user_id IS NOT NULL AND MobilePhone = '$mobile' AND paystatus = 1";
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
    $sql = "SELECT title FROM pish_phocamaps_marker_company INNER JOIN pish_users ON pish_phocamaps_marker_company.user_id=pish_users.id 
    WHERE pish_phocamaps_marker_company.MobilePhone = '$brandmobile' AND pish_phocamaps_marker_company.user_id IS NOT NULL AND pish_users.block= 0";
    $result = $this->conn->query($sql);

    if($result){
      if ($result->num_rows > 0) {
        $status_reslt = true;   
      }
    }

    return $status_reslt;
  }

  private function removeBeforeLikeUserName(int $user_id):bool{
    $user_id = $this->getInput($user_id);
    $sql = "DELETE FROM pish_phocamaps_marker_fake WHERE user_id='$user_id'";
    $result= $this->conn->query($sql);
    if($result){
      return true;
    }else{
      return false;
    }
  }

  private function insertNewFakeCompany(int $brand_id,string $brand_name,string $company_name,string $manager_name,string $company_phone,string $company_mobile,string $company_fax,string $company_email,int $user_id,string $user_name):bool{
    $sql = "INSERT INTO pish_phocamaps_marker_fake (brandSelectedname, user_id, title, ShopName, OwnerName, phone, MobilePhone, Fax, Email)
      VALUES ('$brand_name', '$user_id', '$brand_name', '$company_name', '$manager_name', '$company_phone', '$company_mobile', '$company_fax', '$company_email')";
    $result = $this->conn->query($sql);
    if($result){
      return true;
    }else{
      return false;
    }
  }

  private function saveNewCompany(int $brand_id,$brand_name,string $company_name,string $manager_name,string $company_phone,string $company_mobile,string $company_fax,string $company_email,int $user_id,string $user_name):bool{
    if($this->isMobileDublicateForBlockUser($company_mobile)==false){
      //remvoe before like company with hikahsop user_id inseted before
      $this->removeBeforeLikeUserName($user_id);

      $status_fakeCompany_inserted= $this->insertNewFakeCompany($brand_id, $brand_name, $company_name, $manager_name, $company_phone, $company_mobile, $company_fax, $company_email, $user_id, $user_name);
      //insert fake company
      if($status_fakeCompany_inserted){
        return true;
      }

    }else{
      return false;
    }
  }

  public function insertNewCompanyLevelTwo(){
    $postedDatat = $this->postedData();
    if(isset($postedDatat['insertNewCompanyLeveltwo'])){
      //prepare data
      $brand_id = $postedDatat['brand_id'];
      $brand_name = $postedDatat['brand_name'];
      $company_name = $postedDatat['company_name'];
      $manager_name = $postedDatat['manager_name'];
      $company_phone = $postedDatat['company_phone'];
      $company_mobile = $postedDatat['company_mobile'];
      $company_fax = $postedDatat['company_fax'];
      $company_email = $postedDatat['company_email'];
      $user_id = $postedDatat['user_id'];
      $user_name= $postedDatat['user_name'];
      //check mobile
      if($this->isCompanyMobileExist($company_mobile)){
        //phone exist
        $this->resultJsonEncode(['mobile'=>true,'status'=>false]);
      }else{
        //insert company informations
        if($this->saveNewCompany((int)$brand_id,(string)$brand_name,(string)$company_name,(string)$manager_name,(string)$company_phone,(string)$company_mobile,(string)$company_fax,(string)$company_email,(int)$user_id,(string)$user_name)){
          $this->resultJsonEncode(['inserted'=>true,'status'=>true]);
        }
      }
      //save company
    }
  }

  public function insertOneCompany(){
    $methodType= $_SERVER['REQUEST_METHOD'];
    if($methodType=='POST'){
      echo 'post';
    }
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
    $url = "http://fishopping.ir/serverHypernetShowUnion/adminProduct/webservices/verifyInsertNewCompany.php";
    $post = [
        'userid' => $user_id,
        'price' => $price,
        'unit' => 'تومان',
        'brandSelectedId' => $brandSelectedId
    ];

    $ch = curl_init(); 
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    
    $output = curl_exec($ch);
    echo $output;
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

  public function __destruct()
  {
    $this->showLastResponse();
  }

}

/**
 * create init from class
 */
$updateCompany = new InsertNewCompany($conn);
// $updateCompany->showResultUpdateCompanyLevelInsert();
// // $res = $updateCompany->postedData();
// // echo json_encode(['data'=>$res],JSON_UNESCAPED_UNICODE);
// $updateCompany->verifyInsertNewCompanyLevelThree();
$updateCompany->getPriceRegisterCompany();
$updateCompany->insertNewCompanyLevelOne();
$updateCompany->insertNewCompanyLevelTwo();
// $updateCompany->showLastResponse();
// var_dump($_SERVER['REQUEST_METHOD']);