<?php
declare(strict_types = 1);
require '../../src/bootstrap.php';
if(!$cms->getSession()->logged_in) {
    redirect(DOC_ROOT . "/user/login.php");
}
$id = $cms->getSession()->id;
$surveys_amount = $cms->getSurvey()->surveys_exist($id);
if($surveys_amount['valid']) {
    $data['surveys_amount'] = $surveys_amount['data'];
    if($data['surveys_amount'] > 0) {
        $surveys = $cms->getSurvey()->get_surveys_for_id($id, null);
        if($surveys['valid']) {
            $data['surveys'] = $surveys['data'];
        } else {
            $data['error']['message'] = $surveys['message'];
        }
    }
}
$data['coordinator'] = $_SESSION['coordinator'];
echo $twig->render('define/define.html', $data);
?>