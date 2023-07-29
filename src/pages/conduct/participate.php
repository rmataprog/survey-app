<?php
declare(strict_types = 1);
// require '../../src/bootstrap.php';

$user_id = $_SESSION['id'] ?? 0;

if($_SERVER['REQUEST_METHOD'] == 'GET') {
    if(isset($variable_1)) {
        $survey_id = filter_var($variable_1, FILTER_VALIDATE_INT);
        if($survey_id) {
            $survey = $cms->getSurvey()->get_survey($survey_id);
            $took_survey = $cms->getSurvey()->check_survey_taken($survey_id, $user_id);
            if($survey['valid'] && $survey['data'] && $took_survey['valid']) {
                if($took_survey['data'] > 0) {
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
                            if($questions['valid'] && $answers['valid']) {
                                $questions_answer = create_question_answer_array($questions['data'], $answers['data']);
                                $data['survey'] = $survey['data'];
                                $data['questions'] = $questions_answer;
                                echo $twig->render('conduct/participate.html', $data);
                            } else {
                                $data = [
                                    'type' => 8,
                                    'message' => 'There was a problem getting survey\'s information'
                                ];
                                echo $twig->render('helpers/response.html', $data);
                            }
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
                redirect(DOC_ROOT . 'notFound');
            }
        } else {
            redirect(DOC_ROOT . 'notFound');
        }
    } else {
        redirect(DOC_ROOT . 'notFound');
    }
}

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    if($survey_id) {
        $date = new DateTime();
        $survey_taken_id = $cms->getSurvey()->take_survey($survey_id, $user_id, $date);
        if($survey_taken_id['valid']) {
            $survey_taken_id['data'] = filter_var($survey_taken_id['data'], FILTER_VALIDATE_INT);
            $answers = $_POST;
            $amount = count($_POST);
            $response = $cms->getSurvey()->give_answers($survey_taken_id['data'], $answers, $amount);
            $data = [
                'message' => $response['message']
            ];
            $data['type'] = $response['valid'] ? 1 : 8 ;
            echo $twig->render('helpers/response.html', $data);
        } else {
            $data = [
                'message' => 'There was problem submitting answers',
                'type' => 8
            ];
            echo $twig->render('helpers/response.html', $data);
        }
    } else {
        redirect(DOC_ROOT . 'notFound');
    }
}
?>