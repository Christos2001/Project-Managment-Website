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

    <title>Change phone number</title>
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
                <p>New Phone</p>
                <input type="text" name="newPhone">
                <button type="submit">Change</button>
                

            </form>
            <a  href="../MYprofile.php"><button id = "backP">Back</button></a>
        </div>
    </div>

    <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Check if the form has been submitted
            $phone = $_SESSION["phone"];
            $newPhone = $_POST["newPhone"];


              
            $checkUser = $conn -> prepare("SELECT EXISTS(SELECT 1 FROM user WHERE tel = ?)");
            $checkUser ->bind_param("s",$newPhone);
            if ($checkUser -> execute()){
                $checkUser->bind_result($check);
                $checkUser->fetch();
                $checkUser->close();
                if ($check != 1){
                $stmt = $conn->prepare("UPDATE `user` SET tel = ? WHERE tel = ?");

                $stmt->bind_param("ss", $newPhone, $phone);
                $stmt ->execute();
                
                if ($stmt->affected_rows > 0) {
                    $_SESSION["phone"] = $newPhone;
                    $stmt -> close();
                    echo "<script> alert('Your phone number has been changed!')</script>";
                    echo "<script>window.location.href = '../myProfile.php';</script>";
                } else {
                    echo "Error updating phone: " . $conn->error ;
                }
                }else {
                    echo "<Script>alert('This phone number is not available!') </Script>";
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
