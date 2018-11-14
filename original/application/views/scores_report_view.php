<?php

if (!function_exists('echoQuestionData'))
{
    function formatAverage($avg)
    {
        return number_format((float)$avg, 2, '.', '');
    }

    function echoQuestionData($q)
    {
        echo $q['description'] . ',';
        echo $q['typeName'] . ',';

        for ($i = 0; $i <= 5; $i++)
        {
            echo $q['answers'][$i] . ',';
        }

        echo formatAverage($q['average']) . "\r\n";
    }

    function renderTotalRow($name, $key, $totals, $averages)
    {
        echo ',' . $name . ',';

        for ($i = 0; $i <= 5; $i++)
        {
            echo $totals[$key][$i] . ',';
        }

        echo formatAverage($averages[$key]) . "\r\n";
    }

    function renderQuestionSection($questions)
    {
        if (count($questions) != 0)
        {
            foreach ($questions as $q)
            {
                echoQuestionData($q);
            }
        }
    }
}

echo $info->tag() . '-' . $info->niceSection() . ': ' . $info->title . "\r\n";
echo '"' . $info->instructorName() . ', ' . $info->termName() . ' ' . $info->year() . "\"\r\n";

if ($evalIncomplete)
{
    $d = date('m-d-Y');
    echo "[$d] EVALUATION PERIOD INCOMPLETE\r\n";
}

echo "\r\nQuestion,Type,N/A,1,2,3,4,5,Average\r\n";

renderQuestionSection($qRequired);
renderQuestionSection($qInstructor);
renderQuestionSection($qDepartmental);
renderQuestionSection($qOther);

echo "\r\n\r\nTotals:\r\n";
echo ",Type,N/A,1,2,3,4,5,Average\r\n";

renderTotalRow('Required', 0, $totals, $averages);
renderTotalRow('Instructor', 2, $totals, $averages);
renderTotalRow('Departmental', 3, $totals, $averages);
renderTotalRow('Other', 1, $totals, $averages);
renderTotalRow('Total', 'overall', $totals, $averages);