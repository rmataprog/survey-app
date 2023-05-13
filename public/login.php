<?php
declare(strict_types = 1);
require '../src/bootstrap.php';

$data['error']['email']['look'] = 'hide';
$data['error']['email']['message'] = '';

if($logged_in) {
    redirect('view.php');
}

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $exits = $cms->getUser()->verifyEmailExistense($email);
    if($exits > 0) {
        $data = $cms->getUser()->getUser($email);
        $hash = $data['password_'];
        $confirm = password_verify($password, $hash);
        if(!$confirm) {
            $data['error']['password']['validity'] = 'invalid';
            $data['error']['password']['look'] = '';
            $data['error']['password']['message'] = 'password is incorrect';
            echo $twig->render('login.html', $data);
        } else {
            login();
            $_SESSION['user_id'] = $data['id'];
            redirect('view.php');
        }
    } else {
        $data['error']['email']['validity'] = 'invalid';
        $data['error']['email']['look'] = '';
        $data['error']['email']['message'] = 'email '. $email . ' ' . 'does not exist';
        echo $twig->render('login.html', $data);
    }
} else {
    $data['error']['email']['look'] = 'hide';
    $data['error']['email']['message'] = '';
    echo $twig->render('login.html', $data);
}
?>