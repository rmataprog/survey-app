<?php
declare(strict_types = 1);
require '../../src/bootstrap.php';

if(!$cms->getSession()->logged_in) {
    redirect(DOC_ROOT . "/user/login");
}

$id = $cms->getSession()->id;
$coordinator = $cms->getSession()->coordinator;
$survey_id = filter_input(INPUT_GET, 'survey_id', FILTER_VALIDATE_INT);

if($coordinator) {
    if($_SERVER['REQUEST_METHOD'] == 'GET') {
        if($survey_id) {
            $survey = $cms->getSurvey()->get_survey($survey_id);
            if($survey['valid']) {
                if($survey['data']){
                    $questions = $cms->getSurvey()->get_questions($survey_id);
                    $answers = $cms->getSurvey()->get_answers($survey_id);
                    if($questions['valid'] && $answers['valid']) {
                        $questions_answer = create_question_answer_array($questions['data'], $answers['data']);
                        $survey['data']['title'] = 'COPY - ' . $survey['data']['title'];
                        $data['survey'] = $survey['data'];
                        $data['questions'] = $questions_answer;
                        $data['coordinator'] = $_SESSION['coordinator'];
                        echo $twig->render('define/copy.html', $data);
                    } else {
                        $load = ['error'=>true, 'error_message'=>'There was a problem retrieving data from survey'];
                        redirect(DOC_ROOT . '/define/define', $load);
                    }
                } else {
                    redirect(DOC_ROOT . 'notFound');
                }
            } else {
                $message = "There was a problem retrieving data from survey";
                $load = ['error'=>true, 'error_message'=>$message];
                redirect(DOC_ROOT . '/define/define', $load);
            }
        } else {
            redirect(DOC_ROOT . 'notFound');
        }
    } else {
        redirect(DOC_ROOT . '/view/view');
    }
} else {
    redirect(DOC_ROOT . '/view/view');
}
?>