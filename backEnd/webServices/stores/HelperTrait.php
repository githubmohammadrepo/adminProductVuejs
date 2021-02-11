<?php

trait Helpers {
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
   * select operation on database
   *
   * @param string $sql
   * @return array
   */
  protected function select(string $sql):array
  {
    $dev_array = Array();
    $result = $this->conn->query($sql);
    if($result){
      $count = mysqli_num_rows($result);
      if($count){
        while($row= mysqli_fetch_assoc($result)){
          $dev_array[] =$row;
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