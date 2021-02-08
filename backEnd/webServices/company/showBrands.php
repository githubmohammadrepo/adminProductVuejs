<?php

/**
 * Created by PhpStorm.
 * User: androiddev
 * Date: 7/17/17
 * Time: 10:49 PM
 */


error_reporting(E_ALL);
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
$object = new stdClass();
include "connection.php";
class BrandActions
{
    private $conn;
    public function __construct(mysqli $conn)
    {
        $this->conn = $conn;
    }
    /**
     * insert new Brand
     */
    public function addNewBrand(String $userName, String $password)
    {
        // webservice is exist before in some where
    }

    private function getCountAllBrands(){
        $sql = "SELECT COUNT(pish_hikashop_category.category_id) as brandCount FROM pish_hikashop_category 
        left JOIN pish_hikashop_file ON pish_hikashop_file.file_ref_id = pish_hikashop_category.category_id 
        WHERE pish_hikashop_category.category_type='manufacturer' AND pish_hikashop_category.category_parent_id = 10 ORDER BY pish_hikashop_category.category_id";
        $result = $this->conn->query($sql);
        if($result){
            $count= mysqli_num_rows($result);
            if($count){
                $row = mysqli_fetch_assoc($result);
                return $row['brandCount'];
            }
        }
        return null;
    }


    private function getCountAllCompanies(){
        $sql ="SELECT COUNT(*) as count FROM pish_phocamaps_marker_company";
        $result = $this->conn->query($sql);
        if($result){
            $count = mysqli_num_rows($result);
            if($count){
                $row = mysqli_fetch_assoc($result);
                return $row['count'];
            }
        }
        return -1;
    }

    /**
     * get All brands with offset
     * @param int $offset offset for get brands
     * @return Array array of Objects
     */
    public function getAllBrandWithOffset(int $offset): void
    {
        $countALlBrands = $this->getCountAllBrands();
        $offset = $this->getInput($offset);
        $resultArray = array();

        try {
            // run your code here
            $sql = "SELECT pish_hikashop_category.category_id,
                pish_hikashop_category.user_id,
                pish_hikashop_category.category_parent_id,
                pish_hikashop_category.category_type,
                pish_hikashop_category.category_description,
                pish_hikashop_category.category_name,
                pish_hikashop_category.category_published,
                    pish_hikashop_file.file_path as brand_image FROM pish_hikashop_category 
                left JOIN pish_hikashop_file ON pish_hikashop_file.file_ref_id = pish_hikashop_category.category_id 
                WHERE pish_hikashop_category.category_type='manufacturer' AND pish_hikashop_category.category_parent_id = 10 ORDER BY pish_hikashop_category.category_id limit $offset,10";

            $result = $this->conn->query($sql);
            if ($result) {
                $rowcount = $result->num_rows;
                if ($rowcount > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $resultArray[] = $row;
                    }
                }
            }
        } catch (exception $e) {
            //code to handle the exception
            $this->resultJsonEncode($resultArray);
        }
        //output data
        
