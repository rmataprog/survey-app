<?php
declare(strict_types = 1);
require '../../src/bootstrap.php';
if(!$cms->getSession()->logged_in) {
    redirect(DOC_ROOT . "/user/login.php");
}
$user_id = $_SESSION['id'];
$offset = $_GET['offset'] ?? 0;
$options = [
    'default' => 0
];
$offset = filter_var($offset, FILTER_VALIDATE_INT, $options);
$surveys = $cms->getSurvey()->get_survey_list_submissions($user_id);
$data['surveys'] = array_slice($surveys, $offset, 3);
$data['total'] = count($surveys);
$data['path'] = 'view/view.php';
$data["current"] = floor($offset / 3);
$data['coordinator'] = $_SESSION['coordinator'];
echo $twig->render('view/view.html', $data);
?>