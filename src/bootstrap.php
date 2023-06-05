<?php
define("APP_ROOT", dirname(__FILE__, 2));
require APP_ROOT . '/config/config.php';
require APP_ROOT . '/vendor/autoload.php';
require APP_ROOT . '/src/functions.php';

$twig_options['cache'] = APP_ROOT . '/var/cache';
$twig_options['debug'] = DEV;

$loader = new Twig\Loader\FilesystemLoader([APP_ROOT . '/templates']);
$twig = new Twig\Environment($loader, $twig_options);
$twig->addGlobal('doc_root', DOC_ROOT);
$twig->addExtension(new Twig\Extra\Intl\IntlExtension());

if(DEV === true) {
    $twig->addExtension(new \Twig\Extension\DebugExtension());
} else {
    /*code to handle errors */
}

$cms = new \Survey\CMS($dsn, $username, $password, $email_config);
$cms->getSession();
unset($dsn, $username, $password);
?>