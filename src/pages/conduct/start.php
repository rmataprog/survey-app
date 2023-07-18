<?php
declare(strict_types = 1);
require '../../src/bootstrap.php';
if(!$cms->getSession()->logged_in) {
    redirect(DOC_ROOT . "/user/login.php");
}
$user_id = $cms->getSession()->id;
$coordinator = $cms->getSession()->coordinator;
if($coordinator) {
    $data['coordinator'] = $coordinator;
    $survey_id = filter_input(INPUT_GET, 'survey_id', FILTER_VALIDATE_INT);
    $error = filter_input(INPUT_GET, 'error', FILTER_VALIDATE_BOOLEAN);
    $error_message = isset($_GET['error_message'])? $_GET['error_message'] : '';
    if(!$error) {
        if($survey_id) {
            $survey = $cms->getSurvey()->get_survey_for_user($user_id, $survey_id);
            if($survey['valid']) {
                if($survey['data']) {
                    $data = array_merge($data, $survey['data']);
                } else {
                    redirect(DOC_ROOT . 'notFound.php');
                }
            } else {
                $data['error']['message'] = 'There was problem retrieving survey data';
            }
        } else {
            redirect(DOC_ROOT . 'notFound.php');
        }
    } else {
        $data['error']['message'] = filter_var($error_message, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    }
    echo $twig->render('conduct/start.html', $data);
} else {
    redirect(DOC_ROOT . 'conduct/list.php');
}
?>