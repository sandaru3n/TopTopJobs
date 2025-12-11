<?php

if (!function_exists('getCountryFromIP')) {
    /**
     * Get country information from IP address
     * Uses ip-api.com (free, no API key required, 45 requests/minute)
     * Falls back to ipapi.co if first fails
     * 
     * @param string|null $ip IP address (null to use current request IP)
     * @return array|null Returns ['country' => string, 'country_code' => string] or null on failure
     */
    function getCountryFromIP(?string $ip = null): ?array
    {
        // Get IP address
        if ($ip === null) {
            $ip = getClientIP();
        }
        
        // Skip for localhost/private IPs
        if (empty($ip) || $ip === '127.0.0.1' || $ip === '::1' || !filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
            // Default to US for localhost/private IPs
            return [
                'country' => 'United States',
                'country_code' => 'US'
            ];
        }
        
        // Try ip-api.com first (free, no API key)
        $result = getCountryFromIPAPI($ip);
        if ($result !== null) {
            return $result;
        }
        
        // Fallback to ipapi.co
        $result = getCountryFromIPAPICO($ip);
        if ($result !== null) {
            return $result;
        }
        
        // Final fallback to US
        return [
            'country' => 'United States',
            'country_code' => 'US'
        ];
    }
}

if (!function_exists('getClientIP')) {
    /**
     * Get client IP address from request
     * 
     * @return string
     */
    function getClientIP(): string
    {
        $ipKeys = ['HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR'];
        
        foreach ($ipKeys as $key) {
            if (array_key_exists($key, $_SERVER) === true) {
                foreach (explode(',', $_SERVER[$key]) as $ip) {
                    $ip = trim($ip);
                    if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) !== false) {
                        return $ip;
                    }
                }
            }
        }
        
        return $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
    }
}

if (!function_exists('getCountryFromIPAPI')) {
    /**
     * Get country from ip-api.com
     * 
     * @param string $ip
     * @return array|null
     */
    function getCountryFromIPAPI(string $ip): ?array
    {
        $url = "http://ip-api.com/json/{$ip}?fields=status,country,countryCode";
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 3);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 2);
        $response = @curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        if ($httpCode === 200 && $response) {
            $data = json_decode($response, true);
            if (isset($data['status']) && $data['status'] === 'success' && isset($data['country']) && isset($data['countryCode'])) {
                return [
                    'country' => $data['country'],
                    'country_code' => strtoupper($data['countryCode'])
                ];
            }
        }
        
        return null;
    }
}

if (!function_exists('getCountryFromIPAPICO')) {
    /**
     * Get country from ipapi.co (fallback)
     * 
     * @param string $ip
     * @return array|null
     */
    function getCountryFromIPAPICO(string $ip): ?array
    {
        $url = "https://ipapi.co/{$ip}/json/";
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 3);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 2);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0');
        $response = @curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        if ($httpCode === 200 && $response) {
            $data = json_decode($response, true);
            if (!isset($data['error']) && isset($data['country_name']) && isset($data['country_code'])) {
                return [
                    'country' => $data['country_name'],
                    'country_code' => strtoupper($data['country_code'])
                ];
            }
        }
        
        return null;
    }
}

