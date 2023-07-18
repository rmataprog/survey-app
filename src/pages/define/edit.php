<?php
declare(strict_types = 1);
require '../../src/bootstrap.php';

if(!$cms->getSession()->logged_in) {
    redirect(DOC_ROOT . "/user/login.php");
}

$id = $cms->getSession()->id;
$coordinator = $cms->getSession()->coordinator;
$survey_id = filter_input(INPUT_GET, 'survey_id', FILTER_VALIDATE_INT) ? intval($_GET['survey_id']) : false;

if($coordinator) {
    if($_SERVER['REQUEST_METHOD'] == 'GET') {
        if($survey_id) {
            $survey = $cms->getSurvey()->get_survey($survey_id);
            if($survey['valid']) {
                if($survey['data']) {
                    $questions = $cms->getSurvey()->get_questions($survey_id);
                    $answers = $cms->getSurvey()->get_answers($survey_id);
                    if($questions['valid'] && $answers['valid']) {
                        $questions_answer = create_question_answer_array($questions['data'], $answers['data']);
                        $data['survey'] = $survey['data'];
                        $data['questions'] = $questions_answer;
                        $data['coordinator'] = $_SESSION['coordinator'];
                        echo $twig->render('define/edit.html', $data);
                    } else {
                        $message = "There was a problem retrieving data from survey to edit";
                        $load = ['error'=>true, 'error_message'=>$message];
                        redirect(DOC_ROOT . '/define/define.php', $load);
                    }
                } else {
                    redirect(DOC_ROOT . 'notFound.php');
                }
            } else {
                $message = "There was a problem retrieving data from survey";
                $load = ['error'=>true, 'error_message'=>$message];
                redirect(DOC_ROOT . '/define/define.php', $load);
            }
        } else {
            redirect(DOC_ROOT . 'notFound.php');
        }
    } else {
        redirect(DOC_ROOT . '/view/view.php');
    }
} else {
    redirect(DOC_ROOT . '/view/view.php');
}
?>