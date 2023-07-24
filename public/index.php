<?php
include '../src/bootstrap.php';

$path = mb_strtolower($_SERVER['REQUEST_URI']);

$path_clean = substr($path, strlen(DOC_ROOT));

$parts = explode('/', $path_clean);
/*
echo $path . '<br />';
echo $path_clean . '<br />';
echo var_dump($parts);*/

switch (count($parts)) {
    case 1:
        $php_page = APP_ROOT . '/src/pages/' . $parts[0] . '.php';
        break;
    case 2:
        $php_page = APP_ROOT . '/src/pages/' . $parts[0] . '/' . $parts[1] . '.php';
        break;
    case 3:
        $variable_1 = $parts[2];
        $php_page = APP_ROOT . '/src/pages/' . $parts[0] . '/' . $parts[1] . '.php';
        break;
    case 4:
        $variable_1 = $parts[2];
        $variable_2 = $parts[3];
        $php_page = APP_ROOT . '/src/pages/' . $parts[0] . '/' . $parts[1] . '.php';
        break;
    default:
        break;
}

if (!file_exists($php_page)) {
    $php_page = APP_ROOT . '/src/pages/notFound.php';
}

include $php_page;
?>