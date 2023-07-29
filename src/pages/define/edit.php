<?php
declare(strict_types = 1);
// require '../../src/bootstrap.php';

if(!$cms->getSession()->logged_in) {
    redirect(DOC_ROOT . "user/login");
}

$id = $cms->getSession()->id;
$coordinator = $cms->getSession()->coordinator;

if($coordinator) {
    if($_SERVER['REQUEST_METHOD'] == 'GET') {
        if(isset($variable_1)) {
            $survey_id = filter_var($variable_1, FILTER_VALIDATE_INT);
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
                            redirect(DOC_ROOT . '/define/define/error/' . rawurlencode($message));
                        }
                    } else {
                        redirect(DOC_ROOT . 'notFound.php');
                    }
                } else {
                    $message = "There was a problem retrieving data from survey";
                    redirect(DOC_ROOT . '/define/define/error/' . rawurlencode($message));
                }
            } else {
                redirect(DOC_ROOT . 'notFound.php');
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