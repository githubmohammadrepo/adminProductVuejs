<?php
error_reporting(E_ALL);
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
require_once('./../../connection.php');
class UpdateCompany
{
  private $conn;
  private $regCode;
  private $resonsed = false;
  public function __construct(mysqli $conn)
  {
    $this->conn = $conn;
  }

  private function removeLikeFakeCompany(int $user_id){
    $user_id = $this->getInput($user_id);
    if($user_id=='0'){
      $user_id=null;
    }
    $sql = "DELETE FROM `pish_phocamaps_marker_fake` WHERE user_id = $user_id";
    $result = $this->conn->query($sql);
    if($result){
      return true;
    }else{
      return false;
    }
  }

  /**
   * insert fake one company
   *
   * @param integer $user_id
   * @param string $title
   * @param string $shopName
   * @param string $phone
   * @param string $mobile
   * @param string $owner
   * @param string $address
   * @return bool
   */
  private function insertFakeCompany(int $user_id,string $title,string $shopName,string $phone,string $mobile,string $owner,string $address):bool{
    $user_id = trim($this->getInput($user_id));
    $title = trim($this->getInput($title));
    $shopName = trim($this->getInput($shopName));
    $phone = trim($this->getInput($phone));
    $mobile = trim($this->getInput($mobile));
    $owner = trim($this->getInput($owner));
    $address = trim($this->getInput($address));
    $regCode= rand(10000,99999);
    
    
    $this->removeLikeFakeCompany((int)$user_id);

    $sql = "INSERT INTO pish_phocamaps_marker_fake
    (user_id,title,ShopName,phone,MobilePhone,OwnerName,Address,RegCode)
    VALUES('$user_id','$title','$shopName','$phone','$mobile','$owner','$address','$regCode')";
    $result = $this->conn->query($sql);
    if($result){
      $this->regCode = $regCode;
      $count= mysqli_affected_rows($this->conn);
      return $count ? true : false;
    }
    return false;
  }

