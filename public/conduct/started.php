<?php
declare(strict_types = 1);
require '../../src/bootstrap.php';

if(!$cms->getSession()->logged_in) {
    redirect(DOC_ROOT . "/user/login.php");
}

$user_id = intval($_SESSION['id']);
$is_coordinator = $_SESSION['coordinator'];

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $start_now = isset($_POST['start_immediately']) && $_POST['start_immediately'] == 'on' ? true : false;
    $filters['start_date']['filter'] = FILTER_VALIDATE_REGEXP;
    $filters['start_date']['options']['regexp'] = '/^[A-z]{3}\s([0-2][0-9]|[3][0-1]),\s[2][0][2-9][0-9]$/';
    $filters['end_date']['filter'] = FILTER_VALIDATE_REGEXP;
    $filters['end_date']['options']['regexp'] = '/^[A-z]{3}\s([0-2][0-9]|[3][0-1]),\s[2][0][2-9][0-9]$/';
    $filters['start_time']['filter'] = FILTER_VALIDATE_REGEXP;
    $filters['start_time']['options']['regexp'] = '/^([0][0-9]|[1][0-9]|[2][0-3]):([0-5][0-9])$/';
    $filters['end_time']['filter'] = FILTER_VALIDATE_REGEXP;
    $filters['end_time']['options']['regexp'] = '/^([0][0-9]|[1][0-9]|[2][0-3]):([0-5][0-9])$/';
    $filters['survey_id']['filter'] = FILTER_VALIDATE_REGEXP;
    $filters['survey_id']['options']['regexp'] = '/^\d+$/';

    $data = filter_input_array(INPUT_POST, $filters);

    $survey_id = isset($_POST['survey_id']) ? intval($_POST['survey_id']) : false;

    $start_date_time = format_date_time($data['start_date'], $data['start_time'], $start_now);
    $end_date_time = format_date_time($data['end_date'], $data['end_time'], false);

    if($is_coordinator == 1 && $survey_id) {
        $survey = $cms->getSurvey()->get_survey_for_user($user_id, $survey_id);
        if($survey) {
            if($survey['start_date'] == null) {
                $cms->getSurvey()->start_survey($survey_id, $start_date_time, $end_date_time);
                $survey = $cms->getSurvey()->get_survey_for_user($user_id, $survey_id);
                $submissions = $cms->getSurvey()->get_submissions_count($survey_id);
                $survey['submissions'] = $submissions;
                echo $twig->render("conduct/summary.html", $survey);
            } else {
                redirect(DOC_ROOT . 'conduct/list.php');
            }
        } else {
            redirect(DOC_ROOT . 'conduct/list.php');
        }
    } else {
        redirect(DOC_ROOT . 'conduct/list.php');
    }
}

?>