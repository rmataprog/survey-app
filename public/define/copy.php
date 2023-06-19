<?php
declare(strict_types = 1);
require '../../src/bootstrap.php';

if(!$cms->getSession()->logged_in) {
    redirect(DOC_ROOT . "/user/login.php");
}

$id = $cms->getSession()->id;

$survey_id = filter_input(INPUT_GET, 'survey_id', FILTER_VALIDATE_INT);

if($_SERVER['REQUEST_METHOD'] == 'GET') {
    if($survey_id) {
        $survey = $cms->getSurvey()->get_survey($survey_id);
        if($survey['valid']) {
            $questions = $cms->getSurvey()->get_questions($survey_id);
            $answers = $cms->getSurvey()->get_answers($survey_id);
            $questions_answer = create_question_answer_array($questions, $answers);
            $survey['data']['title'] = 'COPY - ' . $survey['data']['title'];
            $data['survey'] = $survey['data'];
            $data['questions'] = $questions_answer;
            $data['coordinator'] = $_SESSION['coordinator'];
            echo $twig->render('define/copy.html', $data);
        } else {
            redirect(DOC_ROOT . 'notFound.php');
        }
    } else {
        redirect(DOC_ROOT . 'notFound.php');
    }
}
?>