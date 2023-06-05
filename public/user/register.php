<?php
declare(strict_types = 1);
require '../../src/bootstrap.php';

if($cms->getSession()->logged_in) {
    redirect(DOC_ROOT . 'view/view.php');
}

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $valid = true;
    $form = filter_input_array(INPUT_POST);
    $filters['email']['filter'] = FILTER_VALIDATE_EMAIL;
    $filters['email']['flags'] = FILTER_FLAG_EMAIL_UNICODE;
    $filters['first_name']['options']['regexp'] = '/^[a-zA-Z]{0,50}$/';
    $filters['first_name']['filter'] = FILTER_VALIDATE_REGEXP;
    $filters['last_name']['options']['regexp'] = '/^[a-zA-Z]{0,50}$/';
    $filters['last_name']['filter'] = FILTER_VALIDATE_REGEXP;
    $filters['password']['filter'] = FILTER_VALIDATE_REGEXP;
    $filters['password']['options']['regexp'] = '/^[a-zA-Z0-9!#$%&_^]{8,12}$/';
    $data = filter_var_array($form, $filters);
    foreach($data as $field => $value) {
        if($value == false && $field != 'first_name' && $field != 'last_name') {
            $valid = false;
        }
    }
    $data['coordinator'] = isset($form['coordinator']) && $form['coordinator'] == 'on' ? 1 : 0;
    $valid = strlen($form['first_name']) > 0 && !$data['first_name'] ? false : $valid;
    $valid = strlen($form['last_name']) > 0 && !$data['last_name'] ? false : $valid;
    $data['error'] = [];
    $data['error']['email'] = !$data['email'] ? 'Please provide a well formatted email' : '';
    $data['error']['first_name'] = strlen($form['first_name']) > 0 && !$data['first_name'] ? 'First name must be 50 chars long or less, and should only have letters' : '';
    $data['error']['last_name'] = strlen($form['last_name']) > 0 && !$data['last_name'] ? 'Last name must be 50 chars long or less, and should only have letters' : '';
    $data['error']['password'] = !$data['password'] ? 'Password must have between 8 and 12 characters, containt alpha numberic charaters. !#$%&_^ are allowed' : '';
    $data['error']['confirm'] = $data['password'] !== $form['confirm'] ? 'Password and confirmation password must match, please check' : '';
    if($valid) {
        $amount = $cms->getUser()->verifyEmailExistense($data['email']);
        if($amount > 0) {
            $data['error']['header'] = 'email already exists';
            echo $twig->render('user/register.html', $data);
        } else {
            $hash = password_hash($data['password'], PASSWORD_DEFAULT);
            $id = $cms->getUser()->register(!$data['first_name'] ? null : $data['first_name'], !$data['last_name'] ? null : $data['last_name'], $data['email'], $data['coordinator'], $hash);
            $cms->getSession()->start(['id'=>$id, 'coordinator'=>$data['coordinator']]);
            $expiry = new DateTime();
            $interval = new DateInterval('PT30M');
            $expiry->add($interval);
            $expiry_date = $expiry->format('Y-m-d H:i:s');
            $token = $cms->getUser()->createToken($id, 'confirm email', $expiry_date);
            $link = DOMAIN . DOC_ROOT . 'user/confirm.php?token=' . $token;
            $body = "<p>Please click this <a href='$link' target='_blank'>link</a> to confirm your account.</p>";
            $message = [
                'subject'=> 'Survey App: please confirm your email',
                'body'=> $body,
                'altBody'=> "Please click this link to confirm your account: $link"
            ];
            $sent = $cms->getEmail()->send_email($data['email'], $message);
            $data['message'] = 'Your account was created. An email was sent to confirm your email address for confirmation.';
            $data['type'] = 3;
            echo $twig->render('helpers/response.html', $data);
        }
    } else {
        echo $twig->render('user/register.html', $data);
    }
} else {
    echo $twig->render('user/register.html');
}
?>