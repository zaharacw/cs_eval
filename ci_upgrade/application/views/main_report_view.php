<?php
if (!function_exists('echoQuestionValues'))
{
    function formatAverage($avg)
    {
        return number_format((float)$avg, 2, '.', '');
    }

    function echoQuestionValues($q)
    {
        ?>
        <div class="question-info">
            <h3><?php echo $q['description']; ?></h3>
            <table class="table numbers">
                <thead>
                <tr>
                    <th>N/A</th>
                    <th>1</th>
                    <th>2</th>
                    <th>3</th>
                    <th>4</th>
                    <th>5</th>
                    <th>Average</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td><?php echo $q['answers'][0]; ?></td>
                    <td><?php echo $q['answers'][1]; ?></td>
                    <td><?php echo $q['answers'][2]; ?></td>
                    <td><?php echo $q['answers'][3]; ?></td>
                    <td><?php echo $q['answers'][4]; ?></td>
                    <td><?php echo $q['answers'][5]; ?></td>
                    <td><?php echo formatAverage($q['average']); ?></td>
                </tr>
                </tbody>
            </table>
        </div>
    <?php
    }

    function echoQuestionComments($q)
    {
        if (count($q['comments']) > 0) : ?>

            <div class="question-info">
                <h3><?php echo $q['description']; ?></h3>

                <div class="comments">
                    <?php foreach ($q['comments'] as $index => $comment) : ?>
                        <div <?php
                        if ($index + 1 < count($q['comments']))
                        {
                            echo 'class="bordered"';
                        }
                        ?>>&ldquo;<?php echo $comment; ?>&rdquo;</div>
                    <?php endforeach; ?>
                </div>
            </div>

        <?php
        endif;
    }

    function renderQuestionSection($title, $questions)
    {
        if (count($questions) != 0)
        {
            echo "<h2>$title</h2>";
            foreach ($questions as $q)
            {
                echoQuestionValues($q);
            }
        }
    }

    function renderTotalRow($name, $key, $totals, $averages)
    {
        ?>
        <tr>
            <th scope="row"><?php echo $name; ?></th>
            <td><?php echo $totals[$key][0]; ?></td>
            <td><?php echo $totals[$key][1]; ?></td>
            <td><?php echo $totals[$key][2]; ?></td>
            <td><?php echo $totals[$key][3]; ?></td>
            <td><?php echo $totals[$key][4]; ?></td>
            <td><?php echo $totals[$key][5]; ?></td>
            <td><?php echo formatAverage($averages[$key]); ?></td>
        </tr>
    <?php
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>css/bootstrap.min.css"/>
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>css/report.css"/>
    <link href='http://fonts.googleapis.com/css?family=Droid+Sans|Open+Sans:300' rel='stylesheet' type='text/css'>
    <title>Report</title>
</head>

<body>

<header>
    <div class="container">
        <h1><?php echo $info->tag(true) . ': ' . $info->title; ?></h1>

        <h3 id="instructor"><?php echo $info->instructorName() . ', ' . $info->termName() . ' ' . $info->year(); ?></h3>

        <?php if ($evalIncomplete) : ?>
            <h2 style="padding: 0; margin-top: 10px; margin-bottom: -6px; color: red; font-size: 15pt">
                [<?php echo date('m-d-Y'); ?>] EVALUATION PERIOD INCOMPLETE</h2>
        <?php endif; ?>
    </div>
</header>

<div class="container">
    <?php
    renderQuestionSection('Required Questions', $qRequired);
    renderQuestionSection('Instructor Questions', $qInstructor);
    renderQuestionSection('Departmental Questions', $qDepartmental);
    renderQuestionSection('Other Questions', $qOther);
    ?>

    <h2>Score Totals</h2>
    <table id="totals" class="table table-striped numbers">
        <thead>
        <tr>
            <th></th>
            <th>N/A</th>
            <th>1</th>
            <th>2</th>
            <th>3</th>
            <th>4</th>
            <th>5</th>
            <th>Average</th>
        </tr>
        </thead>
        <tbody>
        <?php
        renderTotalRow('Required', 0, $totals, $averages);
        renderTotalRow('Instructor', 2, $totals, $averages);
        renderTotalRow('Departmental', 3, $totals, $averages);
        renderTotalRow('Other', 1, $totals, $averages);
        renderTotalRow('Total', 'overall', $totals, $averages);
        ?>
        </tbody>
    </table>

    <h2>Comments</h2>

    <?php
    $allQuestions = array_merge($qRequired, $qInstructor, $qDepartmental, $qOther);
    foreach ($allQuestions as $q)
    {
        echoQuestionComments($q);
    }
    ?>


    <?php
    if (count($generalComments) == 0)
    {
        echo '<h3>Overall</h3><em>None.</em>';
    }
    else
    {
        echo '<div class="question-info"><h3>Overall</h3><div class="comments">';
        foreach ($generalComments as $index => $comment)
        {
            if ($index + 1 < count($generalComments))
            {
                echo "<div class='bordered'>&ldquo;$comment&rdquo;</div>";
            }
            else
            {
                echo "<div>&ldquo;$comment&rdquo;</div>";
            }
        }
        echo '</div></div>';
    }
    ?>
</div>

</body>
</html>