<?php

use Illuminate\Foundation\Inspiring;
use App\Models\AppSetting;
use App\Models\MembershipPurchase;
use App\Models\User;
use App\Services\Notifications\MemberNotificationService;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('firebase:check', function () {
    $enabled = (bool) config('services.firebase.enabled');
    $projectId = config('services.firebase.project_id');
    $inlineJson = config('services.firebase.service_account_json');
    $file = config('services.firebase.service_account_file');

    if (! $enabled) {
        $this->warn('Firebase push is disabled. Set FIREBASE_PUSH_ENABLED=true to enable FCM.');
    } else {
        $this->info('Firebase push is enabled.');
    }

    if (! filled($projectId)) {
        $this->error('FIREBASE_PROJECT_ID is empty.');

        return self::FAILURE;
    }

    $this->line("Firebase project id: {$projectId}");

    $json = null;
    if (filled($inlineJson)) {
        $json = (string) $inlineJson;
        $this->line('Service account source: FIREBASE_SERVICE_ACCOUNT_JSON');
    } elseif (filled($file)) {
        $path = (string) $file;
        if (! is_file($path) || ! is_readable($path)) {
            $this->error("Service account file is not readable: {$path}");

            return self::FAILURE;
        }

        $json = file_get_contents($path);
        $this->line("Service account source: {$path}");
    }

    if (! is_string($json) || $json === '') {
        $this->error('Firebase service account is empty. Set FIREBASE_SERVICE_ACCOUNT_FILE or FIREBASE_SERVICE_ACCOUNT_JSON.');

        return self::FAILURE;
    }

    $serviceAccount = json_decode($json, true);
    if (! is_array($serviceAccount)) {
        $this->error('Firebase service account JSON cannot be parsed.');

        return self::FAILURE;
    }

    foreach (['project_id', 'client_email', 'private_key', 'token_uri'] as $key) {
        if (empty($serviceAccount[$key])) {
            $this->error("Firebase service account is missing {$key}.");

            return self::FAILURE;
        }
    }

    if ($serviceAccount['project_id'] !== $projectId) {
        $this->error("FIREBASE_PROJECT_ID ({$projectId}) does not match service account project_id ({$serviceAccount['project_id']}).");

        return self::FAILURE;
    }

    $this->info('Firebase server configuration looks valid.');

    return self::SUCCESS;
})->purpose('Validate Firebase Cloud Messaging server configuration');

Artisan::command('membership:send-expiry-reminders', function (MemberNotificationService $notifications) {
    $days = collect(AppSetting::getArray('membership_expiry_reminder_days', [7, 3, 1]))
        ->map(fn ($value): int => (int) $value)
        ->filter(fn (int $value): bool => $value >= 0)
        ->unique()
        ->values();

    if ($days->isEmpty()) {
        $this->warn('No reminder days configured.');

        return self::SUCCESS;
    }

    $sent = 0;

    foreach ($days as $day) {
        $targetDate = now()->addDays($day)->toDateString();

        MembershipPurchase::query()
            ->with(['member.user', 'package'])
            ->where('status', 'active')
            ->whereDate('expires_at', $targetDate)
            ->chunkById(100, function ($purchases) use ($notifications, $day, &$sent): void {
                foreach ($purchases as $purchase) {
                    $user = $purchase->member?->user;
                    if ($user === null) {
                        continue;
                    }

                    $cacheKey = "membership-expiry-reminder:{$purchase->id}:{$day}:".now()->toDateString();
                    if (! Cache::add($cacheKey, true, now()->addDays(14))) {
                        continue;
                    }

                    $packageName = $purchase->package?->name ?? 'membership';
                    $dayLabel = $day === 0 ? 'hari ini' : "{$day} hari lagi";

                    $notifications->send(
                        $user,
                        'Membership hampir habis',
                        "Paket {$packageName} Anda akan berakhir {$dayLabel}. Silakan perpanjang agar latihan tetap lancar.",
                        'membership_expiry_reminder',
                        '/packages',
                    );

                    $sent++;
                }
            });
    }

    $this->info("Membership expiry reminders sent: {$sent}");

    return self::SUCCESS;
})->purpose('Send membership expiry reminders based on admin setting');

Schedule::command('membership:send-expiry-reminders')->dailyAt('08:00');

Artisan::command('firebase:test-user {email}', function (string $email, MemberNotificationService $notifications) {
    $user = User::query()->where('email', $email)->first();

    if ($user === null) {
        $this->error("User {$email} not found.");

        return self::FAILURE;
    }

    $tokenCount = $user->deviceTokens()->count();
    if ($tokenCount === 0) {
        $this->warn("User {$email} has no registered device token. Open the mobile app and login first.");
    }

    $notifications->send(
        $user,
        'Tes notifikasi Akhwat Gym',
        'Jika pesan ini masuk di HP, konfigurasi notifikasi sudah berjalan.',
        'fcm_test',
        '/notifications',
    );

    $this->info("Test notification queued/sent for {$email}. Device tokens: {$tokenCount}");

    return self::SUCCESS;
})->purpose('Send a test database notification and FCM push to one user');
