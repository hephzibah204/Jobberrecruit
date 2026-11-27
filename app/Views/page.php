<?= $this->extend('templates/base') ?>

<?= $this->section('schema') ?>
<?php if (is_file(APPPATH . 'Views/partials/pages/' . $page->slug . '_schema.php')): ?>
<?= $this->include('partials/pages/' . $page->slug . '_schema') ?>
<?php else: ?>
<script type="application/ld+json">
<?= json_encode([
    '@context' => 'https://schema.org',
    '@type'    => 'WebPage',
    'name'     => $page->title,
    'url'      => current_url(),
    'publisher' => [
        '@type' => 'Organization',
        'name'  => 'JobberRecruit',
        'logo'  => [
            '@type' => 'ImageObject',
            'url'   => base_url('images/logo.png'),
        ],
    ],
], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) ?>
</script>
<?php endif ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?= $page->content ?>
<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<?php if (is_file(APPPATH . 'Views/partials/pages/' . $page->slug . '_styles.php')): ?>
<?= $this->include('partials/pages/' . $page->slug . '_styles') ?>
<?php endif ?>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<?php if (is_file(APPPATH . 'Views/partials/pages/' . $page->slug . '_scripts.php')): ?>
<?= $this->include('partials/pages/' . $page->slug . '_scripts') ?>
<?php endif ?>
<?= $this->endSection() ?>
