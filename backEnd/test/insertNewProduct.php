<?php


/*error_reporting(E_ALL);
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
*/

include "connection.php";
//var_export($_FILES);die();
$product_name = $_POST["product_name"];
$bag_money = $_POST["bag"];
$product_code = $_POST["product_code"];
$product_sort_price = $_POST["product_sort_price"];
$product_msrp = $_POST["product_msrp"];
$product_checkout_method = $_POST["product_checkout_method"];
$product_number_in_package = $_POST["product_number_in_package"];
$product_package_type = $_POST["product_package_type"];
$product_weight = $_POST["product_weight"];
$product_delivery_time = $_POST["product_delivery_time"];
$product_description_chechout = $_POST["product_description_checkout"];
$product_sale_type = $_POST["product_sale_type"];
$user_id = $_POST["user_id"];
$fileType = "product";
$product_type = "main";

$product_parent_id = $_POST["category_id"];
$image = $_POST["image"];

$product_sort_price = $product_sort_price * 10;
$product_msrp = $product_msrp * 10;


$sql1 = "SELECT product_code FROM pish_hikashop_product WHERE product_code = '$product_code'";
$result = $conn->query($sql1);


if ($result->num_rows > 0)
{
   
   echo "exist";

} else {
  
$sql_cat = "SELECT * FROM pish_hikashop_category WHERE user_id='$user_id'";
$result_cat = $conn->query($sql_cat);
$cat_id = 0;
if ($result_cat->num_rows > 0){
    $cat = $result_cat->fetch_assoc();
    $cat_id = $cat['category_id'];
}
  
$sql = "INSERT INTO pish_hikashop_product (product_name, product_code, product_published, product_tax_id, product_type, product_sort_price, product_msrp, product_checkout_method, product_number_in_package, product_description_checkout, product_sale_type, product_add_user_id, product_package_type, product_weight, product_delivery_time, product_parent_id, product_manufacturer_id)
VALUES ('$product_name', '$product_code', 1, 11, '$product_type', '$product_sort_price','$product_msrp', '$product_checkout_method', '$product_number_in_package', '$product_description_chechout', '$product_sale_type', '$user_id', '$product_package_type', '$product_weight', '$product_delivery_time', '$product_parent_id', '$cat_id')";

    if ($conn->query($sql) === TRUE) 
    {
        //echo "ok";
        $last_id = $conn->insert_id;
        
        //get last ordering id
        $sql_check_ordering_1 = "SELECT ordering FROM pish_hikashop_product_category WHERE category_id = '$product_parent_id' ORDER BY ordering DESC LIMIT 1";
        $result_check_ordering_1 = $conn->query($sql_check_ordering_1);

        if ($result_check_ordering_1->num_rows > 0)
        {
            $row = $result_check_ordering_1->fetch_assoc();

           //insert into category table
           $newOrdering = $row['ordering'] + 1;
            $sql_cat1 = "INSERT INTO pish_hikashop_product_category (category_id, product_id, ordering) VALUES ('$product_parent_id', '$last_id', '$newOrdering')";
        
        } else {
            //insert into category table
            $sql_cat1 = "INSERT INTO pish_hikashop_product_category (category_id, product_id, ordering) VALUES ('$product_parent_id', '$last_id', 1)";
        }
        //run query
        $conn->query($sql_cat1);

        //get last ordering id
        $sql_check_ordering_2 = "SELECT ordering FROM pish_hikashop_product_category WHERE category_id = '$cat_id' ORDER BY ordering DESC LIMIT 1";
        $result_check_ordering_2 = $conn->query($sql_check_ordering_2);

        if ($result_check_ordering_2->num_rows > 0)
        {
            $row = $result_check_ordering_2->fetch_assoc();

           //insert into category table
           $newOrdering = $row['ordering'] + 1;
            $sql_cat2 = "INSERT INTO pish_hikashop_product_category (category_id, product_id, ordering) VALUES ('$cat_id', '$last_id', '$newOrdering')";
        
        } else {
            //insert into category table
            $sql_cat2 = "INSERT INTO pish_hikashop_product_category (category_id, product_id, ordering) VALUES ('$cat_id', '$last_id', 1)";
        }
        //run query
        $conn->query($sql_cat2);
        
        
        /////////////////////////
        
        $target_dir_profile = "/home/fishopp2/public_html/images/com_hikashop/upload/";
        
        if (!is_dir($target_dir_profile))
        {
            mkdir($target_dir_profile, 0777, true);
            
        }
        
        //set random image file name with time
        $file_name = rand() ."_". time() .".png";
        $target_dir_profile = $target_dir_profile ."/" . $file_name;
        
        if (file_put_contents($target_dir_profile, base64_decode($image))) 
        {
            $sql2 = "INSERT INTO pish_hikashop_file (file_path, file_type, file_ref_id)
    VALUES ('$file_name', '$fileType', '$last_id')";
    
                if ($conn->query($sql2) === TRUE) 
                {
                    
                    
                    
                    
                    $bag_money = ($bag_money - 10000) * 10;
                    
                    
                    
         $m_sql="update pish_phocamaps_marker_company set bagmoneybrand = '$bag_money' where user_id='$user_id'";

        if ($conn->query($m_sql) === TRUE) {
            echo "ok";
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
  
  
}




 
 $conn->close();


?>