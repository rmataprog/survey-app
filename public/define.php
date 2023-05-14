<?php
declare(strict_types = 1);
require '../src/bootstrap.php';

if(!$cms->getSession()->logged_in) {
    redirect('login.php');
}

$id = $cms->getSession()->id;

$surveys_amount = $cms->getSurvey()->surveys_exist($id);
$data['surveys_amount'] = $surveys_amount;

if($surveys_amount > 0) {
    $data['surveys'] = $cms->getSurvey()->get_surveys_for_id($id);
}

echo $twig->render('define.html', $data);
?>