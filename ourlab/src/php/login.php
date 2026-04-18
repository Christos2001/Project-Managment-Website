<?php 

        require_once $_SERVER['DOCUMENT_ROOT'].'/php/secure_cookies.php';
        session_start();
        require_once $_SERVER['DOCUMENT_ROOT'].'/php/connect_to_DB.php';
        

        $user = $_POST["username"];
        $pass = $_POST["password"];
        
        $stmt = $conn->prepare("SELECT password FROM user WHERE username = ?");
        $stmt->bind_param("s", $user);

        if ($stmt->execute()) {
            $stmt->bind_result($hashedPassword);
            
            if ($stmt->fetch()) {
                if (password_verify($pass, $hashedPassword)) {
                    $_SESSION['loggedin'] = true;
                    $stmt->close();
                    $creds = "SELECT firstName,lastName,email,tel,is_active  FROM user WHERE username =  ?";
                    $result = $conn -> prepare($creds);
                    $result->bind_param("s",$user);

                    if (!$result->execute()) {
                        echo '<script type="text/javascript">';
                        echo 'alert("Uknown error.");';
                        echo 'window.history.back()';
                        echo '</script>';
                    }
                    $result = $result->get_result();
                    $row = $result -> fetch_assoc();
                    if($row){
                        if($row["is_active"]!= TRUE){
                            echo '<script type="text/javascript">';
                            echo 'alert("Your account is not verified.");';
                            echo 'window.history.back()';
                            echo '</script>';
                        }
                        $_SESSION["fname"] = $row["firstName"];
                        $_SESSION["lname"] = $row["lastName"];
                        $_SESSION["email"] = $row["email"];
                        $_SESSION["phone"] = $row["tel"];
                    }
                    
                    $_SESSION['user'] = $user;
                    $_SESSION['password'] = $pass;
                    session_write_close();
                    echo '<script type="text/javascript">';
                    echo 'window.location.href = "../html/user/mainu.php"';
                    echo '</script>';
                }else{
                    echo '<script type="text/javascript">';
                    echo 'alert("Wrong creditentials! Redirecting to login page.");';
                    echo 'window.history.back()';
                    echo '</script>';
                }
            }else{
                echo '<script type="text/javascript">';
                echo 'alert("Wrong creditentials! Redirecting to login page.");';
                echo 'window.history.back()';
                echo '</script>';
            }
        }else{
            echo '<script type="text/javascript">';
            echo 'alert("Uknown error.");';
            echo 'window.history.back()';
            echo '</script>';
        }
        $conn->close();
    
    
    
    ?>
