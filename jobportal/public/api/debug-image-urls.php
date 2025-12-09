<?php
/**
 * Debug Image URL Conversion
 * 
 * This endpoint shows how URLs are being converted
 * Access: /api/debug-image-urls.php?url=YOUR_URL
 */

require_once __DIR__ . '/jobs.php';

header('Content-Type: application/json');

$testUrl = $_GET['url'] ?? 'http://toptopjobs.local/uploads/company_logos/1765274392_f778291c07e12d5cb52a.png';

$result = [
    'original_url' => $testUrl,
    'converted_url' => checkImageUrl($testUrl, 48),
    'is_data_uri' => strpos(checkImageUrl($testUrl, 48), 'data:') === 0,
    'server_info' => [
        'http_host' => $_SERVER['HTTP_HOST'] ?? 'not set',
        'https' => (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'yes' : 'no',
        'request_uri' => $_SERVER['REQUEST_URI'] ?? 'not set',
    ]
];

// Test multiple URLs
$testUrls = [
    'http://toptopjobs.local/uploads/company_logos/logo.png',
    'http://toptopjobs.local/uploads/company_logos/1765274392_f778291c07e12d5cb52a.png',
    'https://toptopjobs.local/uploads/company_logos/logo.png',
    '/uploads/company_logos/logo.png',
    'uploads/company_logos/logo.png',
    'https://example.com/image.png',
    'data:image/svg+xml;base64,PHN2Zy...',
    'https://via.placeholder.com/48',
];

$results = [];
foreach ($testUrls as $url) {
    $results[] = [
        'input' => $url,
        'output' => checkImageUrl($url, 48),
        'type' => $this->detectUrlType($url),
    ];
}

function detectUrlType($url) {
    if (empty($url)) return 'empty';
    if (strpos($url, 'data:') === 0) return 'data_uri';
    if (strpos($url, 'via.placeholder.com') !== false) return 'via_placeholder';
    if (strpos($url, 'toptopjobs.local') !== false) return 'local_dev';
    if (filter_var($url, FILTER_VALIDATE_URL)) return 'absolute_url';
    return 'relative_path';
}

$result['batch_tests'] = $results;

echo json_encode($result, JSON_PRETTY_PRINT);

