<?php

namespace App\Services;

use App\Models\Setting;
use Google\Client;
use Google\Service\Drive;
use Google\Service\Drive\DriveFile;

class GoogleDriveService
{
    protected function getClient()
    {
        $client = new Client();
        $client->setClientId(Setting::get('gdrive_client_id'));
        $client->setClientSecret(Setting::get('gdrive_client_secret'));
        $client->setRedirectUri(route('admin.settings.gdrive-callback'));
        $client->addScope(Drive::DRIVE_FILE);
        $client->setAccessType('offline');
        $client->setApprovalPrompt('force');

        return $client;
    }

    public function getAuthUrl()
    {
        return $this->getClient()->createAuthUrl();
    }

    public function authenticate(string $code)
    {
        $client = $this->getClient();
        $accessToken = $client->fetchAccessTokenWithAuthCode($code);
        
        if (isset($accessToken['error'])) {
            throw new \Exception('OAuth error: ' . $accessToken['error_description']);
        }

        // Save tokens and account info
        Setting::set('gdrive_access_token', json_encode($accessToken), 'general');
        if ($client->getRefreshToken()) {
            Setting::set('gdrive_refresh_token', $client->getRefreshToken(), 'general');
        }
        
        Setting::set('gdrive_connected', 'true', 'general');

        // Fetch user email using Drive service
        $client->setAccessToken($accessToken);
        $driveService = new Drive($client);
        try {
            $about = $driveService->about->get(['fields' => 'user']);
            if ($about && $about->getUser()) {
                Setting::set('gdrive_account_email', $about->getUser()->getEmailAddress(), 'general');
            }
        } catch (\Exception $e) {
            Setting::set('gdrive_account_email', 'Terhubung (Akun Terproteksi)', 'general');
        }

        return $accessToken;
    }

    protected function getAuthenticatedClient()
    {
        $client = $this->getClient();
        $tokenJson = Setting::get('gdrive_access_token');
        if (!$tokenJson) {
            throw new \Exception('Google Drive not connected.');
        }

        $accessToken = json_decode($tokenJson, true);
        $client->setAccessToken($accessToken);

        if ($client->isAccessTokenExpired()) {
            $refreshToken = Setting::get('gdrive_refresh_token');
            if ($refreshToken) {
                $newToken = $client->fetchAccessTokenWithRefreshToken($refreshToken);
                if (!isset($newToken['error'])) {
                    $accessToken = array_merge($accessToken, $newToken);
                    Setting::set('gdrive_access_token', json_encode($accessToken), 'general');
                    $client->setAccessToken($accessToken);
                } else {
                    Setting::set('gdrive_connected', 'false', 'general');
                    throw new \Exception('Refresh token expired, please reconnect.');
                }
            } else {
                Setting::set('gdrive_connected', 'false', 'general');
                throw new \Exception('OAuth session expired, please reconnect.');
            }
        }

        return $client;
    }

    public function uploadFile(string $filePath, string $filename, string $orderNumber = null)
    {
        try {
            $client = $this->getAuthenticatedClient();
            $driveService = new Drive($client);

            $parentId = Setting::get('gdrive_folder_id');
            
            if ($orderNumber) {
                $parentId = $this->getOrCreateFolder($driveService, "PHC_Orders_" . $orderNumber, $parentId);
            }

            $fileMetadata = new DriveFile([
                'name' => $filename,
                'parents' => $parentId ? [$parentId] : []
            ]);

            $content = file_get_contents($filePath);
            $mimeType = mime_content_type($filePath);

            $file = $driveService->files->create($fileMetadata, [
                'data' => $content,
                'mimeType' => $mimeType,
                'uploadType' => 'multipart',
                'fields' => 'id,webViewLink,webContentLink'
            ]);

            // Make the file publicly viewable so the thumbnail works in Laravel
            try {
                $permission = new \Google\Service\Drive\Permission([
                    'type' => 'anyone',
                    'role' => 'reader',
                ]);
                $driveService->permissions->create($file->id, $permission);
            } catch (\Exception $e) {
                // Ignore permission error if API restricts it
            }

            return [
                'id' => $file->id,
                'web_view_link' => $file->webViewLink,
                'web_content_link' => "https://drive.usercontent.google.com/download?id=" . $file->id . "&export=view&authuser=0"
            ];
        } catch (\Exception $e) {
            \Log::error("Google Drive Upload Error: " . $e->getMessage());
            throw $e;
        }
    }

    protected function getOrCreateFolder(Drive $driveService, string $folderName, string $parentId = null)
    {
        $query = "name = '{$folderName}' and mimeType = 'application/vnd.google-apps.folder' and trashed = false";
        if ($parentId) {
            $query .= " and '{$parentId}' in parents";
        }

        $results = $driveService->files->listFiles([
            'q' => $query,
            'spaces' => 'drive',
            'fields' => 'files(id)'
        ]);

        if (count($results->getFiles()) > 0) {
            return $results->getFiles()[0]->id;
        }

        $fileMetadata = new DriveFile([
            'name' => $folderName,
            'mimeType' => 'application/vnd.google-apps.folder',
            'parents' => $parentId ? [$parentId] : []
        ]);

        $folder = $driveService->files->create($fileMetadata, [
            'fields' => 'id'
        ]);

        return $folder->id;
    }
}