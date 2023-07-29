<?php
declare(strict_types = 1);
// require '../../src/bootstrap.php';
if(!$cms->getSession()->logged_in) {
    redirect(DOC_ROOT . "/user/login");
}
$user_id = $cms->getSession()->id;
$coordinator = $cms->getSession()->coordinator;

$error = isset($variable_1) && $variable_1 == 'error' ? true : false;

$options = [
    'default' => 0
];

if(!isset($variable_1)) {
    $offset = 0;
} else {
    $offset = filter_var($variable_1, FILTER_VALIDATE_INT) ? filter_var($variable_1, FILTER_VALIDATE_INT) : 0;
}

if($coordinator == 1) {
    $surveys = $cms->getSurvey()->get_survey_list_submissions($user_id);
} else {
    $surveys = $cms->getSurvey()->get_survey_list_submissions_for_respondant($user_id);
}

$data['coordinator'] = $coordinator;

if($surveys['valid']) {
    $data['surveys'] = array_slice($surveys['data'], $offset, 3);
    $data['total'] = count($surveys['data']);
    $data['path'] = 'view/view';
    $data["current"] = floor($offset / 3);
} else {
    $data['error'] = 'there was a problem getting the surveys';
}

if($error) {
    $data['error'] = isset($variable_2) ? $variable_2 : 'There was an error';
}
echo $twig->render('view/view.html', $data);
?>