<?php

if (!function_exists('is_logged_in')) {
    function is_logged_in()
    {
        return service('auth')->check();
    }
}

if (!function_exists('current_user')) {
    function current_user()
    {
        return service('auth')->user();
    }
}

if (!function_exists('is_employer')) {
    function is_employer()
    {
        $user = current_user();
        return $user && $user->user_type === 'employer';
    }
}

if (!function_exists('is_job_seeker')) {
    function is_job_seeker()
    {
        $user = current_user();
        return $user && $user->user_type === 'job_seeker';
    }
}

if (!function_exists('has_permission')) {
    function has_permission($userType)
    {
        $user = current_user();
        return $user && $user->user_type === $userType;
    }
}
