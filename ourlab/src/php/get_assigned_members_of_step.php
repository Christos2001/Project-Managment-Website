<?php
        require_once $_SERVER['DOCUMENT_ROOT'] . '/php/secure_cookies.php';
        require_once $_SERVER['DOCUMENT_ROOT'] . '/php/check_auth.php';
        require_once $_SERVER['DOCUMENT_ROOT'] . '/php/connect_to_DB.php';
        $pid = $_GET['id']; 
        $sql = "SELECT username FROM project_assignments WHERE project_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $pid);
        $stmt->execute();
        $result = $stmt->get_result();


        $all_usernames = []; 


        while ($row = $result->fetch_assoc()) {
            $all_usernames[] = $row['username']; 
        }


        header('Content-Type: application/json');
        echo json_encode($all_usernames);




?>