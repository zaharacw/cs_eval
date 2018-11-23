<?php
$pagetitle = 'Manage Courses';
$scripts = array('core.js', 'manage_course_view.js', 'jquery_download_plugin.js');
include 'header.php';
?>

<div id="manage_course_url" data-url="<?php echo $url ?>"></div>

<div class="row">
    <div class="col-md-1"></div>
    <div class="col-md-7">

        <div id="removeModal" class="modal fade">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="removeModalLabel">Delete section</h4>
                    </div>
                    <div class="modal-body">
                        <p>Are you sure you want to delete this course section?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                        <button id="removeModalSubmit" type="button" class="btn btn-danger">Delete section</button>
                    </div>
                </div>
            </div>
        </div>

        <div id="addModal" class="modal fade">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">Duplicate Section</h4>
                    </div>
                    <div class="modal-body">
                        <form>
                            <div class="form-group">
                                <label class="control-label">Choose instructor:</label>
                                <table id="instructorTable"
                                       data-search="true"
                                       data-show-footer="true"
                                       data-click-to-select="true"
                                       data-select-item-name="btSelectInstructor"
                                       class="table table-hover table-condensed">
                                    <thead>
                                    <tr>
                                        <th data-field="state" data-radio="true"></th>
                                        <th data-field="instructor" data-sortable="true">instructor</th>
                                        <th data-field="id" data-visible="false">id</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php foreach ($instructors as $inst) : ?>
                                        <tr>
                                            <td data-name="state"></td>
                                            <td data-name="instructor"><?php echo $inst->name; ?></td>
                                            <td data-name="id"><?php echo $inst->id; ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                            <input type="hidden" id="modalOperation" value="add">
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                        <button id="addModalSubmit" type="button" class="btn btn-primary">Duplicate section</button>
                    </div>
                </div>
            </div>
        </div>

        <div id="content">
            <h1><i class="fa fa-tachometer accent"></i>Manage Courses</h1>

            <div id="toolbar">
                <button id="duplicateCourse" type="button" class="btn btn-primary" data-toggle="modal"
                        data-target="#addModal" data-operation="add" disabled><i class="fa fa-files-o"></i> Duplicate
                </button>
                <button id="updateInstructor" type="button" class="btn btn-primary" data-toggle="modal"
                        data-target="#addModal" data-operation="modify" disabled><i class="fa fa-pencil-square-o"></i> Modify instructor
                </button>
                <button id="deleteCourse" type="button" class="btn btn-danger" data-toggle="modal"
                        data-target="#removeModal" disabled><i class="fa fa-times"></i> Remove
                </button>
            </div>
            <table id="table"
                   data-toolbar="#toolbar"
                   data-search="true"
                   data-show-toggle="true"
                   data-show-columns="true"
                   data-show-export="true"
                   data-show-pagination-switch="true"
                   data-pagination="true"
                   data-show-footer="true"
                   data-maintain-selected="true"
                   data-click-to-select="true"
                   data-page-size="50"
                   class="table table-hover table-condensed">
                <thead>
                <tr>
                    <th data-field="state" data-checkbox="true"></th>
                    <th data-field="id" data-visible="false">id</th>
                    <th data-field="year" data-sortable="true">year</th>
                    <th data-field="term" data-sortable="true">term</th>
                    <th data-field="subject" data-sortable="true" data-visible="false">subject</th>
                    <th data-field="number" data-sortable="true" data-visible="false">number</th>
                    <th data-field="section" data-sortable="true" data-visible="false">section</th>
                    <th data-field="tag" data-sortable="true">tag</th>
                    <th data-field="title" data-sortable="true">title</th>
                    <th data-field="instructor" data-sortable="true">instructor</th>
                    <th data-field="instructor_id" data-sortable="true" data-visible="false">instructor</th>
                    <th data-field="eval_start" data-sortable="true" data-visible="false">eval start</th>
                    <th data-field="eval_end" data-sortable="true" data-visible="false">eval end</th>
                    <th data-field="start_date" data-sortable="true" data-visible="false">start_date</th>
                    <th data-field="end_date" data-sortable="true" data-visible="false">end_date</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($courses as $course) : ?>
                    <tr <?php $mod = $course->modified;
                        if($mod == 1)
                        {
                            echo "class='info'";
                        } ?> 
                    >
                        <td data-name="state"></td>
                        <td data-name="id"><?php echo $course->section_id; ?></td>
                        <td data-name="year"><?php echo $course->year(); ?></td>
                        <td data-name="term"><?php echo $course->termName(); ?></td>
                        <td data-name="subject"><?php echo $course->course_subject; ?></td>
                        <td data-name="number"><?php echo $course->course_num; ?></td>
                        <td data-name="section"><?php echo $course->course_section; ?></td>
                        <td data-name="tag"><?php echo $course->course_subject . '&nbsp;' . $course->course_num . '&#8209;' . $course->niceSection(); ?></td>
                        <td data-name="title"><?php echo $course->title; ?></td>
                        <td data-name="instructor"><?php echo $course->instructorName(); ?></td>
                        <td data-name="instructor_id"><?php echo $course->instructor; ?></td>
                        <td data-name="eval_start"><?php echo $course->eval_start; ?></td>
                        <td data-name="eval_end"><?php echo $course->eval_end; ?></td>
                        <td data-name="start_date"><?php echo $course->start_date; ?></td>
                        <td data-name="end_date"><?php echo $course->end_date; ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <div class="col-md-3">
        <div class="alert alert-info" role="alert">
            <strong>NOTE:</strong> You may only manage courses whose evaluations have not yet begun.
            To alter evaluation dates, go <a href="<?php echo base_url(); ?>evaluation_period"
                                             class="alert-link">here</a>.
        </div>
        <div id="sidebar">
            <h2>Directions</h2>
            <ul>
                <li>This page allows the administrator to manage the courses for the current quarter.</li>
                <li>An admin may update the instructor for a course.</li>
                <li>Duplicate a course in order to add another instructor.</li>
                <li>Delete a course that should not be evaluated.</li>
                <li>The rows with courses that had instructors modified will be <span class="label label-info">highlighted</span></li>
            </ul>
        </div>
    </div>
    <div class="col-md-1"></div>
</div>
</div>
<script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-table/1.7.0/bootstrap-table.min.js"></script>
</body>
</html>