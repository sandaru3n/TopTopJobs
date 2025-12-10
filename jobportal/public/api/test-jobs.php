<?php
/**
 * Test Jobs API Endpoint
 * Simple test to check if API is working
 */

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

$test = [
    'success' => true,
    'message' => 'API is working',
    'timestamp' => date('Y-m-d H:i:s'),
    'server' => [
        'php_version' => PHP_VERSION,
        'server_software' => $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown',
        'request_method' => $_SERVER['REQUEST_METHOD'] ?? 'Unknown',
        'request_uri' => $_SERVER['REQUEST_URI'] ?? 'Unknown',
    ],
    'database' => [
        'connection' => 'testing...'
    ]
];

// Test database connection
require_once __DIR__ . '/jobs.php';

$conn = getDBConnection();
if ($conn) {
    $test['database']['connection'] = 'success';
    $test['database']['host'] = $conn->host_info;
    
    // Test query
    $result = $conn->query("SELECT COUNT(*) as count FROM jobs WHERE status = 'active'");
    if ($result) {
        $row = $result->fetch_assoc();
        $test['database']['active_jobs'] = (int)$row['count'];
    }
    
    $conn->close();
} else {
    $test['database']['connection'] = 'failed';
    $test['database']['error'] = 'Could not connect to database';
}

echo json_encode($test, JSON_PRETTY_PRINT);

