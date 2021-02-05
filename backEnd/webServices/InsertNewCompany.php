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
   * insert new user
   *
   * @param integer $last_id
   * @return boolean
   */
  private function insertNewUser():bool{
    $last_id = $this->getInput($this->last_id);

    $sql2 = "INSERT INTO pish_user_usergroup_map (user_id, group_id) VALUES ('$last_id', '11')";
    $result = $this->conn->query($sql2);
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
  
  public function insertNewCompanyLevelOne()
  {
    $postedDatat = $this->postedData();
    if(isset($postedDatat['newCompanyLevelOne'])){
      //prepare data
      $userName = $postedDatat['userName'];
      $password= $postedDatat['password'];
      //is user exist
      if($this->IsUserExist($userName)==false){
        //inser new users
        if($this->getLastId()){
          if($this->insertNewUser()){
            if($this->insertHikashopUser($userName)){
              $this->resultJsonEncode(['user_id'=>$this->last_id,'status'=>true]);
              return;
            }
          }
        }
      }
      $this->resultJsonEncode(['status'=>false]);
    }
  }

  public function insertOneCompany(){
    $methodType= $_SERVER['REQUEST_METHOD'];
    if($methodType=='POST'){
      echo 'post';
    }
  }

}

/**
 * create init from class
 */
$updateCompany = new InsertNewCompany($conn);
// $updateCompany->showResultUpdateCompanyLevelInsert();
// // $res = $updateCompany->postedData();
// // echo json_encode(['data'=>$res],JSON_UNESCAPED_UNICODE);

$updateCompany->insertNewCompanyLevelOne();
$updateCompany->showLastResponse();
// var_dump($_SERVER['REQUEST_METHOD']);