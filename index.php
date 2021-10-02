<?php

header("Content-Type: application/json");
    try
    {
        $connection = new PDO("mysql:host=localhost;dbname=projet_final","root","");
        // echo "Good";
    }
    catch(PDOException $e)
    {
        echo "Bad";
    }

    switch($_GET['action'])
    {
        case "select":
            select($_POST['username']);
            break;
        case "selectCount":
            selectCount($_POST['username']);
            break;
        case "selectInfo":
            selectInfo($_POST['id']);
            break;
        case "insert":
            insert($_POST['firstname'],$_POST['lastname'],$_POST['birthdate'],$_POST['cin'],$_POST['sex'],$_POST['adress'],$_POST['phone'],$_POST['situation_marital'],$_POST['profile_photo'],$_POST['username']);
            break;
        case "update":
            update($_POST['id'],$_POST['firstname'],$_POST['lastname'],$_POST['birthdate'],$_POST['cin'],$_POST['sex'],$_POST['adress'],$_POST['phone'],$_POST['situation_marital'],$_POST['profile_photo']);
            break;
        case "delete":
            delete($_POST['id']);
            break;
        case "selectUser":
            selectUser();
            break;
        case "selectInfoUser":
            selectInfoUser($_POST['username']);
            break;
        case "updateUser":
            updateUser($_POST['id'],$_POST['profile_photo']);
            break;
    }

    
    function insert ($firstname, $lastname, $birthdate, $cin, $sex, $adress, $phone, $situation_marital, $profile_photo, $admin_username){
        GLOBAL $connection;
        $queryl = "SELECT id FROM admin_tbl WHERE `username` = :username";
        $al = $connection->prepare($queryl);
        $al->bindvalue(":username",$admin_username);
        $al->execute();
        $result = $al->fetchAll(PDO::FETCH_ASSOC);
        if($result[0]['id']!=0)
        {
            $admin_user = $result[0]['id'];

            $insert = "INSERT INTO `habitant_tbl` (`firstname`,`lastname`,`birthdate`,`cin`,`sex`,`adress`,`phone`,`situation_marital`,`profile_photo`,`admin_id`) VALUES (:firstname,:lastname,:birthdate,:cin,:sex,:adress,:phone,:situation_marital, :profile_photo, :admin_id)";
            $in = $connection->prepare($insert);
            $in->bindvalue(":firstname",$firstname);
            $in->bindvalue(":lastname",$lastname);
            $in->bindvalue(":birthdate",$birthdate);
            $in->bindvalue(":cin",$cin);
            $in->bindvalue(":sex",$sex);
            $in->bindvalue(":adress",$adress);
            $in->bindvalue(":phone",$phone);
            $in->bindvalue(":situation_marital",$situation_marital);
            $in->bindvalue(":profile_photo",$profile_photo);
            $in->bindvalue(":admin_id",$admin_user);
            $in->execute();
            $response["error"] = FALSE;
            $response["error_msg"] = "registration!";
            echo json_encode($response);
            if($insert)
            {
                $response["error"] = FALSE;
                echo json_encode($response);
            }else {
                // user failed to store
                $response["error"] = TRUE;
                $response["error_msg"] = "Unknown error occurred in registration!";
                echo json_encode($response);
            }
        }else {
            // user failed to store
            $response["error"] = TRUE;
            $response["error_msg"] = "Unknown error occurred!";
            echo json_encode($response);
        }
        
    }

    function update($id, $firstname, $lastname, $birthdate, $cin, $sex, $adress, $phone, $situation_marital,$profile_photo){
        GLOBAL $connection;
        $update = "UPDATE `habitant_tbl` SET `firstname` = :firstname, `lastname`= :lastname, `birthdate`= :birthdate, `cin`= :cin, `sex`= :sex, `adress`= :adress, `phone`= :phone, `situation_marital`= :situation_marital, `profile_photo` = :profile_photo WHERE `id` = :id";
        $up = $connection->prepare($update);
        $up->bindvalue(":id",$id);
        $up->bindvalue(":firstname",$firstname);
        $up->bindvalue(":lastname",$lastname);
        $up->bindvalue(":birthdate",$birthdate);
        $up->bindvalue(":cin",$cin);
        $up->bindvalue(":sex",$sex);
        $up->bindvalue(":adress",$adress);
        $up->bindvalue(":phone",$phone);
        $up->bindvalue(":situation_marital",$situation_marital);
        $up->bindvalue(":profile_photo",$profile_photo);
        $up->execute();
    }

    function select($admin_username)
    {
        GLOBAL $connection;

        $queryl = "SELECT * FROM admin_tbl WHERE `username` = :username";
        $al = $connection->prepare($queryl);
        $al->bindvalue(":username",$admin_username);
        $al->execute();
        $result = $al->fetchAll(PDO::FETCH_ASSOC);
        if($result[0]['admin']=='1')
        {

            $select = "SELECT * FROM habitant_tbl";
                $in = $connection->prepare($select);
                $in->execute();
                $result = array();
                $results = $in->fetchAll(PDO::FETCH_ASSOC);
                if(!$results)exit('pas d\'elements');
                echo json_encode(array('result'=>($results)));            
        }
        else
        {
            $admin_usern = $result[0]['id'];

                $select = "SELECT * FROM habitant_tbl WHERE `admin_id` = :admin_id";            
                $in = $connection->prepare($select);
                $in->bindvalue(":admin_id",$admin_usern);
                $in->execute();
                $result = array();
                $results = $in->fetchAll(PDO::FETCH_ASSOC);
                if(!$results)exit('pas d\'elements');
                echo json_encode(array('result'=>($results)));
        }        
       
    }

    function selectInfo($id_habitants)
    {
        GLOBAL $connection;

        $select = "SELECT * FROM habitant_tbl WHERE `id` = :id";            
        $in = $connection->prepare($select);
        $in->bindvalue(":id",$id_habitants);
        $in->execute();
        $result = array();
        $results = $in->fetchAll(PDO::FETCH_ASSOC);
        if(!$results)exit('pas d\'elements');
        echo json_encode(array('result'=>($results)));
    }

    function selectCount($admin_username)
    {
         GLOBAL $connection;

        $queryl = "SELECT * FROM admin_tbl WHERE `username` = :username";
        $al = $connection->prepare($queryl);
        $al->bindvalue(":username",$admin_username);
        $al->execute();
        $result = $al->fetchAll(PDO::FETCH_ASSOC);
        if($result[0]['id']!=0)
        {
            $admin_usern = $result[0]['id'];

                $select = "SELECT COUNT(firstname) FROM habitant_tbl WHERE `admin_id` = :admin_id";            
                $in = $connection->prepare($select);
                $in->bindvalue(":admin_id",$admin_usern);
                $in->execute();
                $result = array();
                $results = $in->fetchAll(PDO::FETCH_ASSOC);
                if(!$results)exit('pas d\'elements');
                echo json_encode(array('result'=>($results)));
        }    
    }


    function delete($id_habit)
    {
        GLOBAL $connection;

        
        $delete = "DELETE FROM habitant_tbl WHERE `id` = :id"; 
        $in = $connection->prepare($delete);
        $in->bindvalue(":id",$id_habit);
        $in->execute();         
        // $in = $connection->prepare($delete);
        // $in->bindvalue(":id",$id_habit);
        
    }

    function selectUser()
    {
        GLOBAL $connection;

        $select = "SELECT `username` FROM `admin_tbl`";
        $in = $connection->prepare($select);
        $in->execute();
        $result = array();
        $results = $in->fetchAll(PDO::FETCH_ASSOC);
        if(!$results)exit('pas d\'elements');
        echo json_encode(array('result'=>($results)));
    }
    function updateUser($id,$profile_photo)
    {
        GLOBAL $connection;
        
        $update = "UPDATE `admin_tbl` SET `profile_photo`= :profile_photo WHERE `id` = :id";
        $up = $connection->prepare($update);
        $up->bindvalue(":id",$id);
        $up->bindvalue(":profile_photo",$profile_photo);
        $up->execute();
    }

    function selectInfoUser($admin_username)
    {
        GLOBAL $connection;
        $queryl = "SELECT * FROM admin_tbl WHERE `username` = :username";
        $al = $connection->prepare($queryl);
        $al->bindvalue(":username",$admin_username);
        $al->execute();
        $result = $al->fetchAll(PDO::FETCH_ASSOC);
        if($result[0]['id']!=0)
        {
            $admin_usern = $result[0]['id'];

            $select = "SELECT `name`, `username`, `profile_photo` FROM admin_tbl WHERE `id` = :id";            
            $in = $connection->prepare($select);
            $in->bindvalue(":id",$admin_usern);
            $in->execute();
            $result = array();
            $results = $in->fetchAll(PDO::FETCH_ASSOC);
            if(!$results)exit('pas d\'elements');
            echo json_encode(array('result'=>($results)));
        }
    }

?>