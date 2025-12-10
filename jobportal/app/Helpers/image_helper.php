<?php

if (!function_exists('fix_image_url')) {
    /**
     * Fix image URL - converts localhost/local domain URLs to current production domain
     * This is a wrapper function that can be used in controllers and views
     * 
     * @param string|null $url The image URL to fix
     * @param int $placeholderSize Size for placeholder if URL is invalid
     * @return string Valid image URL or data URI placeholder
     */
    function fix_image_url($url, $placeholderSize = 48) {
        // If empty or null, return null (not placeholder) so frontend can handle it
        if (empty($url) || $url === null) {
            return null;
        }
        
        $url = (string)$url;
        $url = trim($url);
        
        // If empty after trimming, return null (not placeholder)
        if ($url === '') {
            return null;
        }
        
        // If it's already a data URI placeholder, return null (not placeholder) so frontend can handle it
        // But if it's a valid data URI image (not our placeholder), return as-is
        if (strpos($url, 'data:') === 0) {
            // Check if it's our placeholder SVG - if so, return null
            if (strpos($url, 'data:image/svg+xml') !== false && strpos($url, 'Logo') !== false) {
                return null;
            }
            // Otherwise, it's a valid data URI image, return as-is
            return $url;
        }
        
        // If it's a via.placeholder.com URL, return null
        if (strpos($url, 'via.placeholder.com') !== false) {
            return null;
        }
        
        // Remove /api/ prefix from uploads paths FIRST (fixes old cached URLs)
        if (stripos($url, '/api/uploads/') !== false) {
            $url = str_ireplace('/api/uploads/', '/uploads/', $url);
        }
        
        // Determine current domain and protocol
        $isHttps = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || 
                   (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https') ||
                   (!empty($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == 443) ||
                   (isset($_SERVER['HTTP_HOST']) && (stripos($_SERVER['HTTP_HOST'], 'toptopjobs.com') !== false || stripos($_SERVER['HTTP_HOST'], 'www.') !== false));
        
        $protocol = $isHttps ? 'https' : 'http';
        $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
        
        // PRIORITY 1: If it's a relative path (starts with /), convert to absolute URL
        // This handles: /uploads/company_logos/file.png
        // NO basePath needed - images are served directly from root
        if (strpos($url, '/') === 0) {
            $path = ltrim($url, '/');
            // Simple URL: protocol://host/path (no basePath for uploads)
            return $protocol . '://' . $host . '/' . $path;
        }
        
        // PRIORITY 2: If it looks like a path without leading slash, add it and convert
        // This handles: uploads/company_logos/file.png
        if (stripos($url, 'uploads/') === 0 || stripos($url, 'company_logos/') !== false || stripos($url, 'profile_pictures/') !== false) {
            // Ensure it starts with uploads/
            if (stripos($url, 'uploads/') !== 0) {
                if (stripos($url, 'company_logos/') !== false) {
                    $url = 'uploads/' . $url;
                } elseif (stripos($url, 'profile_pictures/') !== false) {
                    $url = 'uploads/' . $url;
                }
            }
            // Simple URL: protocol://host/path (no basePath for uploads)
            return $protocol . '://' . $host . '/' . $url;
        }
        
        // PRIORITY 3: If it contains localhost, 127.0.0.1, or toptopjobs.local, convert to current domain
        if (stripos($url, 'localhost') !== false || 
            stripos($url, '127.0.0.1') !== false || 
            stripos($url, 'toptopjobs.local') !== false ||
            stripos($url, '.local/') !== false) {
            // Extract the path from the URL
            $parsed = parse_url($url);
            $path = isset($parsed['path']) ? $parsed['path'] : $url;
            
            // Remove domain parts and get just the path
            $path = preg_replace('#https?://[^/]+/?#i', '', $path);
            $path = ltrim($path, '/');
            
            // Rebuild URL with current domain (no basePath for uploads)
            return $protocol . '://' . $host . '/' . $path;
        }
        
        // PRIORITY 4: If it's a valid absolute URL (http/https), fix /api/uploads/ and convert HTTP to HTTPS
        if ((stripos($url, 'http://') === 0 || stripos($url, 'https://') === 0)) {
            // Remove /api/ prefix from uploads if present
            if (stripos($url, '/api/uploads/') !== false) {
                $url = str_ireplace('/api/uploads/', '/uploads/', $url);
            }
            // Convert HTTP to HTTPS to prevent mixed content warnings on production
            if ($isHttps && stripos($url, 'http://') === 0) {
                $url = str_replace('http://', 'https://', $url);
            }
            return $url;
        }
        
        // If we can't determine, return null (not placeholder) so frontend can handle it
        return null;
    }
}

if (!function_exists('get_placeholder_image')) {
    /**
     * Get placeholder image as data URI
     */
    function get_placeholder_image($size = 48) {
        $svg = '<svg xmlns="http://www.w3.org/2000/svg" width="' . $size . '" height="' . $size . '" viewBox="0 0 ' . $size . ' ' . $size . '"><rect width="' . $size . '" height="' . $size . '" fill="#e5e7eb"/><text x="50%" y="50%" font-family="Arial, sans-serif" font-size="' . ($size / 3) . '" fill="#9ca3af" text-anchor="middle" dominant-baseline="middle">Logo</text></svg>';
        return 'data:image/svg+xml;base64,' . base64_encode($svg);
    }
}

if (!function_exists('upload_path')) {
    /**
     * Get relative path for storing in database
     * Returns path in format: /uploads/company_logos/filename.png
     * 
     * @param string $path Relative path from uploads folder (e.g., 'company_logos/filename.png')
     * @return string Relative path starting with /uploads/
     */
    function upload_path($path) {
        // Remove leading slash if present
        $path = ltrim($path, '/');
        
        // Remove /api/ prefix if present
        if (strpos($path, 'api/uploads/') === 0) {
            $path = str_replace('api/uploads/', 'uploads/', $path);
        }
        
        // Ensure path starts with uploads/
        if (strpos($path, 'uploads/') !== 0) {
            $path = 'uploads/' . $path;
        }
        
        // Return with leading slash for relative path
        return '/' . $path;
    }
}

if (!function_exists('upload_url')) {
    /**
     * Generate correct upload URL without /api/ prefix
     * This ensures uploads are always accessible at /uploads/... format
     * 
     * @param string $path Relative path from uploads folder (e.g., 'company_logos/filename.png')
     * @return string Full URL to the upload file
     */
    function upload_url($path) {
        // Use upload_path to get the relative path, then convert to full URL
        $relativePath = upload_path($path);
        $relativePath = ltrim($relativePath, '/'); // Remove leading slash for URL building
        
        // Determine current domain and protocol
        $isHttps = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || 
                   (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https') ||
                   (!empty($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == 443) ||
                   (isset($_SERVER['HTTP_HOST']) && (stripos($_SERVER['HTTP_HOST'], 'toptopjobs.com') !== false || stripos($_SERVER['HTTP_HOST'], 'www.') !== false));
        
        $protocol = $isHttps ? 'https' : 'http';
        $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
        
        return $protocol . '://' . $host . '/' . $relativePath;
    }
}

if (!function_exists('extract_upload_path')) {
    /**
     * Extract relative path from a full URL
     * Converts: http://toptopjobs.local/uploads/company_logos/file.png
     * To: /uploads/company_logos/file.png
     * 
     * @param string|null $url Full URL or relative path
     * @return string|null Relative path or null if invalid
     */
    function extract_upload_path($url) {
        if (empty($url) || $url === null) {
            return null;
        }
        
        $url = trim($url);
        
        // If already a relative path starting with /uploads/, return as-is
        if (strpos($url, '/uploads/') === 0) {
            return $url;
        }
        
        // If it's a path without leading slash but starts with uploads/
        if (strpos($url, 'uploads/') === 0) {
            return '/' . $url;
        }
        
        // If it's a full URL, extract the path
        if (filter_var($url, FILTER_VALIDATE_URL) || strpos($url, 'http://') === 0 || strpos($url, 'https://') === 0) {
            $parsed = parse_url($url);
            $path = isset($parsed['path']) ? $parsed['path'] : '';
            
            // Remove /api/ prefix if present
            if (strpos($path, '/api/uploads/') === 0) {
                $path = str_replace('/api/uploads/', '/uploads/', $path);
            }
            
            // Ensure it starts with /uploads/
            if (strpos($path, '/uploads/') === 0) {
                return $path;
            }
        }
        
        // If it contains uploads/ anywhere, try to extract it
        if (stripos($url, 'uploads/') !== false) {
            $matches = [];
            if (preg_match('#(/uploads/[^?\s]+)#i', $url, $matches)) {
                return $matches[1];
            }
            // Try without leading slash
            if (preg_match('#(uploads/[^?\s]+)#i', $url, $matches)) {
                return '/' . $matches[1];
            }
        }
        
        return null;
    }
}

