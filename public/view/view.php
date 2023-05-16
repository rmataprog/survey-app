<?php
declare(strict_types = 1);
require '../../src/bootstrap.php';
if(!$cms->getSession()->logged_in) {
    redirect(APP_ROOT . "/user/login.php");
}
echo $twig->render('view/view.html');
?>