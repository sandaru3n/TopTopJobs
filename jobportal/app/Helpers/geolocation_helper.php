<?php

if (!function_exists('getCountryFromIP')) {
    /**
     * Get country information from IP address
     * Uses free IP geolocation API (ip-api.com) as fallback
     * Defaults to Sri Lanka (LK) if detection fails
     * 
     * @param string|null $ip IP address (null to auto-detect)
     * @return array ['country' => string, 'country_code' => string]
     */
    function getCountryFromIP($ip = null)
    {
        // Default to Sri Lanka
        $defaultCountry = 'Sri Lanka';
        $defaultCountryCode = 'LK';
        
        // Get IP address if not provided
        if ($ip === null) {
            $request = \Config\Services::request();
            $ip = $request->getIPAddress();
        }
        
        // Skip for localhost/private IPs
        if (empty($ip) || $ip === '127.0.0.1' || $ip === '::1' || 
            filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) === false) {
            return [
                'country' => $defaultCountry,
                'country_code' => $defaultCountryCode
            ];
        }
        
        // Try to get country from IP using ip-api.com (free, no API key required)
        try {
            $url = "http://ip-api.com/json/{$ip}?fields=status,country,countryCode";
            $context = stream_context_create([
                'http' => [
                    'timeout' => 3,
                    'method' => 'GET'
                ]
            ]);
            
            $response = @file_get_contents($url, false, $context);
            
            if ($response !== false) {
                $data = json_decode($response, true);
                
                if (isset($data['status']) && $data['status'] === 'success' && 
                    isset($data['country']) && isset($data['countryCode'])) {
                    return [
                        'country' => $data['country'],
                        'country_code' => strtoupper($data['countryCode'])
                    ];
                }
            }
        } catch (\Exception $e) {
            // Log error but don't break the application
            log_message('error', 'IP Geolocation failed: ' . $e->getMessage());
        }
        
        // Fallback to default (Sri Lanka)
        return [
            'country' => $defaultCountry,
            'country_code' => $defaultCountryCode
        ];
    }
}

if (!function_exists('getCountryList')) {
    /**
     * Get list of countries with codes
     * Common countries for job postings
     * 
     * @return array
     */
    function getCountryList()
    {
        return [
            'LK' => 'Sri Lanka',
            'IN' => 'India',
            'US' => 'United States',
            'UK' => 'United Kingdom',
            'CA' => 'Canada',
            'AU' => 'Australia',
            'SG' => 'Singapore',
            'MY' => 'Malaysia',
            'AE' => 'United Arab Emirates',
            'PK' => 'Pakistan',
            'BD' => 'Bangladesh',
            'NP' => 'Nepal',
            'MM' => 'Myanmar',
            'TH' => 'Thailand',
            'PH' => 'Philippines',
            'ID' => 'Indonesia',
            'VN' => 'Vietnam',
            'CN' => 'China',
            'JP' => 'Japan',
            'KR' => 'South Korea',
            'NZ' => 'New Zealand',
            'DE' => 'Germany',
            'FR' => 'France',
            'IT' => 'Italy',
            'ES' => 'Spain',
            'NL' => 'Netherlands',
            'BE' => 'Belgium',
            'CH' => 'Switzerland',
            'AT' => 'Austria',
            'SE' => 'Sweden',
            'NO' => 'Norway',
            'DK' => 'Denmark',
            'FI' => 'Finland',
            'PL' => 'Poland',
            'PT' => 'Portugal',
            'GR' => 'Greece',
            'IE' => 'Ireland',
            'BR' => 'Brazil',
            'MX' => 'Mexico',
            'AR' => 'Argentina',
            'ZA' => 'South Africa',
            'EG' => 'Egypt',
            'NG' => 'Nigeria',
            'KE' => 'Kenya',
            'GH' => 'Ghana',
        ];
    }
}

