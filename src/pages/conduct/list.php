<?php
declare(strict_types = 1);
// require '../../src/bootstrap.php';

if(!$cms->getSession()->logged_in) {
    redirect(DOC_ROOT . "user/login");
}

$user_id = $cms->getSession()->id;
$coordinator = $cms->getSession()->coordinator;

if($coordinator) {
    $surveys_count = $cms->getSurvey()->surveys_exist($user_id);
    if(isset($variable_1) && $variable_1 == 'error') {
        $data['start_error'] = isset($variable_2) ? rawurldecode($variable_2) : 'Error retrieving list data';
    } else {
        $settings = array(
            'options' => array(
                'min_range' => 1
            )
        );
        $offset = isset($variable_1) && filter_var($variable_1, FILTER_VALIDATE_INT, $settings) ? intval($variable_1) : 1;
        $surveys = $cms->getSurvey()->get_surveys_for_id($user_id, ($offset - 1) * 3);
        $data["path"] = 'conduct/list';
        $data['coordinator'] = $coordinator;
        if($surveys_count['valid']) {
            if($surveys_count['data'] > 0) {
                if($surveys['valid']) {
                    $data["surveys"] = $surveys['data'];
                    $data["total"] = $surveys_count['data'];
                    $data["current"] = floor($offset);
                } else {
                    $data['error']['message'] = 'There was a problem retrieving the surveys';
                }
            } else {
                $data['error']['message'] = "You haven't created any surveys yet";
            }
        } else {
            $data['error']['message'] = 'There was a problem retrieving the surveys';
        }
    }
    if(isset($variable_1) && $variable_1 == 'success') {
        $data['start_error'] = isset($variable_2) ? rawurldecode($variable_2) : '';
    }
    echo $twig->render("conduct/list.html", $data);
} else {
    $now = new DateTime();
    $surveys_count = $cms->getSurvey()->get_active_surveys_count($now->format('Y-m-d H:i:s'));
    $data["path"] = 'conduct/list';
    $data['coordinator'] = $coordinator;
    if($surveys_count['valid']) {
        if($surveys_count['data'] > 0) {
            $settings = array(
                'options' => array(
                    'min_range' => 1
                )
            );
            $offset = isset($variable_1) && filter_var($variable_1, FILTER_VALIDATE_INT, $settings) ? intval($variable_1) : 1;
            $surveys = $cms->getSurvey()->get_latest_surveys_to_respond(($offset - 1) * 3, $now->format('Y-m-d H:i:s'));
            if($surveys['valid']) {
                $data["surveys"] = $surveys['data'];
                $data["total"] = $surveys_count['data'];
                $data["current"] = floor($offset);
            } else {
                $data['error']['message'] = 'There was a problem retrieving the list of surveys';
            }
        } else {
            $data['error']['message'] = 'You may only see surveys that are currently running';
        }
    } else {
        $data['error']['message'] = 'There was a problem retrieving the list of surveys';
    }
    echo $twig->render("conduct/list.html", $data);
}
?>