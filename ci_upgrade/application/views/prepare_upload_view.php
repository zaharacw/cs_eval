<?php
$pagetitle = 'Information Upload';
include 'header.php';
?>

<div class="row">
    <div class="col-md-8">
        <h1><i class="fa fa-cloud-upload accent"></i>Upload
            <small><?php echo $term; ?> Term, <?php echo date("Y"); ?> &mdash; <?php echo $subjects ?></small>
        </h1>
        <div class="post">
            <form id="uploadRequest" action="<?php echo base_url(); ?>upload/retrieval_postback">
                <input class="btn btn-primary btn-lg" type="submit" value="Prepare the Database"/>
            </form>
        </div>
    </div>
    <div class="col-md-4">
        <div id="sidebar">
            <h2>Directions</h2>
            <ul>
                <li>Just click the big button.</li>
            </ul>
        </div>
    </div>
</div>
</div>
</body>
</html>