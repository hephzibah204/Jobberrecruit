<?php

/** Sanitize AI review markup while preserving the explicit Apply Suggestion contract. */
function safe_ai_html(string $html): string
{
    if (!class_exists(\DOMDocument::class)) return htmlspecialchars(strip_tags($html), ENT_QUOTES, 'UTF-8');
    $dom = new \DOMDocument();
    $previous = libxml_use_internal_errors(true);
    $dom->loadHTML('<?xml encoding="UTF-8"><div>' . $html . '</div>', LIBXML_HTML_NODEFDTD | LIBXML_NONET);
    libxml_clear_errors();
    libxml_use_internal_errors($previous);
    $walk = static function ($node) use (&$walk): void {
        foreach (iterator_to_array($node->childNodes) as $child) {
            if ($child instanceof \DOMComment) { $node->removeChild($child); continue; }
            if (!$child instanceof \DOMElement) continue;
            $tag = strtolower($child->tagName);
            if (in_array($tag, ['script','style','iframe','object','embed','svg','math','form','input','img'], true)) { $node->removeChild($child); continue; }
            $walk($child);
            if (!in_array($tag, ['div','p','span','strong','b','em','i','br','ul','ol','li','h2','h3','h4','button'], true)) {
                while ($child->firstChild) $node->insertBefore($child->firstChild, $child);
                $node->removeChild($child); continue;
            }
            foreach (iterator_to_array($child->attributes) as $attribute) {
                $name = strtolower($attribute->name);
                if ($name === 'class' && preg_match('/^[a-zA-Z0-9_\-\s]*$/D', $attribute->value)) continue;
                if ($tag === 'button' && $name === 'data-suggestion') {
                    $child->setAttribute($name, strip_tags($attribute->value));
                    continue;
                }
                $child->removeAttribute($attribute->name);
            }
            if ($tag === 'button') $child->setAttribute('type', 'button');
        }
    };
    $body = $dom->getElementsByTagName('body')->item(0);
    if (!$body) return '';
    $walk($body);
    return implode('', array_map(fn ($node) => $dom->saveHTML($node), iterator_to_array($body->childNodes)));
}
