<?php
        $servername = getenv("DB_SERVER_NAME");             
        $dbname = getenv("DB_NAME");    
        $username = getenv("DB_USERNAME");             
        $password = getenv("DB_PASS");             
        
        
        
        $conn = new mysqli($servername, $username, $password,$dbname);

        if ($conn->connect_error) {
                        echo '<script type="text/javascript">';
                        echo 'alert("An error has occured");';
                        echo "window.location.href = /html/main.html";
                        echo '</script>';  
        }     
        ?>