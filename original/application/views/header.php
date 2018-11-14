<?php
if (!isset($pagetitle))
{
    $pagetitle = 'EWU Online Course Evaluator';
}
?>

    <!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="theme-color" content="#951010">
        
        <link rel="shortcut icon" href="<?php echo base_url(); ?>img/favicon.ico"/>
        <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>css/main.css"/>
		
		<?php       

		if($pagetitle != "Course Evaluations" && $pagetitle !='EWU Online Course Evaluator'){
		 echo '<link rel="stylesheet" type="text/css" href="'; echo base_url(); echo 'css/bootstrap-table.min.css"/>';
	     echo      '<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-table/1.7.0/bootstrap-table.min.css">';
	   }
	   
	   ?>
        <link href='http://fonts.googleapis.com/css?family=Droid+Sans|Open+Sans:300' rel='stylesheet' type='text/css'>
        <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">

        <script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.0/jquery.min.js"></script>
        <script src="<?php echo base_url(); ?>script/bootstrap.min.js"></script>

        <title><?php echo $pagetitle; ?></title>
        <?php
        if (isset($scripts) && is_array($scripts))
        {
            foreach ($scripts as $file)
            {
                echo '<script src="' . base_url() . 'script/' . $file . '"></script>';
            }
        }

        if (isset($stylesheets) && is_array($stylesheets))
        {
            foreach ($stylesheets as $sheet)
            {
                echo '<link href="' . $sheet . '" rel="stylesheet" type="text/css">';
            }
        }
        ?>
    </head>

<body>

    <header>
        <div class="container">
            <a href="<?php echo base_url(); ?>">
                <img id="eagle" src="<?php echo base_url(); ?>img/eagle_white.png" alt="EWU"/>

                <div class="title">
                    <div id="eval-title">Online Course Evaluator</div>
                    <div id="dept-subtitle">Computer Science Department</div>
                </div>
            </a>
        </div>
    </header>

    <div class="container">
        <?php require_once "view_helper.php"; ?>
    </div>

<?php if (!isset($fluid) || $fluid == false) : ?>
    <div class="container">
<?php else: ?>
    <div class="container-fluid">
<?php endif; ?>
