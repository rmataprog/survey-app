<?php
declare(strict_types = 1);
require '../../src/bootstrap.php';

if(!$cms->getSession()->logged_in) {
    redirect(DOC_ROOT . "/user/login.php");
}

$user_id = $cms->getSession()->id;
$coordinator = $cms->getSession()->coordinator;

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $filters['first_name']['options']['regexp'] = '/^[a-zA-Z]{0,50}$/';
    $filters['first_name']['filter'] = FILTER_VALIDATE_REGEXP;
    $filters['last_name']['options']['regexp'] = '/^[a-zA-Z]{0,50}$/';
    $filters['last_name']['filter'] = FILTER_VALIDATE_REGEXP;
    $form = filter_input_array(INPUT_POST, $filters);

    $data['error']['first_name'] = $form['first_name'] == false ? 'First name can only contain letters' : '';
    $data['error']['last_name'] = $form['last_name'] == false ? 'Last name can only contain letters' : '';

    if ($data['error']['first_name'] || $data['error']['last_name']) {
        $user_data = $cms->getUser()->getUserWId($user_id);
        if($user_data['valid']) {
            $data['id'] = $user_data['data']['id'];
            $data['first_name'] = $user_data['data']['first_name'];
            $data['last_name'] = $user_data['data']['last_name'];
            $data['email'] = $user_data['data']['email'];
            $data['confirmed'] = $user_data['data']['confirmed'];
            echo $twig->render('user/profile.html', $data);
        } else {
            redirect(DOC_ROOT . "/user/profile.php");
        }
    } else {
        $success = $cms->getUser()->updateUser($user_id, $form['first_name'], $form['last_name']);
        if($success) {
            redirect(DOC_ROOT . "/user/profile.php");
        } else {
            redirect(DOC_ROOT . "/user/profile.php", ['error'=>'There was a problem updating your data']);
        }
    }
}
?>