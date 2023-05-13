<?php
declare(strict_types = 1);
require '../src/bootstrap.php';

require_login($logged_in);

$id = $_SESSION['user_id'];

$surveys_amount = $cms->getSurvey()->surveys_exist($id);
$data['surveys_amount'] = $surveys_amount;

if($surveys_amount > 0) {
    $data['surveys'] = $cms->getSurvey()->get_surveys_for_id($id);
}

echo $twig->render('define.html', $data);
?>