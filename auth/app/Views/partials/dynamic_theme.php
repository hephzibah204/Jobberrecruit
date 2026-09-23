<?php
/**
 * Dynamic Theme Injector
 * Reads custom colors from the database settings and overrides the default variables.css.
 */

$themeConfigs = [
    'primary_color'   => ['--primary-color', '--ai-accent', '--ai-dark-bg'],
    'primary_hover'   => ['--primary-hover', '--ai-accent-hover'],
    'secondary_color' => ['--secondary-color', '--accent-gold'],
    'secondary_hover' => ['--secondary-hover'],
    'success_color'   => ['--success-color'],
    'danger_color'    => ['--danger-color'],
    'warning_color'   => ['--warning-color'],
    'info_color'      => ['--info-color'],
    'text_main'       => ['--text-main'],
    'text_muted'      => ['--text-muted'],
    'bg_dark_mode'    => ['--bg-dark-mode'],
];

$dynamicStyles = [];

foreach ($themeConfigs as $settingKey => $cssVariables) {
    $dbValue = setting('Theme.' . $settingKey);
    if (!empty($dbValue)) {
        foreach ($cssVariables as $var) {
            $dynamicStyles[] = "{$var}: {$dbValue} !important;";
        }
    }
}
?>
<?php if (!empty($dynamicStyles)): ?>
<!-- Dynamic Admin Theme Override -->
<style id="dynamic-admin-theme">
    :root {
        <?= implode("\n        ", $dynamicStyles) ?>
    }
</style>
<?php endif; ?>
