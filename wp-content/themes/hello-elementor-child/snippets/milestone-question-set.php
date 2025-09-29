<?php
function milestone_form()
{
    $myPlantID = get_the_ID();
    ob_start();
    $is_question_active = get_field('question_active', $myPlantID);
    $current_milestone = get_field('current_milestone_stage', $myPlantID);
    $plantHistoryID = get_field('question_post', $myPlantID);
    if ($is_question_active) {
        $type = get_field('type', $plantHistoryID);
        $milestone_stage = get_field('milestone_stage', $plantHistoryID);
        if ($current_milestone == $milestone_stage && $type == 8) {
            $questions = get_field('milestone_question', $plantHistoryID);
            if (is_array($questions)) {
                /*
				$question = $correctAnswer = 0;
				$newQuestionData = array();	
				if(isset($_POST['milestone_question_submit'])){
					foreach($questions as $key => $questionData){
						$question++;
						$newQuestionData[$key] = $questionData;
						if(isset($_POST['question_'.$key.'_answer']) && $questionData['correct_answer'] == $_POST['question_'.$key.'_answer']){
							$correctAnswer++;
						}
						$newQuestionData[$key]['my_answer'] = isset($_POST['question_'.$key.'_answer']) ? $_POST['question_'.$key.'_answer'] : '';
					}
					update_field('milestone_question', array_values($newQuestionData), $plantHistoryID);
				}
				if($question && $question == $correctAnswer){
					//send email code here .... 
					$newMilestone = $current_milestone + 1;
					update_field('question_active', 0, $myPlantID);
					update_field('current_milestone_stage', $newMilestone, $myPlantID);
					update_field('question_post', "", $myPlantID);
					fetch_milestone_data($myPlantID, $newMilestone);
				}
				*/
                if (!$question || ($question && $question != $correctAnswer)) {
                    echo "<form class='milestone-question-form' action='' method='POST'>";
                    foreach ($questions as $key => $questionData) {
?>
                        <div class="form-group">
                            <label><strong><?php echo $questionData['question']; ?></strong></label><br />
                            <?php
                            $ansTitle = 'answer_';
                            for ($i = 1; $i <= 4; $i++) {
                                if ($questionData[$ansTitle . $i]) {
                                    echo '<label><input type="radio" name="question_' . $key . '_answer" value="' . $questionData[$ansTitle . $i] . '" class="answer ' . $ansTitle . $i . '" /> ' . $questionData[$ansTitle . $i] . "</label><br/>";
                                }
                            }
                            ?>
                        </div>
<?php
                    }
                    echo "<div class='form-button'>";
                    echo "<input type='submit' class='milestone_question_submit' name='milestone_question_submit' value='Submit Data' />";
                    echo "</div></form>";
                }
            }
        }
    }
    $contents = ob_get_contents();
    ob_end_clean();
    return $contents;
}
add_shortcode('milestone_form', 'milestone_form');
?>