<?php
declare(strict_types = 1);
require '../../src/bootstrap.php';
if(!$cms->getSession()->logged_in) {
    redirect(DOC_ROOT . "/user/login.php");
}
$user_id = $_SESSION['id'];
$is_coordinator = $_SESSION['coordinator'];
$survey_id = filter_input(INPUT_GET, 'survey_id', FILTER_VALIDATE_INT);
if($is_coordinator && $survey_id) {
    $survey = $cms->getSurvey()->get_survey_for_user($user_id, $survey_id);
    if($survey) {
        echo $twig->render('conduct/start.html', $survey);
    } else {
        redirect(DOC_ROOT . 'conduct/list.php');
    }
} else {
    echo 'llego aqui';
    //redirect(DOC_ROOT . 'conduct/list.php');
}
?>