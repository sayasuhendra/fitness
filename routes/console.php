<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

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
