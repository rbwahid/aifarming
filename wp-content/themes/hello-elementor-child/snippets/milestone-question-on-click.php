<?php
function check_milestone_question()
{
    if (is_singular('my-plant')) {
        $myPlantID = get_the_ID();
        $is_question_active = get_field('question_active', $myPlantID);
        $current_milestone = get_field('current_milestone_stage', $myPlantID);
        $plantHistoryID = get_field('question_post', $myPlantID);
        if ($is_question_active) {
            $type = get_field('type', $plantHistoryID);
            $milestone_stage = get_field('milestone_stage', $plantHistoryID);
            if ($current_milestone == $milestone_stage && $type == 8) {
                $questions = get_field('milestone_question', $plantHistoryID);
                if (is_array($questions)) {
                    $question = $correctAnswer = 0;
                    $newQuestionData = array();
                    if (isset($_POST['milestone_question_submit'])) {
                        foreach ($questions as $key => $questionData) {
                            $question++;
                            $newQuestionData[$key] = $questionData;
                            print_r($_POST['question_' . $key . '_answer']);
                            if (isset($_POST['question_' . $key . '_answer']) && $questionData['correct_answer'] == $_POST['question_' . $key . '_answer']) {
                                $correctAnswer++;
                            }
                            $newQuestionData[$key]['my_answer'] = isset($_POST['question_' . $key . '_answer']) ? $_POST['question_' . $key . '_answer'] : '';
                        }
                        update_field('milestone_question', array_values($newQuestionData), $plantHistoryID);
                    }
                    if ($question && $question == $correctAnswer) {
                        //send email code here .... 
                        $newMilestone = $current_milestone + 1;
                        update_field('question_active', 0, $myPlantID);
                        update_field('current_milestone_stage', $newMilestone, $myPlantID);
                        update_field('question_post', "", $myPlantID);
                        fetch_milestone_data($myPlantID, $newMilestone);
                    }
                }
            }
        }
    }
}
add_action('template_redirect', 'check_milestone_question');
add_action('wp_footer', function () {

    if (is_singular('my-plant')) {
        $question_active = get_field('question_active', get_the_ID());
        if ($question_active) {
?>
            <script>
                jQuery(document).ready(function($) {
                    setTimeout(function() {
                        elementorProFrontend.modules.popup.showPopup({
                            id: 5123
                        });
                    }, 1000);
                    $(".unlock-btn.active").on("click", function() {
                        elementorProFrontend.modules.popup.showPopup({
                            id: 5123
                        }); // Replace 1234 with your Popup ID
                    });
                });
            </script>
<?php
        }
    }
});

?>