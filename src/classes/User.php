<?php
namespace Survey;
class User {
    protected $db = null;

    public function __construct(Database $db) {
        $this->db = $db;
    }

    public function register(string|null $first_name, string|null $last_name, string $email, bool $coordinator, string $password): int {
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
        $this->db->runSQL($sql, $input);
        return $this->db->lastInsertId();
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