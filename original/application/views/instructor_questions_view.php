<?php
$pagetitle = 'Evaluation Questions';
$scripts = array('instructor_questions_view.js');
include 'header.php';
?>

<div id="instructor-questions" class="row">
    <div class="post">
        <div id="Add-Question" title="Create new Question">
            <p class="validateTips">Please enter a question for your course.</p>

            <form method="post" action="instructor_questions/addQuestion">
                <fieldset>
                    <label type="text">Question</label>
                    <input type="text" name="description" id="description"
                           class="text ui-widget-content ui-corner-all"/>
                </fieldset>
            </form>

            <div id="Remove-Question" title="Remove Question">
                <form>
                    <label>Are you sure you want to remove this question?</label>
                </form>
            </div>
            <div id="Modify-Question" title="Modify Question">
                <p class="validateTips">Please enter a question for your course.</p>

                <form method="post" action="instructor_questions/modifyQuestion">
                    <fieldset>
                        <label type="text">Question</label>
                        <input type="text" name="description" id="descriptionMod"
                               class="text ui-widget-content ui-corner-all"/>
                    </fieldset>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div>
            <input type="hidden" id="url" value="<?php echo base_url(); ?>"/>
            <input type="hidden" id="c_id" value="<?php echo $info['c_id'] ?>"/>
            <input type="hidden" id="user" value="<?php echo $i_id ?>"/>
            <input type="hidden" id="userName" value="<?php echo $instructor_name ?>"/>
            <input type="hidden" id="courNum" value="<?php echo $info['number'] ?>"/>
            <input type="hidden" id="subject" value="<?php echo $info['subject'] ?>"/>

            <h3 id="optHeader">Optional Questions:</h3>
            <select id="optionalQuestions" size="10" multiple="multiple">
                <?php
                for ($i = 0; $i < $admin_optional['count']; $i++)
                {
                    echo "<option value=" . $admin_optional['q_id' . $i] . "_" . $admin_optional['type' . $i] . ">" . $admin_optional['name' . $i] . "_" . $info['subject'] . $info['number'] . "_" . $admin_optional['description' . $i] . "</option>";
                }
                ?>
            </select>
        </div>
        <div>
            <h3 id="courHeader">Instructor Questions:</h3>
            <select id="courseQuestions" size="10" multiple="multiple">
                <?php
                for ($i = 0; $i < $course_specific['count']; $i++)
                {
                    echo "<option value=" . $course_specific['q_id' . $i] . "_" . $course_specific['type' . $i] . ">" . $course_specific['name' . $i] . "_" . $info['subject'] . $info['number'] . "_" . $course_specific['description' . $i] . "</option>";
                }
                ?>
            </select>
        </div>
        <div>
            <button id="AddQuestionButton" class="btn btn-default">Add</button>
            <button id="ModifyButton" class="btn btn-default">Modify</button>
            <button id="RemoveButton" class="btn btn-danger">Remove</button>
        </div>
    </div>
    <div class="col-md-4">
        <div id="swap-buttons">
            <button id="leftArrowButton" class="btn btn-info btn-lg">&lt;</button>
            <button id="rightArrowButton" class="btn btn-info btn-lg">&gt;</button>
        </div>
        <div>
            <h3 id="evalHeader">Questions that will appear on evaluation form:</h3>
            <select id="evaluationQuestions" size="10" multiple="multiple">
                <?php
                for ($i = 0; $i < $selected['count']; $i++)
                {
                    echo "<option value=" . $selected['q_id' . $i] . "_" . $selected['type' . $i] . ">" . $selected['name' . $i] . "_" . $info['subject'] . $info['number'] . "_" . $selected['description' . $i] . "</option>";
                }
                ?>
            </select>
        </div>
    </div>
    <div class="col-md-4">
        <div id="sidebar">
            <h2>Directions</h2>
            <ul>
                <li>This page allows the instructor to add or remove questions to/from the evaluation of a specific
                    class.
                </li>
                <li>To choose questions for the evaluation: select one or more general and instructor questions from
                    list boxes on the left and click right arrow.
                </li>
                <li>To remove questions from the evaluation: select those questions in the evaluation questions list box
                    and click left arrow.
                </li>
                <li>To add a question: click "Add", fill in the required fields and click "Add Question".</li>
                <li>To modify a question: select the question you want to modify and click "Modify" or you can double
                    click the question in the list box.
                </li>
                <li>To remove questions: select questions you want to remove and click "Remove".</li>
                <li>In order to Modify or Remove a question it must be in the list box on the left.</li>
            </ul>
        </div>
    </div>
</div>
</body>
</html>
