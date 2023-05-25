<?php
declare(strict_types = 1);
require '../../src/bootstrap.php';

$survey_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if($_SERVER['REQUEST_METHOD'] == 'GET') {
    if($survey_id) {
        $survey = $cms->getSurvey()->get_survey($survey_id);
        if($survey) {
            $start_date = new DateTime($survey['start_date']);
            $end_date = new DateTime($survey['end_date']);
            $now = new DateTime();
            if($now->getTimestamp() > $start_date->getTimestamp()) {
                $questions = $cms->getSurvey()->get_questions($survey_id);
                $answers = $cms->getSurvey()->get_answers($survey_id);
            
                $questions_answer = create_question_answer_array($questions, $answers);
            
                $data['survey'] = $survey;
                $data['questions'] = $questions_answer;
            
                echo $twig->render('conduct/participate.html', $data);
            }
        } else {
            redirect(DOC_ROOT . 'notFound.php');
        }
    } else {
        redirect(DOC_ROOT . 'notFound.php');
    }
}

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_SESSION['id'] ?? 0;
    $date = new DateTime();
    $survey_taken_id = intval($cms->getSurvey()->take_survey($survey_id, $user_id, $date));
    $answers = $_POST;
    $amount = count($_POST);
    $valid = $cms->getSurvey()->give_answers($survey_taken_id, $answers, $amount);
    if($valid) {

    }
}
?>