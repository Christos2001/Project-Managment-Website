<?php

    ini_set('session.cookie_httponly', 1); 
    ini_set('session.use_only_cookies', 1); 

    if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') {
        ini_set('session.cookie_secure', 1);
    }

?>