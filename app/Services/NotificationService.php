<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class NotificationService
{
    public static function send(User $user, string $type, string $title, string $body = '', array $data = []): Notification
    {
        return Notification::create([
            'law_firm_id' => $user->law_firm_id,
            'user_id' => $user->id,
            'type' => $type,
            'title' => $title,
            'body' => $body ?: null,
            'data' => !empty($data) ? $data : null,
        ]);
    }

    public static function markAllRead(User $user): void
    {
        Notification::where('user_id', $user->id)
            ->unread()
            ->update(['read_at' => now()]);
    }

    public static function getUnread(User $user, int $limit = 10): Collection
    {
        return Notification::where('user_id', $user->id)
            ->unread()
            ->latest()
            ->limit($limit)
            ->get();
    }
}