        $this->resultJsonEncode(['brands'=>$resultArray,'count'=>$countALlBrands]);
    }


    
    /**
    * get All brands with offset
    * @param int $offset offset for get brands
    * @return Array array of Objects
    */
    public function getAllSubBrandWithOffset(int $offset, int $category_parent_id): void
    {
        $offset = $this->getInput($offset);
        $category_parent_id = $this->getInput($category_parent_id);
        $resultArray = array();


        try {
            // run your code here
            $sql = "SELECT pish_hikashop_category.category_id,pish_hikashop_category.category_published,pish_hikashop_category.category_name, pish_hikashop_category.user_id, pish_hikashop_file.file_path as brand_image  FROM pish_hikashop_category 
      LEFT JOIN pish_hikashop_file ON pish_hikashop_file.file_ref_id = pish_hikashop_category.category_id 
      WHERE pish_hikashop_category.category_type='manufacturer' AND pish_hikashop_category.category_parent_id = $category_parent_id
       limit $offset,10";

            $result = $this->conn->query($sql);
            if ($result) {
                $rowcount = $result->num_rows;
                if ($rowcount > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $resultArray[] = $row;
                    }
                }
            }
        } catch (exception $e) {
            //code to handle the exception
            $this->resultJsonEncode($resultArray);
        }
        //output data
        $this->resultJsonEncode($resultArray);
    }
    /**
     * update one Brand
     */

    /**
     * delete one brand
     */
    public function deleteOneBrand(int $category_id)
    {
        $category_id = $this->getInput($category_id);
        if ($category_id < 0) {
            $this->resultJsonEncode(false);
        }
        $sql = "DELETE FROM pish_hikashop_category WHERE pish_hikashop_category.category_id = $category_id";
        $result = $this->conn->query($sql);
        if ($result) {
            $count = mysqli_affected_rows($this->conn);
            if ($count) {
                $this->resultJsonEncode(true);
            } else {
                $this->resultJsonEncode(false);
            }
        } else {
            $this->resultJsonEncode(false);
        }
    }


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
     * update brand
     */
    private function updateBrandLevelTwo(int $userId, string $owner, string $brandname, string $MobilePhone, string $phone, string $CompanyName, String $email, string $Address):bool
    {
        $userId= $this->getInput($userId);
        // main infos
        $Address= $this->getInput($Address);
        $CompanyName= $this->getInput($CompanyName);
        $MobilePhone= $this->getInput($MobilePhone);
        $OwnerName= $this->getInput($owner);
        $brandname= $this->getInput($brandname);
        $phone= $this->getInput($phone);

        //insert into fake
    
        // insert into  pish_phocamaps_marker_fake set
        // brandSelectedname = brandSelectedId,
        // user_id = userid,
        // title = brandname,
        // ShopName = CompanyName,
        // phone = phone,
        // MobilePhone = MobilePhone,
        // OwnerName = OwnerName,
        // Address = Address,
        // RegCode = RegCode
        // delete trash data



        //  delete trash data
        $sql = "DELETE FROM pish_phocamaps_marker_fake WHERE user_id = $userId";
        $sql =stripcslashes(mysqli_real_escape_string($this->conn, $sql));
        $result = $this->conn->query($sql);

        // insert new company fake
        $sql = "INSERT INTO  pish_phocamaps_marker_fake SET brandSelectedname = brandSelectedId, user_id = userid, title = $brandname, ShopName = $CompanyName, phone = $phone, MobilePhone = $MobilePhone, OwnerName = $OwnerName, Address = $Address, RegCode = RegCode";
        $sql = stripcslashes(mysqli_real_escape_string($this->conn, $sql));
        $result = $this->conn->query($sql);
        if ($result) {
            $rowcount =(mysqli_affected_rows($this->conn));
            if ($rowcount) {
                return true;
            }
        }
    }

    /**
     * section just show output methods
     */
    public function resultJsonEncode($data)
    {
        if (gettype($data) == 'array') {
            echo json_encode($data, JSON_UNESCAPED_UNICODE);
        } else {
            echo json_encode([$data], JSON_UNESCAPED_UNICODE);
        }
    }

    /**
     * get all companies by offset
     */
    public function getAllCompanies(int $offset):void {
      $devArray = Array();

      $AllCompanieCounts = $this->getCountAllCompanies();

      $offset = $this->getInput($offset);
      $sql= "SELECT companyCategory.*,pish_hikashop_file.file_ref_id,pish_hikashop_file.file_path
      FROM (SELECT new.*, pish_hikashop_category.user_id as category_user_id FROM(SELECT  pish_phocamaps_marker_company.id
                      ,pish_phocamaps_marker_company.user_id
                      ,pish_phocamaps_marker_company.ShopName
                      ,pish_phocamaps_marker_company.ManagerName
                      ,pish_phocamaps_marker_company.phone
                      ,pish_phocamaps_marker_company.MobilePhone
                      ,pish_phocamaps_marker_company.Address
                     
              FROM `pish_phocamaps_marker_company`
             ORDER BY pish_phocamaps_marker_company.id ASC
      LIMIT $offset,10
              
              ) AS new
              LEFT JOIN pish_hikashop_category
              ON new.user_id = pish_hikashop_category.user_id) AS companyCategory
      LEFT JOIN pish_hikashop_file 
      ON companyCategory.category_user_id = pish_hikashop_file.file_ref_id";
      $result = $this->conn->query($sql);
      if($result){
        $count= mysqli_num_rows($result);
        if($count){
          while($row = mysqli_fetch_assoc($result)){
            $devArray[] = $row;
          }
        }
      }
      $this->resultJsonEncode(['companies' => $devArray , 'count'=>$AllCompanieCounts]);
    }

    /**
     * get one brand info for update
     */
    public function getBrandInfo(int $id)
    {
        $id = $this->getInput($id);

        $sql = "SELECT `pish_phocamaps_marker_company`.title
    FROM `pish_phocamaps_marker_company` 
    INNER JOIN `pish_users` ON `pish_phocamaps_marker_company`.user_id = $id";
    }

    /***
     * public function update one brand
     */
    public function updateOneBrand(
        $category_id,
        $user_id,
        $category_parent_id,
        $category_name,
        $category_description,
        $category_published,
        $typeAction
    ) {
        $category_id = $this->getInput($category_id);
        $user_id = $this->getInput($user_id);
        $category_parent_id = $this->getInput($category_parent_id);
        $category_name = $this->getInput($category_name);
        $category_description = $this->getInput($category_description);
        $category_published = $this->getInput($category_published);
        $typeAction = $this->getInput($typeAction);
    
        $sql = "UPDATE pish_hikashop_category SET `user_id` =$user_id,`category_parent_id`='$category_parent_id',`category_name`='$category_name',`category_description`='$category_description',`category_published`='$category_published' WHERE `category_id` =$category_id";
    
        $result = $this->conn->query($sql);
        if ($result) {
            $count = mysqli_affected_rows($this->conn);
            if ($count) {
                $this->resultJsonEncode(true);
            } else {
                $this->resultJsonEncode(false);
            }
        } else {
            $this->resultJsonEncode(false);
        }
    }

    /**
     * show all users
     */
    public function shwoAllUsers()
    {
        $sql = "SELECT id,name FROM pish_users WHERE name IS NOT NULL AND length(name)";
        $result = $this->conn->query($sql);
        if ($result) {
            $count = mysqli_num_rows($result);
            $devArray = array();
            while ($row = $result->fetch_assoc()) {
                $devArray[]=$row;
            }
            $this->resultJsonEncode($devArray);
        } else {
            $this->resultJsonEncode([]);
        }
    }
    /**
     * show all users
     */
    public function getAllCategoryParent($category_id)
    {
        $category_id = $this->getInput($category_id);
        $sql = "SELECT category_id,category_name,category_parent_id FROM pish_hikashop_category WHERE category_parent_id=(SELECT category_parent_id from pish_hikashop_category WHERE category_id = 10) AND pish_hikashop_category.category_name is not null And length(category_name)";
        $result = $this->conn->query($sql);
        if ($result) {
            $count = mysqli_num_rows($result);
            $devArray = array();
            while ($row = $result->fetch_assoc()) {
                $devArray[]=$row;
            }
            $this->resultJsonEncode($devArray);
        } else {
            $this->resultJsonEncode([]);
        }
    }
  


    /**
     * insert new brand by  admin
     *
     * @param integer $user_id
     * @param string $title
     * @return void
     */
    public function insertJustBrand(int $user_id,string $title):void
    {
      $user_id = $this->getInput($user_id);
      $title = $this->getInput($title);
      
        $sql3 = "SELECT * FROM pish_hikashop_category where category_parent_id = 10 ORDER BY category_ordering DESC LIMIT 1";
        $result1 = $this->conn->query($sql3);

        $dev = array();
        if ($result1->num_rows > 0) {
            for ($i = 0; $i < $result1-> num_rows; $i++) {
                $dev[$i] = $result1->fetch_assoc();
            }

            $catOrdering = $dev[0]['category_ordering'] + 1;
            $catLeft = $dev[0]['category_right'] + 1;
            $catRight = $catLeft + 1;

            $time = time();
            $random = rand(1000000, 10000000);
            $category_namekey = "manufacturer_${time}_${random}";

            $sql3 = "INSERT INTO pish_hikashop_category (user_id, category_parent_id, category_type, category_name, category_published, category_ordering, category_left, category_right, category_depth, category_namekey)
              VALUES ('$user_id', 10, 'manufacturer', '$title', 1, '$catOrdering', '$catLeft', '$catRight', 2, '$category_namekey')";


            if ($this->conn->query($sql3) === true) {
                $this->resultJsonEncode('ok');
            } else {
              $this->resultJsonEncode('notok');
            }
        } else {
          $this->resultJsonEncode('notok');
        }
    }
}


