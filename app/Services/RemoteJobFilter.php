<?php
namespace App\Services;

/** Shared by the homepage count and public job search to avoid count/filter drift. */
final class RemoteJobFilter
{
    public static function apply($query, $db)
    {
        $columns = [];
        foreach (['location_type', 'work_arrangement'] as $column) {
            if ($db->fieldExists($column, 'jobs')) $columns[] = 'jobs.' . $column;
        }
        $query->groupStart();
        if ($columns) {
            foreach ($columns as $index => $column) {
                $index === 0 ? $query->where($column, 'remote') : $query->orWhere($column, 'remote');
            }
        } else {
            // Only legacy schemas without structured work-arrangement fields.
            $query->like('jobs.title', 'remote')->orLike('jobs.skills', 'remote');
        }
        return $query->groupEnd();
    }
}
