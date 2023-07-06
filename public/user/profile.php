<?php
declare(strict_types = 1);
require '../../src/bootstrap.php';

if(!$cms->getSession()->logged_in) {
    redirect(DOC_ROOT . "/user/login.php");
}

$user_id = $cms->getSession()->id;
$coordinator = $cms->getSession()->coordinator;

$data = $cms->getUser()->getUserWId($user_id);
if($data['valid']) {
    echo $twig->render('user/profile.html', $data['data']);
}
?>