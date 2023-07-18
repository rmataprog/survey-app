<?php
declare(strict_types = 1);
require '../../src/bootstrap.php';

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $expiry_date = get_expiration_date();
    $user_data = $cms->getUser()->getUserWEmail($email);
    $token = $cms->getUser()->createToken($user_data['data']['id'], 'forgot password', $expiry_date);
    if($user_data['valid'] && $token['valid']) {
        $link = DOMAIN . DOC_ROOT . 'user/reset_password/' . $token['data'];
        $body = $cms->getUser()->CreateEmailTemplate(2, $link);
        if($body['valid']) {
            $email_config = [
                'subject'=>'Survey App: this is your password reset link',
                'body'=>$body['data'],
                'altBody'=>"Please click this link to reset your password: $link"
            ];
            $sent = $cms->getEmail()->send_email($email, $email_config);
            if($sent) {
                $data['message'] = 'An email was sent to your account to reset your password.';
                $data['type'] = 3;
            } else {
                $data['message'] = 'There was a problem sending the reset password email.';
                $data['type'] = 9;
            }
            echo $twig->render('helpers/response.html', $data);
        } else {
            $data['message'] = 'There was a problem sending the reset password email.';
            $data['type'] = 9;
            echo $twig->render('helpers/response.html', $data);
        }
    } else {
        $data['message'] = 'There was a problem sending the reset password email.';
        $data['type'] = 9;
        echo $twig->render('helpers/response.html', $data);
    }
} else {
    $data = [];
    if(isset($_GET['email'])) {
        $data['email'] = $_GET['email'];
    }
    echo $twig->render('user/forgot_password.html', $data);
}
?>