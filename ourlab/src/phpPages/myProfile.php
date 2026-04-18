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
    <link rel="stylesheet"  type="text/css" href="../../css/main.css?v=1.0">
    <link id='theme' type="text/css" rel='stylesheet' href="../../css/navbar_footer.css?">
    <link id='theme' type="text/css" rel='stylesheet' href="../../css/profile.css?v=1.0">

    <title>MY profile</title>
</head>
<body>

<div id="topnav">
            <a href="/html/user/mainu.php">Home</a>
            <a href="myProfile.php">Profile</a>
            <a href="/html/user/projects/myProjects.html">My Projects</a>
            <div id="authButtons">
                <a class="left-corner" href="/php/logout.php">Logout</a>
            </div>

    </div>
    
<div id="container">

 


<div id="fname" ><h2>Hello,  </h2> <?php echo $_SESSION["fname"] .  " " . $_SESSION["lname"] ?> </div>

<br>From here you can see and change your data account.<br>If you want to permantly delete your account <form id="dltForm" action="../../php/dltProf.php" method="POST"><button type = "submit" id="dltALL">Press here</button></form>

    <div id = "MYprofile">


        <div id = "cred">
        <h3>Creditentials</h3>

        <p><b>Username</b>: <?php echo $_SESSION["user"] ?></p> <a href="changeProf/changeName.php"><button> Change username</button></a>

       
</p> <a href="changeProf/changePass.php"><button> Change password</button></a>
        </div>


        <div id = "contact">
        <h3>Contact</h3>
            <p><b>Email</b>:<?php echo $_SESSION["email"] ?> </p> <a href="changeProf/changeMail.php"><button> Change email</button></a>
            <p><b>Tel.</b>: <?php echo $_SESSION["phone"] ?> </p> <a href="changeProf/changeTel.php"><button> Change phone number</button></a>
        </div>


    

</div>
</div>

<footer id='footer'>
    <span id='copyright'>
        <p><i>Creator's Github: </i><a target="_blank" href="https://github.com/Christos2001">Christos2001</a></p>
    </span>

</footer>
    




    
</body>


<script src="../../js/dlt.js?v=2.0"></script>


</html>