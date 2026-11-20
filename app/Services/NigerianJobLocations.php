<?php
namespace App\Services;

/** Public geographic options only; never changes stored records or assignments. */
final class NigerianJobLocations
{
    private const STATES = [
        'abia', 'adamawa', 'akwa ibom', 'anambra', 'bauchi', 'bayelsa', 'benue',
        'borno', 'cross river', 'delta', 'ebonyi', 'edo', 'ekiti', 'enugu',
        'gombe', 'imo', 'jigawa', 'kaduna', 'kano', 'katsina', 'kebbi', 'kogi',
        'kwara', 'lagos', 'nasarawa', 'niger', 'ogun', 'ondo', 'osun', 'oyo',
        'plateau', 'rivers', 'sokoto', 'taraba', 'yobe', 'zamfara',
    ];

    public static function key(string $name): ?string
    {
        $name = strtolower(trim($name));
        $name = trim(preg_replace('/[^a-z]+/', ' ', $name));
        $name = preg_replace('/ state$/', '', $name);
        if (in_array($name, ['fct', 'abuja', 'abuja fct', 'fct abuja', 'federal capital territory', 'federal capital territory abuja', 'abuja federal capital territory'], true)) return 'fct';
        return in_array($name, self::STATES, true) ? $name : null;
    }

    public static function filter(array $locations): array
    {
        $seen = []; $result = [];
        foreach ($locations as $location) {
            $key = self::key((string) ($location->name ?? ''));
            if ($key === null || isset($seen[$key])) continue;
            $seen[$key] = true;
            $result[] = $location;
        }
        return $result;
    }
}
