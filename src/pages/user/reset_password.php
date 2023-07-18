<?php
declare(strict_types = 1);
require '../../src/bootstrap.php';

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $password = $_POST['password'];
    $confirm = $_POST['confirm'];
    $token = $_POST['token'];
    $regexp = '/^[a-zA-Z0-9!#$%&_^]{8,12}$/';
    if(preg_match($regexp, $password) == 0) {
        $data['error']['password'] = 'Password must have between 8 and 12 characters, containt alpha numberic charaters. !#$%&_^ are allowed';
        echo $twig->render('user/reset_password.html', $data);
    }
    if($password == $confirm) {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $token_data = $cms->getUser()->getTokenData($token);
        if($token_data['valid']) {
            $reset = $cms->getUser()->resetPassword($token_data['data']['user_id'], $hash);
            if($reset) {
                $data['message'] = 'Password was changed.';
                $data['type'] = 6;
                $cms->getSession()->start(['id'=>$token_data['data']['user_id'], 'coordinator'=>$token_data['data']['coordinator']]);
                echo $twig->render('helpers/response.html', $data);
            } else {
                $data['message'] = 'Password could not be changed.';
                $data['type'] = 7;
                echo $twig->render('helpers/response.html', $data);
            }
        } else {
            $data['message'] = 'Password could not be changed.';
            $data['type'] = 7;
            echo $twig->render('helpers/response.html', $data);
        }
    } else {
        $data['error']['confirm'] = 'Password and confirmation password must match, please check';
        echo $twig->render('user/reset_password.html', $data);
    }
} else {
    if(isset($_GET['token'])) {
        $data['token'] = $_GET['token'];
        echo $twig->render('user/reset_password.html', $data);
    } else {
        redirect(DOC_ROOT . "user/login");
    }
}
?>