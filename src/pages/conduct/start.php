<?php
declare(strict_types = 1);
// require '../../src/bootstrap.php';
if(!$cms->getSession()->logged_in) {
    redirect(DOC_ROOT . "user/login");
}
$user_id = $cms->getSession()->id;
$coordinator = $cms->getSession()->coordinator;
if($coordinator) {
    $data['coordinator'] = $coordinator;
    $error = isset($variable_1) && $variable_1 == 'error' ? true : false;
    if(!$error) {
        $survey_id = isset($variable_1) ? filter_var($variable_1, FILTER_VALIDATE_INT) : false;
        if($survey_id) {
            $survey = $cms->getSurvey()->get_survey_for_user($user_id, $survey_id);
            if($survey['valid']) {
                if($survey['data']) {
                    $data = array_merge($data, $survey['data']);
                } else {
                    redirect(DOC_ROOT . 'notFound');
                }
            } else {
                $data['error']['message'] = 'There was problem retrieving survey data';
            }
        } else {
            redirect(DOC_ROOT . 'notFound');
        }
    } else {
        $data['error']['message'] = rawurldecode($variable_2);
    }
    echo $twig->render('conduct/start.html', $data);
} else {
    redirect(DOC_ROOT . 'conduct/list');
}
?>