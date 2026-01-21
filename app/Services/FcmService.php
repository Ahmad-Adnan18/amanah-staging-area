<?php

namespace App\Services;

use Google\Auth\Credentials\ServiceAccountCredentials;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FcmService
{
    private $projectId;
    private $credentialsPath;

    public function __construct()
    {
        $this->credentialsPath = storage_path('app/firebase_credentials.json');
        
        if (file_exists($this->credentialsPath)) {
            $json = json_decode(file_get_contents($this->credentialsPath), true);
            $this->projectId = $json['project_id'] ?? null;
        }
    }

    private function getAccessToken()
    {
        $scopes = ['https://www.googleapis.com/auth/firebase.messaging'];
        $credentials = new ServiceAccountCredentials($scopes, $this->credentialsPath);
        $token = $credentials->fetchAuthToken();
        
        return $token['access_token'] ?? null;
    }

    public function sendNotification($targetToken, $title, $body, $data = [])
    {
        if (!$this->projectId || !$targetToken) {
            return false;
        }

        try {
            $accessToken = $this->getAccessToken();
            
            $url = "https://fcm.googleapis.com/v1/projects/{$this->projectId}/messages:send";
            
            $payload = [
                'message' => [
                    'token' => $targetToken,
                    'notification' => [
                        'title' => $title,
                        'body' => $body,
                    ],
                    'data' => $data,
                ],
            ];

            $response = Http::withToken($accessToken)
                ->withHeaders(['Content-Type' => 'application/json'])
                ->post($url, $payload);

            if ($response->failed()) {
                Log::error('FCM Send Error: ' . $response->body());
                return false;
            }

            return true;

        } catch (\Exception $e) {
            Log::error('FCM Exception: ' . $e->getMessage());
            return false;
        }
    }

    public function broadcast($tokens, $title, $body, $data = [])
    {
        // FCM v1 doesn't support multicast natively like legacy API
        // So we loop (or use topic). For now loop is fine for small scale.
        // For production, consider using Topic Subscription.
        
        $successCount = 0;
        foreach ($tokens as $token) {
            if ($this->sendNotification($token, $title, $body, $data)) {
                $successCount++;
            }
        }
        
        return $successCount;
    }
}
