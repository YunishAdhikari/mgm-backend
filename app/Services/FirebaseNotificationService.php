<?php

namespace App\Services;

use Google\Auth\Credentials\ServiceAccountCredentials;
use Illuminate\Support\Facades\Http;

class FirebaseNotificationService
{
    public function sendToToken(string $token, string $title, string $body, array $data = [])
    {
        $projectId = env('FIREBASE_PROJECT_ID');

        $credentialsPath = storage_path('app/firebase-service-account.json');

        $credentials = new ServiceAccountCredentials(
            ['https://www.googleapis.com/auth/firebase.messaging'],
            $credentialsPath
        );

        $authToken = $credentials->fetchAuthToken();

        $accessToken = $authToken['access_token'];

        return Http::withToken($accessToken)
            ->post("https://fcm.googleapis.com/v1/projects/{$projectId}/messages:send", [
                'message' => [
                    'token' => $token,
                    'notification' => [
                        'title' => $title,
                        'body' => $body,
                    ],
                    'data' => $data,
                ],
            ])
            ->json();
    }
}