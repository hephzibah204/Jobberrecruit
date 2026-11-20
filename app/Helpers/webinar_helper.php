<?php

function webinar_topics(string $content): array
{
    $patterns = [
        'interview'=>'/interview|interviewing/i', 'cv'=>'/\bcv\b|resum[eé]/iu',
        'salary'=>'/salary|negotiat|compensation/i', 'tech'=>'/\btech\b|software|coding|programming|data science/i',
        'linkedin'=>'/linkedin|personal brand/i', 'remote'=>'/remote|work from home/i',
    ];
    $topics = [];
    foreach ($patterns as $key=>$pattern) if (preg_match($pattern, $content)) $topics[] = $key;
    return $topics ?: ['general'];
}

function webinar_calendar_url(object $webinar): string
{
    $start = new DateTimeImmutable($webinar->scheduled_at, new DateTimeZone(config('App')->appTimezone));
    $start = $start->setTimezone(new DateTimeZone('UTC'));
    return 'https://calendar.google.com/calendar/render?' . http_build_query([
        'action'=>'TEMPLATE','text'=>$webinar->title,
        'dates'=>$start->format('Ymd\THis\Z') . '/' . $start->modify('+90 minutes')->format('Ymd\THis\Z'),
        'details'=>'JobberRecruit Career Webinar','location'=>'Online',
    ], '', '&', PHP_QUERY_RFC3986);
}
