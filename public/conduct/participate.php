<?php
declare(strict_types = 1);
require '../../src/bootstrap.php';

$survey_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT) ? intval($_GET['id']) : false;
$user_id = $_SESSION['id'] ?? 0;

if($_SERVER['REQUEST_METHOD'] == 'GET') {
    if($survey_id) {
        $survey = $cms->getSurvey()->get_survey($survey_id);
        if($survey['valid'] && $survey['data']) {
            $took_survey = $cms->getSurvey()->check_survey_taken($survey_id, $user_id);
            if($took_survey > 0) {
                $data = [
                    'type' => 0,
                    'message' => 'It seems that you already took this survey'
                ];
                echo $twig->render('helpers/response.html', $data);
            } else {
                if($survey['data']['start_date']) {
                    $start_date = new DateTime($survey['data']['start_date']);
                    $end_date = new DateTime($survey['data']['end_date']);
                    $now = new DateTime();
                    $valid = $now->getTimestamp() > $start_date->getTimestamp() && $now->getTimestamp() < $end_date->getTimestamp() ? true : false;
                    if($valid) {
                        $questions = $cms->getSurvey()->get_questions($survey_id);
                        $answers = $cms->getSurvey()->get_answers($survey_id);
                    
                        $questions_answer = create_question_answer_array($questions, $answers);
                    
                        $data['survey'] = $survey['data'];
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
                        echo $twig->render('helpers/response.html', $data);
                    }
                } else {
                    $data = [
                        'type' => 2,
                        'message' => 'Survey has not started'
                    ];
                    echo $twig->render('helpers/response.html', $data);
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
    if($survey_id) {
        $date = new DateTime();
        $survey_taken_id = intval($cms->getSurvey()->take_survey($survey_id, $user_id, $date));
        $answers = $_POST;
        $amount = count($_POST);
        $response = $cms->getSurvey()->give_answers($survey_taken_id, $answers, $amount);
        $data = [
            'message' => $response['message']
        ];
        $data['type'] = $response['valid'] ? 1 : 8 ;
        echo $twig->render('helpers/response.html', $data);
    } else {
        redirect(DOC_ROOT . 'notFound.php');
    }
}
?>