<?php
$pagetitle = 'Course Evaluations';
include 'header.php';
?>

<div class="row">
    <div class="col-md-12">
        <?php if ($alert != null) : ?>
            <div class="alert <?php echo $alert_type; ?>" role="alert"><?php echo $alert; ?></div>
        <?php endif; ?>
        <h1><i class="fa fa-graduation-cap accent"></i>Course Evaluations</h1>
        <p><?php echo $message; ?></p>
    </div>
</div>

<div class="row">
  <div class="col-md-12">
    <?php if ($courses == null || count($courses) == 0) : ?>
        <div class="panel panel-default">
          <div class="panel-heading">
            <h3 class="panel-title">
                <i class="fa fa-exclamation-triangle accent"></i>No courses to evaluate&hellip;
            </h3>
          </div>
          <div class="panel-body">
            Check back later.
          </div>
        </div>
    <?php else : ?>
        <div class="list-group">
    <?php
        foreach ($courses as $course) :
            $courseUrl = base_url() . 'evaluations/index/' . $course->section_id;
            $status = $course->status($student_id);

            $elemClass = '';
            $link = true;

            if ($status == 'Submitted')
            {
                $elemClass = 'list-group-item-success';
                $link = false;
            }
            elseif ($status == 'Saved')
            {
                $elemClass = 'list-group-item-warning';
            }
            elseif ($status == 'Unavailable')
            {
                $elemClass = 'disabled';
                $link = false;
            }

            if ($link) : ?>
            <a href="<?php echo $courseUrl; ?>" class="list-group-item <?php echo $elemClass; ?>">
            <?php else : ?>
            <span class="list-group-item <?php echo $elemClass; ?>">
            <?php endif; ?>

                <h4 class="list-group-item-heading">
                    <?php echo $course->title; ?>
                </h4>
                <span class="pull-right">
                    <strong>
                        <?php echo $course->status($student_id); ?>
                    </strong>
                </span>
                <p class="list-group-item-text">
                    <?php echo $course->tag(true); ?>
                    <br>
                    <?php echo $course->instructorName(); ?>
                </p>
            
            <?php if ($link) : ?></a><?php else : ?></span>
            <?php endif; endforeach; ?>
    </div>
    <?php endif; ?>
  </div>
</div>
</div>
</body>
</html>