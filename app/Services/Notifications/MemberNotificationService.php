<?php

declare(strict_types=1);

namespace App\Services\Notifications;

use App\Models\User;
use App\Notifications\MemberEventNotification;

class MemberNotificationService
{
    public function __construct(
        private readonly FirebaseCloudMessagingService $firebase,
    ) {}

    public function send(User $user, string $title, string $body, string $type, ?string $actionUrl = null): void
    {
        $user->notify(new MemberEventNotification($title, $body, $type, $actionUrl));

        $this->firebase->sendToUser($user, $title, $body, [
            'type' => $type,
            'action_url' => $actionUrl ?? '',
        ]);
    }
}
