<?php
$pagetitle = 'Instructor Questions';
$scripts = array('admin_instructor_questions_view.js');
include 'header.php';
?>
<style>
    .alignLabel {
        width: 85px;
    }

</style>

<div class="row">
    <div class="col-md-8">
        <div class="post">
            <div id="Add-Question" title="Create New Question">
                <p class="alert alert-info" role="alert">All fields required.</p>

                <form method="post" action="index.php?/admin_instructor_questions/addQuestion">
                    <fieldset>
                        <label type="text" class="alignLabel">Question</label>
                        <input type="text" class="alignBox" id="description"/>
                    </fieldset>
                    <fieldset>
                        <label type="text" class="alignLabel">Instructor</label>
                        <input type="text" class="alignBox" name="instructor" id="instructor"/>
                    </fieldset>

                    <label type="text">Select the course(s) this question applies to</label>
                    <br/>

                    <form>
                        <?php
                        echo "<input type=checkbox name=checkAll value=Check All / Uncheck All" . " class=floatLeft id=checkAll >";
                        echo "<label for=checkAll id=checkAllLabel class=floatLeft>Check All / Uncheck All </label>";
                        echo "<div id=clearFloat></div>";

                        foreach ($results2 as $row)
                        {
                            echo "<input type=checkbox name=course value=" . $row['subject'] . $row['number'] . " class=floatLeft id=" . $row['subject'] . $row['number'] . ">";
                            echo "<label for=" . $row['subject'] . $row['number'] . " id=" . $row['subject'] . $row['number'] . "Label class=floatLeft>" . $row['subject'] . $row['number'] . " - " . $row['title'] . "</label>";
                            echo "<div id=clearFloat></div>";

                        }
                        ?>
                    </form>
                </form>
            </div>

            <div id="Remove-Question" title="Remove Question">
                <form>
                    <label>Are you sure you want to remove this question?</label>
                </form>
            </div>

            <div id="Modify-Question" title="Modify Question">
                <p class="validateTips">Please enter a question for this course.</p>

                <form method="post" action="instructor_questions/modifyQuestion">
                    <fieldset>
                        <label type="text" class="alignLabel">Question</label>
                        <input type="text" class="alignBox" name="description" id="descriptionMod"
                               class="text ui-widget-content ui-corner-all"/>
                    </fieldset>
                </form>
            </div>

            <h1><span class="glyphicon glyphicon-question-sign accent" aria-hidden="true"></span>Instructor Questions
            </h1>
            <select id="mainSelect" size="10"
                    style="width: 100%;  overflow-x: scroll; overflow: -moz-scrollbars-horizontal;">

                <?php
                for ($i = 0; $i < $question['numQuestions']; $i++)
                {
                    ?>
                    <option
                        value="<?php echo $question['q_id' . $i]; ?>"><?php echo $question['instructorName' . $i] . "_" . $question['course_number' . $i] . "_" . $question['description' . $i];?> </option>
                <?php } ?>
            </select>
            <input type="hidden" id="url" value="<?php echo base_url(); ?>"/>
            <button class="btn btn-default" id="AddButton"> Add</button>
            <button class="btn btn-default" id="ModifyButton"> Modify</button>
            <button class="btn btn-danger" id="RemoveButton"> Remove</button>
        </div>
    </div>
    <div class="col-md-4">
        <div id="sidebar">
            <h2>Directions</h2>
            <ul>
                <li>This page allows the administrator to add, modify, or remove instructor questions.</li>
                <li>To add a question: click "Add", fill in the question, fill in the instructor's name, and select the
                    course(s) that the question will apply to.
                </li>
                <li>To modify a question: click on the question you want to modify and click "Modify", or you can double
                    click the question in the list box.
                </li>
                <li>To remove a question: click on the question you want to remove and click "Remove".</li>
            </ul>
            <h2>Notes</h2>
            <ul>
                <li>Once a question is changed, refresh the page to see the changes.</li>
            </ul>
        </div>
    </div>
</div>