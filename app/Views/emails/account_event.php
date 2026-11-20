<!doctype html>
<html lang="en"><head><meta charset="UTF-8"><title><?= esc($event_title) ?></title></head>
<body style="font-family:Arial,sans-serif;color:#182230;line-height:1.6;max-width:640px;margin:32px auto;padding:24px">
    <p>JobberRecruit</p>
    <h1 style="font-size:24px"><?= esc($event_title) ?></h1>
    <p><?= esc($event_message) ?></p>
    <?php if (!empty($event_details)): ?>
        <dl><?php foreach ($event_details as $label => $value): ?>
            <dt style="font-weight:bold"><?= esc($label) ?></dt><dd style="margin:0 0 12px"><?= esc((string) $value) ?></dd>
        <?php endforeach; ?></dl>
    <?php endif; ?>
    <p><a href="<?= esc($action_url, 'attr') ?>">View account</a></p>
</body></html>
