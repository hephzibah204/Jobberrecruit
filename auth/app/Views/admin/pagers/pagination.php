<?php
$pager->setSurroundCount(2);

$currentPage = $pager->getCurrentPageNumber();
$pageCount   = $pager->getPageCount();
?>

<?php if ($pageCount > 1): ?>
    <ul class="pagination mb-4 justify-content-end">

        <!-- Prev -->
        <li class="page-item <?= $pager->hasPreviousPage() ? '' : 'disabled' ?>">
            <a class="page-link"
                href="<?= $pager->hasPreviousPage()
                            ? $pager->getPreviousPage()
                            : 'javascript:void(0);' ?>">
                Prev
            </a>
        </li>

        <!-- Page Numbers -->
        <?php foreach ($pager->links() as $link): ?>
            <li class="page-item <?= $link['active'] ? 'active' : '' ?>">
                <a class="page-link" href="<?= $link['uri'] ?>">
                    <?= esc($link['title']) ?>
                </a>
            </li>
        <?php endforeach; ?>

        <!-- Next -->
        <li class="page-item <?= $pager->hasNextPage() ? '' : 'disabled' ?>">
            <a class="page-link text-primary"
                href="<?= $pager->hasNextPage()
                            ? $pager->getNextPage()
                            : 'javascript:void(0);' ?>">
                Next
            </a>
        </li>

    </ul>
<?php endif; ?>