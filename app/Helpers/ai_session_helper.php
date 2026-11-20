<?php
function ai_history_valid($history): bool
{
    if (!is_array($history) || !$history || count($history) > 100) return false;
    foreach ($history as $turn) {
        if (!is_array($turn) || !in_array($turn['sender'] ?? '', ['user','model','bot'], true)
            || !is_string($turn['message'] ?? null) || strlen($turn['message']) > 12000) return false;
    }
    return strlen(json_encode($history)) <= 200000;
}

function ai_negotiation_errors(array $data): array
{
    $errors = [];
    foreach (['confidence_score','persuasion_score','overall_score'] as $field) {
        if (!is_numeric($data[$field] ?? null) || $data[$field] < 0 || $data[$field] > 100) $errors[] = 'Scores must be between 0 and 100.';
    }
    foreach (['base_salary_offered','target_salary','final_salary'] as $field) {
        if (!is_numeric($data[$field] ?? null) || !is_finite((float) $data[$field]) || $data[$field] < 0 || $data[$field] > 1e10) $errors[] = 'Choose valid salary amounts.';
    }
    if (!ctype_digit((string) ($data['rounds_completed'] ?? '')) || (int) $data['rounds_completed'] < 1 || (int) $data['rounds_completed'] > 100) $errors[] = 'Invalid completed round count.';
    $transcriptJson = $data['transcript_json'] ?? '';
    $evaluationJson = $data['evaluation_json'] ?? '';
    if (!is_string($transcriptJson) || strlen($transcriptJson) > 200000) {
        $errors[] = 'A valid conversation transcript is required.';
        $transcriptJson = '';
    }
    if (!is_string($evaluationJson) || strlen($evaluationJson) > 20000) {
        $errors[] = 'Invalid practice evaluation.';
        $evaluationJson = '';
    }
    $transcript = json_decode($transcriptJson, true);
    if (!ai_history_valid($transcript)) $errors[] = 'A valid conversation transcript is required.';
    if (!is_array(json_decode($evaluationJson, true))) $errors[] = 'Invalid practice evaluation.';
    return array_unique($errors);
}
