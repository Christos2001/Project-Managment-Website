<?php 
        require_once $_SERVER['DOCUMENT_ROOT'] . '/php/secure_cookies.php';
        require_once $_SERVER['DOCUMENT_ROOT'] . '/php/check_auth.php';
        require_once $_SERVER['DOCUMENT_ROOT'] . '/php/connect_to_DB.php';


        $nameAssigned = $_POST["Iassign"];
        $username = $_SESSION['user'];
        $title = $_POST['title'];
        $subject = $_POST['subject'];
        $list = $_POST['id'];


        $result = $conn -> prepare(
        "SELECT  creator
        FROM `projectlist` WHERE  id  = ?;");

        $result->bind_param("i",$list);

        $result->execute();
        $res = $result->get_result(); 
        $row = $res->fetch_assoc();   

        if ($row) {
            $creator = $row['creator']; 
        } else {
            echo "<script> 
                alert('Uknown error.')
                window.location.href ='/html/user/projects/createStep.html?id=$list'
            </script>";
            exit();
        }
        
        if($creator != $username){//only hacker can do that.
             echo "<script> 
                alert('Uknown error.')
                window.location.href ='/html/user/projects/createStep.html?id=$list'
            </script>";
        }




        if($username == $nameAssigned){
            echo "<script> 
                alert('You can not assign yourself.')
                window.location.href ='/html/user/projects/createStep.html?id=$list'
            </script>";
            die();
        }
        $conn->begin_transaction();
        try{

        $fileName = "empty";

        $result = $conn -> prepare("SELECT username  FROM `user` WHERE username = ?;");
        $result->bind_param("s",$nameAssigned);

        if(!$result->execute()){
                echo '<script type="text/javascript">';
                echo 'alert("An error has occured.")';
                echo  "window.location.href ='/html/user/projects/createStep.html?id=$list'";
                echo '</script>';
        }
        $result = $result->get_result(); 

        if($result->num_rows > 0){
            $result->close();



            $check = $conn->prepare("SELECT 1 
                    FROM `list_permissions` 
                    WHERE `list_id` = ? 
                    AND `username` = ?
                ");//check project assignment (hacker protection)
            $check->bind_param("is",$list,$username);
            $check->execute();
            $resCheck = $check->get_result();
            if(! ($resCheck->num_rows > 0)){
                throw new Exception(1);
            }

            //update list_permissions
            $result = $conn->prepare("INSERT INTO list_permissions (list_id, username) VALUES (?, ?) ON DUPLICATE KEY UPDATE username=username;");
            $result->bind_param("is",$list,$nameAssigned);
            $result->execute();

                                
            $result->close();
            if (isset($_FILES["Descfile"]) && $_FILES["Descfile"]["error"] == 0) {
                //defense for Fake Extension Hack
                    $allowedMimes = [
                    // --- IMAGES ---
                    'image/jpeg',      // .jpg, .jpeg
                    'image/png',       // .png
                    'image/gif',       // .gif
                    'image/webp',      // .webp (Modern, highly recommended)
                    'image/bmp',       // .bmp
                    'image/tiff',      // .tif, .tiff
                    'image/svg+xml',   // .svg (Careful: SVGs can carry malicious scripts!)

                    // --- PDF ---
                    'application/pdf', // .pdf

                    // --- WORD DOCUMENTS ---
                    'application/msword',                                                         // .doc (Legacy)
                    'application/vnd.openxmlformats-officedocument.wordprocessingml.document',    // .docx (Modern)
                    'application/vnd.openxmlformats-officedocument.wordprocessingml.template',    // .dotx (Word Template)
                ];

                $finfo = new finfo(FILEINFO_MIME_TYPE);
                $mime = $finfo->file($_FILES['Descfile']['tmp_name']);

                if (!in_array($mime, $allowedMimes)) {
                    echo '<script type="text/javascript">';
                    echo 'alert("Not supported file.");';
                    echo  "window.location.href ='/html/user/projects/createStep.html?id=$list'";
                    echo '</script>';
                    exit();
                }
                //add file
                $target_dir = "/var/www/private_data/descriptions/";
                $result = $conn -> query("SELECT MAX(id) FROM `project`");
                if (!$result) {
                    throw new Exception(2);
                }

                $row = $result->fetch_row();
                $result -> close();
                $project_id = $row[0] + 1;

                $originalName = basename($_FILES["Descfile"]["name"]);//defense against Directory Traversal
                $originalName = preg_replace("/[^a-zA-Z0-9._-]/", "_", $originalName);//clean from dangerous characters
                $fileName = "desc_" . $project_id . "_" . $originalName;
                $target_file = $target_dir . $fileName;

                if (move_uploaded_file($_FILES["Descfile"]["tmp_name"], $target_file)) {
                    $prpIns = $conn->prepare( "INSERT INTO project (title,datePublished,creator,subject,list,status,description_file)
                    VALUES (?,CURRENT_TIMESTAMP(),?,?,?,'Pending',?) ");

                    $prpIns->bind_param("sssis",$title,$username,$subject,$list,$fileName);
                    if (!$prpIns->execute()) {
                        throw new Exception(3);
                    }
                
            } else {
                throw new Exception(4);
            }


            }else{
                        $prpIns = $conn->prepare( "INSERT INTO project (title,datePublished,creator,subject,list,status)
                        VALUES (?,CURRENT_TIMESTAMP(),?,?,?,'Pending') ");

                        $prpIns->bind_param("sssi",$title,$username,$subject,$list);
                        if (!$prpIns->execute()) {
                            throw new Exception(5);
                        }
            }
            
            $newProjectId = $conn->insert_id; 

            $result = $conn->prepare("INSERT INTO project_assignments(project_id, username) VALUES (?, ?)");
            $result->bind_param("is",$newProjectId,$nameAssigned);
            if(!$result->execute()){
                throw new Exception(6);
            }
            $result->bind_param("is",$newProjectId,$username);
            if(!$result->execute()){
                throw new Exception(7);
            }

            $result->close();
            $conn->commit();
            echo '<script type="text/javascript">';
            echo 'alert("Project has been created! ");';
            echo  "window.location.href ='/html/user/projects/createStep.html?id=$list'";
            echo '</script>';
            

            }else{
                $result->close();
                echo "<script> 
                        alert('Username $nameAssigned does not exist.')
                        window.location.href ='/html/user/projects/createStep.html?id=$list'
                    </script>";
            } 
        }catch (Exception $e){
            $conn->rollback();
            if (file_exists($target_file)) {
                unlink($target_file);
            }
            echo "error code:" . $e->getMessage();
            echo '<script type="text/javascript">';
            echo 'alert("An error has occured.")';
            echo  "window.location.href ='/html/user/projects/createStep.html?id=$list'";
            echo '</script>';
        }


            
    
    ?>









