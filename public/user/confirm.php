<?php
declare(strict_types = 1);
require '../../src/bootstrap.php';

$token = filter_input(INPUT_GET, 'token', FILTER_DEFAULT);

if($token) {
    $data = $cms->getUser()->getTokenData($token);
    $expiration_date = new DateTime($data['expiry']);
    $now = new DateTime();
    if($now->getTimestamp() < $expiration_date->getTimestamp()) {
        $confirmed = $cms->getUser()->confirmUser($data['user_id']);
        $data['message'] = 'Thanks for your subscription. Your email was confirmed.';
        $data['type'] = 4;
        echo $twig->render('helpers/response.html', $data);
    } else {
        $data['message'] = 'This link has expired, you need to request another confirmation link.';
        $data['type'] = 5;
        echo $twig->render('helpers/response.html', $data);
    }
}
?>