<?php


/**
 * @throws Exception
 */
function format_timestamp($timestamp): string
{
    $now = new DateTime();
    $article_time = new DateTime($timestamp);

    $interval = $now->diff($article_time);

    if ($interval->d > 0) {
        $days = $interval->d == 1 ? "day" : "days";
        return $interval->format("%a $days ago, " . $article_time->format("d/m/Y"));
    } elseif ($interval->h > 0) {
        $hours = $interval->h == 1 ? "hour" : "hours";
        return $interval->format("%h $hours ago, " . $article_time->format("d/m/Y"));
    } else {
        $minutes = $interval->i == 1 ? "minute" : "minutes";
        return $interval->format("%i $minutes ago, " . $article_time->format("d/m/Y"));
    }
}

function get_substring($content, $max_length = 200): string
{
    $substring = mb_substr($content, 0, $max_length);

    if (mb_strlen($content) > $max_length) {
        $substring .= "...";
    }

    return $substring;
}
