<?php
define('DEV', true);
define('DOC_ROOT', '/survey-app/public/');
define('ROOT_FOLDER', 'public');
define('DOMAIN', 'http://localhost');

$type = 'mysql';
$server = 'localhost';
$db = 'survey';
$port = '3306';
$charset = 'utf8mb4';
$username = 'root';
$password = '18657395';
$dsn = "$type:host=$server;dbname=$db;port=$port;charset=$charset";
$email_config = [
    'server'=>'smtp.gmail.com',
    'port'=>465,
    'username'=>'rmata.ufs@gmail.com',
    'password'=>'etgxmwmjtppflmwr',
    'security'=>'tls',
    'admin_email'=>'postmaster@localhost.com',
    'debug'=>(DEV) ? 2 : 0
];
?>