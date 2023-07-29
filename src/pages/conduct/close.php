<?php
declare(strict_types = 1);
if(!$cms->getSession()->logged_in) {
    redirect(DOC_ROOT . "user/login");
}

$user_id = $cms->getSession()->id;
$coordinator = $cms->getSession()->coordinator;

if($coordinator) {
    $survey_id = filter_var($variable_1, FILTER_VALIDATE_INT);
    if($survey_id) {
        $end_date = get_close_date();
        $response = $cms->getSurvey()->close_survey($survey_id, $end_date);
        if ($response['valid']) {
            $message = "Survey with id: $survey_id has succesfully closed";
            redirect(DOC_ROOT . 'conduct/list/success/' . rawurlencode($message));
        } else {
            $message = "There was a problem closing the survey with id: $survey_id, try again later";
            redirect(DOC_ROOT . 'conduct/list/error/' . rawurlencode($message));
        }
    }
} else {
    $message = "You don't have permission for closing surveys";
    redirect(DOC_ROOT . 'conduct/list/error/' . rawurlencode($message));
}
?>