<?php
    require_once $_SERVER['DOCUMENT_ROOT'] . '/php/connect_to_DB.php';

    $message = "";
    $status = ""; 

    if (isset($_GET['token'])) {
        $token = $_GET['token'];

        $stmt = $conn->prepare("SELECT user_id FROM user WHERE v_token = ? AND expiry_time > NOW()  AND is_active != TRUE LIMIT 1");
        $stmt->bind_param("s", $token);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        if ($user) {
            $update = $conn->prepare("UPDATE user SET is_active = TRUE, v_token = NULL, expiry_time = NULL WHERE user_id = ?");
            $update->bind_param("i", $user['user_id']);
            
            if ($update->execute()) {
                $status = "success";
                $message = "<strong>Success!</strong> Your account has been activated. You can now log in.";
            } else {
                $status = "error";
                $message = "<strong>Error!</strong> Something went wrong on our server. Please try again later.";
            }
        } else {
            $status = "tryAgain";
            $message = "<strong>Invalid or Expired Link!</strong> The verification link is incorrect or has expired (24h limit).";
        }
    } else {
        header("Location: /html/main.html"); 
        exit();
    }
?>

<!DOCTYPE html>
<html lang="el">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Verification | Ourlab</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; display: flex; align-items: center; height: 100vh; }
        .verify-container { max-width: 500px; margin: auto; padding: 20px; background: white; border-radius: 10px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); text-align: center; }
        .icon { font-size: 50px; margin-bottom: 20px; }
    </style>
</head>
<body>

<div class="container">
    <div class="verify-container">
        <?php if ($status === "success"): ?>
            <div class="icon text-success">✔</div>
            <h2 class="mb-4">Verified!</h2>
            <div class="alert alert-success"><?php echo $message; ?></div>
            <a href="/html/login.html" class="btn btn-primary w-100 mt-3">Go to Login</a>
        <?php elseif($status == "tryAgain"): ?>
            <div class="icon text-danger">✖</div>
            <h2 class="mb-4">Verification Failed</h2>
            <div class="alert alert-danger"><?php echo $message; ?></div>
            <a href="/html/signup.html" class="btn btn-outline-secondary w-100 mt-3">Try Registering Again</a>
        <?php else: ?>
            <div class="icon text-danger">✖</div>
            <h2 class="mb-4">Verification Failed</h2>
            <div class="alert alert-danger"><?php echo $message; ?></div>
            <a href="javascript:location.reload();" class="btn btn-outline-secondary w-100 mt-3">Uknown error</a>
        <?php endif; ?>
        
    </div>
</div>

</body>
</html>