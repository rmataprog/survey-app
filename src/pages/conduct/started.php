<?php
declare(strict_types = 1);
// require '../../src/bootstrap.php';

if(!$cms->getSession()->logged_in) {
    redirect(DOC_ROOT . "user/login");
}

$user_id = $cms->getSession()->id;
$coordinator = $cms->getSession()->coordinator;

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $start_now = isset($_POST['start_immediately']) && $_POST['start_immediately'] == 'on' ? true : false;
    $filters['start_date']['filter'] = FILTER_VALIDATE_REGEXP;
    $filters['start_date']['options']['regexp'] = '/^[A-z]{3}\s([0-2][0-9]|[3][0-1]),\s[2][0][2-9][0-9]$/';
    $filters['end_date']['filter'] = FILTER_VALIDATE_REGEXP;
    $filters['end_date']['options']['regexp'] = '/^[A-z]{3}\s([0-2][0-9]|[3][0-1]),\s[2][0][2-9][0-9]$/';
    $filters['start_time']['filter'] = FILTER_VALIDATE_REGEXP;
    $filters['start_time']['options']['regexp'] = '/^([0][0-9]|[1][0-9]|[2][0-3]):([0-5][0-9])$/';
    $filters['end_time']['filter'] = FILTER_VALIDATE_REGEXP;
    $filters['end_time']['options']['regexp'] = '/^([0][0-9]|[1][0-9]|[2][0-3]):([0-5][0-9])$/';
    $filters['survey_id']['filter'] = FILTER_VALIDATE_REGEXP;
    $filters['survey_id']['options']['regexp'] = '/^\d+$/';

    $data = filter_input_array(INPUT_POST, $filters);

    $survey_id = filter_input(INPUT_POST, 'survey_id', FILTER_VALIDATE_INT);

    $start_date_time = format_date_time($data['start_date'], $data['start_time'], $start_now);
    $end_date_time = format_date_time($data['end_date'], $data['end_time'], false);

    if($coordinator) {
        if($survey_id) {
            $survey = $cms->getSurvey()->get_survey_for_user($user_id, $survey_id);
            if($survey['valid']) {
                if($survey['data']) {
                    $title = $survey['data']['title'];
                    if($survey['data']['start_date'] == null) {
                        $valid = $cms->getSurvey()->start_survey($survey_id, $start_date_time, $end_date_time);
                        if($valid['valid']) {
                            $survey = $cms->getSurvey()->get_survey_for_user($user_id, $survey_id);
                            $submissions = $cms->getSurvey()->get_submissions_count($survey_id);
                            if($survey['valid'] && $submissions['valid']) {
                                $data = $survey['data'];
                                $data['submissions'] = $submissions['data'];
                                $data['coordinator'] = $coordinator;
                                echo $twig->render("conduct/summary.html", $data);
                            } else {
                                $message = "We manage to start the survey: \"$title\", but had trouble retrieving its information";
                                $load = ['survey_id'=>$survey_id, 'error'=>true, 'error_message'=>$message];
                                redirect(DOC_ROOT . 'conduct/list.php', $load);
                            }
                        } else {
                            $load = ['error'=>true, 'error_message'=>$valid['message']];
                            redirect(DOC_ROOT . 'conduct/start.php', $load);
                        }
                    } else {
                        $message = "Survey \"$title\" already started";
                        $load = ['survey_id'=>$survey_id, 'error'=>true, 'error_message'=>$message];
                        redirect(DOC_ROOT . 'conduct/list.php', $load);
                    }
                } else {
                    redirect(DOC_ROOT . 'notFound.php');
                }
            } else {
                $load = ['survey_id'=>$survey_id, 'error'=>true, 'error_message'=>'There was a problem starting the survey'];
                redirect(DOC_ROOT . 'conduct/start.php', $load);
            }
        } else {
            redirect(DOC_ROOT . 'notFound.php');
        }
    } else {
        redirect(DOC_ROOT . 'conduct/list.php');
    }
}

?>