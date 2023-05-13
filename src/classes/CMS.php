<?php
namespace Survey;
class CMS {
    protected $db = null;
    protected $user = null;
    protected $survey = null;

    public function __construct($dsn, $username, $password) {
        $this->db = new Database($dsn, $username, $password);
    }

    public function getUser() {
        if ($this->user === null) {
            $this->user = new User($this->db);
        }
        return $this->user;
    }

    public function getSurvey() {
        if ($this->survey === null) {
            $this->survey = new Survey($this->db);
        }
        return $this->survey;
    }
}
?>