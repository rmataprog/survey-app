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
            $id = $cms->getUser()->register(!$data['first_name'] ? null : $data['first_name'], !$data['last_name'] ? null : $data['last_name'], $data['email'], true, $hash);
            $cms->getSession()->start(['id'=>$id, 'coordinator'=>true]);
            redirect(DOC_ROOT . 'view/view.php');
        }
    } else {
        echo $twig->render('user/register.html', $data);
    }
} else {
    echo $twig->render('user/register.html');
}
?>