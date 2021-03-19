<?php
/**
 * Created by PhpStorm.
 * User: androiddev
 * Date: 7/17/17
 * Time: 10:47 PM
 */
 
$servername = "localhost";
$username = "fishopp2_ing";
$password = "fslhggi@2020";
$db_name = "fishopp2_ing";
// Create connection
$conn = new mysqli($servername, $username, $password, $db_name);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

mysqli_set_charset($conn,"utf8");


new class() {
  public function __construct(bool $access=false){
    if($this->ownRequest()==false){
      if($access==false){
        exit();
      }
    }
  }
  private function ownRequest():bool{
    $REMOTE_ADDR = trim($_SERVER['REMOTE_ADDR']);
    $SERVER_ADDR = trim($_SERVER['SERVER_ADDR']);
    if($REMOTE_ADDR === $SERVER_ADDR){
      return true;
    }else{
      return false;
    }
  }
};
?>