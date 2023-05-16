<?php
namespace Survey;

class Session {

    public $id;
    public $coordinator;
    public $logged_in;

    public function __construct() {
        session_start();
        $this->id = $_SESSION['id'] ?? 0;
        $this->coordinator = $_SESSION['coordinator'] ?? false;
        $this->logged_in = $_SESSION['logged_in'] ?? false;
    }

    public function start($user) {
        session_regenerate_id(true);
        $_SESSION['id'] = $user['id'];
        $_SESSION['coordinator'] = $user['coordinator'];
        $_SESSION['logged_in'] = true;
    }

    public function delete() {
        $_SESSION = [];
        $params = session_get_cookie_params();
        setcookie('PHPSESSID', '', time() - 3600, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
        session_destroy();
    }
}
?>