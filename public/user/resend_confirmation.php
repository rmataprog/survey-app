<?php
declare(strict_types = 1);
require '../../src/bootstrap.php';

$user_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if($user_id) {
    $expiration_date = get_expiration_date();
    $token = $cms->getUser()->createToken($user_id, 'confirm email', $expiration_date);
    $link = DOMAIN . DOC_ROOT . 'user/confirm.php?token=' . $token['data'];
    $body = $cms->getUser()->CreateEmailTemplate(1, $link);
    if($token['valid'] && $body['valid']) {
        $message = [
            'subject'=> 'Survey App: please confirm your email',
            'body'=> $body['data'],
            'altBody'=> "Please click this link to confirm your account: $link"
        ];
        $sent = $cms->getEmail()->send_email($data['email'], $message);
        if($sent) {
            $data['message'] = 'An email was sent to confirm your email address for confirmation.';
            $data['type'] = 3;
        } else {
            $data['message'] = 'There was a problem sending the confirmation email.';
            $data['type'] = 9;
        }
        echo $twig->render('helpers/response.html', $data);
    } else {
        $data['message'] = 'There was a problem sending the confirmation email.';
        $data['type'] = 9;
        echo $twig->render('helpers/response.html', $data);
    }
} else {
    redirect(DOC_ROOT . 'notFound.php');
}
?>