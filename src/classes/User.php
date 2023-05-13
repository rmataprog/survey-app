<?php
namespace Survey;
class User {
    protected $db = null;

    public function __construct(Database $db) {
        $this->db = $db;
    }

    public function register(string $first_name, string $last_name, string $email, int $coordinator, string $password) {
        $sql = 'INSERT INTO user_(
            first_name,
            last_name,
            email,
            coordinator,
            password_
            ) VALUES (:first_name, :last_name, :email, :coordinator, :password)';
        
        $input = [
            "first_name" => $first_name,
            "last_name" => $last_name,
            "email" => $email,
            "coordinator" => $coordinator,
            "password" => $password
        ];

        return $this->db->runSQL($sql, $input);
    }

    public function verifyEmailExistense(string $email): int {
        $sql = 'SELECT COUNT(*)
            FROM user_
            WHERE user_.email = :email';

        $input = [
            "email" => $email
        ];

        return $this->db->runSQL($sql, $input)->fetchColumn();
    }

    public function getUser(string $email) {
        $sql = "SELECT id,
            first_name,
            last_name,
            email,
            coordinator,
            password_
            FROM user_
            WHERE user_.email = :email";
        
        $input = [
            'email' => $email
        ];

        return $this->db->runSQL($sql, $input)->fetch();
    }
}
?>