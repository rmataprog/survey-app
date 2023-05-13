<?php
define('DEV', true);
define('DOC_ROOT', '/survey-app/public/');
define('ROOT_FOLDER', 'public');

$type = 'mysql';
$server = 'localhost';
$db = 'survey';
$port = '3306';
$charset = 'utf8mb4';
$username = 'root';
$password = '18657395';
$dsn = "$type:host=$server;dbname=$db;port=$port;charset=$charset";
?>