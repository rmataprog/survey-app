<?php
declare(strict_types = 1);
require '../../src/bootstrap.php';
if(!$cms->getSession()->logged_in) {
    redirect(DOC_ROOT . "/user/login.php");
}
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $cms->getSession()->id;
    $title = $_POST['title'];
    $survey_id = $cms->getSurvey()->create_survey($id, $title);
    $questions_array = [];
    $answers_array = [];
    foreach ($_POST as $item => $value) {
        $array = explode('-', $item);
        if(count($array) == 2) {
            array_push($questions_array, $value);
        }
        if(count($array) == 3) {
            array_push($answers_array, [$array[1], $value]);
        }
    }
    $cms->getSurvey()->create_questions($survey_id, $questions_array);
    $questions = $cms->getSurvey()->get_questions($survey_id);
    $answers_array_output = [];
    foreach($answers_array as $answer) {
        $q_id = $questions[$answer[0]-1]['id'];
        $content = $answer[1];
        array_push($answers_array_output, [$q_id, $content]);
    }
    $cms->getSurvey()->create_answers($answers_array_output);
    $answers = $cms->getSurvey()->get_answers($survey_id);
    $survey = [];
    $survey['title'] = $title;
    $survey['questions'] = create_question_answer_array($questions, $answers);
    redirect(DOC_ROOT . '/define/defined.php', ['survey_id'=>$survey_id]);
} else {
    echo $twig->render('define/create.html');
}
?>