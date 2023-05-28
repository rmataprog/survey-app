<?php
declare(strict_types = 1);
require '../../src/bootstrap.php';

$survey_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
$user_id = $_SESSION['id'] ?? 0;

if($_SERVER['REQUEST_METHOD'] == 'GET') {
    if($survey_id) {
        $survey = $cms->getSurvey()->get_survey($survey_id);
        $took_survey = $cms->getSurvey()->check_survey_taken($survey_id, $user_id);
        if($survey) {
            $took_survey = $cms->getSurvey()->check_survey_taken($survey_id, $user_id);
            if($took_survey == 1) {
                $data = [
                    'type' => 0,
                    'message' => 'It seems that you already took this survey'
                ];
                echo $twig->render('conduct/response.html', $data);
            } else {
                if($survey['start_date']) {
                    $start_date = new DateTime($survey['start_date']);
                    $end_date = new DateTime($survey['end_date']);
                    $now = new DateTime();
                    $valid = $now->getTimestamp() > $start_date->getTimestamp() && $now->getTimestamp() < $end_date->getTimestamp() ? true : false;
                    if($valid) {
                        $questions = $cms->getSurvey()->get_questions($survey_id);
                        $answers = $cms->getSurvey()->get_answers($survey_id);
                    
                        $questions_answer = create_question_answer_array($questions, $answers);
                    
                        $data['survey'] = $survey;
                        $data['questions'] = $questions_answer;
                    
                        echo $twig->render('conduct/participate.html', $data);
                    } else {
                        $message = '';
                        $message .= $now->getTimestamp() < $start_date->getTimestamp() ? 'Survey has not started' : '';
                        $message .= $now->getTimestamp() > $end_date->getTimestamp() ? 'Survey has finished' : '';
                        $data = [
                            'type' => 2,
                            'message' => $message
                        ];
                        echo $twig->render('conduct/response.html', $data);
                    }
                } else {
                    $data = [
                        'type' => 2,
                        'message' => 'Survey has not started'
                    ];
                    echo $twig->render('conduct/response.html', $data);
                }
            }
        } else {
            redirect(DOC_ROOT . 'notFound.php');
        }
    } else {
        redirect(DOC_ROOT . 'notFound.php');
    }
}

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $date = new DateTime();
    $survey_taken_id = intval($cms->getSurvey()->take_survey($survey_id, $user_id, $date));
    $answers = $_POST;
    $amount = count($_POST);
    $valid = $cms->getSurvey()->give_answers($survey_taken_id, $answers, $amount);
    $data = [
        'type' => 1,
        'message' => 'Thanks for participating'
    ];
    if($valid) {
        echo $twig->render('conduct/response.html', $data);
    }
}
?>