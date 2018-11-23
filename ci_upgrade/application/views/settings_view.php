<?php
$pagetitle = 'Settings';
include 'header.php';
?>

<div class="row">
    <div class="col-md-8">
        <?php if ($success != null) : ?>
            <div class="alert alert-success" role="alert"><?php echo $success; ?></div>
        <?php endif; ?>

        <h1><i class="fa fa-gears accent"></i>Settings</h1>

        <form action="<?php echo base_url('settings'); ?>/modify_settings" method="post">
            <div class="form-group">
                <label for="mainMessage">Welcome Message</label>
                <textarea id="mainMessage" name="mainMessage" class="form-control" rows="5"
                          placeholder="Lorem ipsum dolor sit amet, consectetur adipiscing elit. Praesent a libero nec felis mollis pretium sit amet eget augue. Praesent in molestie nisl. Mauris sed tortor leo. Cras et rhoncus augue, nec vulputate sem."><?php echo $mainMessage; ?></textarea>
            </div>
            <div class="form-group">
                <label for="evalMessage">Evaluation Message</label>
                <textarea id="evalMessage" name="evalMessage" class="form-control" rows="5"
                          placeholder="Lorem ipsum dolor sit amet, consectetur adipiscing elit. Praesent a libero nec felis mollis pretium sit amet eget augue. Praesent in molestie nisl. Mauris sed tortor leo. Cras et rhoncus augue, nec vulputate sem."><?php echo $evalMessage; ?></textarea>
            </div>
            <div class="form-group">
                <label>
                    <input type="checkbox" name="developerMode" value="enabled" <?php if ($developerMode)
                    {
                        echo 'checked';
                    } ?>>
                    DEVELOPER MODE
                </label>
            </div>
            <button type="submit" class="btn btn-primary">Save</button>
        </form>
    </div>
    <div class="col-md-4">
        <div id="sidebar">
            <h2>Directions</h2>
            <ul>
                <li>The <strong>welcome message</strong> will be displayed at the top of the student homepage, where
                    students may select from a list of their current courses.
                </li>
                <li>The <strong>evaluation message</strong> is shown at the top of each evaluation.</li>
                <li>Enabling <strong>developer mode</strong> will trigger developer-specific functionality. Nothing will
                    <em>break</em>, per se, but this will allow for some potentially unwanted behavior.
                </li>
            </ul>
        </div>
    </div>
</div>
</div>
</body>
</html>