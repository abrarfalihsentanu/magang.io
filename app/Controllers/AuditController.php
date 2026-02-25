<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\AuditLogModel;

class AuditController extends BaseController
{
    protected AuditLogModel $auditModel;
    protected int $perPage = 25;

    public function __construct()
    {
        $this->auditModel = new AuditLogModel();
    }

    /**
     * Audit Log index — filterable, paginated
     */
    public function index(): string
    {
        $filters = [
            'search'    => trim($this->request->getGet('search') ?? ''),
            'module'    => $this->request->getGet('module') ?? '',
            'action'    => $this->request->getGet('action') ?? '',
            'user_id'   => $this->request->getGet('user_id') ?? '',
            'date_from' => $this->request->getGet('date_from') ?? '',
            'date_to'   => $this->request->getGet('date_to') ?? '',
        ];

        // Strip empty values so they don't affect queries
        $activeFilters = array_filter($filters, fn($v) => $v !== '');

        $page       = max(1, (int)($this->request->getGet('page') ?? 1));
        $totalRows  = $this->auditModel->countFiltered($activeFilters);
        $totalPages = (int)ceil($totalRows / $this->perPage);
        $logs       = $this->auditModel->getFilteredLogs($activeFilters, $this->perPage, $page);

        // Dropdown data
        $modules    = array_column($this->auditModel->getDistinctModules(), 'module');
        $actions    = array_column($this->auditModel->getDistinctActions(), 'action');
        $stats      = $this->auditModel->getStats();

        // Users list for filter (admin only ever sees this page)
        $users = db_connect()
            ->table('users')
            ->select('id_user, nama_lengkap, email')
            ->where('status !=', 'archived')
            ->orderBy('nama_lengkap', 'ASC')
            ->get()
            ->getResultArray();

        return view('audit/index', [
            'title'       => 'Audit Log',
            'logs'        => $logs,
            'stats'       => $stats,
            'modules'     => $modules,
            'actions'     => $actions,
            'users'       => $users,
            'filters'     => $filters,
            'page'        => $page,
            'perPage'     => $this->perPage,
            'totalRows'   => $totalRows,
            'totalPages'  => $totalPages,
        ]);
    }
}
