<?php
$pagetitle = 'Set Evaluation Period';
$scripts = array('core.js', 'evaluation_period.js', 'jquery_download_plugin.js', 'bootstrap-datepicker.min.js');
$stylesheets = array(base_url() . 'css/bootstrap-datepicker.min.css');

include 'header.php';
?>

<div id="manage_course_url" data-url="<?php echo $url ?>"></div>

<div class="row">
    <div class="col-md-1"></div>
    <div class="col-md-7">
        <div id="updateModal" class="modal fade">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="addModal">Set Evaluation Period</h4>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-sm-6">
                                <label for="start">Start: </label>
                            </div>
                            <div class="col-sm-6">
                                <label for="end">End: </label>
                            </div>
                        </div>
                        <div class="input-daterange input-group" id="datepicker">
                            <input type="text" class="form-control" name="start" id="start"/>
                            <span class="input-group-addon">to</span>
                            <input type="text" class="form-control" name="end" id="end"/>

                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                        <button id="updateModalSubmit" type="button" class="btn btn-primary">Modify</button>
                    </div>
                </div>
            </div>
        </div>
        <div id="content">
            <h1><i class="fa fa-calendar accent"></i>Set Evaluation Period</h1>

            <div id="toolbar">
                <button id="updateCourse" type="button" class="btn btn-primary" data-toggle="modal"
                        data-target="#updateModal" data-operation="add" disabled><i class="fa fa-pencil-square-o"></i> Modify
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
                    <th data-field="year" data-sortable="true" data-visible="false">year</th>
                    <th data-field="term" data-sortable="true" data-visible="false">term</th>
                    <th data-field="subject" data-sortable="true" data-visible="false">subject</th>
                    <th data-field="number" data-sortable="true" data-visible="false">number</th>
                    <th data-field="section" data-sortable="true" data-visible="false">section</th>
                    <th data-field="tag" data-sortable="true" data-visible="false">tag</th>
                    <th data-field="title" data-sortable="true">title</th>
                    <th data-field="instructor" data-sortable="true">instructor</th>
                    <th data-field="eval_start" data-sortable="true">eval start</th>
                    <th data-field="eval_end" data-sortable="true">eval end</th>
                    <th data-field="start_date" data-sortable="true" data-visible="false">start_date</th>
                    <th data-field="end_date" data-sortable="true" data-visible="false">end_date</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($courses as $course) : ?>
                    <tr <?php $mod = $course->modified_date;
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
        <div id="sidebar">
            <h2>Directions</h2>
            <ul>
                <li>This page allows the administrator to set the time-frame during which a student may evaluate a
                    course.</li>
                <li>Only courses whose evaluations have not ended within the past 30 days are shown.</li>
                <li>When the start or end date is modified, course rows will be <span class="label label-info">highlighted</span></li>
            </ul>
        </div>
    </div>
    <div class="col-md-1"></div>
</div>
</div>
<script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-table/1.7.0/bootstrap-table.min.js"></script>
<!-- <script src="//cdnjs.cloudflare.com/ajax/libs/less.js/2.5.0/less.min.js"></script>-->
</body>
</html>