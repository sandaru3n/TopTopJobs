<?php
/**
 * Test Image URL Checker
 * 
 * This file demonstrates how to use the checkImageUrl() function
 * to validate and normalize image URLs.
 * 
 * Usage: Access via browser: http://your-domain/api/test-image-url.php
 */

// Include the jobs API to get access to the functions
require_once __DIR__ . '/jobs.php';

header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html>
<head>
    <title>Image URL Checker - Test</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
            background: #f5f5f5;
        }
        .test-case {
            background: white;
            padding: 15px;
            margin: 10px 0;
            border-radius: 5px;
            border-left: 4px solid #007bff;
        }
        .input {
            color: #666;
            font-weight: bold;
        }
        .output {
            color: #28a745;
            margin-top: 5px;
        }
        .error {
            color: #dc3545;
        }
        h1 {
            color: #333;
        }
        .info {
            background: #e7f3ff;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <h1>Image URL Checker - Test Results</h1>
    
    <div class="info">
        <strong>Function:</strong> checkImageUrl($url, $placeholderSize = 48)<br>
        <strong>Purpose:</strong> Validates and normalizes image URLs, returns placeholder if invalid
    </div>

    <?php
    // Test cases
    $testCases = [
        'Empty URL' => '',
        'Data URI' => 'data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSI0OCIgaGVpZ2h0PSI0OCIgdmlld0JveD0iMCAwIDQ4IDQ4Ij48cmVjdCB3aWR0aD0iNDgiIGhlaWdodD0iNDgiIGZpbGw9IiNlNWU3ZWIiLz48dGV4dCB4PSI1MCUiIHk9IjUwJSIgZm9udC1mYW1pbHk9IkFyaWFsLCBzYW5zLXNlcmlmIiBmb250LXNpemU9IjE2IiBmaWxsPSIjOWNhM2FmIiB0ZXh0LWFuY2hvcj0ibWlkZGxlIiBkb21pbmFudC1iYXNlbGluZT0ibWlkZGxlIj5Mb2dvPC90ZXh0Pjwvc3ZnPg==',
        'via.placeholder.com' => 'https://via.placeholder.com/48',
        'Local dev URL' => 'http://toptopjobs.local/uploads/company_logos/logo.png',
        'Relative path' => '/uploads/company_logos/logo.png',
        'Absolute URL (valid)' => 'https://example.com/image.png',
        'Absolute URL (http)' => 'http://example.com/image.png',
        'Invalid URL' => 'not-a-valid-url',
        'Null value' => null,
    ];

    foreach ($testCases as $testName => $testUrl) {
        echo '<div class="test-case">';
        echo '<div class="input">Test: ' . htmlspecialchars($testName) . '</div>';
        echo '<div class="input">Input: <code>' . htmlspecialchars($testUrl ?: '(empty/null)') . '</code></div>';
        
        try {
            $result = checkImageUrl($testUrl, 48);
            echo '<div class="output">Output: <code>' . htmlspecialchars($result) . '</code></div>';
            
            // Show preview if it's a data URI
            if (strpos($result, 'data:image') === 0) {
                echo '<div style="margin-top: 10px;">';
                echo '<img src="' . htmlspecialchars($result) . '" alt="Preview" style="width: 48px; height: 48px; border: 1px solid #ddd; border-radius: 4px;">';
                echo '</div>';
            }
        } catch (Exception $e) {
            echo '<div class="error">Error: ' . htmlspecialchars($e->getMessage()) . '</div>';
        }
        
        echo '</div>';
    }
    ?>

    <div class="info" style="margin-top: 30px;">
        <h3>How to use in your code:</h3>
        <pre style="background: #f8f9fa; padding: 15px; border-radius: 5px; overflow-x: auto;">
// Example 1: Check company logo
$logoUrl = checkImageUrl($job['company_logo'], 48);

// Example 2: Check with custom placeholder size
$largeLogo = checkImageUrl($company['logo'], 150);

// Example 3: Always get a valid image URL
$imageUrl = checkImageUrl($userInput, 64);
// Returns: Valid URL, data URI placeholder, or converted absolute URL
        </pre>
    </div>
</body>
</html>

