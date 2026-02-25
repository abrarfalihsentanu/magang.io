<?php

namespace App\Models;

use CodeIgniter\Model;

class NotificationModel extends Model
{
    protected $table            = 'notifications';
    protected $primaryKey       = 'id_notification';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'id_user',
        'type',
        'title',
        'message',
        'link',
        'is_read',
        'read_at',
        'created_at',
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected $useTimestamps = false;

    // ============================================
    // QUERY HELPERS
    // ============================================

    /**
     * Get unread notifications for a user (max 15)
     */
    public function getUnreadForUser(int $userId, int $limit = 15): array
    {
        return $this->where('id_user', $userId)
            ->where('is_read', 0)
            ->orderBy('created_at', 'DESC')
            ->limit($limit)
            ->findAll();
    }

    /**
     * Get all notifications for a user (paginated)
     */
    public function getForUser(int $userId, int $limit = 20, int $offset = 0): array
    {
        return $this->where('id_user', $userId)
            ->orderBy('created_at', 'DESC')
            ->limit($limit, $offset)
            ->findAll();
    }

    /**
     * Count unread notifications for a user
     */
    public function countUnread(int $userId): int
    {
        return $this->where('id_user', $userId)
            ->where('is_read', 0)
            ->countAllResults();
    }

    /**
     * Mark a single notification as read
     */
    public function markAsRead(int $id, int $userId): bool
    {
        return $this->where('id_notification', $id)
            ->where('id_user', $userId)
            ->set([
                'is_read' => 1,
                'read_at' => date('Y-m-d H:i:s'),
            ])
            ->update();
    }

    /**
     * Mark all notifications as read for a user
     */
    public function markAllAsRead(int $userId): bool
    {
        return $this->where('id_user', $userId)
            ->where('is_read', 0)
            ->set([
                'is_read' => 1,
                'read_at' => date('Y-m-d H:i:s'),
            ])
            ->update();
    }

    /**
     * Create a notification record
     */
    public function createNotification(
        int $userId,
        string $type,
        string $title,
        string $message,
        ?string $link = null
    ): int|false {
        $data = [
            'id_user'    => $userId,
            'type'       => $type,
            'title'      => $title,
            'message'    => $message,
            'link'       => $link,
            'is_read'    => 0,
            'read_at'    => null,
            'created_at' => date('Y-m-d H:i:s'),
        ];

        return $this->insert($data, true);
    }

    /**
     * Delete old read notifications (older than 30 days) for a user
     */
    public function cleanOld(int $userId): void
    {
        $this->where('id_user', $userId)
            ->where('is_read', 1)
            ->where('read_at <', date('Y-m-d H:i:s', strtotime('-30 days')))
            ->delete();
    }

    /**
     * Get count by type for a user
     */
    public function countByType(int $userId): array
    {
        $rows = $this->select('type, COUNT(*) as total')
            ->where('id_user', $userId)
            ->where('is_read', 0)
            ->groupBy('type')
            ->findAll();

        $counts = [];
        foreach ($rows as $row) {
            $counts[$row['type']] = (int) $row['total'];
        }
        return $counts;
    }
}
