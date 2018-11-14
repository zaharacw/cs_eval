<?php
$pagetitle = 'Course Evaluations';
include 'header.php';
?>

<div class="row">
    <div class="col-md-12">
        <?php if ($alert != null) : ?>
            <div class="alert <?php echo $alert_type; ?>" role="alert"><?php echo $alert; ?></div>
        <?php endif; ?>
        <h1><i class="fa fa-flask accent"></i>Instructor Home</h1>
        <p><?php echo $message; ?></p>
    </div>
</div>

<?php if (count($courses) == 0) : ?>
    <p><em>There are currently no courses to show.</em></p>
<?php else : ?>
<div class="table-responsive">
    <table id="evaluations-table" class="table">
        <thead>
        <tr>
            <th>Course Number</th>
            <th>Title</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
            <?php foreach ($courses as $course) 
            {
                $previewUrl = base_url() . 'evaluations/index/' . $course->section_id.'/instructor';
                $editUrl = '#';
                $isAvailable = $course->isAvailableForEvaluation(false);
                ?>
                <tr>
                    <td><?php echo $course->tag(true) ?></td>
                    <td><?php echo $course->title?></td>
                    <td><?php echo $isAvailable ? 'Evaluation in progress' : 'Editable'; ?> </td>
                    <td class="actions">
                        <div class="btn-group" role="group">
                            <a class='btn btn-default' title="Preview" href="<?php echo $previewUrl; ?>"><i class="fa fa-eye"></i></a>
                            <a class='btn btn-default' title="Edit" href="<?php echo $editUrl; ?>" <?php
                            if ($isAvailable) { echo 'disabled'; } ?>><i class="fa fa-edit"></i></a>
                        </div>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>
<?php endif; ?>
</div>
</body>
</html>