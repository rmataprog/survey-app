<?php
function redirect(string $location, array $parameters = [], $response_code = 302) {
    $qs = $parameters ? '?' . http_build_query($parameters) : '';
    $location = $location . $qs;
    header('Location: ' . $location, $response_code);
    exit;
}
?>