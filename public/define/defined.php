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
        $questions = $cms->getSurvey()->get_questions($survey_id);
        $answers = $cms->getSurvey()->get_answers($survey_id);
        $data = [];
        $data['title'] = $survey['title'];
        $data['survey_id'] = $survey['id'];
        $data['questions'] = create_question_answer_array($questions, $answers);
        $data['started'] = $survey['start_date'] == null ? false : true;
        echo $twig->render('define/defined.html', $data);
    } else {
        redirect(DOC_ROOT . '/define/define.php');
    }
} else {
    redirect(DOC_ROOT . '/define/define.php');
}
?>