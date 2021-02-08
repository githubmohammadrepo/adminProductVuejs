<?php

error_reporting(E_ALL);
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);

include "connection.php";

$userId = $_POST['userid'];
$price = $_POST['price'];
$unit = $_POST['unit'];
$brandSelectedId = $_POST['brandSelectedId'];

$sql0 = "UPDATE pish_users set activation=0, block=0 WHERE id= $userId";

$result0 = $conn->query($sql0);
$sql = "INSERT INTO pish_rsmembership_membership_subscribers (user_id, price, currency, status)
VALUES ('$userId', '$price', '$unit', 1)";

if ($conn->query($sql) === TRUE) {

  if ($brandSelectedId == "") {

    $sql1 = "SELECT * FROM pish_phocamaps_marker_fake WHERE user_id = '$userId'";

    $result = $conn->query($sql1);

    $dev = array();

    if ($result->num_rows > 0) {

      for ($i = 0; $i < $result->num_rows; $i++) {
        $dev[$i] = $result->fetch_assoc();
      }

      $myUserId = $dev[0]['user_id'];
      $title = $dev[0]['title'];
      $ShopName = $dev[0]['ShopName'];
      $phone = $dev[0]['phone'];
      $MobilePhone = $dev[0]['MobilePhone'];
      $OwnerName = $dev[0]['OwnerName'];
      $Fax = $dev[0]['Fax'];
      $Email = $dev[0]['Email'];
      $ShopKind = $dev[0]['ShopKind'];
      $sms_confirmed = $dev[0]['sms_confirmed'];
      $marketer_user_id = $dev[0]['marketer_user_id'];

      if ($marketer_user_id == "") {

        $sql2 = "INSERT INTO pish_phocamaps_marker_company (user_id, title, ShopName, phone, MobilePhone, OwnerName, Fax, Email)
            VALUES ('$myUserId', '$title', '$ShopName', '$phone', '$MobilePhone', '$OwnerName', '$Fax', '$Email')";

        if ($conn->query($sql2) === TRUE) {

          $inserted_marker_id = $conn->insert_id;
          $sql_vendor = "INSERT INTO pish_hikamarket_vendor (vendor_id, vendor_name, vendor_alias, vendor_canonical, vendor_image, vendor_template_id, vendor_site_id) VALUES ('$inserted_marker_id', '$title', '$ShopName', '', '', '', '')";
          $conn->query($sql_vendor);

          $sql3 = "SELECT * FROM pish_hikashop_category where category_parent_id = 10 ORDER BY category_ordering DESC LIMIT 1";
          $result1 = $conn->query($sql3);

          $dev = array();
          if ($result1->num_rows > 0) {

            for ($i = 0; $i < $result1->num_rows; $i++) {
              $dev[$i] = $result1->fetch_assoc();
            }

            $catOrdering = $dev[0]['category_ordering'] + 1;
            $catLeft = $dev[0]['category_right'] + 1;
            $catRight = $catLeft + 1;

            $time = time();
            $random = rand(1000000, 10000000);
            $category_namekey = "manufacturer_${time}_${random}";

            $sql3 = "INSERT INTO pish_hikashop_category (user_id, category_parent_id, category_type, category_name, category_published, category_ordering, category_left, category_right, category_depth, category_namekey)
              VALUES ('$myUserId', 10, 'manufacturer', '$title', 1, '$catOrdering', '$catLeft', '$catRight', 2, '$category_namekey')";

            if ($conn->query($sql3) === TRUE) {
              echo "ok";
            } else {
              echo "notok";
            }
          } else {
            echo "notok";
          }
        } else {
          echo "notok";
        }
      } else {

        $sql2 = "INSERT INTO pish_phocamaps_marker_company (user_id, title, ShopName, phone, MobilePhone, OwnerName, Fax, Email, marketer_user_id)
          VALUES ('$myUserId', '$title', '$ShopName', '$phone', '$MobilePhone', '$OwnerName', '$Fax', '$Email', '$marketer_user_id')";

        if ($conn->query($sql2) === TRUE) {

          $inserted_marker_id = $conn->insert_id;
          $sql_vendor = "INSERT INTO pish_hikamarket_vendor (vendor_id, vendor_name, vendor_alias, vendor_canonical, vendor_image, vendor_template_id, vendor_site_id) VALUES ('$inserted_marker_id', '$title', '$ShopName', '', '', '', '')";
          $conn->query($sql_vendor);

          $sql3 = "SELECT * FROM pish_hikashop_category where category_parent_id = 10 ORDER BY category_ordering DESC LIMIT 1";
          $result1 = $conn->query($sql3);

          $dev = array();
          if ($result1->num_rows > 0) {

            for ($i = 0; $i < $result1->num_rows; $i++) {
              $dev[$i] = $result1->fetch_assoc();
            }

            $catOrdering = $dev[0]['category_ordering'] + 1;
            $catLeft = $dev[0]['category_right'] + 1;
            $catRight = $catLeft + 1;

            $time = time();
            $random = rand(1000000, 10000000);
            $category_namekey = "manufacturer_${time}_${random}";

            $sql3 = "INSERT INTO pish_hikashop_category (user_id, category_parent_id, category_type, category_name, category_published, category_ordering, category_left, category_right, category_depth, category_namekey)
              VALUES ('$myUserId', 10, 'manufacturer', '$title', 1, '$catOrdering', '$catLeft', '$catRight', 2, '$category_namekey')";

            if ($conn->query($sql3) === TRUE) {
              echo "ok";
            } else {
              echo "notok";
            }
          } else {
            echo "notok";
          }
        } else {
          echo "notok";
        }
      }
    } else {

      echo "notok";
    }
  } else {

    $sql1 = "SELECT * FROM pish_phocamaps_marker_fake WHERE brandSelectedname = '$brandSelectedId'";
    $result = $conn->query($sql1);

    $dev = array();

    if ($result->num_rows > 0) {

      for ($i = 0; $i < $result->num_rows; $i++) {
        $dev[$i] = $result->fetch_assoc();
      }

      $myBrandSelectedId = $dev[0]['brandSelectedname'];
      $myUserId = $dev[0]['user_id'];
      $title = $dev[0]['title'];
      $ShopName = $dev[0]['ShopName'];
      $phone = $dev[0]['phone'];
      $MobilePhone = $dev[0]['MobilePhone'];
      $OwnerName = $dev[0]['OwnerName'];
      $Fax = $dev[0]['Fax'];
      $Email = $dev[0]['Email'];
      $ShopKind = $dev[0]['ShopKind'];
      $sms_confirmed = $dev[0]['sms_confirmed'];
      $marketer_user_id = $dev[0]['marketer_user_id'];

      if ($marketer_user_id == "") {

        $sql2 = "UPDATE  pish_phocamaps_marker_company SET user_id='$myUserId', title = '$title'
          , ShopName = '$ShopName' , phone = '$phone' ,
          MobilePhone = '$MobilePhone' , OwnerName = '$OwnerName' , Fax = '$Fax' 
          , Email = '$Email' WHERE user_id = '$myBrandSelectedId'";

        if ($conn->query($sql2) === TRUE) {

          $sql_vendor = "INSERT INTO pish_hikamarket_vendor (vendor_id, vendor_name, vendor_alias, vendor_canonical, vendor_image, vendor_template_id, vendor_site_id) VALUES ('$myBrandSelectedId', '$title', '$ShopName', '', '', '', '')";
          $conn->query($sql_vendor);
          ///////////////////////////////////////////

          $sql6 = "SELECT * FROM pish_hikashop_category WHERE category_name = '$title'";
          $result = $conn->query($sql6);

          if ($result->num_rows > 0) {

            $sql4 = "UPDATE pish_hikashop_category SET user_id = '$myUserId'
              WHERE category_name = '$title'";

            if ($conn->query($sql4) === TRUE) {
              echo "ok";
            } else {
              echo "notok";
            }
          } else {

            $catOrdering = $dev[0]['category_ordering'] + 1;
            $catLeft = $dev[0]['category_right'] + 1;
            $catRight = $catLeft + 1;

            $time = time();
            $random = rand(1000000, 10000000);
            $category_namekey = "manufacturer_${time}_${random}";

            $sql6 = "INSERT INTO pish_hikashop_category (user_id, category_parent_id, category_type, category_name, category_published, category_ordering, category_left, category_right, category_depth, category_namekey)
              VALUES ('$myUserId', 10, 'manufacturer', '$title', 1, '$catOrdering', '$catLeft', '$catRight', 2, '$category_namekey')";

            if ($conn->query($sql6) === TRUE) {
              echo "ok";
            } else {

              echo "notok";
            }
          }

          ////////////////////////////////////////////////////

        } else {
          echo "notok";
        }
      } else {

        $sql2 = "UPDATE  pish_phocamaps_marker_company SET user_id='$myUserId', title = '$title'
          , ShopName = '$ShopName' , phone = '$phone' ,
          MobilePhone = '$MobilePhone' , OwnerName = '$OwnerName' , Fax = '$Fax' 
          , Email = '$Email' ,marketer_user_id = '$marketer_user_id' WHERE user_id = '$myBrandSelectedId'";

        if ($conn->query($sql2) === TRUE) {

          $sql_vendor = "INSERT INTO pish_hikamarket_vendor (vendor_id, vendor_name, vendor_alias, vendor_canonical, vendor_image, vendor_template_id, vendor_site_id) VALUES ('$myBrandSelectedId', '$title', '$ShopName', '', '', '', '')";
          $conn->query($sql_vendor);
          ///////////////////////////////////////////

          $sql6 = "SELECT * FROM pish_hikashop_category WHERE category_name = '$title'";
          $result = $conn->query($sql6);

          if ($result->num_rows > 0) {

            $sql4 = "UPDATE pish_hikashop_category SET user_id = '$myUserId' WHERE category_name = '$title'";

            if ($conn->query($sql4) === TRUE) {
              echo "ok";
            } else {
              echo "notok";
            }
          } else {

            $catOrdering = $dev[0]['category_ordering'] + 1;
            $catLeft = $dev[0]['category_right'] + 1;
            $catRight = $catLeft + 1;

            $time = time();
            $random = rand(1000000, 10000000);
            $category_namekey = "manufacturer_${time}_${random}";

            $sql6 = "INSERT INTO pish_hikashop_category (user_id, category_parent_id, category_type, category_name, category_published, category_ordering, category_left, category_right, category_depth, category_namekey)
              VALUES ('$myUserId', 10, 'manufacturer', '$title', 1, '$catOrdering', '$catLeft', '$catRight', 2, '$category_namekey')";

            if ($conn->query($sql6) === TRUE) {

              echo "ok";
            } else {

              echo "notok";
            }
          }

          ////////////////////////////////////////////////////

        } else {
          echo "notok";
        }
      }
    } else {

      echo "notok";
    }
  }
} else {
  echo "notok";
}

$conn->close();
