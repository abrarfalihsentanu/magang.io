<?php

namespace App\Controllers;

use App\Models\NotificationModel;

class NotificationController extends BaseController
{
    protected NotificationModel $model;

    public function __construct()
    {
        $this->model = new NotificationModel();
    }

    // ============================================
    // FULL PAGE — /notifications
    // ============================================
    public function index()
    {
        $userId = (int) session()->get('user_id');
        $role   = session()->get('role_code');
        $filter = $this->request->getGet('filter') ?? 'all'; // all | unread

        if ($filter === 'unread') {
            $notifications = $this->model->getUnreadForUser($userId, 50);
        } else {
            $notifications = $this->model->getForUser($userId, 50);
        }

        $unreadCount = $this->model->countUnread($userId);
        $countByType = $this->model->countByType($userId);

        return view('notifications/index', [
            'title'         => 'Notifikasi',
            'notifications' => $notifications,
            'unreadCount'   => $unreadCount,
            'countByType'   => $countByType,
            'filter'        => $filter,
        ]);
    }

    // ============================================
    // API — GET /api/notifications
    // JSON: unread notifications for navbar bell
    // ============================================
    public function getUnread()
    {
        $userId      = (int) session()->get('user_id');
        $unread      = $this->model->getUnreadForUser($userId, 10);
        $totalUnread = $this->model->countUnread($userId);

        $items = array_map(function ($n) {
            return [
                'id'         => $n['id_notification'],
                'type'       => $n['type'],
                'title'      => $n['title'],
                'message'    => $n['message'],
                'link'       => $n['link'],
                'is_read'    => (bool) $n['is_read'],
                'created_at' => $n['created_at'],
                'time_ago'   => $this->timeAgo($n['created_at']),
                'icon'       => $this->getIcon($n['type']),
                'color'      => $this->getColor($n['type']),
            ];
        }, $unread);

        return $this->response->setJSON([
            'success'      => true,
            'count'        => $totalUnread,
            'notifications' => $items,
        ]);
    }

    // ============================================
    // API — POST /api/notification/mark-read/:id
    // ============================================
    public function markAsRead(int $id)
    {
        $userId = (int) session()->get('user_id');

        $ok = $this->model->markAsRead($id, $userId);

        return $this->response->setJSON([
            'success' => $ok,
            'count'   => $this->model->countUnread($userId),
        ]);
    }

    // ============================================
    // API — POST /api/notifications/mark-all-read
    // ============================================
    public function markAllAsRead()
    {
        $userId = (int) session()->get('user_id');

        $this->model->markAllAsRead($userId);

        return $this->response->setJSON([
            'success' => true,
            'count'   => 0,
        ]);
    }

    // ============================================
    // PRIVATE HELPERS
    // ============================================

    private function timeAgo(?string $datetime): string
    {
        if (empty($datetime)) {
            return '-';
        }

        $timestamp = strtotime($datetime);
        $diff      = time() - $timestamp;

        if ($diff < 60) {
            return 'Baru saja';
        } elseif ($diff < 3600) {
            return floor($diff / 60) . ' menit lalu';
        } elseif ($diff < 86400) {
            return floor($diff / 3600) . ' jam lalu';
        } elseif ($diff < 604800) {
            return floor($diff / 86400) . ' hari lalu';
        } else {
            return date('d M Y', $timestamp);
        }
    }

    private function getIcon(string $type): string
    {
        return match ($type) {
            'activity_submitted'  => 'ri-file-add-line',
            'activity_approved'   => 'ri-checkbox-circle-line',
            'activity_rejected'   => 'ri-close-circle-line',
            'correction_submitted' => 'ri-calendar-todo-line',
            'correction_approved' => 'ri-calendar-check-line',
            'correction_rejected' => 'ri-calendar-close-line',
            'leave_submitted'     => 'ri-article-line',
            'leave_approved'      => 'ri-check-double-line',
            'leave_rejected'      => 'ri-close-circle-line',
            'allowance_paid'      => 'ri-money-dollar-circle-line',
            'kpi_assessed'        => 'ri-bar-chart-line',
            'kpi_calculated'      => 'ri-award-line',
            default               => 'ri-notification-3-line',
        };
    }

    private function getColor(string $type): string
    {
        return match ($type) {
            'activity_approved', 'correction_approved', 'leave_approved', 'allowance_paid', 'kpi_assessed', 'kpi_calculated' => 'success',
            'activity_rejected', 'correction_rejected', 'leave_rejected' => 'danger',
            'activity_submitted', 'correction_submitted', 'leave_submitted' => 'warning',
            default => 'primary',
        };
    }
}