  /**
   * sent generated code to owner company
   *
   * @param string $mobile
   * @param string $text
   * @return boolean
   */
  private function sentSms(string $mobile, string $text): bool
  {
    try {
      $user = "rjabrisham";
      $pass = "rj9354907433";

      $client = new SoapClient("http://188.0.240.110/class/sms/wsdlservice/server.php?wsdl");
      $user = $user;
      $pass = $pass;
      $fromNum = "500010708120";
      $toNum = array($mobile);
      $pattern_code = "nnrkxkp1zk";
      $input_data = array(
        "verification-code" => $text
      );

      $res =  $client->sendPatternSms($fromNum, $toNum, $user, $pass, $pattern_code, $input_data);
      if(is_numeric($res)){
        return true;
      }else{
        return false;
      }
    } catch (SoapFault $ex) {
      return false;
    }
    return false;
  }

  
  /**
   * get input and make sure it secure
   */
  private function getInput($input)
  {
      $result = htmlspecialchars(strip_tags($input));
      if (preg_match('/<>;:\$^/', $result)) {
          return;
      } else {
          return $result;
      }
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
   * showresult update company level insert to fake table
   *
   * @return void
   */
  public function showResultUpdateCompanyLevelInsert()
  {
    $postedData = $this->postedData();
    if(isset($postedData['insertFakeUpdateConpany'])){
      $user_id = $postedData['user_id'];
      $title = $postedData['brandName'];
      $shopName = $postedData['companyName'];
      $phone = $postedData['phone'];
      $mobile = $postedData['mobile'];
      $owner = $postedData['managerName'];
      $address = $postedData['address'];
      
      $insertedStatus = $this->insertFakeCompany((int)$user_id,(string)$title,(string)$shopName,(string)$phone,(string)$mobile,(string)$owner,(string)$address);
      if($insertedStatus==true){
        $result = $this->sentSms($mobile,$this->regCode);
        $this->resultJsonEncode(['status'=>$result]);
        return;
      }else{
        $this->resultJsonEncode(['status'=>false]);
        return;
      }
    }

  }


  /**
   * section just show output methods
   */
  public function resultJsonEncode($data)
  {
      $this->resonsed = true;
      if (gettype($data) == 'array') {
          echo json_encode($data, JSON_UNESCAPED_UNICODE);
      } else {
          echo json_encode([$data], JSON_UNESCAPED_UNICODE);
      }
  }


  /**
   * get fake company inserted
   *
   * @param integer $user_id
   * @return array
   */
  private function getFakeInsertedCompany(int $user_id):array
  {
    $user_id= $this->getInput($user_id);
    $sql = "SELECT `pish_phocamaps_marker_fake`.* 
      FROM `pish_phocamaps_marker_fake`
      INNER JOIN `pish_users` ON `pish_phocamaps_marker_fake`.user_id= $user_id";
    $result = $this->conn->query($sql);
    if($result){
      $count= mysqli_num_rows($result);
      if($count){
        $row = mysqli_fetch_assoc($result);
        return $row;
      }
    }
    return Array();
  }

  private function updateRealCompany(int $user_id,string $title,string $shopName,string $phone,string $mobile,string $owner,string $address,int $company_id)
  {
    $user_id = $this->getInput($user_id);
    $title = $this->getInput($title);
    $shopName = $this->getInput($shopName);
    $phone = $this->getInput($phone);
    $mobile = $this->getInput($mobile);
    $owner = $this->getInput($owner);
    $address = $this->getInput($address);
    $company_id = $this->getInput($company_id);

    $sql = "UPDATE pish_phocamaps_marker_company
    SET title = '$title',
    ShopName = '$shopName',
    phone ='$phone',
    ManagerName = '$owner',
    MobilePhone = '$mobile',
    OwnerName = '$owner',
    Address = '$address',
    sms_confirmed = '1'
    
    WHERE id = '$company_id'";

    $result = $this->conn->query($sql);
    if($result){
      return true;
    }else{
      return false;
    }
  }


  private function updateUserName(string $ownerName,int $user_id){
    $user_id = $this->getInput($user_id);
    $ownerName = $this->getInput($ownerName);
    $sql = "UPDATE pish_users SET name = '$ownerName' WHERE id=$user_id";
    $result = $this->conn->query($sql);
    if($result){
      return true;
    }
    return false;
  }

  public function confirmSmsCodeAndUpdaeCompany()
  {
    $postedData = $this->postedData();
    if(isset($postedData['confirmSmsCode'])){
      // var_dump($postedData);
    $user_id= (int)$this->getInput($postedData['user_id']);
    $regCode = (int)$this->getInput($postedData['regCode']);
    $company_id =(int) $this->getInput($postedData['id']);

    $fakeCompany = $this->getFakeInsertedCompany($user_id);
    if(count($fakeCompany)){
      //check regCode
      $inserted_regCode = $fakeCompany['RegCode'];
      if(trim($regCode) == trim($inserted_regCode)){
        //regCode is true update real company
        //prepare data
        $title = $fakeCompany['title'];
        $user_id = $fakeCompany['user_id'];
        $title = $fakeCompany['title'];
        $OwnerName = $fakeCompany['OwnerName'];
        $ShopName = $fakeCompany['ShopName'];
        $phone = $fakeCompany['phone'];
        $MobilePhone = $fakeCompany['MobilePhone'];
        $Address = $fakeCompany['Address'];
        $id = $fakeCompany['id'];

        //update real company
        $updatedCompanyStatus = $this->updateRealCompany($user_id,$title,$ShopName,$phone,$MobilePhone,$OwnerName,$Address,$company_id);
        if($updatedCompanyStatus==true){
          //update user
          $updateUserStatus= $this->updateUserName($OwnerName,$user_id);
          if($updateUserStatus==true){
            $this->resultJsonEncode(['status'=>true]);
            return;
          }else{
            $this->resultJsonEncode(['status'=>false]);
            return;
          }
        }else{
          $this->resultJsonEncode(['status'=>false]);
        }

      }
    }else{
      //error get fake company inserted before
      $this->resultJsonEncode(['status'=>false]);
    }
  }
  }

  public function showLastResponse(){
    if($this->resonsed==false){
      $this->resultJsonEncode(['status'=>false]);
    }
  }
  

}

/**
 * create init from class
 */
$updateCompany = new UpdateCompany($conn);
$updateCompany->showResultUpdateCompanyLevelInsert();
// $res = $updateCompany->postedData();
// echo json_encode(['data'=>$res],JSON_UNESCAPED_UNICODE);
$updateCompany->confirmSmsCodeAndUpdaeCompany();

$updateCompany->showLastResponse();