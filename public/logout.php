<?php
declare(strict_types = 1);
require '../src/bootstrap.php';

logout();
redirect('login.php');
?>