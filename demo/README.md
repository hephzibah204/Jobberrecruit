# CodeIgniter 4 Application Starter

## What is CodeIgniter?

CodeIgniter is a PHP full-stack web framework that is light, fast, flexible and secure.
More information can be found at the [official site](https://codeigniter.com).

This repository holds a composer-installable app starter.
It has been built from the
[development repository](https://github.com/codeigniter4/CodeIgniter4).

More information about the plans for version 4 can be found in [CodeIgniter 4](https://forum.codeigniter.com/forumdisplay.php?fid=28) on the forums.

You can read the [user guide](https://codeigniter.com/user_guide/)
corresponding to the latest version of the framework.

## Installation & updates

`composer create-project codeigniter4/appstarter` then `composer update` whenever
there is a new release of the framework.

When updating, check the release notes to see if there are any changes you might need to apply
to your `app` folder. The affected files can be copied or merged from
`vendor/codeigniter4/framework/app`.

## Setup

Copy `env` to `.env` and tailor for your app, specifically the baseURL
and any database settings.

## Important Change with index.php

`index.php` is no longer in the root of the project! It has been moved inside the *public* folder,
for better security and separation of components.

This means that you should configure your web server to "point" to your project's *public* folder, and
not to the project root. A better practice would be to configure a virtual host to point there. A poor practice would be to point your web server to the project root and expect to enter *public/...*, as the rest of your logic and the
framework are exposed.

**Please** read the user guide for a better explanation of how CI4 works!

## Repository Management

We use GitHub issues, in our main repository, to track **BUGS** and to track approved **DEVELOPMENT** work packages.
We use our [forum](http://forum.codeigniter.com) to provide SUPPORT and to discuss
FEATURE REQUESTS.

This repository is a "distribution" one, built by our release preparation script.
Problems with it can be raised on our forum, or as issues in the main repository.

## Server Requirements

PHP version 8.1 or higher is required, with the following extensions installed:

- [intl](http://php.net/manual/en/intl.requirements.php)
- [mbstring](http://php.net/manual/en/mbstring.installation.php)

> [!WARNING]
> - The end of life date for PHP 7.4 was November 28, 2022.
> - The end of life date for PHP 8.0 was November 26, 2023.
> - If you are still using PHP 7.4 or 8.0, you should upgrade immediately.
> - The end of life date for PHP 8.1 will be December 31, 2025.

Additionally, make sure that the following extensions are enabled in your PHP:

- json (enabled by default - don't turn it off)
- [mysqlnd](http://php.net/manual/en/mysqlnd.install.php) if you plan to use MySQL
- [libcurl](http://php.net/manual/en/curl.requirements.php) if you plan to use the HTTP\CURLRequest library


<!-- Sample: Generating Job Alerts -->
```php
$jobSeeker = model(\App\Models\JobSeekerModel::class)->find($id);

// 1. Get their preferences
$industries = $jobSeeker->getIndustries();
$categories = $jobSeeker->getCategories();
$alerts     = $jobSeeker->getJobAlerts();

// 2. Example query: find new jobs matching preferences
$jobs = model(\App\Models\JobModel::class)
    ->select('jobs.*')
    ->join('job_categories', 'jobs.category_id = job_categories.id')
    ->whereIn('jobs.category_id', array_column($categories, 'id'))
    ->where('jobs.status', 'open')
    ->findAll();

// 3. Optionally filter by keyword/location/frequency
foreach ($alerts as $alert) {
    if ($alert->keyword) {
        $jobs = array_filter($jobs, fn($j) =>
            stripos($j->title, $alert->keyword) !== false
        );
    }
    if ($alert->location_id) {
        $jobs = array_filter($jobs, fn($j) =>
            $j->state_id == $alert->location_id
        );
    }
}


// 🔹 Example Usage

$jobAlertModel = model(\App\Models\JobAlertModel::class);

// Create new alert
$alert = new \App\Entities\JobAlert([
    'job_seeker_id' => 5,
    'keyword'       => 'backend developer',
    'location_id'   => 2,
    'frequency'     => 'weekly'
]);

$jobAlertModel->save($alert);

// Fetch alerts for a seeker
$alerts = $jobAlertModel->where('job_seeker_id', 5)->findAll();

foreach ($alerts as $alert) {
    echo "Keyword: {$alert->keyword}, Frequency: {$alert->frequency}";
}

// 🔹 JobSeeker Entity (with getMatchingJobs)
$jobSeeker = model(\App\Models\JobSeekerModel::class)->find(5);

// Get matching jobs
$jobs = $jobSeeker->getMatchingJobs();

foreach ($jobs as $job) {
    echo $job->title . " (" . $job->job_type . ")\n";
}

```
