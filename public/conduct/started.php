<?php
declare(strict_types = 1);
require '../../src/bootstrap.php';

if(!$cms->getSession()->logged_in) {
    redirect(DOC_ROOT . "/user/login.php");
}

$user_id = $_SESSION['id'];
$is_coordinator = $_SESSION['coordinator'];

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $filters['start_time']['filter'] = FILTER_VALIDATE_REGEXP;
    $filters['start_time']['options']['regexp'] = '/^([0][0-9]|[1][0-9]|[2][0-3]):([0-5][0-9])$/';
    $filters['end_time']['filter'] = FILTER_VALIDATE_REGEXP;
    $filters['end_time']['options']['regexp'] = '/^([0][0-9]|[1][0-9]|[2][0-3]):([0-5][0-9])$/';
    $filters['survey_id']['filter'] = FILTER_VALIDATE_REGEXP;
    $filters['survey_id']['options']['regexp'] = '/^\d+$/';
    $data = filter_input_array(INPUT_POST, $filters);
    echo var_dump($_POST);
    echo '<br /><br />';
    echo var_dump($data);
    /*
    $survey_id = $_POST['survey_id'];
    $end_date_timestamp = strtotime($_POST['end_date']);
    $end_date = date('Y-m-d', $end_date_timestamp);
    $end_time = $_POST['end_time'];
    $end_date_time = "$end_date $end_time:00";
    $start_date_time_timestamp = time();
    $start_date_time = date('Y-m-d H:i:s', $start_date_time_timestamp);
    
    if(($is_coordinator == 1 || $is_coordinator == true) && $survey_id) {
        $survey = $cms->getSurvey()->get_survey_for_user($user_id, $survey_id);
        if($survey) {
            if($survey['start_date'] == null) {
                $cms->getSurvey()->start_survey($survey_id, $start_date_time, $end_date_time);
                
            }
        } else {
            redirect(DOC_ROOT . 'conduct/list.php');
        }
    } else {
        redirect(DOC_ROOT . 'conduct/list.php');
    }*/
}

?>