<?php
$pagetitle = 'Course Questions';
$scripts = array('core.js', 'admin_course_questions_view.js');
include 'header.php';

function printCheckboxNew($sect_id, $csub, $cnum, $csect, $title)
{
    $fancyid = $csub . $cnum . '-' . sprintf('%02d', $csect);
    echo "<input type='checkbox' name='course' value='$sect_id' class='sectionQuestionBox' id='section$sect_id'>";
    echo "$fancyid: $title";
}

?>

<div class="row">
    <div class="col-md-8">
        <div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="addModalLabel"
             aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="addModalLabel">Add question</h4>
                    </div>
                    <div class="modal-body">
                        <div id="addModalAlert" class="alert alert-info" role="alert">All fields required.</div>
                        <form>
                            <div class="form-group">
                                <label for="questionType" class="control-label">Type:</label>
                                <select class="form-control" name="questionType" id="questionType">
                                    <option value="2">Instructor</option>
                                    <option value="3">Departmental</option>
                                    <option value="1">Other</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="questionDescription" class="control-label">Question:</label>
                                <input type="text" class="form-control" name="questionDescription"
                                       id="questionDescription">
                            </div>
                            <div class="form-group">
                                <label class="control-label">Courses:</label>

                                <div class="well checkboxList">
                                    <ul class="list-group">
                                        <li class="list-group-item active">
                                            <div class="checkbox">
                                                <label>
                                                    <input type="checkbox" name="checkAll" value="Check / Uncheck All"
                                                           class="sectionQuestionBox" id="checkAll">
                                                    Check / Uncheck All
                                                </label>
                                            </div>
                                        </li>
                                        <?php foreach ($results2 as $row) : ?>
                                            <li class="list-group-item">
                                                <div class="checkbox">
                                                    <label>
                                                        <?php printCheckboxNew($row->section_id, $row->course_subject, $row->course_num, $row->course_section, $row->title); ?>
                                                    </label>
                                                </div>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                            </div>
                            <input type="hidden" id="modalOperation" value="add">
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                        <button id="addModalSubmit" type="button" class="btn btn-primary">Add question</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="removeModal" tabindex="-1" role="dialog" aria-labelledby="removeModalLabel"
             aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="removeModalLabel">Remove question</h4>
                    </div>
                    <div class="modal-body">
                        <p>Are you sure you want to remove this question?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                        <button id="removeModalSubmit" type="button" class="btn btn-danger">Remove question</button>
                    </div>
                </div>
            </div>
        </div>

        <h1><i class="fa fa-question-circle accent"></i>Course Questions</h1>

        <div id="toolbar">
            <button id="add" class="btn btn-primary" title="Add" data-toggle="modal" data-target="#addModal"
                    data-operation="add">
                <i class="fa fa-plus"></i> Add
            </button>
            <button id="modify" class="btn btn-default" disabled data-toggle="modal" data-target="#addModal"
                    data-operation="modify">
                <i class="fa fa-pencil-square-o"></i> Modify
            </button>
            <button id="remove" class="btn btn-danger" disabled data-toggle="modal" data-target="#removeModal">
                <i class="fa fa-times"></i> Remove
            </button>
        </div>
        <table id="table"
               data-toolbar="#toolbar"
               data-search="true"
               data-maintain-selected="true"
               data-click-to-select="true"
               class="table table-hover table-condensed"
            >
            <thead>
            <tr>
                <th data-field="state" data-radio="true"></th>
                <th data-field="id" data-visible="false">id</th>
                <th data-field="sections" data-visible="false">sections</th>
                <th data-field="type" data-sortable="true">type</th>
                <th data-field="description" data-sortable="true">description</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($questions as $qid => $data) :
                ?>
                <tr>
                    <td data-name="state"></td>
                    <td data-name="id"><?php echo $qid; ?></td>
                    <td data-name="sections"><?php echo implode('_', $data['sections']); ?></td>
                    <td data-name="type"><?php echo $data['type']; ?></td>
                    <td data-name="description"><?php echo $data['description']; ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <div class="col-md-4">
    <div class="alert alert-info" role="alert">
            <strong>NOTE:</strong> You may only manage questions for courses whose evaluations have not yet begun.
            To alter evaluation dates, go <a href="<?php echo base_url(); ?>evaluation_period"
                                             class="alert-link">here</a>.
        </div>
        <div id="sidebar">
            <h2>Directions</h2>
            <ul>
                <li>This page allows the administrator to add, modify, or remove course-specific questions (ABET,
                    departmental, or optional).
                </li>
                <li>To add a question: click <span class="button-help"><i class="fa fa-plus"></i> Add</span>.</li>
                <li>To modify a question: double click the question or select it and click <span class="button-help"><i class="fa fa-pencil-square-o"></i> Modify</span>.</li>
                <li>To remove a question: click on the question you want to remove and click <span class="button-help"><i class="fa fa-times"></i> Remove</span>.</li>
            </ul>
        </div>
    </div>
</div>

<script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-table/1.7.0/bootstrap-table.min.js"></script>
</div>
</body>
</html>