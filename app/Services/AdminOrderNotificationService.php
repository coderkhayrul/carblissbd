<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class AdminOrderNotificationService
{
    private function query(int $userId)
    {
        return DB::table('admin_order_notifications as notices')
            ->join('orders', 'orders.id', '=', 'notices.order_id')
            ->leftJoin('order_customer_infos as recipients', 'recipients.order_id', '=', 'orders.id')
            ->leftJoin('admin_order_notification_reads as reads', function ($join) use ($userId) {
                $join->on('reads.notification_id', '=', 'notices.id')->where('reads.user_id', $userId);
            });
    }

    public function feed(int $userId): array
    {
        $unread = DB::table('admin_order_notifications as notices')->whereNotExists(function ($query) use ($userId) {
            $query->selectRaw('1')->from('admin_order_notification_reads as reads')->whereColumn('reads.notification_id', 'notices.id')->where('reads.user_id', $userId);
        })->count();
        $items = $this->query($userId)->orderByDesc('notices.id')->limit(20)
            ->get(['notices.id', 'notices.order_id', 'notices.created_at', 'orders.order_no', 'orders.order_source', 'orders.entry_channel', 'orders.grand_total', 'recipients.name', 'reads.read_at'])
            ->map(function ($row) {
                $source = $row->entry_channel === 'Landing Page' ? 'Landing Page' : ($row->entry_channel === 'Admin' || $row->order_source === 'GSale' ? 'Admin / GSale' : 'Website');
                return [
                    'id' => (int) $row->id, 'order_id' => (int) $row->order_id, 'order_no' => $row->order_no,
                    'customer' => $row->name ?: 'Customer', 'amount' => number_format((float) $row->grand_total, 2),
                    'source' => $source, 'read' => $row->read_at !== null,
                    'time' => \Carbon\Carbon::parse($row->created_at)->timezone('Asia/Dhaka')->format('d M, h:i A'),
                    'url' => route('admin.orders.show', $row->order_id),
                ];
            })->all();
        return ['unread_count' => $unread, 'items' => $items];
    }

    public function markRead(int $userId, int $notificationId): void
    {
        abort_unless(DB::table('admin_order_notifications')->where('id', $notificationId)->exists(), 404);
        DB::table('admin_order_notification_reads')->insertOrIgnore(['user_id' => $userId, 'notification_id' => $notificationId, 'read_at' => now()]);
    }

    public function markAllRead(int $userId, int $throughId): void
    {
        // Orders with higher notification IDs than the displayed snapshot remain unread.
        $source = DB::table('admin_order_notifications as notices')->where('notices.id', '<=', $throughId)
            ->whereNotExists(function ($query) use ($userId) {
                $query->selectRaw('1')->from('admin_order_notification_reads as reads')->whereColumn('reads.notification_id', 'notices.id')->where('reads.user_id', $userId);
            })->selectRaw('notices.id, ? as user_id, ? as read_at', [$userId, now()]);
        DB::table('admin_order_notification_reads')->insertOrIgnoreUsing(['notification_id', 'user_id', 'read_at'], $source);
    }
}
