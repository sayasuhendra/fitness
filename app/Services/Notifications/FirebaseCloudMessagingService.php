<?php

declare(strict_types=1);

namespace App\Services\Notifications;

use App\Models\DeviceToken;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FirebaseCloudMessagingService
{
    public function sendToUser(User $user, string $title, string $body, array $data = []): void
    {
        if (! $this->isConfigured()) {
            return;
        }

        $tokens = $user->deviceTokens()->pluck('token');

        foreach ($tokens as $token) {
            $this->sendToToken($token, $title, $body, $data);
        }
    }

    private function sendToToken(string $token, string $title, string $body, array $data): void
    {
        $projectId = (string) config('services.firebase.project_id');
        $accessToken = $this->accessToken();

        if ($accessToken === null) {
            return;
        }

        $response = Http::withToken($accessToken)
            ->acceptJson()
            ->post("https://fcm.googleapis.com/v1/projects/{$projectId}/messages:send", [
                'message' => [
                    'token' => $token,
                    'notification' => [
                        'title' => $title,
                        'body' => $body,
                    ],
                    'data' => $this->stringifyData($data),
                ],
            ]);

        if ($response->status() === 404 || $response->status() === 400) {
            DeviceToken::query()->where('token', $token)->delete();
        }

        if ($response->failed()) {
            Log::warning('Firebase push notification failed.', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);
        }
    }

    private function isConfigured(): bool
    {
        return (bool) config('services.firebase.enabled')
            && filled(config('services.firebase.project_id'))
            && filled(config('services.firebase.service_account_json'));
    }

    private function accessToken(): ?string
    {
        return Cache::remember('firebase_access_token', 3300, function (): ?string {
            $serviceAccount = json_decode((string) config('services.firebase.service_account_json'), true);

            if (! is_array($serviceAccount) || empty($serviceAccount['client_email']) || empty($serviceAccount['private_key'])) {
                Log::warning('Firebase service account JSON is invalid.');

                return null;
            }

            $now = time();
            $assertion = $this->jwt([
                'alg' => 'RS256',
                'typ' => 'JWT',
            ], [
                'iss' => $serviceAccount['client_email'],
                'scope' => 'https://www.googleapis.com/auth/firebase.messaging',
                'aud' => 'https://oauth2.googleapis.com/token',
                'iat' => $now,
                'exp' => $now + 3600,
            ], $serviceAccount['private_key']);

            $response = Http::asForm()->post('https://oauth2.googleapis.com/token', [
                'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
                'assertion' => $assertion,
            ]);

            if ($response->failed()) {
                Log::warning('Firebase access token request failed.', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);

                return null;
            }

            return $response->json('access_token');
        });
    }

    private function jwt(array $header, array $payload, string $privateKey): string
    {
        $segments = [
            $this->base64UrlEncode(json_encode($header, JSON_THROW_ON_ERROR)),
            $this->base64UrlEncode(json_encode($payload, JSON_THROW_ON_ERROR)),
        ];

        openssl_sign(implode('.', $segments), $signature, $privateKey, OPENSSL_ALGO_SHA256);
        $segments[] = $this->base64UrlEncode($signature);

        return implode('.', $segments);
    }

    private function base64UrlEncode(string $value): string
    {
        return rtrim(strtr(base64_encode($value), '+/', '-_'), '=');
    }

    /**
     * @return array<string, string>
     */
    private function stringifyData(array $data): array
    {
        return collect($data)
            ->mapWithKeys(fn ($value, $key): array => [(string) $key => (string) $value])
            ->all();
    }
}
