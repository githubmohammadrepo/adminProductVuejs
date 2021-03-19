<?php
error_reporting(E_ALL);
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
require_once('./../../../connection.php');
require_once('./HelperTrait.php');
class RemoveOneStore
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
   * if user exist
   *
   * @param string $brandusername
   * @return boolean
   */
  private function IsUserExist(int $user_id):bool{
    $user_id = $this->getInput($user_id);
    $sql = "SELECT * FROM pish_users WHERE id = '$user_id'";
    $result = $this->conn->query($sql);
    if($result){
      $count= mysqli_num_rows($result);
      return $count ? true : false;
    }
    return false;
  }

  /**
   * remove hikashop user
   *
   * @param integer $user_id
   * @return boolean
   */
  private function pish_hikashop_user(int $user_id):bool{
    $sql = "DELETE FROM `pish_hikashop_user` WHERE user_id = '$user_id'";
    $status = $this->remove($sql);
    return $status ? true : false;
  }

  /**
   * remove pish_user_group_map by user_id
   *
   * @param integer $user_id
   * @return boolean
   */
  private function pish_user_usergroup_map(int $user_id):bool{
    $sql = "DELETE FROM `pish_user_usergroup_map` WHERE user_id = '$user_id'";
    $status = $this->remove($sql);
    return $status ? true : false;
  }


  /**
   * remove rememmbership_subscriber by user_id
   *
   * @param integer $user_id
   * @return boolean
   */
  private function pish_rsmembership_membership_subscribers(int $user_id):bool{
    $sql = "DELETE FROM `pish_rsmembership_membership_subscribers` WHERE user_id = '$user_id'";
    $status = $this->remove($sql);
    return $status ? true : false;
  }


  /**
   * remove user by user_id
   *
   * @param integer $user_id
   * @return boolean
   */
  private function pish_users(int $user_id):bool{
    $sql = "DELETE FROM `pish_users` WHERE id = '$user_id'";
    $status = $this->remove($sql);
    return $status ? true : false;
  }

  /**
   * rmeove store by store_id
   *
   * @param integer $store_id
   * @return boolean
   */
  private function pish_phocamaps_marker_store(int $store_id,string $table_name):bool{
    $sql = "DELETE FROM `$table_name` WHERE id = '$store_id'";
    $status = $this->remove($sql);
    return $status ? true : false;
  }

  /**
   * remove store level one
   * remove section store that belogs to user
   *
   * @return void
   */
  private function removeLevelOne(int $user_id):bool{
    // IsUserExist
    $userExist = $this->IsUserExist($user_id);
    if($userExist){
      // pish_hikashop_user
      $status_remvoed_hiakshop= $this->pish_hikashop_user($user_id);
      
      // pish_user_usergroup_map
      $status_removed_groupMap = $this->pish_user_usergroup_map($user_id);

      // pish_rsmembership_membership_subscribers
      $status_remove_rsmembership_subscriber = $this->pish_rsmembership_membership_subscribers($user_id);

      // pish_users
      $status_remove_user= $this->pish_users($user_id);

      if(
        $status_remvoed_hiakshop &&
        $status_removed_groupMap &&
        $status_remove_rsmembership_subscriber &&
        $status_remove_user
      ){
        return true;
      }else{
        return false;
      } 
    }else{
      return false;
    }
  }


  /**
   * is store exist by store_id
   *
   * @param integer $store_id
   * @return boolean
   */
  private function isStoreIdExist(int $store_id,string $table_name):bool{
    $sql = "SELECT * FROM $table_name WHERE id = $store_id";
    $store= $this->select($sql,true);
    if(count($store)){
      return true;
    }else{
      return false;
    }
  }

  /**
   * remove store level two
   * remove section store that belogs to store
   *
   * @return void
   */
  private function removeLevelTwo(int $store_id,string $table_name):bool{
    $statusStoreExist= $this->isStoreIdExist($store_id,$table_name);
    if($statusStoreExist){
      //remove store
      $status_remvoe_store = $this->pish_phocamaps_marker_store($store_id,$table_name);
      if($status_remvoe_store){
        return true;
      }else{
        return false;
      }
    }else{
      //sotre does not exist
      return false;
    }
  }

  /**
   * doing proccess insert new stoer
   */
  protected function proccessRemoveOneStore():bool{
    //get data
    $postedData =$this->postedData();

    if(isset($postedData['removeOneStore'])){
      $user_id = (int)($this->getInput(isset($postedData['user_id']) ? $postedData['user_id'] : null));
      $store_id = (int)($this->getInput(isset($postedData['store_id']) ? $postedData['store_id'] : null));
      $province_id = (int)($this->getInput(isset($postedData['province_id']) ? $postedData['province_id'] : -1));
      $city_id = (int)($this->getInput(isset($postedData['city_id']) ? $postedData['city_id'] : -1));
      $region_id = (int)($this->getInput(isset($postedData['region_id']) ? $postedData['region_id'] : -1));
      
      //get table_name
      //get table_name
      $table_name = (string)$this->getStoreTableName((int)$province_id, (int)$city_id, (int)$region_id);
      // test showResponse

      if (strlen($table_name)==0) {
        return  false;
      }

      if($user_id==0){
        //user_id does not set
        return false;
      }else{
          //we have user_id lets remove level one
         $status_insert_levelOne = $this->removeLevelOne($user_id);
          if ($status_insert_levelOne) {
              //insert level two
              $status_insert_levelTwo = $this->removeLevelTwo($store_id,$table_name);
              if ($status_insert_levelTwo) {
                  return true;
              } else {
                  return false;
              }
          }else{
            return false;
          }
      }
    }else{
      return false;
    }
  }


  public function showResultRemoveNewStore(){
    $status = $this->transactionProccess('proccessRemoveOneStore');
    if($status){
      $this->resultJsonEncode(['removed'=>true,'status'=>true]);
    }else{
      $this->resultJsonEncode(['removed'=>false,'status'=>false]);
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
$updateCompany = new RemoveOneStore($conn);
$updateCompany->showResultRemoveNewStore();

