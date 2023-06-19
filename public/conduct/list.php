<?php
declare(strict_types = 1);
require '../../src/bootstrap.php';

if(!$cms->getSession()->logged_in) {
    redirect(DOC_ROOT . "/user/login.php");
}

$user_id = $_SESSION['id'];
$is_coordinator = $_SESSION['coordinator'];
$offset = filter_input(INPUT_GET, 'offset', FILTER_VALIDATE_INT) ? intval($_GET['offset']) : 0;

if($is_coordinator) {
    $surveys_count = $cms->getSurvey()->surveys_exist($user_id);
    $surveys = $cms->getSurvey()->get_surveys_for_id($user_id, $offset);
    $data["path"] = 'conduct/list.php';
    $data['coordinator'] = $is_coordinator;
    if($surveys_count['valid']) {
        if($surveys_count['data'] > 0) {
            if($surveys['valid']) {
                $data["surveys"] = $surveys['data'];
                $data["total"] = $surveys_count['data'];
                $data["current"] = floor($offset / $surveys['show']);
            } else {
                $data['error']['message'] = 'There was a problem retrieving the surveys';
            }
        } else {
            $data['error']['message'] = "You haven't created any surveys yet";
        }
    } else {
        $data['error']['message'] = 'There was a problem retrieving the surveys';
    }
    echo $twig->render("conduct/list.html", $data);
} else {
    $now = new DateTime();
    $surveys_count = $cms->getSurvey()->get_active_surveys_count($now->format('Y-m-d H:i:s'));
    $data["path"] = 'conduct/list.php';
    $data['coordinator'] = $_SESSION['coordinator'];
    if($surveys_count > 0) {
        $surveys = $cms->getSurvey()->get_latest_surveys_to_respond($show, $offset, $now->format('Y-m-d H:i:s'));
        $data["surveys"] = $surveys;
        $data["total"] = $surveys_count;
        $data["current"] = floor($offset / 3);
    } else {
        $data['error']['message'] = 'You may only see surveys that have started';
    }
    echo $twig->render("conduct/list.html", $data);
}
?>