<?php
error_reporting(E_ALL);
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
require_once('./../../connection.php');
require_once('./HelperTrait.php');
class UpdateCompany
{
  use Helpers;
  private $conn;
  private $regCode;
  private $resonsed = false;
  
  public function __construct(mysqli $conn)
  {
    $this->conn = $conn;
  }

  
  public function removeOneCompany(){

  }

}

/**
 * create init from class
 */
$updateCompany = new UpdateCompany($conn);
// $updateCompany->showResultUpdateCompanyLevelInsert();
// // $res = $updateCompany->postedData();
// // echo json_encode(['data'=>$res],JSON_UNESCAPED_UNICODE);

// $updateCompany->showLastResponse();
var_dump($_SERVER['REQUEST_METHOD']);