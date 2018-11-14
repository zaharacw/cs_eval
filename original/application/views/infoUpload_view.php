<?php
$pagetitle = 'Information Upload';
$scripts = array('infoUpload_view.js');
include 'header.php';

$keys = array('subject', 'number', 'section', 'title', 'instructor', 'quarter', 'year', 'eval_start', 'eval_end');
$num = 0;
?>

<div class="row">
    <div class="col-md-1"></div>
    <div class="col-md-7">
        <h1><i class="fa fa-cloud-upload accent"></i>Upload
            <small><?php echo $term; ?> Term, <?php echo date("Y"); ?> &mdash; <?php echo $subjects ?></small>
        </h1>
        <div class="post">
            <form id="course-selection" method="post">
                <div id="toolbar"></div>
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
                       data-page-size="25"
                       class="table table-hover table-condensed"
                    >
                    <thead>
                    <tr>
                        <th data-field="state" data-checkbox="true"></th>
                        <th data-field="id" data-sortable="true" data-visible="false">id</th>
                        <th data-field="duplicate" data-sortable="true" data-visible="false">will overwrite</th>
                        <th data-field="subject" data-sortable="true" data-visible="false">subject</th>
                        <th data-field="number" data-sortable="true" data-visible="false">number</th>
                        <th data-field="section" data-sortable="true" data-visible="false">section</th>
                        <th data-field="tag" data-sortable="true">tag</th>
                        <th data-field="title" data-sortable="true">title</th>
                        <th data-field="instructor" data-sortable="true">instructor</th>
                        <th data-field="term" data-sortable="true">term</th>
                        <th data-field="year" data-sortable="true" data-visible="false">year</th>
                        <th data-field="eval_start" data-sortable="true">eval start</th>
                        <th data-field="eval_end" data-sortable="true">eval end</th>
                        <th data-field="start_date" data-sortable="true" data-visible="false">start_date</th>
                        <th data-field="end_date" data-sortable="true" data-visible="false">end_date</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $num = 0;
                    foreach ($courses as $course) :
                        $duplicate = $course->getOriginal() != null;
                        ?>
                        <tr<?php if ($duplicate)
                        {
                            echo ' class="danger"';
                        } ?>>
                            <td data-name="state"></td>
                            <td data-name="id"><?php echo $num; ?></td>
                            <td data-name="duplicate"><?php echo $duplicate ? 'T' : 'F'; ?></td>
                            <td data-name="subject"><?php echo $course->course_subject; ?></td>
                            <td data-name="number"><?php echo $course->course_num; ?></td>
                            <td data-name="section"><?php echo $course->course_section; ?></td>
                            <td data-name="tag"><?php echo $course->course_subject . '&nbsp;' . $course->course_num . '&#8209;' . $course->niceSection(); ?></td>
                            <td data-name="title"><?php echo $course->title; ?></td>
                            <td data-name="instructor"><?php echo $course->instructorName(); ?></td>
                            <td data-name="term"><?php echo $course->termName(); ?></td>
                            <td data-name="year"><?php echo $course->year(); ?></td>
                            <td data-name="eval_start"><?php echo $course->eval_start; ?></td>
                            <td data-name="eval_end"><?php echo $course->eval_end; ?></td>
                            <td data-name="start_date"><?php echo $course->start_date; ?></td>
                            <td data-name="end_date"><?php echo $course->end_date; ?></td>
                        </tr>
                        <?php $num += 1; endforeach; ?>
                    </tbody>
                </table>
                <input id="Submit" class="btn btn-primary" value="Submit" type="submit"
                       formaction="<?php echo base_url(); ?>upload/submission_postback"/>
                <input id="Cancel" class="btn btn-danger" value="Cancel" type="submit"
                       formaction="<?php echo base_url(); ?>upload/submission_postback"/>
                <input type="hidden" id="url" name="url" value="<?php echo base_url(); ?>"/>
            </form>
        </div>
    </div>
    <div class="col-md-3">
        <div id="sidebar">
            <h2>Directions</h2>
            <ul>
                <li>This page allows you to upload courses into the database for the current quarter.</li>
                <li>Check the boxes next to courses you want evaluated and click <span class="button-help">Submit</span>.</li>
            </ul>
            <h2>Notes</h2>
            <ul>
                <li>If selected, courses highlighted <span style="color: red">RED</span> will overwrite previous changes
                    made through the <strong>Manage Courses</strong> page.
                </li>
            </ul>
        </div>
    </div>
    <div class="col-md-1"></div>
</div>
</div>

<script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-table/1.7.0/bootstrap-table.min.js"></script>

<script>
    var $table = $('#table');
    $(function () {
        $table.bootstrapTable({
            height: 650
        });
        $(window).resize(function () {
            $table.bootstrapTable('resetView', {
                height: 650
            });
        });
    });
</script>
</body>
</html>