<?php
namespace Survey;
class Survey {
    protected $db = null;

    public function __construct(Database $db) {
        $this->db = $db;
    }

    public function get_surveys_for_id(int $id, int $offset = null, int $show = 3): array {
        $sql = "SELECT id,
            title,
            start_date,
            end_date
            FROM survey
            WHERE survey.user_id = :id";
        $input = [ "id"=>$id ];
        if($offset !== null) {
            $sql .= ' ' . 'LIMIT :show OFFSET :offset';
            $input['show'] = $show;
            $input['offset'] = $offset;
        }
        try {
            $data = $this->db->runSQL($sql, $input)->fetchAll();
            return ['valid'=>true, 'data'=>$data, 'show'=>$show];
        } catch (\PDOException $e) {
            return ['valid'=>false, 'message'=>'There was a problem retrieving surveys'];
        } 
    }

    public function surveys_exist(int $id): array {
        $sql = "SELECT count(*)
            FROM survey
            WHERE survey.user_id = :id";
        $input = [ "id"=>$id ];
        try {
            $data = $this->db->runSQL($sql, $input)->fetchColumn();
            return ['valid'=>true, 'data'=>$data];
        } catch (\PDOException $e) {
            return ['valid'=>false, 'message'=>'There was a problem retrieving surveys count'];
        }
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
        try {
            $data = $this->db->runSQL($sql, $input)->fetch();
            return ['valid'=>true, 'data'=>$data];
        } catch (\PDOException $e) {
            return ['valid'=>false, 'message'=>'There was a problem retrieving survey data for user'];
        }
    }

    public function get_survey(int $id) {
        $sql = "SELECT id,
            title,
            start_date,
            end_date
            FROM survey
            WHERE survey.id = :id";
        $input = [
            "id"=>$id
        ];
        try {
            $data = $this->db->runSQL($sql, $input)->fetch();
            return ['valid'=>true, 'data'=>$data];
        } catch (\PDOException $e) {
            return ['valid'=>false, 'message'=>'There was problem retrieving the survey'];
        }
    }

    public function create_survey(int $id, string $title) {
        $sql = "INSERT INTO survey (title, user_id)
            VALUES (:title, :id)";
        $input = [
            "title"=>$title,
            "id"=>$id
        ];
        try {
            $this->db->runSQL($sql, $input);
            $lastId = $this->db->lastInsertId();
            return ['valid'=>true, 'data'=>$lastId];
        } catch (\PDOException $e) {
            return ['valid'=>false, 'message'=>'There was a problem creating the survey'];
        }
    }

    public function update_survey(int $id, string $title) {
        $sql = "UPDATE survey
            SET title = :title
            WHERE survey.id = :id";
        $input = [
            "title"=>$title,
            "id"=>$id
        ];
        try {
            $this->db->runSQL($sql, $input);
            return ['valid'=>true];
        } catch (\PDOException $e) {
            return ['valid'=>false, 'message'=>'There was a problem updating the survey'];
        }
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
        try {
            $this->db->runSQL($sql, $input);
            return ['valid'=>true];
        } catch (\PDOException $e) {
            return ['valid'=>false, 'message'=>'There was a problem starting the survey'];
        }
    }

    public function create_questions(int $id, array $questions) {
        $sql = "INSERT INTO question (survey_id, content)
            VALUES ";
        try {
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
            return ['valid'=>true];
        } catch (\PDOException $e) {
            return ['valid'=>false, 'message'=>'There was a problem creating the survey'];
        }
    }

    public function get_questions(int $id) {
        $sql = "SELECT *
            FROM question
            WHERE question.survey_id = :id";
        $input = [
            "id" => $id
        ];
        try {
            $data = $this->db->runSQL($sql, $input)->fetchAll();
            return ['valid'=>true, 'data'=>$data];
        } catch (\PDOException $e) {
            return ['valid'=>false];
        }
    }

    public function create_answers(array $answers) {
        $sql = "INSERT INTO answer (question_id, content)
            VALUES ";
        try {
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
            return ['valid'=>true];
        } catch (\PDOException $e) {
            return ['valid'=>false];
        }
    }

