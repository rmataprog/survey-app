<?php
declare(strict_types = 1);
// require '../../src/bootstrap.php';
if(!$cms->getSession()->logged_in) {
    redirect(DOC_ROOT . "/user/login");
}
$user_id = $cms->getSession()->id;
if(isset($variable_1)) {
    $survey_id = filter_var($variable_1, FILTER_VALIDATE_INT);
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
                    $message = 'There was a problem getting survey results';
                    redirect(DOC_ROOT . "view/view/error/" . rawurlencode($message));
                }
            } else {
                $message = 'survey could not be found';
                redirect(DOC_ROOT . "view/view/error/" . rawurlencode($message));
            }
        } else {
            $message = 'There was a problem retrieving survey participation data';
            redirect(DOC_ROOT . "view/view/error/" . rawurlencode($message));
        }
    } else {
        $message = 'survey could not be found';
        redirect(DOC_ROOT . "view/view/error/" . rawurlencode($message));
    }
} else {
    $message = 'survey could not be found';
    redirect(DOC_ROOT . "view/view/error/" . rawurlencode($message));
}
?>