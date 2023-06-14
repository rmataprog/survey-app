<?php
declare(strict_types = 1);
require '../../src/bootstrap.php';

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $expiry_date = get_expiration_date();
    $user_data = $cms->getUser()->getUser($email);
    $token = $cms->getUser()->createToken($user_data['id'], 'forgot password', $expiry_date);
    $link = DOMAIN . DOC_ROOT . 'user/reset_password.php?token=' . $token;
    $body = $cms->getUser()->CreateEmailTemplate(2, $link);
    $email_config = [
        'subject'=>'Survey App: this is your password reset link',
        'body'=>$body,
        'altBody'=>"Please click this link to reset your password: $link"
    ];
    $sent = $cms->getEmail()->send_email($email, $email_config);
    $data['message'] = 'An email was sent to your account to reset your password';
    $data['type'] = 3;
    echo $twig->render('helpers/response.html', $data);
} else {
    echo $twig->render('user/forgot_password.html');
}
?>