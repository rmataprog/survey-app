<?php
declare(strict_types = 1);
// require '../../src/bootstrap.php';
if(!$cms->getSession()->logged_in) {
    redirect(DOC_ROOT . "user/login");
}
$coordinator = $cms->getSession()->coordinator;
if($coordinator) {
    if($_SERVER['REQUEST_METHOD'] == 'POST') {
        $regexp = '/^[A-z0-9\s¿?-]+$/';
        $id = $cms->getSession()->id;
        $title = $_POST['title'];
        $filters['title']['filter'] = FILTER_VALIDATE_REGEXP;
        $filters['title']['options']['regexp'] = $regexp;
        $questions_array = [];
        $answers_array = [];
        $errors = [];
        foreach ($_POST as $item => $value) {
            $array = explode('-', $item);
            if(count($array) == 2) {
                $filters[$item]['filter'] = FILTER_VALIDATE_REGEXP;
                $filters[$item]['options']['regexp'] = $regexp;
                array_push($questions_array, $value);
            }
            if(count($array) == 3) {
                $filters[$item]['filter'] = FILTER_VALIDATE_REGEXP;
                $filters[$item]['options']['regexp'] = $regexp;
                array_push($answers_array, [$array[1], $value]);
            }
        };
        $data = filter_input_array(INPUT_POST, $filters);
        foreach ($data as $field => $value) {
            $array = explode('-', $field);
            if(!$value) {
                switch (count($array)) {
                    case 1:
                        $errors[$field] = "Title can only have alpha numberic characters and interrogation symbol";
                        break;
                    case 2:
                        $errors[$field] = "Questions can only have alpha numberic characters and interrogation symbol";
                        break;
                    default:
                        $errors[$field] = "Options can only have alpha numberic characters and interrogation symbol";
                }
            }
        };
        $invalid = implode('', $errors);
    
        if($invalid) {
            $q = [];
            foreach($questions_array as $index => $value) {
                $a = [];
                foreach($answers_array as $answer) {
                    if($index == $answer[0] - 1) {
                        array_push($a, ['content'=>$answer[1]]);
                    }
                }
                array_push($q, ['question'=> ['content'=>$value], 'answers'=>$a]);
            }
            $input['title'] = ['content'=>$title, 'error'=>$errors['title']];
            $input['questions'] = $q;
            $input['errors'] = $errors;
            $input['invalid'] = true;
            echo $twig->render('define/create.html', $input);
        } else {
            $edit = isset($_POST['edit']) ? true : false;
            if($edit) {
                if(isset($variable_1)) {
                    $survey_id = filter_var($variable_1, FILTER_VALIDATE_INT);
                    if($survey_id) {
                        $data = $cms->getSurvey()->update_survey($survey_id, $title);
                        if($data['valid']) {
                            $deleted = $cms->getSurvey()->delete_questions($survey_id);
                            if(!$deleted) {
                                redirect(DOC_ROOT . 'define/define/error/' . rawurlencode('There was a problem saving the edits of the survey'));
                            }
                        } else {
                            redirect(DOC_ROOT . 'define/create/error/' . rawurlencode($data['message']));
                        }
                    } else {
                        redirect(DOC_ROOT . 'notFound');
                    }
                } else {
                    redirect(DOC_ROOT . 'notFound');
                }
            } else {
                $data = $cms->getSurvey()->create_survey($id, $title);
                if($data['valid']) {
                    $survey_id = filter_var($data['data'], FILTER_VALIDATE_INT);
                } else {
                    redirect(DOC_ROOT . 'define/create/error/' . rawurlencode($data['message']));
                }
            }
            $create = $cms->getSurvey()->create_questions($survey_id, $questions_array);
            $questions = $cms->getSurvey()->get_questions($survey_id);
            if($create['valid'] && $questions['valid']) {
                $answers_array_output = [];
                foreach($answers_array as $answer) {
                    $q_id = $questions['data'][$answer[0]-1]['id'];
                    $content = $answer[1];
                    array_push($answers_array_output, [$q_id, $content]);
                }
                $create_answers = $cms->getSurvey()->create_answers($answers_array_output);
                $answers = $cms->getSurvey()->get_answers($survey_id);
                if($create_answers['valid'] && $answers['valid']) {
                    $survey = [];
                    $survey['title'] = $title;
                    $survey['questions'] = create_question_answer_array($questions['data'], $answers['data']);
                    redirect(DOC_ROOT . 'define/defined/' . $survey_id);
                } else {
                    $message = 'There was problem creating answers for the survey';
                    redirect(DOC_ROOT . 'define/create/error/' . rawurlencode($message));                        
                }
            } else {
                redirect(DOC_ROOT . 'define/create/error/' . rawurlencode($create['message']));
            }
        }
    } else {
        $data['coordinator'] = $coordinator;
        $retrieve_error = isset($variable_2) ? $variable_2 : '';
        if(isset($variable_1) && $variable_1 == 'error') {
            $data['error']['message'] = rawurldecode($retrieve_error);
        }
        echo $twig->render('define/create.html', $data);
    }
} else {
    redirect(DOC_ROOT . 'view/view');
}
?>