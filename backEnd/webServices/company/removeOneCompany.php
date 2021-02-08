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
   * is user exist
   *
   * @param integer $user_id
   * @return boolean
   */
  private function isUserExist(int $user_id):bool{
    $user_id = $this->getInput(($user_id));
    $sql = "SELECT * FROM pish_users  WHERE id ='$user_id' ORDER BY id DESC LIMIT 1";
    $result = $this->conn->query($sql);
    if($result){
      $count = mysqli_num_rows($result);
      return $count ? true : false;
    }
    return false;
  }


  /**
   * get hikashop user by cmd user_id
   *
   * @param integer $user_id
   * @return integer
   */
  private function getHikashopUser(int $user_id):int{
    $sql = "SELECT pish_hikashop_user.user_id FROM pish_hikashop_user WHERE pish_hikashop_user.user_cms_id ='$user_id' ORDER BY user_id DESC LIMIT 1";
    $result= $this->conn->query($sql);
    if($result){
      $count = mysqli_num_rows($result);
      if($count){
        $row = mysqli_fetch_assoc($result);
        return $row['user_id'];
      }
    }
    return 0;
  }

  
  /**
   * remove category by user_id
   *
   * @param integer $user_id
   * @return boolean
   */
  private function removeHikashopCategory(int $user_id):bool{
    $sql = "DELETE FROM pish_hikashop_category  WHERE user_id = '$user_id'  ORDER BY category_id DESC LIMIT 1";
    $result= $this->conn->query($sql);
    if($result){
      return true;
    }
    return false;
  }

  /**
   * remove company by user_id
   *
   * @param integer $user_id
   * @return boolean
   */
  private function removeCompany(int $user_id):bool{
    $sql = "DELETE FROM `pish_phocamaps_marker_company` WHERE `user_id` =  '$user_id' ORDER BY id desc LIMIT 1";
    $result= $this->conn->query($sql);
    if($result){
      return true;
    }
    return false;
  }


  /**
   * remove hikamarket_vendor
   *
   * @param integer $user_id
   * @return boolean
   */
  private function removeHikamarketVendor(int $user_id):bool{
    $sql = "DELETE FROM `pish_hikamarket_vendor` WHERE vendor_admin_id = '$user_id' ORDER BY vendor_id DESC LIMIT 1";
    $result= $this->conn->query($sql);
    if($result){
      return true;
    }
    return false;
  }

  /**
   * remove membershipSubscriber
   *
   * @param integer $user_id
   * @return boolean
   */
  private function removeMembershipSubscriber(int $user_id):bool{
    $sql = "DELETE FROM `pish_rsmembership_membership_subscribers` WHERE user_id= '$user_id'  ORDER BY id LIMIT 1";
    $result= $this->conn->query($sql);
    if($result){
      return true;
    }
    return false;
  }

  /**
   * remove user group map
   *
   * @param integer $user_id
   * @return boolean
   */
  private function removeUserGroupMap(int $user_id):bool{
    $sql = "DELETE FROM pish_user_usergroup_map  WHERE user_id = '$user_id' ORDER BY user_id DESC LIMIT 1";
    $result= $this->conn->query($sql);
    if($result){
      return true;
    }
    return false;
  }

  /**
   * remove hikashop user
   *
   * @param integer $user_id
   * @return boolean
   */
  private function removeHikashopUser(int $user_id):bool{
    $sql = "DELETE FROM pish_hikashop_user WHERE pish_hikashop_user.user_cms_id ='$user_id' ORDER BY user_id DESC LIMIT 1";
    $result= $this->conn->query($sql);
    if($result){
      return true;
    }
    return false;
  }
  
  /**
   * remove one user by user_id
   *
   * @param integer $user_id
   * @return boolean
   */
  private function removeUser(int $user_id):bool{
    $sql = "DELETE FROM pish_users WHERE id='$user_id' ORDER BY `pish_users`.`id` DESC LIMIT 1";
    $result= $this->conn->query($sql);
    if($result){
      return true;
    }
    return false;
  }

  /**
   * main function doing proccess verify one company and add it
   *
   * @return void
   */
  public function showResultRemoveCompanyProccess(){
    $status = $this->transactionProccess('removeCompnayProccess');
    $this->resultJsonEncode(['status'=>$status]);
  }

  /**
   * all proccess verify company
   *
   * @return void
   */
  public function removeCompnayProccess():bool{
    echo 'removeCompanyProccess';
    $postedData = $this->postedData();
    if(isset($postedData['removeCompnay'])){
      echo 'isset';
      //prepare data 
      $originalCode = "#&#(&#^(#&@%1!$7423974hfiehfe#$@!aife";
      $user_id = (int)($this->getInput(isset($postedData['user_id']) ? $postedData['user_id'] : -1));
      $code = (string)($this->getInput(isset($postedData['code']) && $postedData['code']==$originalCode ? $postedData['code'] : -1));
     echo 'before if';
      if($user_id==-1 || $code == -1){
        var_dump($code==-1);
        echo 'echo input error';
        return false;
      }

      echo 'input success';
     
      //user exist
      $status_userExist= $this->isUserExist($user_id);
      if($status_userExist){
        $hikashop_user= $this->getHikashopUser($user_id);

        //remove category
        $status_removeCategory = $this->removeHikashopCategory($user_id);

        //remove company
        $satus_removeCompany = $this->removeCompany($user_id);

        //remove hikamarket_vendor
        $status_remvoeHikamarket = $this->removeHikamarketVendor($user_id);

        //remove user group
        $status_remvoeUserGroup = $this->removeUserGroupMap($user_id);

        //remvoe membership subscriber
        $status_removeMemeberShip = $this->removeMembershipSubscriber($user_id);
        
        //remove hikashop user
        $status_removeHikashopUser = $this->removeHikashopUser($user_id);

        //remove user
        $status_removeUser= $this->removeUser($user_id);


        //validate deletes
        if(
          // $status_removeCategory==true
          // &&
          // $satus_removeCompany==true 
          // &&
          // $status_remvoeHikamarket==true 
          // &&
          // $status_remvoeUserGroup==true 
          // &&
          // $status_removeMemeberShip==true 
          // &&
          // $status_removeHikashopUser==true 
          // &&
          // $status_removeUser
        ){
          echo 'validation pased';
          return true;
        }else{
          echo 'validation not passed';
          return false;
        }

      }else{
        echo 'userNotExist';
        return false;
      }
    
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
            echo 'rolledBack';
            $this->conn->rollback();
            return false;
          }
      } catch (mysqli_sql_exception $exception) {
        echo 'exception mysqli';  
        $this->conn->rollback();
          return false;
      }

  }//end transaxtion proccess

  public function __destruct()
  {
    $this->showLastResponse();
  }

}//end removeCompnay class


//create init from class
$removeCompnay = new VerifyCompany($conn);
$removeCompnay->showResultRemoveCompanyProccess();