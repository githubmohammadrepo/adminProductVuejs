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

}