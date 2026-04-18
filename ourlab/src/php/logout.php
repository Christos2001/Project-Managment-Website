<?php
    require_once $_SERVER['DOCUMENT_ROOT'] . '/php/secure_cookies.php';
    require_once $_SERVER['DOCUMENT_ROOT'] . '/php/connect_to_DB.php';
    require_once $_SERVER['DOCUMENT_ROOT'] . '/php/check_auth.php';

    $_SESSION = array();


if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

session_destroy();

header("Location: /html/main.html");
exit();
?>