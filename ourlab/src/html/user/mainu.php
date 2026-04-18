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
    <link rel="stylesheet"  type="text/css" href="../../css/navbar_footer.css">
    <link id='theme' type="text/css" rel='stylesheet' href="../../css/main.css">

    <title>Main page</title>
</head>
<body>


<div id="container">
    
 <div id="topnav">
            <a href="mainu.php">Home</a>
            <a href="../../phpPages/myProfile.php">Profile</a>
            <a href="projects/myProjects.html">My Projects</a>
            <div id="authButtons">
                <a class="left-corner" href="/php/logout.php">Logout</a>
            </div>

    </div>

    <div id="main" >
        <p>Publish your projects and see others projects in ourLab</p><br>

        <img src="../../img/book.avif" alt="" style="width: 150px; height: 150px;">
    </div>
    <div class="separator"></div>

    <div>
        <p>OurLab is designed to help teams organize tasks and projects with ease. Users can create workflows step-by-step <br>and track progress from start to finish. Once a task is finished, the assignee simply marks the step as complete / or attach a solution file to keep the project moving.</p>
    </div>




</div>


<footer id='footer'>
    <span id='copyright'>
        <p><i>Creator's Github: </i><a target="_blank" href="https://github.com/Christos2001">Christos2001</a></p>
    </span>
</footer>
    
<script>
    if (new URLSearchParams(window.location.search).get("error") == "db_conn"){
        alert("An error occured")
    }
</script>




    
</body>



</html>