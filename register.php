<?php
 
 require_once 'include/DB_Functions.php';
 $db = new DB_Functions();
  
 // json response array
 $response = array("error" => FALSE);
  
 if (isset($_POST['name']) && isset($_POST['username']) && isset($_POST['admin']) &&isset($_POST['password'])) {
  
     // receiving the post params
     $name = $_POST['name'];
     $username = $_POST['username'];
     $admi = $_POST['admin'];
     $password = $_POST['password'];
  
     // check if user is already existed with the same username
     if ($db->isUserExisted($username)) {
         // user already existed
         $response["error"] = TRUE;
         $response["error_msg"] = "User already existed with " . $username;
         echo json_encode($response);
     } else {
         // create a new user
         $user = $db->storeUser($name, $username, $password,$admi);
         if ($user) {
             // user stored successfully
             $response["error"] = FALSE;
             $response["uid"] = $user["unique_id"];
             $response["user"]["name"] = $user["name"];
             $response["user"]["username"] = $user["username"];
             $response["user"]["admin"] = $user["admin"];
             $response["user"]["created_at"] = $user["created_at"];
             $response["user"]["updated_at"] = $user["updated_at"];
             echo json_encode($response);
         } else {
             // user failed to store
             $response["error"] = TRUE;
             $response["error_msg"] = "Unknown error occurred in registration!";
             echo json_encode($response);
         }
     }
 } else {
     $response["error"] = TRUE;
     $response["error_msg"] = "Required parameters (name, username or password) is missing!";
     echo json_encode($response);
 }

 ?>