<?php
declare(strict_types = 1);
include '../src/bootstrap.php';

$data = $cms->getUser()->getUser('rmata.ufs@gmail.com');

$valid = password_verify('18657395', $data['password_']);

echo $valid ? 'verified' : 'not verified';
/*
$email = 'rmata.ufs@gmail.com';
$first_name = 'Ruben';
$last_name = 'Mata';
$coordinator = 1;
$password = '18657395';
$hash = password_hash($password, PASSWORD_DEFAULT);
$cms->getUser()->register($first_name, $last_name, $email, $coordinator, $hash);*/

?>