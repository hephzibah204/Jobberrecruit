<?php
$pager->setSurroundCount(2);
$currentPage = $pager->getCurrentPageNumber();
$pageCount   = $pager->getPageCount();
?>
<?php if ($pageCount > 1): ?>
    <!-- Prev -->
    <?php if ($pager->hasPreviousPage()): ?>
        <a href="<?= $pager->getPreviousPage() ?>" class="pager-btn" aria-label="Previous page">
            <svg aria-hidden="true" style="width:13px;height:13px"><use href="#i-arrow-l"/></svg>
        </a>
    <?php else: ?>
        <button class="pager-btn" aria-disabled="true" aria-label="Previous page" type="button">
            <svg aria-hidden="true" style="width:13px;height:13px"><use href="#i-arrow-l"/></svg>
        </button>
    <?php endif; ?>

    <!-- Page Numbers -->
    <?php foreach ($pager->links() as $link): ?>
        <a href="<?= $link['uri'] ?>" class="pager-btn <?= $link['active'] ? 'on' : '' ?>">
            <?= esc($link['title']) ?>
        </a>
    <?php endforeach; ?>

    <!-- Next -->
    <?php if ($pager->hasNextPage()): ?>
        <a href="<?= $pager->getNextPage() ?>" class="pager-btn" aria-label="Next page">
            <svg aria-hidden="true" style="width:13px;height:13px"><use href="#i-arrow-r"/></svg>
        </a>
    <?php else: ?>
        <button class="pager-btn" aria-disabled="true" aria-label="Next page" type="button">
            <svg aria-hidden="true" style="width:13px;height:13px"><use href="#i-arrow-r"/></svg>
        </button>
    <?php endif; ?>
<?php endif; ?>