    public function get_answers(int $id) {
        $sql = "SELECT a.*
            FROM survey AS s
            INNER JOIN question AS q ON q.survey_id = s.id
            INNER JOIN answer AS a ON q.id = a.question_id
            WHERE s.id = :id";
        $input = [
            "id"=>$id
        ];
        try {
            $data = $this->db->runSQL($sql, $input)->fetchAll();
            return ['valid'=>true, 'data'=>$data];
        } catch (\PDOException $e) {
            return ['valid'=>false];
        }
    }

    public function get_submissions_count(int $survey_id) {
        $sql = "SELECT COUNT(*) AS 'submissions'
            FROM survey_taken
            WHERE survey_taken.survey_id = :survey_id";
        $input = [
            'survey_id'=>$survey_id
        ];
        try {
            $data = $this->db->runSQL($sql, $input)->fetchColumn();
            return ['valid'=>true, 'data'=>$data];
        } catch (\PDOException $e) {
            return ['valid'=>false];
        }
    }

    public function take_survey(int $survey_id, int $user_id = 0, $date) {
        $sql = "INSERT INTO survey_taken(survey_id, " . ($user_id == 0 ? ' ' : 'user_id,') . " date)
            VALUES (:survey_id, " . ($user_id == 0 ? ' ' : ':user_id,') . " :date)";
        try {
            $formatted_date = $date->format('Y-m-d H:i:s');
            $input = [
                'survey_id'=>$survey_id,
                'date'=>$formatted_date
            ];
            if($user_id > 0) {
                $input['user_id'] = $user_id;
            }
            $this->db->runSQL($sql, $input);
            $data = $this->db->lastInsertId();
            return ['valid'=>true, 'data'=>$data];
        } catch (\PDOException $e) {
            return ['valid'=>false];
        }
    }

    public function give_answers(int $survey_taken_id, array $answers, int $amount) {
        try {
            $sql = "INSERT INTO answer_given(survey_taken_id, answer_id)
            VALUES ";
            $count = 1;
            $input = [];
            foreach ($answers as $question=>$answer) {
                $sql .= "(:survey_taken_id_$count, :answer_$count)";
                $input["survey_taken_id_$count"] = $survey_taken_id;
                $input["answer_$count"] = $answer;
                if($count < $amount) {
                    $sql .= ', ';
                }
                $count += 1;
            }
            $this->db->runSQL($sql, $input);
            return ['valid'=>true,'message'=>'Thanks for participating'];
        } catch (\PDOException $e) {
            return ['valid'=>false,'message'=>'there was a problem submitting your answers, try again later'];
        }
    }

    public function check_survey_taken(int $survey_id, int $user_id) {
        $sql = "SELECT COUNT(*) as took_survey
            FROM survey_taken
            WHERE survey_taken.survey_id = :survey_id AND survey_taken.user_id = :user_id";
        $input = [
            'survey_id' => $survey_id,
            'user_id' => $user_id
        ];
        try {
            $data = $this->db->runSQL($sql, $input)->fetchColumn();
            return ['valid'=>true, 'data'=>$data];
        } catch (\PDOException $e) {
            return ['valid'=>false];
        }
    }

    public function get_survey_list_submissions(int $user_id) {
        $sql = "SELECT st.survey_id,
                s.title,
                s.start_date,
                s.end_date,
                count(*) AS submissions
                FROM survey_taken AS st
                INNER JOIN survey AS s ON s.id = st.survey_id
                where s.user_id = :user_id_1
                GROUP BY st.survey_id
                UNION
                SELECT s.id,
                s.title,
                s.start_date,
                s.end_date,
                0
                FROM survey AS s
                WHERE s.start_date IS NOT NULL
                    AND s.user_id = :user_id_2
                    AND s.id NOT IN (
                        SELECT st.survey_id
                        FROM survey_taken AS st
                        GROUP BY st.survey_id
                        )";
        $input = [
            'user_id_1' => $user_id,
            'user_id_2' => $user_id
        ];
        try {
            $data = $this->db->runSQL($sql, $input)->fetchAll();
            return ['valid'=>true, 'data'=>$data];
        } catch (\PDOException $e) {
            return ['valid'=>false];
        }
    }

