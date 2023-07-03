<?php
declare(strict_types = 1);
require '../../src/bootstrap.php';
if(!$cms->getSession()->logged_in) {
    redirect(DOC_ROOT . "/user/login.php");
}
$user_id = $cms->getSession()->id;
$survey_id = filter_input(INPUT_GET, 'survey_id', FILTER_VALIDATE_INT);

if($survey_id) {
    $survey = $cms->getSurvey()->get_survey($survey_id);
    if($survey['valid']) {
        if($survey['data']) {
            $questions = $cms->getSurvey()->get_questions($survey_id);
            $results = $cms->getSurvey()->get_survey_results($survey_id);
            $results_data = $results['data'];
            if($questions['valid'] && $results['valid']) {
                $data['results'] = array_map(function($q) use ($results_data) {
                    $r['question'] = $q;
                    $r['answers'] = [];
                    foreach($results_data as $v) {
                        if($q['id'] == $v['question_id']) {
                            array_push($r['answers'], $v);
                        };
                    };
                    return $r;
                }, $questions['data']);
                $data['coordinator'] = $cms->getSession()->coordinator;
                echo $twig->render('view/results.html', $data);
            } else {
                redirect(DOC_ROOT . "view/view.php", ['error'=>'There was a problem getting survey results']);
            }
        } else {
            redirect(DOC_ROOT . "view/view.php", ['error'=>'survey could not be found']);
        }
    } else {
        redirect(DOC_ROOT . "view/view.php", ['error'=>'There was a problem retrieving survey participation data']);
    }
} else {
    redirect(DOC_ROOT . "view/view.php", ['error'=>'survey could not be found']);
}
?>