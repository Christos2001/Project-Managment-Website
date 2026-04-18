<?php
    require_once $_SERVER['DOCUMENT_ROOT'] . '/php/secure_cookies.php';
    require_once $_SERVER['DOCUMENT_ROOT'] . '/php/check_auth.php';
    require_once $_SERVER['DOCUMENT_ROOT'] . '/php/connect_to_DB.php';



    $username = $_SESSION["user"];
    $pid = $_POST["pid"];
    $nameAssigned = $_POST["Iassign"];

    $result = $conn -> prepare(
        "SELECT  creator,list
        FROM `project` WHERE  id  = ?;");

    $result->bind_param("i",$pid);

    $result->execute();
    $res = $result->get_result(); 
    $row = $res->fetch_assoc();   

    if ($row) {
        $creator = $row['creator']; 
        $lid = $row["list"];
    } else {
         echo json_encode(["success" => false , "message" => "Uknown error."]);
         exit();
    }




    if($creator == $username){//protection from hacker bcs form is hidden

        $conn->begin_transaction();
        try{
            $resultA = $conn -> prepare("SELECT username  FROM `user` WHERE username = ? ");
            $resultA->bind_param("s",$nameAssigned);
            if(!$resultA->execute()){
                throw new Exception();
            }
            $resultA->store_result();
            if($resultA->num_rows > 0){
                $resultA->close();


                $resultA = $conn->prepare("INSERT INTO project_assignments(project_id,username) VALUES (?,?) ON DUPLICATE KEY UPDATE username=username;");
                $resultA->bind_param("is",$pid,$nameAssigned);

                $resultPhelp = $conn ->prepare("SELECT list  FROM `project` WHERE id=?;");
                $resultPhelp->bind_param("i",$pid);

                if ($resultA->execute()) {                         
                        if(!$resultPhelp ->execute()){
                            throw new Exception();
                        }
                        $res = $resultPhelp->get_result(); 
                        $row = $res->fetch_assoc();
                        if (!$row) {
                            throw new Exception();
                        }
                        $list = $row["list"];
                        
                        $result2 = $conn->prepare("INSERT INTO list_permissions(list_id,username) VALUES (?,?) ON DUPLICATE KEY UPDATE username=username;");

                        $result2->bind_param("is",$list,$nameAssigned);
                        if ($result2->execute()) {
                            $conn->commit();
                            $nameAssigned = htmlspecialchars($nameAssigned, ENT_QUOTES, 'UTF-8');//protection
                            echo json_encode(["success" => false, "message"=>$nameAssigned.' is assigned to the project.']);
                            exit();
                        }else{
                            throw new Exception();
                        }
                    $result2 ->close();
            } else {
                throw new Exception();                            
            }

            }else{
                $nameAssigned = htmlspecialchars($nameAssigned, ENT_QUOTES, 'UTF-8');//protection
                echo json_encode(["success" => false, "message"=>'Username '. $nameAssigned.' does not exist.']);
                exit();
            }
        }catch(Exception $e){
            echo json_encode(["success" => false ,"message"=>"Uknown error."]);
            exit();
        }
    }


?>
