<?php
namespace Survey;
class Survey {
    protected $db = null;

    public function __construct(Database $db) {
        $this->db = $db;
    }

    public function get_surveys_for_id(int $id): array {
        $sql = "SELECT id,
            title,
            start_date,
            end_date
            FROM survey
            WHERE survey.user_id = :id";
        
        $input = [ "id"=>$id ];

        return $this->db->runSQL($sql, $input)->fetchAll();
    }

    public function surveys_exist(int $id): int {
        $sql = "SELECT count(*)
            FROM survey
            WHERE survey.user_id = :id";
        
        $input = [ "id"=>$id ];

        return $this->db->runSQL($sql, $input)->fetchColumn();
    }
}
?>