<?php

if (!function_exists('echoQuestionData'))
{
    function formatAverage($avg)
    {
        return number_format((float)$avg, 2, '.', '');
    }

    function echoQuestionData($q)
    {
        foreach ($q['comments'] as $comment)
        {
            $escaped = '"' . str_replace('"', '""', $comment) . '"';
            echo $q['description'] . ',' . $q['typeName'] . ',' . $escaped . "\r\n";
        }
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

echo "\r\nQuestion,Type,Comment\r\n";

renderQuestionSection($qRequired);
renderQuestionSection($qInstructor);
renderQuestionSection($qDepartmental);
renderQuestionSection($qOther);

echo "\r\n\r\nGeneral Comments:\r\n";

foreach ($generalComments as $comment)
{
    echo ",,\"$comment\"\r\n";
}
