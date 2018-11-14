<?php
$pagetitle = 'Evaluation';
include 'header.php';

function PrintCheck($questions, $x, $val)
{
    if (array_key_exists('answer' . $x, $questions) && $questions['answer' . $x] == $val)
    {
        echo 'checked';
    }
}

?>

<div class="row top">
    <div class="col-md-12">
        <h1><i class="fa fa-file-text accent"></i><?php echo $course->title; ?> <small style="white-space: nowrap;"><?php echo $course->tag(true); ?></small></h1>
        <h3><?php echo $course->instructorName() . ' &ndash; ' . $course->termName() . ' ' . $course->year(); ?></h3>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <p><?php echo $message; ?></p>
    </div>
</div>

<div class="panel panel-default">
  <div class="panel-body text-center">
    <div class="col-md-6">lower is worse</div>
    <div class="col-md-6">higher is better</div>
</div>
</div>

<form id="evaluation" method="post" action="<?php echo base_url(); ?>evaluations/postback">
    <div id="evaluation-chart">
        <div class="row mobile-hidden reduced">
            <div class="col-md-4"></div>    
            <div class="col-md-4 eval-scores">
                <div class="col-xs-2"><label>N/A</label></div>
                <div class="col-xs-2"><label>1</label></div>
                <div class="col-xs-2"><label>2</label></div>
                <div class="col-xs-2"><label>3</label></div>
                <div class="col-xs-2"><label>4</label></div>
                <div class="col-xs-2"><label>5</label></div>
            </div>
            <div class="col-md-4"><label>Comments</label></div>    
        </div>
        
        <?php for ($i = 1; $i <= $num_questions; $i++) : ?>

            <div class="row eval-question">
            <div class="col-md-4"><span class="description" data-num="<?php echo $i; ?>"><?php echo $question['description' . $i]; ?></span></div>    
            <div class="col-md-4 eval-scores">
                <div class="col-xs-2">
                    <label data-title="N/A">
                    <input type="radio" name="question<?php echo $i ?>" value="0" <?php if ($questions['answer' . $x] == null) : ?> checked <?php endif; ?>/>
                    </label>
                </div>
                <div class="col-xs-2">
                    <label data-title="1">
                    <input type="radio" name="question<?php echo $i ?>" value="1" <?php PrintCheck($question, $i, 1); ?> />
                    </label>
                </div>
                <div class="col-xs-2">
                    <label data-title="2">
                    <input type="radio" name="question<?php echo $i ?>" value="2" <?php PrintCheck($question, $i, 2); ?> />
                    </label>
                </div>
                <div class="col-xs-2">
                    <label data-title="3">
                    <input type="radio" name="question<?php echo $i ?>" value="3" <?php PrintCheck($question, $i, 3); ?> />
                    </label>
                </div>
                <div class="col-xs-2">
                    <label data-title="4">
                    <input type="radio" name="question<?php echo $i ?>" value="4" <?php PrintCheck($question, $i, 4); ?> />
                    </label>
                </div>
                <div class="col-xs-2">
                    <label data-title="5">
                    <input type="radio" name="question<?php echo $i ?>" value="5" <?php PrintCheck($question, $i, 5); ?> />
                    </label>
                </div>
                <input type="hidden" name="q_id<?php echo $i ?>" value=<?php echo $question['q_id' . $i] ?>>
            </div>
            <div class="col-md-4">
                <textarea class="form-control comment-box" rows="2" placeholder="&hellip;" name="comment<?php echo $i ?>"><?php
                        if (!empty($question['comment' . $i]))
                        {
                            echo $question['comment' . $i];
                        }
                ?></textarea>
            </div>
        </div>

        <?php endfor; ?>

        <input type="hidden" name="section_id" value=<?php echo $section_id ?>>
        <input type="hidden" name="num_questions" value=<?php echo $num_questions ?>>
        <?php if ($viewingType != 'admin') : ?>
            <input type="hidden" id="unsaved_changes" value="0">
        <?php endif; ?>
    </div>

    <div class="row">
        <div class="col-md-12">
            <h3>General Comments</h3>
            <textarea id="comments" class="form-control" rows="7" name="comments"
                      placeholder="&hellip;"><?php if (!empty($comments))
                {
                    echo $comments;
                } ?></textarea>
        </div>
    </div>

    <div class="row">
        <?php if ($viewingType == 'student') : ?>
            <input type="submit" name="save" value="Save" class="btn btn-default btn-lg" id="saveButton"/>
            <input type="submit" name="submit" value="Submit" class="btn btn-primary btn-lg" id="submitButton"/>
        <?php else : ?>
            <input type="button" value="Save" class="btn btn-default btn-lg" disabled/>
            <input type="button" value="Submit" class="btn btn-primary btn-lg" disabled/>
        <?php endif; ?>
    </div>
</form>

</div>

<script type="text/javascript">
    $(document).ready(function () {
        $('#submitButton').click(function () {
            return confirm("After submitting, no further changes can be made. Are you sure you want to submit?");
        });

        $('input').change(function () {
            $('#unsaved_changes').val('1');
        });

        $('#saveButton, #submitButton').click(function () {
            $('#unsaved_changes').val('0');
        });

        window.onbeforeunload = function () {
            if ($('#unsaved_changes').val() === '1') {
                return 'You have unsaved changes.';
            }
        };
    });
</script>
</body>
</html>