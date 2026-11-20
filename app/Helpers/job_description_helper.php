<?php

/** Convert saved rich text for plain-text editing; escape separately on output. */
function job_description_text(?string $description): string
{
    $text = $description ?? '';
    if (!preg_match('/<\/?[a-z][^>]*>/i', $text)) {
        return $text;
    }

    $text = preg_replace('~<(script|style)\b[^>]*>.*?</\1\s*>~is', '', $text);
    $text = preg_replace('~<br\s*/?>~i', "\n", $text);
    $text = preg_replace('~<li\b[^>]*>~i', '• ', $text);
    $text = preg_replace('~</(?:p|div|h[1-6]|ul|ol)\s*>~i', "\n\n", $text);
    $text = preg_replace('~</li\s*>~i', "\n", $text);
    $text = html_entity_decode(strip_tags($text), ENT_QUOTES | ENT_HTML5, 'UTF-8');
    $text = str_replace(["\r\n", "\r", "\xc2\xa0"], ["\n", "\n", ' '], $text);
    $text = preg_replace('/[\t ]+\n/', "\n", $text);
    return trim(preg_replace('/\n{3,}/', "\n\n", $text));
}
