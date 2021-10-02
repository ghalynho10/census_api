<?php
require_once 'include/DB_Functions.php';
$db = new DB_Functions();
 
// json response array
$response = array("error" => FALSE);
 
if (isset($_POST['username']) && isset($_POST['password'])) {
 
    // receiving the post params
    $email = $_POST['username'];
    $password = $_POST['password'];
 
    // get the user by email and password
    $admin_tbl = $db->getUserByEmailAndPassword($email, $password);
 
    if ($admin_tbl != false) {
        // use is found
        $response["error"] = FALSE;
        $response["uid"] = $admin_tbl["unique_id"];
        $response["admin_tbl"]["name"] = $admin_tbl["name"];
        $response["admin_tbl"]["username"] = $admin_tbl["username"];
        $response["admin_tbl"]["created_at"] = $admin_tbl["created_at"];
        $response["admin_tbl"]["updated_at"] = $admin_tbl["updated_at"];
        echo json_encode($response);
    } else {
        // user is not found with the credentials
        $response["error"] = TRUE;
        $response["error_msg"] = "Login credentials are wrong. Please try again!";
        echo json_encode($response);
    }
} else {
    // required post params is missing
    $response["error"] = TRUE;
    $response["error_msg"] = "Required parameters email or password is missing!";
    echo json_encode($response);
}
?>