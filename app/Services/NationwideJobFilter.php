<?php

namespace App\Services;

use CodeIgniter\Database\BaseBuilder;

/** Shared nationwide matching for counts, listings, and job alerts. */
final class NationwideJobFilter
{
    public static function apply($query)
    {
        return $query->groupStart()
            ->whereIn('jobs.state_id', static function (BaseBuilder $states) {
                return $states->select('id')->from('states')
                    ->groupStart()
                        ->where('slug', 'nigeria-nationwide')
                        ->orLike('name', 'nationwide')
                        ->orWhere('name', 'Nigeria')
                    ->groupEnd();
            })
            ->orLike('jobs.title', 'nationwide')
            ->groupEnd();
    }
}
