<?php
$pagetitle = 'Reports';
$scripts = array('core.js', 'admin_reports_view.js', 'jquery_download_plugin.js');
include 'header.php';
?>

<div class="row">
    <div class="col-md-1"></div>
    <div class="col-md-7">
        <div id="content">
            <h1><i class="fa fa-bar-chart accent"></i>Reports</h1>

            <div id="toolbar">
                <button class="btn btn-default" id="rawScoreGen" disabled>Scores</button>
                <button class="btn btn-default" id="rawCommentGen" disabled>Comments</button>
                <button class="btn btn-primary" id="pdfGen" disabled>PDF report</button>
                <button class="btn btn-primary" id="countGen" disabled>Count report</button>
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
                    <th data-field="students" data-sortable="true" data-visible="false">students</th>
                    <th data-field="saved" data-sortable="true" data-visible="false">saved</th>
                    <th data-field="submitted" data-sortable="true">submitted</th>
                    <th data-field="eval_start" data-sortable="true" data-visible="false">eval start</th>
                    <th data-field="eval_end" data-sortable="true" data-visible="false">eval end</th>
                    <th data-field="start_date" data-sortable="true" data-visible="false">start_date</th>
                    <th data-field="end_date" data-sortable="true" data-visible="false">end_date</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($courses as $course) : ?>
                    <tr>
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
                        <td data-name="students"><?php echo $course->student_count; ?></td>
                        <td data-name="saved"><?php echo $course->getEvaluation()->saved_count(); ?></td>
                        <td data-name="submitted"><?php echo $course->getEvaluation()->submitted_count(); ?></td>
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
                <li>This page allows the administrator to generate reports for a given class or multiple classes.</li>
                <li>First, select one or more courses.</li>
                <li>Raw scores can be obtained by clicking <span class="button-help">Scores</span>.</li>
                <li>For raw comments, click <span class="button-help">Comments</span>.</li>
                <li>To generate PDF reports&mdash;the most comprehensive&mdash;click <span class="button-help">PDF report</span>.</li>
                <li>Select <span class="button-help">Count report</span> to determine how many students have completed evaluations for a
                    given course.
                </li>
            </ul>
        </div>
    </div>
    <div class="col-md-1"></div>
</div>
</div>
<script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-table/1.7.0/bootstrap-table.min.js"></script>
</body>
</html>