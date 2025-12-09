<?php
/**
 * Verify URL Conversion
 * 
 * This script tests if the checkImageUrl function is working correctly
 * Access: /api/verify-url-conversion.php
 */

require_once __DIR__ . '/jobs.php';

header('Content-Type: application/json');

$testUrls = [
    'http://toptopjobs.local/uploads/company_logos/1765274392_f778291c07e12d5cb52a.png',
    'http://toptopjobs.local/uploads/company_logos/1765265652_eff844fb5eea6e0cee1e.png',
    'https://toptopjobs.local/uploads/logo.png',
    '/uploads/logo.png',
    'uploads/logo.png',
    'https://example.com/image.png',
    'data:image/svg+xml;base64,PHN2Zy...',
    'https://via.placeholder.com/48',
    '',
    null,
];

$results = [];
foreach ($testUrls as $url) {
    $original = $url ?? '(null)';
    $converted = checkImageUrl($url, 48);
    $hasLocal = strpos($converted, 'toptopjobs.local') !== false;
    
    $results[] = [
        'original' => $original,
        'converted' => $converted,
        'has_local_url' => $hasLocal,
        'is_data_uri' => strpos($converted, 'data:') === 0,
        'status' => $hasLocal ? 'FAILED - Still contains local URL' : 'OK'
    ];
}

// Test with actual server environment
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$host = $_SERVER['HTTP_HOST'] ?? 'localhost';

$response = [
    'server_info' => [
        'protocol' => $protocol,
        'host' => $host,
        'expected_base_url' => $protocol . '://' . $host,
    ],
    'test_results' => $results,
    'summary' => [
        'total_tests' => count($results),
        'passed' => count(array_filter($results, fn($r) => !$r['has_local_url'])),
        'failed' => count(array_filter($results, fn($r) => $r['has_local_url'])),
    ]
];

echo json_encode($response, JSON_PRETTY_PRINT);

