<?php
$pagetitle = 'Evaluations';
include 'header.php';
?>

<div class="row">
    <div class="col-md-8">
        <h1><i class="fa fa-eye accent"></i>Evaluations</h1>

        <div class="post">
            <table id="table"
                   data-toolbar="#toolbar"
                   data-search="true"
                   data-show-pagination-switch="true"
                   data-pagination="true"
                   data-show-footer="true"
                   data-page-size="50"
                   class="table table-hover table-condensed">
                <thead>
                <tr>
                    <th data-field="tag" data-sortable="true">Tag</th>
                    <th data-field="title" data-sortable="true">Title</th>
                    <th data-field="instructor" data-sortable="true">Instructor</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($courses as $course) : ?>
                    <tr>
                        <td data-name="tag"><a href="
                            <?php echo base_url(); ?>evaluations/index/<?php echo $course->section_id ?>/admin">
                                <?php echo $course->tag(true); ?></a></td>
                        <td data-name="title"><?php echo $course->title; ?></td>
                        <td data-name="instructor"><?php echo $course->instructorName(); ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <div class="col-md-4">
        <div id="sidebar">
            <h2>Directions</h2>
            <ul>
                <li>To view the evaluation questions for a particular course, click on one of the links.</li>
            </ul>
        </div>
    </div>
</div>
</div>
<script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-table/1.7.0/bootstrap-table.min.js"></script>
<script type="text/javascript">
    $(document).ready(function () {
        var $table = $('#table');
        $table.bootstrapTable({
            height: 400
        });
        $(window).resize(function () {
            $table.bootstrapTable('resetView', {
                height: 400
            });
        });
    });
</script>
</body>
</html>