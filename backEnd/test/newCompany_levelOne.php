<?php
/**
 * Created by PhpStorm.
 * User: androiddev
 * Date: 7/17/17
 * Time: 10:49 PM
 */
/* 
error_reporting(E_ALL);
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
*/

include "connection.php";

$brandusername = $_POST["brandusername"];
$brandpass = md5($_POST["brandpassword"]);

$sql = "SELECT * FROM pish_users WHERE username = '$brandusername' AND block= '0'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // output data of each row
   echo "exist";
  
} else {


    $sql1 = "INSERT INTO pish_users (username, password, block,activation)
VALUES ('$brandusername', '$brandpass', '1', '1')";

    if ($conn->query($sql1) === TRUE) 
    {
        //echo "ok";
         $last_id = $conn->insert_id;
         
         $sql2 = "INSERT INTO pish_user_usergroup_map (user_id, group_id)
VALUES ('$last_id', '11')";

        $conn->query($sql2);
        
        $time = time();
        $email = $brandusername . '@hypernetshow.com';
        $sql3 = "INSERT INTO `pish_hikashop_user`(`user_cms_id`, `user_email`, `user_created`) VALUES ('$last_id', '$email', '$time')";

        $conn->query($sql3);
         
        echo $last_id;

    
    } else {
        echo "notok";
    }
    

}
    



$conn->close();
?>
