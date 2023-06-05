<?php
declare(strict_types = 1);
require '../../src/bootstrap.php';
if(!$cms->getSession()->logged_in) {
    redirect(DOC_ROOT . "/user/login.php");
}
$user_id = $_SESSION['id'];
$survey_id = filter_input(INPUT_GET, 'survey_id', FILTER_VALIDATE_INT);
$questions = $cms->getSurvey()->get_questions($survey_id);
$results = $cms->getSurvey()->get_survey_results($survey_id);

$data['results'] = array_map(function($q) use ($results) {
    $r['question'] = $q;
    $r['answers'] = [];
    foreach($results as $v) {
        if($q['id'] == $v['question_id']) {
            array_push($r['answers'], $v);
        };
    };
    return $r;
}, $questions);
$data['coordinator'] = $_SESSION['coordinator'];

echo $twig->render('view/results.html', $data);
?>