    public function get_survey_list_submissions_for_respondant(int $user_id) {
        $sql = "SELECT st.survey_id,
                s.title,
                s.start_date,
                s.end_date,
                count(*) AS submissions
                FROM survey_taken AS st
                INNER JOIN survey AS s ON s.id = st.survey_id
                WHERE s.id IN (SELECT survey_taken.survey_id
                    FROM survey_taken
                    WHERE survey_taken.user_id = :user_id)
                GROUP BY st.survey_id";
        $input = [
            'user_id' => $user_id
        ];
        try {
            $data = $this->db->runSQL($sql, $input)->fetchAll();
            return ['valid'=>true, 'data'=>$data];
        } catch (\PDOException $e) {
            return ['valid'=>false];
        }
    }

    public function get_survey_results(int $survey_id) {
        $sql = "SELECT q.id AS question_id, ag.answer_id, a.content, COUNT(*) AS total
                FROM answer_given AS ag
                LEFT JOIN survey_taken AS st ON st.id = ag.survey_taken_id
                LEFT JOIN answer AS a ON a.id = ag.answer_id
                LEFT JOIN question AS q ON q.id = a.question_id
                WHERE st.survey_id = :survey_id_1
                GROUP BY q.id, ag.answer_id
                UNION
                SELECT a.question_id, a.id, a.content, 0
                FROM answer AS a
                LEFT JOIN question AS q ON q.id = a.question_id
                LEFT JOIN survey AS s ON s.id = q.survey_id
                WHERE s.id = :survey_id_2 AND a.id NOT IN (
                    SELECT ag.answer_id
                    FROM answer_given AS ag
                    LEFT JOIN survey_taken AS st ON st.id = ag.survey_taken_id
                    LEFT JOIN answer AS a ON a.id = ag.answer_id
                    LEFT JOIN question AS q ON q.id = a.question_id
                    GROUP BY ag.answer_id
                    )";
        $input = [
            'survey_id_1' => $survey_id,
            'survey_id_2' => $survey_id
        ];
        try {
            $data = $this->db->runSQL($sql, $input)->fetchAll();
            return ['valid'=>true, 'data'=>$data];
        } catch (\PDOException $e) {
            return ['valid'=>false];
        }
    }

    public function delete_questions(int $survey_id) {
        $sql = "DELETE FROM question
                WHERE question.survey_id = :survey_id";
        $input = [
            'survey_id' => $survey_id
        ];
        try {
            $this->db->runSQL($sql, $input);
            return true;
        } catch (\PDOException $e) {
            return false;
        }
    }

    public function get_latest_surveys_to_respond($offset, $dateTime, $show = 3): array {
        $sql = "SELECT id,
                title,
                start_date,
                end_date
                FROM survey
                WHERE start_date IS NOT null
                    AND end_date > :now_1
                    AND start_date <= :now_2
                ORDER BY start_date desc
                LIMIT :show OFFSET :offset";
        $input = [
            "now_1"=>$dateTime,
            "now_2"=>$dateTime,
            'show'=>$show,
            'offset'=>$offset
        ];
        try {
            $data = $this->db->runSQL($sql, $input)->fetchAll();
            return ['valid'=>true, 'data'=>$data];
        } catch (\PDOException $e) {
            return ['valid'=>false];
        }
    }

    public function get_active_surveys_count($dateTime): array {
        $sql = "SELECT COUNT(*)
                FROM survey
                WHERE start_date IS NOT NULL
                    AND end_date > :now_1
                    AND start_date <= :now_2";
        $input = [
            "now_1"=>$dateTime,
            "now_2"=>$dateTime
        ];
        try {
            $data = $this->db->runSQL($sql, $input)->fetchColumn();
            return ['valid'=>true, 'data'=>$data];
        } catch (\PDOException $e) {
            return ['valid'=>false];
        }
    }

    public function close_survey(int $survey_id, $end_date): array {
        $sql = "UPDATE survey
            SET end_date = :end_date
            WHERE id = :survey_id";
        $input = [
            "survey_id"=>$survey_id,
            "end_date"=>$end_date
        ];
        try {
            $this->db->runSQL($sql, $input);
            return ['valid'=>true];
        } catch (\PDOException $e) {
            return ['valid'=>false];
        }
    }
}
?>