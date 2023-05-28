<?php
declare(strict_types = 1);
require '../../src/bootstrap.php';
if(!$cms->getSession()->logged_in) {
    redirect(DOC_ROOT . "/user/login.php");
}
$user_id = $_SESSION['id'];
$data['surveys'] = $cms->getSurvey()->get_survey_list_submissions($user_id);
echo $twig->render('view/view.html', $data);
?>