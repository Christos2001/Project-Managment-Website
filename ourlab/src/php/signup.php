<?php 
    require_once $_SERVER['DOCUMENT_ROOT'] . '/php/secure_cookies.php';
    require_once $_SERVER['DOCUMENT_ROOT'] . '/php/connect_to_DB.php';
    session_start();

    require_once __DIR__ . '/../vendor/autoload.php'; 

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;
    use PHPMailer\PHPMailer\SMTP;
    

$fname = $_POST["fname"];
$lname = $_POST["lname"];
$username = $_POST["username"];
$password = $_POST["password"];
$email = $_POST["email"];
$tel = $_POST["phone"];

if (empty($fname) || empty($lname) || empty($username) ){
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo '<script type="text/javascript">';
        echo 'alert("Wrong email address.");';
        echo 'window.history.back()';//use back, so user's input is not lost, (works most of the time)
        echo '</script>';
    }
}



$prpUcheck = $conn -> prepare("SELECT COUNT(username) FROM user WHERE username = ?");
$prpUcheck ->bind_param("s",$username);
$prpUcheck -> execute();
$prpUcheck->bind_result($countU);
$prpUcheck -> fetch();
$prpUcheck->close();

$prpEcheck = $conn -> prepare("SELECT COUNT(email) FROM user WHERE email = ?");
$prpEcheck ->bind_param("s",$email);
$prpEcheck -> execute();
$prpEcheck->bind_result($countE);
$prpEcheck->fetch();
$prpEcheck->close();

$prpTcheck = $conn -> prepare("SELECT COUNT(tel) FROM user WHERE tel = ?");
$prpTcheck ->bind_param("s",$tel);
$prpTcheck -> execute();
$prpTcheck->bind_result($countT);
$prpTcheck -> fetch();
$prpTcheck->close();

$signup = true;
$uTrue = $eTrue = $tTrue =  false;


if ($countU == 0 ){
    $uTrue =  true;
}else{
    $signup = false;
}


if($countE == 0){
    $eTrue =  true;
}else{
    $signup = false;
}


if($countT == 0) {
    $tTrue = true;
}else{
    $signup = false;
}




if ($signup == true) {

    $v_token = bin2hex(random_bytes(16));
    date_default_timezone_set('Europe/Athens');
    $expiry = date("Y-m-d H:i:s", strtotime('+24 hours'));
    try {


        $conn->begin_transaction();
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $prpIns = $conn->prepare( "INSERT INTO user (firstName,lastName,username,password,email,tel,v_token,expiry_time,is_active) 
        VALUES (?,?,?,?,?,?,?,?,FALSE)");

        $prpIns->bind_param("ssssssss",$fname,$lname,$username, $hashedPassword, $email, $tel,$v_token,$expiry);

        if ($prpIns->execute()) {

            if ($prpIns->affected_rows > 0) {
                $mail = new PHPMailer(true);
                $mail->isSMTP();
                $mail->Host       = getenv('EMAIL_HOST');
                $mail->SMTPAuth   = true;
                $mail->Username   = getenv('EMAIL_USER');
                $mail->Password   = getenv('EMAIL_PASS');
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port       = 587;

                $mail->setFrom(getenv('EMAIL_USER'), 'Ourlab');
                $mail->addAddress($email);

                $url = "http://localhost:8080/phpPages/verify_email.php?token=$v_token";

                $mail->isHTML(true);
                $mail->Subject = 'Verify account';
                $mail->Body    = "<h3>Welcome!</h3>
                                <p>Click here to verify your account:</p>
                                <a href='$url'>link</a>
                                <p>Link works for 24hr only.</p>";

                $mail->send();
                $conn->commit();
                echo '<script type="text/javascript">';
                echo 'alert("Sign up completed. Verify your account on your email.");';
                echo '/html/login.html';//use back, so user's input is not lost
                echo '</script>';
                exit();

            }else{
                echo '<script type="text/javascript">';
                echo 'alert("An error occured.");';
                echo 'window.history.back()';
                echo '</script>';
                exit();

            }
        } else{
            echo '<script type="text/javascript">';
            echo 'alert("An error occured.");';
            echo 'window.history.back()';
            echo '</script>';
            exit();

        }
    } catch (Exception $e) {
        $conn->rollback();
        echo $e->getMessage();
    }

}else{
    if ($uTrue != true){
        echo '<script type="text/javascript">';
        echo 'alert("Someone is using this username.");';
        echo 'window.history.back()';
        echo '</script>';
        exit();
    }

    if($eTrue !=  true){
        echo '<script type="text/javascript">';
        echo 'alert("Someone is using this email.");';
        echo 'window.history.back();';
        echo '</script>';
        exit();

    }


    if($tTrue !=  true){
        echo '<script type="text/javascript">';
        echo 'alert("Someone is using this phone number.");';
        echo 'window.location.href = "window.history.back()";';
        exit();

        echo '</script>';
    }
}



$conn -> close();
?>

