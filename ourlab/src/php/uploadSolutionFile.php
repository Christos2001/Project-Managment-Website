<?php
    require_once $_SERVER['DOCUMENT_ROOT'] . '/php/secure_cookies.php';
    require_once $_SERVER['DOCUMENT_ROOT'] . '/php/check_auth.php';
    require_once $_SERVER['DOCUMENT_ROOT'] . '/php/connect_to_DB.php';


    $username = $_SESSION["user"];
    $pid3 = $_POST["pid3"];

    $result = $conn -> prepare(
        "SELECT  creator
        FROM `project` WHERE  id  = ?;");

    $result->bind_param("i",$pid3);

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
    if (isset($_FILES["sol_file"]) && $_FILES["sol_file"]["error"] == 0) {
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
        $mime = $finfo->file($_FILES['sol_file']['tmp_name']);

        if (!in_array($mime, $allowedMimes)) {
            echo json_encode(["success"=>true,"message"=>"Not supported file."]);
            exit();
        }
        $target_dir = "/var/www/private_data/solutions/";;
        $originalName = basename($_FILES["sol_file"]["name"]);//defense against Directory Traversal
        $originalName = preg_replace("/[^a-zA-Z0-9._-]/", "_", $originalName);//clean from dangerous characters
        $fileName = "sol_" . $pid3 . "_" . $originalName;
        $target_file = $target_dir . $fileName;
            
        if (move_uploaded_file($_FILES["sol_file"]["tmp_name"], $target_file)) {
                try{
                $result2 = $conn->prepare("UPDATE `project` SET solution_file = ?, `status`='Completed'  WHERE id = ?
                            AND EXISTS (
                                SELECT 1 
                                FROM `project_assignments` 
                                WHERE `project_id` = ? 
                                AND `username` = ?
                            )");//check project assignment (hacker protection)
                $result2->bind_param("siis",$fileName,$pid3,$pid3,$username);
            
                if ($result2->execute()) {
                    if($result2->affected_rows >0){
                        echo json_encode(["success"=>true,"message"=>"File uploaded!"]);
                        exit();
                        }else{
                            unlink($target_file);
                            echo json_encode(["success"=>false,"message"=>"Error uploading file."]);
                            exit();
                        }
                }else{
                    unlink($target_file);
                    echo json_encode(["success"=>false,"message"=>"Error uploading file."]);
                    exit();
                }
                $result2 ->close();
            }catch(Exception $e){
                unlink($target_file);
                echo json_encode(["success"=>false,"message"=>"Error uploading file."]);
                exit();
            }
            
        }else{
            echo json_encode(["success"=>false,"message"=>"Error uploading file."]);
            exit();
        }
    }else{
        echo json_encode(["success"=>false,"message"=>"Error uploading file."]);
        exit();
    }

}



?>