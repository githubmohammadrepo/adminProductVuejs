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
  protected function select(string $sql,$free =false):array
  {
    $dev_array = Array();
    $result = $this->conn->query($sql);
    if($result){
      $count = mysqli_num_rows($result);
      if($count){
        while($row= mysqli_fetch_assoc($result)){
          $dev_array[] =$row;
        }
        if($free==true){

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
   * post curl with array post fields
   *
   * @param Array $postFields
   * @return boolean
   */
  private function postCurl(array $postFields, string $url)
  {
    $post = $postFields;
    if (count($post) == 0) {
      return false;
    }

    $url = trim($url);



    $postdata = json_encode($post, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
    $result = curl_exec($ch);
    $decodedResult = json_decode($result, JSON_UNESCAPED_UNICODE);
    if ($decodedResult['status'] == 'ok') {
      return $decodedResult;
    } else {
      return false;
    }
    curl_close($ch);
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
   * get store table name base on inputs province,city,region
   *
   * @param integer $province
   * @param integer $city
   * @param integer $region
   * @return string
   */
  protected function getStoreTableName(int $province=-1,int $city=-1,int $region=-1):string{
    if($province ==-1 && $city ==-1 && $region ==-1){
      return '';
    }
    //post curl for get table name
    $curlResponse = $this->postCurl([
      "province" => $province,
      "city" => $city,
      "region" => $region,
      "exact" => false
    ], "http://fishopping.ir/serverHypernetShowUnion/helpers/getStoreTableName.php");

    // test showResponse

    // test showResponse
    $table_name = '';
    if ($curlResponse['status'] == 'ok') {
      $table_name = trim($curlResponse['tableName']);
    } else {
      return false;
    }
    return $table_name;
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