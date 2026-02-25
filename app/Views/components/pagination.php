<?php

/**
 * Custom Pagination Template for CodeIgniter 4
 * 
 * Usage: <?= $pager->links('default', 'custom_pagination') ?>
 * 
 * Available variables from PagerRenderer:
 * - $pager->hasPrevious()
 * - $pager->getPrevious() 
 * - $pager->hasNext()
 * - $pager->getNext()
 * - $pager->getFirst()
 * - $pager->getLast()
 * - $pager->links() - returns array
 */

// Get links array
$links = $pager->links();

// Only show pagination if there are multiple pages
if (count($links) > 0):
?>
    <div class="d-flex justify-content-center mt-3">
        <nav aria-label="Page navigation">
            <ul class="pagination pagination-sm mb-0">
                <?php if ($pager->hasPrevious()): ?>
                    <li class="page-item">
                        <a class="page-link" href="<?= $pager->getFirst() ?>" aria-label="First">
                            <i class="ri-skip-back-mini-line"></i>
                        </a>
                    </li>
                    <li class="page-item">
                        <a class="page-link" href="<?= $pager->getPrevious() ?>" aria-label="Previous">
                            <i class="ri-arrow-left-s-line"></i>
                        </a>
                    </li>
                <?php else: ?>
                    <li class="page-item disabled">
                        <span class="page-link"><i class="ri-skip-back-mini-line"></i></span>
                    </li>
                    <li class="page-item disabled">
                        <span class="page-link"><i class="ri-arrow-left-s-line"></i></span>
                    </li>
                <?php endif; ?>

                <?php foreach ($links as $link): ?>
                    <li class="page-item <?= $link['active'] ? 'active' : '' ?>">
                        <a class="page-link" href="<?= $link['uri'] ?>"><?= $link['title'] ?></a>
                    </li>
                <?php endforeach; ?>

                <?php if ($pager->hasNext()): ?>
                    <li class="page-item">
                        <a class="page-link" href="<?= $pager->getNext() ?>" aria-label="Next">
                            <i class="ri-arrow-right-s-line"></i>
                        </a>
                    </li>
                    <li class="page-item">
                        <a class="page-link" href="<?= $pager->getLast() ?>" aria-label="Last">
                            <i class="ri-skip-forward-mini-line"></i>
                        </a>
                    </li>
                <?php else: ?>
                    <li class="page-item disabled">
                        <span class="page-link"><i class="ri-arrow-right-s-line"></i></span>
                    </li>
                    <li class="page-item disabled">
                        <span class="page-link"><i class="ri-skip-forward-mini-line"></i></span>
                    </li>
                <?php endif; ?>
            </ul>
        </nav>
    </div>
<?php endif; ?>