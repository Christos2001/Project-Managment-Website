<?php
    require_once $_SERVER['DOCUMENT_ROOT'] . '/php/secure_cookies.php';
    require_once $_SERVER['DOCUMENT_ROOT'] . '/php/check_auth.php';
    require_once $_SERVER['DOCUMENT_ROOT'] . '/php/connect_to_DB.php';



    $username = $_SESSION["user"];
    $pid4 = $_POST["pid4"];
    $approve = $_POST["approve"];

    $result = $conn -> prepare(
        "SELECT  creator
        FROM `project` WHERE  id  = ?;");

    $result->bind_param("i",$pid4);

    $result->execute();
    $res = $result->get_result(); 
    $row = $res->fetch_assoc();   

    if ($row) {
        $creator = $row['creator']; 
    } else {
         echo json_encode(["success"=>false,"message" => "Uknwon error."]);
         exit();
    }


    
    if($creator == $username){//protection from hacker bcs form is hidden
   
        if($approve == 1){
            $result1 = $conn -> prepare("UPDATE `project` SET `approved` = ? WHERE id = ?");
        }else{
            $result1 = $conn -> prepare("UPDATE `project` SET `approved` = ?,status='In progress' WHERE id = ? ");
        }

    
        $result1->bind_param("ii",$approve,$pid4);
        if($result1->execute()){
            echo json_encode(["success"=>true,"message" => null]);
            exit();
        }else{
            echo json_encode(["success"=>false,"message" => "Uknown error."]);
            exit();

        }
        
    }

              



?>
