<?php
namespace Survey;
class Survey {
    protected $db = null;

    public function __construct(Database $db) {
        $this->db = $db;
    }

    public function get_surveys_for_id(int $id, int $show = null, int $offset = null): array {
        $sql = "SELECT id,
            title,
            start_date,
            end_date
            FROM survey
            WHERE survey.user_id = :id";
        $input = [ "id"=>$id ];
        if($show !== null && $offset !== null) {
            $sql .= ' ' . 'LIMIT :show OFFSET :offset';
            $input['show'] = $show;
            $input['offset'] = $offset;
        }
        return $this->db->runSQL($sql, $input)->fetchAll();
    }

    public function surveys_exist(int $id): int {
        $sql = "SELECT count(*)
            FROM survey
            WHERE survey.user_id = :id";
        $input = [ "id"=>$id ];
        return $this->db->runSQL($sql, $input)->fetchColumn();
    }

    public function get_survey_for_user(int $id, int $survey_id): array {
        $sql = "SELECT id,
            title,
            start_date,
            end_date
            FROM survey
            WHERE survey.user_id = :id
                AND survey.id = :survey_id";
        $input = [
            "id"=>$id,
            "survey_id"=>$survey_id
        ];
        return $this->db->runSQL($sql, $input)->fetch();
    }

    public function get_survey(int $id): array {
        $sql = "SELECT id,
            title,
            start_date,
            end_date
            FROM survey
            WHERE survey.id = :id";
        $input = [
            "id"=>$id
        ];
        return $this->db->runSQL($sql, $input)->fetch();
    }

    public function create_survey(int $id, string $title) {
        $sql = "INSERT INTO survey (title, user_id)
            VALUES (:title, :id)";
        $input = [
            "title"=>$title,
            "id"=>$id
        ];
        $this->db->runSQL($sql, $input);
        return $this->db->lastInsertId();
    }

    public function start_survey(int $id, string $start_date, string $end_date) {
        $sql = "UPDATE survey
            SET start_date = :start_date,
                end_date = :end_date
            WHERE id = :id";
        $input = [
            "end_date"=>$end_date,
            "start_date"=>$start_date,
            "id"=>$id
        ];
        $this->db->runSQL($sql, $input);
    }

    public function create_questions(int|string $id, array $questions) {
        $sql = "INSERT INTO question (survey_id, content)
            VALUES ";
        $counter = 1;
        $amount_questions = count($questions);
        $input = [];
        foreach($questions as $question) {
            $input["id_$counter"] = $id;
            $input["content_$counter"] = $question;
            if($counter == $amount_questions) {
                $sql .= "(:id_" . "$counter" . ', :content_' . "$counter" . ")";
            } else {
                $sql .= "(:id_" . "$counter" . ', :content_' . "$counter" . "),";
            }
            $counter += 1;
        }
        $this->db->runSQL($sql, $input);
    }

    public function get_questions(int|string $id) {
        $sql = "SELECT *
            FROM question
            WHERE question.survey_id = :id";
        $input = [
            "id" => $id
        ];
        return $this->db->runSQL($sql, $input)->fetchAll();
    }

    public function create_answers(array $answers) {
        $sql = "INSERT INTO answer (question_id, content)
            VALUES ";
        $counter = 1;
        $amount_answers = count($answers);
        $input = [];
        foreach($answers as $answer) {
            $input["id_$counter"] = $answer[0];
            $input["content_$counter"] = $answer[1];
            if($counter == $amount_answers) {
                $sql .= "(:id_" . "$counter" . ', :content_' . "$counter" . ")";
            } else {
                $sql .= "(:id_" . "$counter" . ', :content_' . "$counter" . "),";
            }
            $counter += 1;
        }
        $this->db->runSQL($sql, $input);
    }

    public function get_answers(int|string $id) {
        $sql = "SELECT a.*
            FROM survey AS s
            INNER JOIN question AS q ON q.survey_id = s.id
            INNER JOIN answer AS a ON q.id = a.question_id
            WHERE s.id = :id";
        $input = [
            "id"=>$id
        ];
        return $this->db->runSQL($sql, $input)->fetchAll();
    }

    public function get_submissions_count(int $survey_id) {
        $sql = "SELECT COUNT(*) AS 'submissions'
            FROM survey_taken
            WHERE survey_taken.survey_id = :survey_id";
        $input = [
            'survey_id'=>$survey_id
        ];
        return $this->db->runSQL($sql, $input)->fetchColumn();
    }
}
?>