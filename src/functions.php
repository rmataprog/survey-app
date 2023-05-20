<?php
function redirect(string $location, array $parameters = [], $response_code = 302) {
    $qs = $parameters ? '?' . http_build_query($parameters) : '';
    $location = $location . $qs;
    header('Location: ' . $location, $response_code);
    exit;
}

function create_question_answer_array(array $questions, array $answers) {
    $result = [];
    foreach($questions as $question) {
        $res['question'] = $question;
        $q_id = $question['id'];
        $answers_for_question = array_filter($answers, function($a, $i) use($q_id) {
            return $a['question_id'] == $q_id;
        }, ARRAY_FILTER_USE_BOTH);
        $res['answers'] = $answers_for_question;
        array_push($result, $res);
    }
    return $result;
}

function format_date_time($date, $time, $now) {
    $date_object = $now ? new DateTime() : date_create_from_format('M d, Y', $date);
    if(!$now) {
        $time_array = explode(':', $time);
        $date_object->setTime($time_array[0], $time_array[1]);
    }
    return $date_object->format('Y-m-d H:i:s');
}
?>