<?php
$currentPage = $pager->getCurrentPage();
$pageCount   = $pager->getPageCount();
$links       = $pager->getLinks();

$prevPage = $currentPage > 1 ? $currentPage - 1 : null;
$nextPage = $currentPage < $pageCount ? $currentPage + 1 : null;
?>

<?php if ($pageCount > 1): ?>
    <ul class="pagination mb-4 justify-content-end">

        <!-- Prev -->
        <li class="page-item <?= $prevPage ? '' : 'disabled' ?>">
            <a class="page-link"
                href="javascript:void(0);"
                <?= $prevPage ? "onclick=\"applyFilters($prevPage)\"" : '' ?>>
                Prev
            </a>
        </li>

        <!-- Page Numbers -->
        <?php foreach ($links as $link): ?>
            <li class="page-item <?= $link['active'] ? 'active' : '' ?>">
                <a class="page-link"
                    href="javascript:void(0);"
                    onclick="applyFilters(<?= (int) $link['title'] ?>)">
                    <?= esc($link['title']) ?>
                </a>
            </li>
        <?php endforeach; ?>

        <!-- Next -->
        <li class="page-item <?= $nextPage ? '' : 'disabled' ?>">
            <a class="page-link text-primary"
                href="javascript:void(0);"
                <?= $nextPage ? "onclick=\"applyFilters($nextPage)\"" : '' ?>>
                Next
            </a>
        </li>

    </ul>
<?php endif; ?>