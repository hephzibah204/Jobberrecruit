<?php
$pager->setSurroundCount(2);

$currentPage = $pager->getCurrentPageNumber();
$pageCount   = $pager->getPageCount();
?>

<?php if ($pageCount > 1): ?>
    <nav aria-label="Blog pagination" class="mt-5 pt-4">
        <ul class="pagination justify-content-center">
            <!-- First Page -->
            <?php if ($pager->hasPreviousPage()): ?>
                <li class="page-item">
                    <a class="page-link" href="<?= $pager->getFirst() ?>" aria-label="First">
                        <i class="bi bi-chevron-double-left"></i>
                    </a>
                </li>
            <?php endif; ?>

            <!-- Previous Page -->
            <?php if ($pager->hasPreviousPage()): ?>
                <li class="page-item">
                    <a class="page-link" href="<?= $pager->getPreviousPage() ?>" aria-label="Previous">
                        <i class="bi bi-chevron-left"></i>
                    </a>
                </li>
            <?php endif; ?>

            <!-- Page Numbers -->
            <?php foreach ($pager->links() as $link): ?>
                <li class="page-item <?= $link['active'] ? 'active' : '' ?>">
                    <a class="page-link" href="<?= $link['uri'] ?>">
                        <?= $link['title'] ?>
                    </a>
                </li>
            <?php endforeach; ?>

            <!-- Next Page -->
            <?php if ($pager->hasNextPage()): ?>
                <li class="page-item">
                    <a class="page-link" href="<?= $pager->getNextPage() ?>" aria-label="Next">
                        <i class="bi bi-chevron-right"></i>
                    </a>
                </li>
            <?php endif; ?>

            <!-- Last Page -->
            <?php if ($pager->hasNextPage()): ?>
                <li class="page-item">
                    <a class="page-link" href="<?= $pager->getLast() ?>" aria-label="Last">
                        <i class="bi bi-chevron-double-right"></i>
                    </a>
                </li>
            <?php endif; ?>
        </ul>
        <p class="text-center text-muted small mt-3">
            Showing <?= $pager->getCurrentPageNumber() ?> of <?= $pager->getPageCount() ?> pages
            (<?= $pager->getTotal() ?> total articles)
        </p>
    </nav>
<?php endif ?>