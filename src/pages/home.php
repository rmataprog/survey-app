<?php
declare(strict_types = 1);
// require '../src/bootstrap.php';

if($cms->getSession()->logged_in) {
    redirect(DOC_ROOT . 'view/view');
}

echo $twig->render('home.html');
?>