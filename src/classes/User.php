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
        try {
            $this->db->runSQL($sql, $input);
            $data = $this->db->lastInsertId();
            return ['valid'=>true, 'data'=>$data];
        } catch (\PDOException $e) {
            return ['valid'=>false];
        } 
    }

    public function verifyEmailExistense(string $email): array {
        $sql = 'SELECT COUNT(*)
            FROM user_
            WHERE user_.email = :email';
        $input = [
            "email" => $email
        ];
        try {
            $data = $this->db->runSQL($sql, $input)->fetchColumn();
            return ['valid'=>true, 'data'=>$data];
        } catch (\PDOException $e) {
            return ['valid'=>false];
        }
    }

    public function getUserWEmail(string $email) {
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
        try {
            $data = $this->db->runSQL($sql, $input)->fetch();
            return ['valid'=>true, 'data'=>$data];
        } catch (\PDOException $e) {
            return ['valid'=>false];
        }
    }

    public function getUserWId(int $id) {
        $sql = "SELECT id,
            first_name,
            last_name,
            email,
            coordinator,
            password_,
            confirmed
            FROM user_
            WHERE user_.id = :id";
        
        $input = [
            'id' => $id
        ];
        try {
            $data = $this->db->runSQL($sql, $input)->fetch();
            return ['valid'=>true, 'data'=>$data];
        } catch (\PDOException $e) {
            return ['valid'=>false];
        }
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
        try {
            $this->db->runSQL($sql, $input);
            return ['valid'=>true, 'data'=>$token];
        } catch (\PDOException $e) {
            return ['valid'=>false];
        }
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
        try {
            $data = $this->db->runSQL($sql, $input)->fetch();
            return ['valid'=>true,'data'=>$data];
        } catch (\PDOException $e) {
            return ['valid'=>false];
        }
    }

    public function confirmUser(int $id) {
        $sql = "UPDATE user_
                SET confirmed = 1
                WHERE id = :id";
        $input = [
            'id'=>$id
        ];
        try {
            $this->db->runSQL($sql, $input);
            return true;
        } catch (\PDOException $e) {
            return false;
        }
    }

    public function resetPassword(int $id, string $password) {
        $sql = "UPDATE user_
                SET password_ = :password
                WHERE id = :id";
        $input = [
            'id'=>$id,
            'password'=>$password
        ];
        try {
            $this->db->runSQL($sql, $input);
            return true;
        } catch (\PDOException $e) {
            return false;
        }
    }

    public function CreateEmailTemplate(int $type, string $url) {
        $sql = "SELECT *
                FROM email_templates
                WHERE email_templates.type_ = :type";
        $input = [
            'type'=>$type
        ];
        try {
            $templates = $this->db->runSQL($sql, $input)->fetchAll();
            $result = $templates[0]['template'] . $url . $templates[1]['template'];
            return ['valid'=>true,'data'=>$result];
        } catch (\PDOException $e) {
            return ['valid'=>false];
        }
    }

    public function updateUser(int $id, string $first_name, string $last_name) {
        $sql = "UPDATE user_
                SET first_name = :first_name,
                    last_name = :last_name
                WHERE id = :id";
        $input = [
            'id'=>$id,
            'first_name'=>$first_name,
            'last_name'=>$last_name
        ];
        try {
            $this->db->runSQL($sql, $input);
            return true;
        } catch (\PDOException $e) {
            return false;
        }
    }
}
?>