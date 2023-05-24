<?php
declare(strict_types = 1);
require '../../src/bootstrap.php';

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

$survey = $cms->getSurvey()->get_survey($id);
$questions = $cms->getSurvey()->get_questions($id);
$answers = $cms->getSurvey()->get_answers($id);

$questions_answer = create_question_answer_array($questions, $answers);

$data['title'] = $survey['title'];
$data['questions'] = $questions_answer;

echo $twig->render('conduct/participate.html', $data);
?>