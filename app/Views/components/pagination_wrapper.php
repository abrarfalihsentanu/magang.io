<?php

/**
 * Pagination Wrapper Component
 * 
 * Includes:
 * - Per-page selector (10, 25, 50, 100)
 * - Data info (Menampilkan X - Y dari Z)
 * - Pagination links
 * 
 * Required variables:
 * - $pager: Pager instance (from controller)
 * - $total: Total records
 * - $perPage: Current per page value
 * - $currentPage: Current page number
 */

$perPageOptions = [10, 25, 50, 100];
$currentPerPage = $perPage ?? 10;
$currentPageNum = $currentPage ?? 1;
$totalRecords = $total ?? 0;

// Calculate display range
$startRecord = $totalRecords > 0 ? (($currentPageNum - 1) * $currentPerPage + 1) : 0;
$endRecord = min($currentPageNum * $currentPerPage, $totalRecords);

// Calculate total pages
$totalPages = $currentPerPage > 0 ? ceil($totalRecords / $currentPerPage) : 1;

// Build base URL with existing query params
$currentUrl = current_url();
$queryParams = $_GET;
unset($queryParams['page']); // Page will be added separately
$baseQuery = http_build_query($queryParams);
$baseUrl = $currentUrl . ($baseQuery ? '?' . $baseQuery . '&' : '?');
?>

<?php if ($totalRecords > 0): ?>
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mt-3 pt-3 border-top">
        <!-- Left side: Per page selector and info -->
        <div class="d-flex align-items-center gap-3">
            <div class="d-flex align-items-center gap-2">
                <label class="form-label mb-0 text-nowrap">Tampilkan:</label>
                <select class="form-select form-select-sm" style="width: 75px;" onchange="changePerPage(this.value)">
                    <?php foreach ($perPageOptions as $option): ?>
                        <option value="<?= $option ?>" <?= $currentPerPage == $option ? 'selected' : '' ?>>
                            <?= $option ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="text-muted small">
                Menampilkan <?= number_format($startRecord) ?> - <?= number_format($endRecord) ?> dari <?= number_format($totalRecords) ?> data
            </div>
        </div>

        <!-- Right side: Pagination -->
        <?php if ($totalPages > 1): ?>
            <nav aria-label="Page navigation">
                <ul class="pagination pagination-sm mb-0">
                    <!-- First & Previous -->
                    <?php if ($currentPageNum > 1): ?>
                        <li class="page-item">
                            <a class="page-link" href="<?= $baseUrl ?>page=1" aria-label="First">
                                <i class="ri-skip-back-mini-line"></i>
                            </a>
                        </li>
                        <li class="page-item">
                            <a class="page-link" href="<?= $baseUrl ?>page=<?= $currentPageNum - 1 ?>" aria-label="Previous">
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

                    <!-- Page Numbers -->
                    <?php
                    $surroundCount = 2;
                    $startPage = max(1, $currentPageNum - $surroundCount);
                    $endPage = min($totalPages, $currentPageNum + $surroundCount);

                    // Show first page if not in range
                    if ($startPage > 1): ?>
                        <li class="page-item">
                            <a class="page-link" href="<?= $baseUrl ?>page=1">1</a>
                        </li>
                        <?php if ($startPage > 2): ?>
                            <li class="page-item disabled"><span class="page-link">...</span></li>
                        <?php endif; ?>
                    <?php endif; ?>

                    <?php for ($i = $startPage; $i <= $endPage; $i++): ?>
                        <li class="page-item <?= $i == $currentPageNum ? 'active' : '' ?>">
                            <a class="page-link" href="<?= $baseUrl ?>page=<?= $i ?>"><?= $i ?></a>
                        </li>
                    <?php endfor; ?>

                    <?php // Show last page if not in range
                    if ($endPage < $totalPages): ?>
                        <?php if ($endPage < $totalPages - 1): ?>
                            <li class="page-item disabled"><span class="page-link">...</span></li>
                        <?php endif; ?>
                        <li class="page-item">
                            <a class="page-link" href="<?= $baseUrl ?>page=<?= $totalPages ?>"><?= $totalPages ?></a>
                        </li>
                    <?php endif; ?>

                    <!-- Next & Last -->
                    <?php if ($currentPageNum < $totalPages): ?>
                        <li class="page-item">
                            <a class="page-link" href="<?= $baseUrl ?>page=<?= $currentPageNum + 1 ?>" aria-label="Next">
                                <i class="ri-arrow-right-s-line"></i>
                            </a>
                        </li>
                        <li class="page-item">
                            <a class="page-link" href="<?= $baseUrl ?>page=<?= $totalPages ?>" aria-label="Last">
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
        <?php endif; ?>
    </div>

    <script>
        function changePerPage(value) {
            const url = new URL(window.location.href);
            url.searchParams.set('perPage', value);
            url.searchParams.delete('page'); // Reset to page 1
            window.location.href = url.toString();
        }
    </script>
<?php endif; ?>