/**
 * this section get all post inputs
 */
$json = file_get_contents('php://input');
$post = json_decode($json, true);
$typeAction = $post['typeAction'];

$brandAction = new BrandActions($conn);
if ($typeAction == 'select') {
    $offset = $post['offset'];
    //get all brand with offset
    $brandAction->getAllCompanies($offset);
} elseif ($typeAction == 'subSelect') {
    $offset = $post['offset'];
    $category_parent_id = $post['category_parent_id'];
  
    $brandAction->getAllSubBrandWithOffset($offset, $category_parent_id);
} elseif ($typeAction == 'getAllUsers') {
    $brandAction->shwoAllUsers();
} elseif ($typeAction == 'getAllCategoryParent') {
    $category_id = $post['category_id'];
    $brandAction->getAllCategoryParent($category_id);
} elseif ($typeAction == 'updateOneBrand') {
    $category_id=$post['category_id'];
    
    $user_id=$post['user_id'];
    $category_parent_id=$post['category_parent_id'];
    $category_name=$post['category_name'];
    $category_description=$post['category_description'];
    $category_published=$post['category_published'];
    $typeAction=$post['typeAction'];
    //update one brand
    $brandAction->updateOneBrand(
        $category_id,
        $user_id,
        $category_parent_id,
        $category_name,
        $category_description,
        $category_published,
        $typeAction
    );
} elseif ($typeAction == 'delete') {
    //delete one brand
    $category_id = $post['category_id'];
    $category_id = $category_id ? $category_id : -1;
    $brandAction->deleteOneBrand($category_id);
} elseif ($typeAction == 'insertJustBrand') {
    //insert new brand by admin
    $user_id = (int)$post['user_id'];
    $title = $post['title'];

    $brandAction->insertJustBrand($user_id, $title);
} else {
}
