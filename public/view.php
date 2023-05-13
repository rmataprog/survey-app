<?php
declare(strict_types = 1);
require '../src/bootstrap.php';

require_login($logged_in);

echo $twig->render('view.html');
?>