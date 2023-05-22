<?php
declare(strict_types = 1);
require '../../src/bootstrap.php';

if(!$cms->getSession()->logged_in) {
    redirect(DOC_ROOT . "/user/login.php");
}

$user_id = $_SESSION['id'];
$is_coordinator = $_SESSION['coordinator'];
$show = filter_input(INPUT_GET, 'show', FILTER_VALIDATE_INT) ? intval(filter_input(INPUT_GET, 'show')) : 3;
$offset = filter_input(INPUT_GET, 'offset', FILTER_VALIDATE_INT) ? intval(filter_input(INPUT_GET, 'offset')) : 0;

if($is_coordinator) {
    $surveys_count = $cms->getSurvey()->surveys_exist($user_id);
    $surveys = $cms->getSurvey()->get_surveys_for_id($user_id, $show, $offset);
    $data["surveys"] = $surveys;
    $data["total"] = $surveys_count;
    $data["current"] = floor($offset / 3);
    echo $twig->render("conduct/list.html", $data);
} else {
    redirect(DOC_ROOT . 'view/view.php');
}
?>