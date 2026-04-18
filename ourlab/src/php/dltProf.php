<?php
        require_once $_SERVER['DOCUMENT_ROOT'] . '/php/secure_cookies.php';
        require_once $_SERVER['DOCUMENT_ROOT'] . '/php/check_auth.php';
        require_once $_SERVER['DOCUMENT_ROOT'] . '/php/connect_to_DB.php';
       
    

            $username = $_SESSION['user'];
            $stmt = $conn->prepare("
            UPDATE user
            SET 
                firstName = SUBSTRING(CONCAT(SHA2(RAND(), 256), SHA2(RAND(), 256)), 1, 16),
                lastName = SUBSTRING(CONCAT(SHA2(RAND(), 256), SHA2(RAND(), 256)), 1, 16),
                username = SUBSTRING(CONCAT(SHA2(RAND(), 256), SHA2(RAND(), 256)), 1, 16),
                password = SUBSTRING(CONCAT(SHA2(RAND(), 256), SHA2(RAND(), 256)), 1, 16),
                email = SUBSTRING(CONCAT(SHA2(RAND(), 256), SHA2(RAND(), 256)), 1, 16),
                tel = SUBSTRING(CONCAT(SHA2(RAND(), 256), SHA2(RAND(), 256)), 1, 16)
            WHERE username = ?;");

            $stmt->bind_param("s", $username);
            $stmt ->execute();
            
            if ($stmt->affected_rows > 0) {
                $stmt -> close();
                echo "<script> alert('Your account deleted succesfully!')</script>";
                session_destroy();
                echo "<script>window.location.href = '../html/main.html';</script>";
            } else {
                echo "<script> alert('Error deleting your account.')</script>";
                echo "Error deleting account " . $conn->error ;
            }
    

    ?>








    
</body>
 

</html>
