<?php

    require_once $_SERVER['DOCUMENT_ROOT'] . '/php/secure_cookies.php';
    require_once $_SERVER['DOCUMENT_ROOT'] . '/php/check_auth.php';
    require_once $_SERVER['DOCUMENT_ROOT'] . '/php/connect_to_DB.php';

 

    $username = $_SESSION["user"];
    $filename = $_GET['name'];
    $pid = $_GET['pid'];
    //Security check to prevent directory traversal (../../)
    $filename = basename($filename);

    $result = $conn->prepare("SELECT 1 FROM project_assignments WHERE project_id = ? AND username = ?");
    $result->bind_param("is",$pid,$username);


    if(!$result->execute()){
        header("Location: ../html/user/mainu.php?error=db_conn");
        exit();
    }



    $res = $result->get_result();

    if(!$res->num_rows > 0){//only hacker can access this
        exit();
    }
    $file = "/var/www/private_data/solutions/".$filename;



     if (file_exists($file)) {
        //ensures the browser handles the bits correctly
        $mime = mime_content_type($file); 

        // Required headers for download
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . basename($file) . '"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($file));

        // 6. Stream the file
        readfile($file);
        exit;
    }
?>