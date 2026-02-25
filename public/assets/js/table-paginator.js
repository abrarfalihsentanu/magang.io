/**
 * TablePaginator - Reusable client-side table pagination
 * 
 * Usage: Add class "table-paginated" to any <table> element.
 * The paginator will automatically wrap around the table's parent .table-responsive div.
 * 
 * Options via data attributes on <table>:
 *   data-per-page="10"  - Default items per page (default: 10)
 * 
 * The pagination UI matches the existing pagination_wrapper component styling.
 */
(function () {
    'use strict';

    class TablePaginator {
        constructor(table) {
            this.table = table;
            this.tbody = table.querySelector('tbody');
            if (!this.tbody) return;

            this.allRows = Array.from(this.tbody.querySelectorAll('tr'));
            if (this.allRows.length === 0) return;

            this.perPage = parseInt(table.dataset.perPage) || 10;
            this.currentPage = 1;
            this.totalRows = this.allRows.length;
            this.totalPages = Math.ceil(this.totalRows / this.perPage);

            // Only paginate if there are more rows than perPage
            if (this.totalRows <= this.perPage) {
                // Still show info text if more than a few rows
                if (this.totalRows > 5) {
                    this.createWrapper();
                    this.updateInfo();
                }
                return;
            }

            this.createWrapper();
            this.render();
        }

        createWrapper() {
            // Find the table-responsive parent or create a wrapper
            const tableResponsive = this.table.closest('.table-responsive') || this.table.parentElement;

            // Create pagination container after table-responsive
            this.container = document.createElement('div');
            this.container.className = 'table-paginator-wrapper d-flex justify-content-between align-items-center flex-wrap gap-3 px-4 py-3 border-top';

            // Left side: per-page selector + info
            const leftSide = document.createElement('div');
            leftSide.className = 'd-flex align-items-center gap-3';

            // Per-page selector
            const selectorWrap = document.createElement('div');
            selectorWrap.className = 'd-flex align-items-center gap-2';
            selectorWrap.innerHTML = `
                <label class="form-label mb-0 text-nowrap">Tampilkan:</label>
                <select class="form-select form-select-sm" style="width:75px">
                    <option value="10" ${this.perPage === 10 ? 'selected' : ''}>10</option>
                    <option value="25" ${this.perPage === 25 ? 'selected' : ''}>25</option>
                    <option value="50" ${this.perPage === 50 ? 'selected' : ''}>50</option>
                    <option value="100" ${this.perPage === 100 ? 'selected' : ''}>100</option>
                </select>
            `;
            this.selectEl = selectorWrap.querySelector('select');
            this.selectEl.addEventListener('change', () => {
                this.perPage = parseInt(this.selectEl.value);
                this.currentPage = 1;
                this.totalPages = Math.ceil(this.totalRows / this.perPage);
                this.render();
            });

            // Info text
            this.infoEl = document.createElement('div');
            this.infoEl.className = 'text-muted small';

            leftSide.appendChild(selectorWrap);
            leftSide.appendChild(this.infoEl);

            // Right side: page navigation
            this.navEl = document.createElement('nav');
            this.navEl.setAttribute('aria-label', 'Table pagination');

            this.container.appendChild(leftSide);
            this.container.appendChild(this.navEl);

            // Insert after the table-responsive div
            tableResponsive.insertAdjacentElement('afterend', this.container);
        }

        render() {
            this.showPage();
            this.updateInfo();
            this.updateNav();
            // Re-number rows if they have a sequential # column
            this.renumberRows();
        }

        showPage() {
            const start = (this.currentPage - 1) * this.perPage;
            const end = start + this.perPage;

            this.allRows.forEach((row, i) => {
                row.style.display = (i >= start && i < end) ? '' : 'none';
            });
        }

        updateInfo() {
            const start = this.totalRows > 0 ? (this.currentPage - 1) * this.perPage + 1 : 0;
            const end = Math.min(this.currentPage * this.perPage, this.totalRows);
            this.infoEl.textContent = `Menampilkan ${start.toLocaleString('id-ID')} - ${end.toLocaleString('id-ID')} dari ${this.totalRows.toLocaleString('id-ID')} data`;
        }

        updateNav() {
            this.totalPages = Math.ceil(this.totalRows / this.perPage);
            if (this.totalPages <= 1) {
                this.navEl.innerHTML = '';
                return;
            }

            let html = '<ul class="pagination pagination-sm mb-0">';

            // First & Previous
            if (this.currentPage > 1) {
                html += `<li class="page-item"><a class="page-link" href="#" data-page="1" aria-label="First"><i class="ri-skip-back-mini-line"></i></a></li>`;
                html += `<li class="page-item"><a class="page-link" href="#" data-page="${this.currentPage - 1}" aria-label="Previous"><i class="ri-arrow-left-s-line"></i></a></li>`;
            } else {
                html += `<li class="page-item disabled"><span class="page-link"><i class="ri-skip-back-mini-line"></i></span></li>`;
                html += `<li class="page-item disabled"><span class="page-link"><i class="ri-arrow-left-s-line"></i></span></li>`;
            }

            // Page numbers
            const surround = 2;
            let startPage = Math.max(1, this.currentPage - surround);
            let endPage = Math.min(this.totalPages, this.currentPage + surround);

            if (startPage > 1) {
                html += `<li class="page-item"><a class="page-link" href="#" data-page="1">1</a></li>`;
                if (startPage > 2) html += `<li class="page-item disabled"><span class="page-link">...</span></li>`;
            }

            for (let i = startPage; i <= endPage; i++) {
                html += `<li class="page-item ${i === this.currentPage ? 'active' : ''}"><a class="page-link" href="#" data-page="${i}">${i}</a></li>`;
            }

            if (endPage < this.totalPages) {
                if (endPage < this.totalPages - 1) html += `<li class="page-item disabled"><span class="page-link">...</span></li>`;
                html += `<li class="page-item"><a class="page-link" href="#" data-page="${this.totalPages}">${this.totalPages}</a></li>`;
            }

            // Next & Last
            if (this.currentPage < this.totalPages) {
                html += `<li class="page-item"><a class="page-link" href="#" data-page="${this.currentPage + 1}" aria-label="Next"><i class="ri-arrow-right-s-line"></i></a></li>`;
                html += `<li class="page-item"><a class="page-link" href="#" data-page="${this.totalPages}" aria-label="Last"><i class="ri-skip-forward-mini-line"></i></a></li>`;
            } else {
                html += `<li class="page-item disabled"><span class="page-link"><i class="ri-arrow-right-s-line"></i></span></li>`;
                html += `<li class="page-item disabled"><span class="page-link"><i class="ri-skip-forward-mini-line"></i></span></li>`;
            }

            html += '</ul>';
            this.navEl.innerHTML = html;

            // Bind click events
            this.navEl.querySelectorAll('a[data-page]').forEach(a => {
                a.addEventListener('click', (e) => {
                    e.preventDefault();
                    this.currentPage = parseInt(a.dataset.page);
                    this.render();
                });
            });
        }

        renumberRows() {
            // If first column in thead is "#" or "No", re-number visible rows
            const thead = this.table.querySelector('thead');
            if (!thead) return;
            const firstTh = thead.querySelector('th');
            if (!firstTh) return;
            const text = firstTh.textContent.trim();
            if (text !== '#' && text !== 'No' && text !== 'No.') return;

            const start = (this.currentPage - 1) * this.perPage;
            this.allRows.forEach((row, i) => {
                if (row.style.display !== 'none') {
                    const firstTd = row.querySelector('td');
                    if (firstTd && /^\d+$/.test(firstTd.textContent.trim())) {
                        firstTd.textContent = (i + 1);
                    }
                }
            });
        }
    }

    // Auto-initialize on DOMContentLoaded
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('table.table-paginated').forEach(table => {
            new TablePaginator(table);
        });
    });

    // Expose for manual initialization
    window.TablePaginator = TablePaginator;
})();
