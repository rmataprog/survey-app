<?php
declare(strict_types = 1);
require '../../src/bootstrap.php';

$data['error']['email']['look'] = 'hide';
$data['error']['email']['message'] = '';

if($cms->getSession()->logged_in) {
    redirect(DOC_ROOT . 'view/view');
}

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $existence = $cms->getUser()->verifyEmailExistense($email);
    if($existence['valid']) {
        $exits = $existence['data'];
        if($exits > 0) {
            $data = $cms->getUser()->getUserWEmail($email);
            if($data['valid']) {
                $hash = $data['data']['password_'];
                $confirm = password_verify($password, $hash);
                if(!$confirm) {
                    $data['error']['password']['validity'] = 'invalid';
                    $data['error']['password']['look'] = '';
                    $data['error']['password']['message'] = 'password is incorrect';
                    echo $twig->render('user/login.html', $data);
                } else {
                    $cms->getSession()->start($data['data']);
                    redirect(DOC_ROOT . 'view/view');
                }
            } else {
                $data['error']['email']['message'] = 'there was a problem login';
                echo $twig->render('user/login.html', $data);
            }
        } else {
            $data['error']['email']['validity'] = 'invalid';
            $data['error']['email']['look'] = '';
            $data['error']['email']['message'] = 'email '. $email . ' ' . 'does not exist';
            echo $twig->render('user/login.html', $data);
        }
    } else {
        $data['error']['email']['message'] = 'there was a problem login';
        echo $twig->render('user/login.html', $data);
    }
} else {
    $data['error']['email']['look'] = 'hide';
    $data['error']['email']['message'] = '';
    echo $twig->render('user/login.html', $data);
}
?>