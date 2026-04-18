<?php
        require_once $_SERVER['DOCUMENT_ROOT'] . '/php/secure_cookies.php';
        require_once $_SERVER['DOCUMENT_ROOT'] . '/php/check_auth.php';
        require_once $_SERVER['DOCUMENT_ROOT'] . '/php/connect_to_DB.php';

        $username = $_SESSION["user"];
      
        $lid = $_POST["hidden"];
                
        $stmt1 = $conn->prepare("SELECT creator FROM `projectlist` WHERE id = ?");
        $stmt1->bind_param("i", $lid);
        $stmt1->execute();
        $stmt1->bind_result($creator);
        $stmt1->fetch();
        if($creator == $username){ //protection from hacker bcs form/button is hidden
            $stmt1->close();
            try{
                $conn->begin_transaction();

                $files = $conn->prepare("SELECT solution_file,description_file FROM `project` WHERE list = ?");
                $files->bind_param("i", $lid);
                $files->execute();
                $files = $files->get_result(); 
                if(!$files){
                    throw new Exception();
                }


                $stmt1 = $conn->prepare("DELETE FROM `project` WHERE list = ?");
                $stmt1->bind_param("i", $lid);
                $stmt1->execute();
                $deletedProject = $stmt1->affected_rows;

                $stmt2 = $conn->prepare("DELETE FROM `projectlist` WHERE id = ?");
                $stmt2->bind_param("i", $lid);
                $stmt2->execute();
                $deletedList = $stmt2->affected_rows;

                if (!$deletedList && !$deletedProject) {
                    throw new Exception();
                }else{

                    $target_dirSol = "/var/www/private_data/solutions/";
                    $target_dirDesc = "/var/www/private_data/descriptions/";

                    //delete files
                    if ($files->num_rows > 0) {
                        while($row = $files->fetch_assoc()) {
                            $solFile = $row["solution_file"];
                            $descFile = $row["description_file"];

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


                        }
                    }
                    $files->close();
                    

                    $conn->commit();
                    echo '<script type="text/javascript">';
                    echo 'alert("Project deleted.");';
                    echo "window.location.href = '/html/user/projects/myProjects.html'";
                    echo '</script>';  
                    }
            }catch(Exception $e){
                $conn->rollback();
                echo '<script type="text/javascript">';
                echo 'alert("Uknown error.");';
                echo "window.location.href = '/html/user/projects/myProjectList.html'";
                echo '</script>';  
            }
        }
    



?>