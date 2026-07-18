<?php

declare(strict_types=1);

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class MemberEventNotification extends Notification
{
    use Queueable;

    public function __construct(
        private readonly string $title,
        private readonly string $body,
        private readonly string $type,
        private readonly ?string $actionUrl = null,
    ) {}

    /**
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * @return array<string, string|null>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'title' => $this->title,
            'body' => $this->body,
            'type' => $this->type,
            'action_url' => $this->actionUrl,
        ];
    }
}
