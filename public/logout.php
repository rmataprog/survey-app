<?php
declare(strict_types = 1);
require '../src/bootstrap.php';

$cms->getSession()->delete();

redirect('login.php');
?>