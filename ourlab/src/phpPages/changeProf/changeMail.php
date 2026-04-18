<?php
        require_once $_SERVER['DOCUMENT_ROOT'] . '/php/secure_cookies.php';
        require_once $_SERVER['DOCUMENT_ROOT'] . '/php/check_auth.php';
        require_once $_SERVER['DOCUMENT_ROOT'] . '/php/connect_to_DB.php';
       
    ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet"type="text/css" href="../../../css/main.css">
    <link id='theme' type="text/css" rel='stylesheet' href="../../../css/navbar_footer.css">
    <link id='theme' type="text/css" rel='stylesheet' href="../../../css/profile.css">

    <title>Change Mail</title>
</head>
<body>
    <div id="topnav">
            <a href="/html/mainu.php">Home</a>
            <a href="../myProfile.php">Profile</a>
            <a href="/html/user/projects/myProjects.html">My Projects</a>
            <div id="authButtons">
                <a class="left-corner" href="/php/logout.php">Logout</a>
            </div>

    </div>

    

   
    <div id="container">

    <div  id="MYprofile">
        <div>
            <form method="POST">
                <p>New Email</p>
                <input type="text" name="newMail">
                <button type="submit">Change</button>
                

            </form>
            <a  href="../MYprofile.php"><button id = "backP">Back</button></a>
        </div>
    </div>

    <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $email = $_SESSION["email"];
            $newMail = $_POST["newMail"];


            $checkUser = $conn -> prepare("SELECT EXISTS(SELECT 1 FROM user WHERE email = ?)");
            $checkUser ->bind_param("s",$newMail);
            if ($checkUser -> execute()){
                $checkUser->bind_result($check);
                $checkUser->fetch();
                $checkUser->close();
                if ($check != 1){
                    $stmt = $conn->prepare("UPDATE `user` SET email = ? WHERE email = ?");

                    $stmt->bind_param("ss", $newMail, $email);
                    $stmt ->execute();
                    
                    if ($stmt->affected_rows > 0) {
                        $_SESSION["email"] = $newMail;
                        $stmt -> close();
                        echo "<script> alert('Your email has been changed!')</script>";
                        echo "<script>window.location.href = '../myProfile.php';</script>";
                    } else {
                        echo "Error updating email: " . $conn->error ;
                    }
                
                }else {
                    echo "<Script>alert('This email is not available!') </Script>";
                }
            }
    
         }
    ?>

    </div>



<footer id='footer'>
    <span id='copyright'>
        <p><i>Creator's Github: </i><a target="_blank" href="https://github.com/Christos2001">Christos2001</a></p>
    </span>
</footer>
    




    
</body>

 

</html>
