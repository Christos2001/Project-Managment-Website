<?php
function validateTurnstile($token, $secret, $remoteip = null) {
    if (empty($token)) {
        return ['success' => false, 'error-codes' => ['missing-input-response']];
    }

    $url = 'https://challenges.cloudflare.com/turnstile/v0/siteverify';
    $data = [
        'secret' => $secret,
        'response' => $token,
        'remoteip' => $remoteip
    ];

    $options = [
        'http' => [
            'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
            'method'  => 'POST',
            'content' => http_build_query($data),
        ]
    ];

    $context  = stream_context_create($options);
    $response = file_get_contents($url, false, $context);

    if ($response === FALSE) {
        return ['success' => false, 'error-codes' => ['network-error']];
    }

    return json_decode($response, true);
}

$secret_key = get_env("CD_SECRET_KEY");
$token = $_POST['cf-turnstile-response'] ?? '';

$remoteip = $_SERVER['HTTP_CF_CONNECTING_IP'] ?? $_SERVER['HTTP_X_FORWARDED_FOR'] ?? $_SERVER['REMOTE_ADDR'];

$validation = validateTurnstile($token, $secret_key, $remoteip);

if (!$validation['success']) {
    echo "<script>alert('Confirm you are human.'); window.history.back();</script>";
    exit;
}
?>