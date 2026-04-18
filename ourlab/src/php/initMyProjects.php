<?php
        require_once $_SERVER['DOCUMENT_ROOT'] . '/php/secure_cookies.php';
        require_once $_SERVER['DOCUMENT_ROOT'] . '/php/check_auth.php';
        require_once $_SERVER['DOCUMENT_ROOT'] . '/php/connect_to_DB.php';

        $username = $_SESSION["user"];

        $titles = array();
        $creators = array();    
        $dates = array();
        $ids = array();
                
        


        $result = $conn->prepare("SELECT title, creator, Date, id FROM projectlist 
                                    INNER JOIN list_permissions lp ON id = lp.list_id
                                    WHERE username = ? ");
        $result->bind_param("s",$username);
        if(!$result->execute()){
            echo json_encode([
                    "success" => false,
                    "errorType" => "unknown",
                    "message" => "Unknown error.",
                    "redirect" => "/html/user/mainu.php"
                ]); 
                exit();
        }
        $result = $result->get_result(); 

        

    
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $titles[] = $row["title"];
                $creators[] = $row["creator"];
                $dates[] = $row["Date"];
                $ids[] = $row["id"];
                    
                }
         }

            

      

        $response = [ 
            "success" => true,
            "titles"   => $titles,   
            "creators" => $creators, 
            "dates"    => $dates,    
            "lIDs"     => $ids,      
            "user"     => $username  
        ];


        echo json_encode($response, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT);

        exit;

?>