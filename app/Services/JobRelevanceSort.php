<?php
namespace App\Services;

final class JobRelevanceSort
{
    public static function apply($query, $db, ?string $keywords)
    {
        $keywords = trim((string) $keywords);
        if ($keywords !== '') {
            $exact = $db->escape($keywords);
            $contains = $db->escape('%' . $db->escapeLikeString($keywords) . '%');
            $prefix = $db->escape($db->escapeLikeString($keywords) . '%');
            $score = "(CASE WHEN jobs.title = $exact THEN 20 WHEN jobs.title LIKE $prefix ESCAPE '!' THEN 10 WHEN jobs.title LIKE $contains ESCAPE '!' THEN 5 ELSE 0 END"
                . " + CASE WHEN jobs.skills LIKE $contains ESCAPE '!' THEN 3 ELSE 0 END"
                . " + CASE WHEN jobs.description LIKE $contains ESCAPE '!' THEN 1 ELSE 0 END)";
            $query->orderBy($score, 'DESC', false);
        }
        return $query->orderBy('jobs.created_at', 'DESC')->orderBy('jobs.id', 'DESC');
    }
}
