<?php
declare(strict_types = 1);
require '../src/bootstrap.php';

if(!$cms->getSession()->logged_in) {
    redirect('login.php');
}

echo $twig->render('view.html');
?>