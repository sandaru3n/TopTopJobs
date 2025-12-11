<?php

namespace App\Services;

use CodeIgniter\HTTP\CURLRequest;
use Config\Services;

class GoogleIndexingService
{
    protected $accessToken;
    protected $serviceAccountPath;
    protected $baseUrl;
    protected $enabled;

    public function __construct()
    {
        $this->baseUrl = rtrim(base_url(), '/');
        // Path to service account JSON file (should be stored securely, outside public directory)
        $this->serviceAccountPath = APPPATH . 'Config/google-service-account.json';
        
        // Check if Google Indexing API is enabled (set in .env or config)
        $this->enabled = env('GOOGLE_INDEXING_ENABLED', false);
    }

    /**
     * Get OAuth 2.0 access token using service account
     * Uses Google PHP Client Library if available, otherwise returns null
     */
    protected function getAccessToken()
    {
        if (!$this->enabled) {
            return null;
        }

        // Check if we have a cached token
        $cache = \Config\Services::cache();
        $cachedToken = $cache->get('google_indexing_token');
        
        if ($cachedToken) {
            return $cachedToken;
        }

        // Check if service account file exists
        if (!file_exists($this->serviceAccountPath)) {
            log_message('debug', 'Google service account file not found. Indexing API disabled.');
            return null;
        }

        // Try to use Google PHP Client Library if available
        if (class_exists('\Google_Client')) {
            try {
                $client = new \Google_Client();
                $client->setAuthConfig($this->serviceAccountPath);
                $client->addScope('https://www.googleapis.com/auth/indexing');
                $client->setAccessType('offline');
                
                // Get access token
                $accessToken = $client->fetchAccessTokenWithAssertion();
                
                if (isset($accessToken['access_token'])) {
                    // Cache token for 50 minutes (tokens expire in 1 hour)
                    $cache->save('google_indexing_token', $accessToken['access_token'], 3000);
                    return $accessToken['access_token'];
                }
            } catch (\Exception $e) {
                log_message('error', 'Google Client Library error: ' . $e->getMessage());
            }
        } else {
            // Google PHP Client Library not installed
            // User needs to run: composer require google/apiclient
            log_message('debug', 'Google PHP Client Library not found. Install with: composer require google/apiclient');
        }

        return null;
    }

    /**
     * Notify Google that a URL has been updated (new or updated job posting)
     */
    public function notifyUrlUpdated($url)
    {
        if (!$this->enabled) {
            return false;
        }

        $accessToken = $this->getAccessToken();
        
        if (!$accessToken) {
            // Silently fail if not configured - don't spam logs
            return false;
        }

        $client = \Config\Services::curlrequest();
        
        try {
            $response = $client->post('https://indexing.googleapis.com/v3/urlNotifications:publish', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $accessToken,
                    'Content-Type' => 'application/json',
                ],
                'json' => [
                    'url' => $url,
                    'type' => 'URL_UPDATED',
                ],
                'timeout' => 10,
            ]);

            $statusCode = $response->getStatusCode();
            $body = json_decode($response->getBody(), true);

            if ($statusCode === 200) {
                log_message('info', 'Successfully notified Google Indexing API for URL: ' . $url);
                return true;
            } else {
                log_message('error', 'Google Indexing API error (Status ' . $statusCode . '): ' . json_encode($body));
                return false;
            }
        } catch (\Exception $e) {
            log_message('error', 'Exception while notifying Google Indexing API: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Notify Google that a URL has been deleted
     */
    public function notifyUrlDeleted($url)
    {
        if (!$this->enabled) {
            return false;
        }

        $accessToken = $this->getAccessToken();
        
        if (!$accessToken) {
            // Silently fail if not configured - don't spam logs
            return false;
        }

        $client = \Config\Services::curlrequest();
        
        try {
            $response = $client->post('https://indexing.googleapis.com/v3/urlNotifications:publish', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $accessToken,
                    'Content-Type' => 'application/json',
                ],
                'json' => [
                    'url' => $url,
                    'type' => 'URL_DELETED',
                ],
            ]);

            $statusCode = $response->getStatusCode();
            $body = json_decode($response->getBody(), true);

            if ($statusCode === 200) {
                log_message('info', 'Successfully notified Google Indexing API to delete URL: ' . $url);
                return true;
            } else {
                log_message('error', 'Google Indexing API error: ' . json_encode($body));
                return false;
            }
        } catch (\Exception $e) {
            log_message('error', 'Exception while notifying Google Indexing API: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Get notification status for a URL
     */
    public function getNotificationStatus($url)
    {
        $accessToken = $this->getAccessToken();
        
        if (!$accessToken) {
            return null;
        }

        $encodedUrl = urlencode($url);
        $client = \Config\Services::curlrequest();
        
        try {
            $response = $client->get('https://indexing.googleapis.com/v3/urlNotifications/metadata?url=' . $encodedUrl, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $accessToken,
                ],
            ]);

            $statusCode = $response->getStatusCode();
            
            if ($statusCode === 200) {
                return json_decode($response->getBody(), true);
            }
            
            return null;
        } catch (\Exception $e) {
            log_message('error', 'Exception while getting notification status: ' . $e->getMessage());
            return null;
        }
    }
}

/**
 * Helper function to build job URL from slug
 */
if (!function_exists('buildJobUrl')) {
    function buildJobUrl($slug)
    {
        $baseUrl = rtrim(base_url(), '/');
        return $baseUrl . '/job/' . $slug . '/';
    }
}

