<?php
    require_once $_SERVER['DOCUMENT_ROOT'] . '/php/secure_cookies.php';
    require_once $_SERVER['DOCUMENT_ROOT'] . '/php/check_auth.php';
    require_once $_SERVER['DOCUMENT_ROOT'] . '/php/connect_to_DB.php';


    $username = $_SESSION["user"];
    $pid2 = $_POST["pid2"];

    $result = $conn -> prepare(
        "SELECT  creator
        FROM `project` WHERE  id  = ?;");

    $result->bind_param("i",$pid2);

    $result->execute();
    $res = $result->get_result(); 
    $row = $res->fetch_assoc();   

    if ($row) {
        $creator = $row['creator']; 
    } else {
         echo json_encode(["success"=>false,"message" => "Uknwon error."]);
         exit();
    }

    if($creator != $username){//protection from hacker bcs form is hidden
                        $sstatus = ucfirst($_POST["status"]);
                        $result1 = $conn -> prepare("UPDATE `project` SET `status` = ? WHERE id = ? AND EXISTS (
                                    SELECT 1 
                                    FROM `project_assignments` 
                                    WHERE `project_id` = ? 
                                    AND `username` = ?
                                )");//check project assignment (hacker protection)
                        $result1->bind_param("siis",$sstatus,$pid2,$pid2,$username);

                        if ($result1->execute()) {
                                echo json_encode(["success"=>true,"message"=>"Status changed."]);
                                exit();
                        }else {
                            echo json_encode(["success"=>false,"message"=>"Uknown error."]);
                            exit();
                        }
    }

?>