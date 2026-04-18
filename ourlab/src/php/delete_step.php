<?php
        require_once $_SERVER['DOCUMENT_ROOT'] . '/php/secure_cookies.php';
        require_once $_SERVER['DOCUMENT_ROOT'] . '/php/check_auth.php';
        require_once $_SERVER['DOCUMENT_ROOT'] . '/php/connect_to_DB.php';
        
        $username = $_SESSION["user"];

        $lid = $_GET["lid"];
        $stmt1 = $conn->prepare("SELECT creator FROM `projectlist` WHERE id = ?");
        $stmt1->bind_param("i", $lid);
        $stmt1->execute();
        $stmt1->bind_result($creator);
        $stmt1->fetch();
        $stmt1->close();
        if($username == $creator){//protection from hacker
            $pid = $_GET["pid"];
            try{
                //for deleting files
                $files = $conn->prepare("SELECT solution_file,description_file FROM `project` WHERE id = ?");
                $files->bind_param("i", $pid);
                $files->execute();
                $files = $files->get_result(); 
                if(!$files){
                    throw new Exception();
                }

                $stmt = $conn->prepare("DELETE FROM project WHERE id = ?");
                $stmt->bind_param("i", $pid);
                $conn->begin_transaction();
                if(!$stmt->execute()){
                    $stmt->close(); 
                    throw new Exception();
                }else{
                    $stmt->close();
                    
                    if ($files->num_rows > 0) {
                        while($row = $files->fetch_assoc()) {
                            $solFile = $row["solution_file"];
                            $descFile = $row["description_file"];
                        }
                    }
                    //delete files
                    $files->close();
                    $target_dirSol = "/var/www/private_data/solutions/";;
                    $target_dirDesc = "/var/www/private_data/descriptions/";;

                    if($solFile != null){
                        $target_fileSol = $target_dirSol . $solFile;
                        //Check if it exists so we don't crash if a previous attempt of this file partially succeeded
                        if (file_exists($target_fileSol)) {
                            if(!unlink($target_fileSol)){
                                throw new Exception();
                            }
                        }
                    }

                    if($descFile != null){
                         $target_fileDesc = $target_dirDesc . $descFile;
                         if (file_exists($target_fileDesc)) {
                            if(!unlink($target_fileDesc)){
                                throw new Exception();
                            }
                         }
                    }

                    $conn->commit();
                    echo json_encode(["success"=>true]);
                    exit();
                }
            }catch(Exception $e){
                $conn->rollback();
                echo json_encode(["success"=>false]);
                exit();
            }
        }
         

    

?>