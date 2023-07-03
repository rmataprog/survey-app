<?php
declare(strict_types = 1);
require '../../src/bootstrap.php';

$token = filter_input(INPUT_GET, 'token', FILTER_DEFAULT);

if($token) {
    $data = $cms->getUser()->getTokenData($token);
    if($data['valid']) {
        $expiration_date = new DateTime($data['data']['expiry']);
        $now = new DateTime();
        if($now->getTimestamp() < $expiration_date->getTimestamp()) {
            $confirmed = $cms->getUser()->confirmUser($data['data']['user_id']);
            if($confirmed) {
                $data['message'] = 'Thanks for your subscription. Your email was confirmed.';
                $data['type'] = 4;
                $cms->getSession()->start(['id'=>$data['data']['user_id'], 'coordinator'=>$data['data']['coordinator']]);
                echo $twig->render('helpers/response.html', $data);
            } else {
                $data['message'] = 'There was a problem confirming your email. Please try again later.';
                $data['type'] = 5;
                $cms->getSession()->delete();
                echo $twig->render('helpers/response.html', $data);
            }
        } else {
            $data['message'] = 'This link has expired, you need to request another confirmation link.';
            $data['type'] = 5;
            $cms->getSession()->delete();
            echo $twig->render('helpers/response.html', $data);
        }
    } else {
        $data['message'] = 'There was a problem confirming your email. Please try again later.';
        $data['type'] = 5;
        $cms->getSession()->delete();
        echo $twig->render('helpers/response.html', $data);
    }
} else {
    $cms->getSession()->delete();
    redirect(DOC_ROOT . 'notFound.php');
}
?>