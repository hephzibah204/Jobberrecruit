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

/** Render a saved description as HTML: stored rich text passes through untouched; plain text becomes paragraphs (blank-line separated), single line breaks become <br>, and consecutive bullet lines become a list. */
function job_description_html(?string $description): string
{
    $text = $description ?? '';
    if (trim($text) === '') {
        return '';
    }
    if (preg_match('/<\/?[a-z][^>]*>/i', $text)) {
        return $text;
    }

    $text = str_replace(["\r\n", "\r"], "\n", $text);
    $html = '';
    foreach (preg_split('/\n{2,}/', trim($text)) as $block) {
        $lines = array_values(array_filter(array_map('trim', explode("\n", $block)), static fn ($line) => $line !== ''));
        if ($lines === []) {
            continue;
        }
        $isList = count($lines) > 1;
        foreach ($lines as $line) {
            if (!preg_match('/^(?:\x{2022}|-|\*)\s+/u', $line)) {
                $isList = false;
                break;
            }
        }
        if ($isList) {
            $items = array_map(static fn ($line) => '<li>' . esc(preg_replace('/^(?:\x{2022}|-|\*)\s+/u', '', $line)) . '</li>', $lines);
            $html .= '<ul>' . implode('', $items) . '</ul>';
        } else {
            $html .= '<p>' . implode('<br>', array_map('esc', $lines)) . '</p>';
        }
    }
    return $html;
}
