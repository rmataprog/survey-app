<?php
namespace Survey;
class User {
    protected $db = null;

    public function __construct(Database $db) {
        $this->db = $db;
    }

    public function register(string|null $first_name, string|null $last_name, string $email, int $coordinator, string $password): int {
        $sql = 'INSERT INTO user_(
            first_name,
            last_name,
            email,
            coordinator,
            password_,
            confirmed
            ) VALUES (:first_name, :last_name, :email, :coordinator, :password, 0)';
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

    public function createToken(int $user_id, string $purpose, string $expiry_date) {
        $token = bin2hex(random_bytes(64));
        $sql = "INSERT INTO token (
                token,
                user_id,
                expiry,
                purpose
            ) VALUES (
                :token,
                :user_id,
                :expiry,
                :purpose
            )";
        $input = [
            "token"=>$token,
            "user_id"=>$user_id,
            "expiry"=>$expiry_date,
            "purpose"=>$purpose
        ];
        $this->db->runSQL($sql, $input);
        return $token;
    }

    public function getTokenData(string $token) {
        $sql = "SELECT 
            token.token,
            token.expiry,
            token.purpose,
            token.user_id,
            user_.email,
            user_.coordinator
            FROM token
            INNER JOIN user_ ON user_.id = token.user_id
            WHERE token.token = :token";
        $input = [
            'token'=>$token
        ];
        return $this->db->runSQL($sql, $input)->fetch();
    }

    public function confirmUser(int $id) {
        $sql = "UPDATE user_
                SET confirmed = 1
                WHERE id = :id";
        $input = [
            'id'=>$id
        ];
        $this->db->runSQL($sql, $input);
        return true;
    }

    public function resetPassword(int $id, string $password) {
        $sql = "UPDATE user_
                SET password_ = :password
                WHERE id = :id";
        $input = [
            'id'=>$id,
            'password'=>$password
        ];
        $this->db->runSQL($sql, $input);
        return true;
    }

    public function CreateEmailTemplate(int $type, string $url) {
        $sql = "SELECT *
                FROM email_templates
                WHERE email_templates.type_ = :type";
        $input = [
            'type'=>$type
        ];
        $templates = $this->db->runSQL($sql, $input)->fetchAll();
        $result = $templates[0]['template'] . $url . $templates[1]['template'];
        return $result;
    }
}
?>