<?php
session_start();
$logged_in = isset($_SESSION['logged_in']) ? $_SESSION['logged_in'] : false;

function login(){
    session_regenerate_id(true);
    $_SESSION['logged_in'] = true;
};

function logout() {
    $_SESSION = [];

    $params = session_get_cookie_params();

    setcookie('PHPSESSID', '', time() - 3600, $params['path'], $params['domain'], $params['secure'], $params['httponly']);

    session_destroy();
}

function require_login($logged_in) {
    if($logged_in == false) {
        redirect('login.php');
    }
}
?>