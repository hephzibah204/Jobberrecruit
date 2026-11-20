<?php

namespace App\Services;

class AssessmentGrader
{
    public static function optionIds($selected): array
    {
        if (!is_array($selected)) {
            throw new \InvalidArgumentException('Select an answer option.');
        }
        $ids = [];
        foreach ($selected as $value) {
            if (filter_var($value, FILTER_VALIDATE_INT) === false || (int) $value < 1) {
                throw new \InvalidArgumentException('Invalid answer option.');
            }
            $ids[] = (int) $value;
        }
        if (count(array_unique($ids)) !== count($ids)) {
            throw new \InvalidArgumentException('Duplicate answer option.');
        }
        sort($ids);
        return $ids;
    }

    public static function correct(array $selected, array $options): bool
    {
        try {
            $selected = self::optionIds($selected);
        } catch (\InvalidArgumentException $e) {
            return false;
        }
        $valid = array_map('intval', array_column($options, 'id'));
        $correct = array_map('intval', array_column(array_filter($options, static fn ($o) => !empty($o['is_correct'])), 'id'));
        sort($correct);
        return $correct !== [] && array_diff($selected, $valid) === [] && $selected === $correct;
    }
}
