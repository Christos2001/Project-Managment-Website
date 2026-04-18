<?php 


        require_once $_SERVER['DOCUMENT_ROOT'] . '/php/secure_cookies.php';
        require_once $_SERVER['DOCUMENT_ROOT'] . '/php/check_auth.php';
        require_once $_SERVER['DOCUMENT_ROOT'] . '/php/connect_to_DB.php';


        $username = $_SESSION["user"];
        $assign = $username;
        $lid =  $_GET["id"];

        $ids = array();
        $titles = array();
        $subjects = array();
        $status = array();
        $approved = array();
        $creator = null;    
        $dates = array();
        $assigned = array();
        $description_files = array();
        $solution_files = array();


        $result = $conn -> prepare(
            "SELECT title , creator, datePublished,subject,id,status,approved,description_file,solution_file  
            FROM `project` LEFT JOIN project_assignments pa ON project_id = id
            WHERE  list  = ? AND username = ?;");

        $result->bind_param("is",$lid,$username);
        if(!$result->execute()){
             echo json_encode([
                "success" => false,
                "errorType" => "unknown",
                "message" => "Uknown error.",
                "redirect" => "/html/projects/myProjectList.html"
            ]); 
            exit();
        }
        $result = $result->get_result();

       

        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {      
                $titles[] = $row["title"];
                $subjects[] = $row["subject"];
                $creator = $row["creator"];
                $dates[] = $row["datePublished"];
                $ids[] = $row["id"];
                $status[] = $row["status"];
                $approved[] = $row["approved"];
                $description_files[] = $row["description_file"];
                $solution_files[] = $row["solution_file"];
            }       
        }else{
             echo json_encode([
                "success" => false,
                "errorType" => "unknown",
                "message" => "Project is empty.",
                "redirect" => "/html/user/projects/myProjects.html"
            ]); 
            exit();

        }

        $result->close();

        $response = [
            "success" => true,
            "titles" => $titles,
            "subjects" => $subjects,
            "creator" => $creator,
            "dates" => $dates,
            "listID" => $lid,
            "user" => $username,
            "statuses" => $status,
            "approved" => $approved,
            "ids" => $ids,
            "description_files" => $description_files,
            "solution_files" => $solution_files
        ];

        echo json_encode($response, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT);


?>