<?php

trait Helpers {
  /**
   * get input and make sure it secure
   */
  protected function getInput($input)
  {
      $result = htmlspecialchars(strip_tags($input));
      if (preg_match('/<>;:\$^/', $result)) {
          return;
      } else {
          return $result;
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
  public function getPostRequestField(string $name,string $type,$defaultValue,$blackBox=-INF){
    $postedData = $this->postedData();
    if(isset($postedData[$name]) || $postedData[$name]==0) {
      if($type=='int'){
        return (int)($postedData[$name]);
      }else if($type=='string'){
        return (string)($postedData[$name]);
      }else if($type=='bool'){
        return (bool)($postedData[$name]);
      }else{
        return $postedData[$name];
      }
      
    }else{
      if($blackBox != -INF){
        return $defaultValue;
      }else{
        return -1;
      }
    }
  }

  /**
   *  select operation on database
   *
   * @param string $sql
   * @param boolean $freeResult
   * @return array
   */
  protected function select(string $sql,bool $freeResult = false):array
  {
    $dev_array = Array();
    $result = $this->conn->query($sql);
    if($result){
      $count = mysqli_num_rows($result);
      if($count){
        while($row= mysqli_fetch_assoc($result)){
          $dev_array[] =$row;
        }

        if($freeResult==true){
          mysqli_free_result($result);
        }
      }
    }
    return $dev_array;
  }



  /**
   * insert operation
   *
   * @param string $sql
   * @param boolean $strict
   * @return boolean
   */
  protected function insert(string $sql,bool $strict= false):bool{
    $result= $this->conn->query($sql);
    if($result){
      if($strict==true){
        $count = mysqli_affected_rows($this->conn);
        if($count){
          return true;
        }else{
          return false;
        }
      }else{
        return true;
      }
    }
    return false;
  }



  /**
   * update operation
   *
   * @param string $sql
   * @param boolean $strict
   * @return boolean
   */
  protected function update(string $sql,bool $strict= false):bool{
    $result= $this->conn->query($sql);
    if($result){
      if($strict==true){
        $count = mysqli_affected_rows($this->conn);
        if($count){
          return true;
        }else{
          return false;
        }
      }else{
        return true;
      }
    }
    return false;
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

  protected function remove(string $sql,$strict=false):bool{
    $result = $this->conn->query($sql);
    if($result){
      if($strict==false){
        return true;
      }else{
        $count = mysqli_affected_rows($this->conn);
        return $count ? true : false;
      }
    }else{
      return false;
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


  public function showLastResponse(){
    if($this->resonsed==false){
      $this->resultJsonEncode(['status'=>false]);
    }
  }
}