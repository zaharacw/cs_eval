<?php
$pagetitle = ($required) ? 'Required Questions' : 'Optional Questions';
$scripts = array('core.js', 'admin_required_questions_view.js');
include 'header.php';
?>

<!-- TODO set limit on how long a question can be, i.e. no hundred character question, maybe 50 at most -->

<div class="row">
    <div class="col-md-8">
        <div class="alert alert-danger alert-dismissible" role="alert" id="selectionAlert" hidden>
            <button type="button" class="close" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <strong>Wait!</strong> You must select a question.
        </div>

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
                                <label for="questionDescription" class="control-label">Question:</label>
                                <input type="text" class="form-control" name="questionDescription"
                                       id="questionDescription">
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

        <h1><i class="fa fa-question-circle accent"></i><?php echo $pagetitle; ?>
        </h1>

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
                <th data-field="description" data-sortable="true">description</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($results as $row) : ?>
                <tr>
                    <td data-name="state"></td>
                    <td data-name="id"><?php echo $row->q_id; ?></td>
                    <td data-name="description"><?php echo $row->description; ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <div class="col-md-4">
        <div id="sidebar">
            <h2>Directions</h2>
            <ul>
                <li>This page allows the administrator to add, modify, or remove questions which will
                    be <?php echo ($required) ? 'required' : 'optional'; ?> on all evaluations.
                </li>
                <li>To add a question: click <span class="button-help"><i class="fa fa-plus"></i> Add</span>.</li>
                <li>To modify a question: click on the question you want to modify and click <span class="button-help"><i class="fa fa-pencil-square-o"></i> Modify</span>, or you can double
                    click the question in the list box.
                </li>
                <li>To remove a question: click on the question you want to remove and click <span class="button-help"><i class="fa fa-times"></i> Remove</span>.</li>
            </ul>
        </div>
    </div>
</div>

<script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-table/1.7.0/bootstrap-table.min.js"></script>
</div>
</body>
</html>