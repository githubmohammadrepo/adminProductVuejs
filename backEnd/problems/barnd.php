<?php

include "connection.php";
// $page = $_POST['page'];
/*$page = 1;
if($page == null) {
    $page = 1;
}
$offset = ($page - 1) * 20;*/

$sql = "SELECT brands.*, pish_hikashop_file.file_path as brand_image, COUNT(pish_hikashop_product.product_id) as productsCount FROM( SELECT pish_hikashop_category.category_id, pish_hikashop_category.category_name, pish_hikashop_category.user_id FROM pish_hikashop_category WHERE pish_hikashop_category.category_type = 'manufacturer' AND pish_hikashop_category.category_parent_id = 10 LIMIT 0,25)as brands LEFT JOIN pish_hikashop_file ON pish_hikashop_file.file_ref_id = brands.category_id AND pish_hikashop_file.file_type = 'category' LEFT JOIN pish_hikashop_product ON pish_hikashop_product.product_manufacturer_id = brands.category_id GROUP BY brands.category_id";

// WHERE pish_hikashop_category.category_type='manufacturer' AND pish_hikashop_category.category_parent_id = 10 GROUP BY pish_hikashop_category.category_id LIMIT {$offset},20";

$imagePath = "http://www.fishopping.ir/images/com_hikashop/upload/";

$result = $conn->query($sql);

$dev_array = array();

if ($result->num_rows > 0) 
{
    // output data of each row
    for ($i = 0; $i < $result-> num_rows; $i++)
    {
        $row = $result->fetch_assoc();
        $dev_array[$i] = $row;
        $dev_array[$i]['brand_image'] = $imagePath . $row['brand_image'];
       // $dev_array[$i]['cat_id'] = $row['category_id'];
	   // $dev_array[$i]['cat_name'] = $row['category_name'];
		$cat_id = $row['category_id'];
		$cat_name = $row['category_name'];
		$cat_mobile = $cat_id*$cat_id;
		$user_id = $row['user_id'];
		//////////////
		$sqlmarker = "SELECT * FROM pish_phocamaps_marker inner join pish_hikashop_category where pish_phocamaps_marker.title=  pish_hikashop_category.category_name and pish_phocamaps_marker.ShopKind =11 and  pish_hikashop_category.category_id= $cat_id";
        
$resultmarker = $conn->query($sqlmarker);
if ($resultmarker->num_rows > 0) {
  
         $marker_row = $resultmarker->fetch_assoc();
		 $marker_id= $marker_row['id'];
	
}else{
	$sqlmarker2 = "INSERT INTO pish_phocamaps_marker (user_id, title, ShopName, ShopKind, MobilePhone)
VALUES ('$user_id','$cat_name','$cat_name',11,'$cat_mobile')";

 if ($conn->query($sqlmarker2) === TRUE) {

    $marker_id = $conn->insert_id;
 }

}
	  $dev_array[$i]['markerid'] = $marker_id;		
	
		//////////////
        
        $sql2 = "SELECT pish_hikashop_category.category_id,pish_hikashop_category.category_name, pish_hikashop_category.user_id, pish_hikashop_file.file_path as brand_image, COUNT(pish_hikashop_product.product_id) as productsCount FROM pish_hikashop_category 
        LEFT JOIN pish_hikashop_file ON pish_hikashop_file.file_ref_id = pish_hikashop_category.category_id 
        LEFT JOIN pish_hikashop_product ON pish_hikashop_product.product_manufacturer_id = pish_hikashop_category.category_id 
        WHERE pish_hikashop_category.category_type='manufacturer' AND pish_hikashop_category.category_parent_id = '$cat_id' GROUP BY pish_hikashop_category.category_id";

        $result2 = $conn->query($sql2);
        $sub_array = [];
        if ($result2->num_rows > 0) 
        {
                   $dev_array[$i]['flag'] = 1;
    }else {
    $dev_array[$i]['flag'] = 0;
	}

}
} else {
    echo "notok";
	
}
$jsonEncode = json_encode($dev_array, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

    echo $jsonEncode;
$conn->close();

