<?php
/**
 * Test Image URL Fixing
 * Access: /api/test-image-fix.php
 */

require_once __DIR__ . '/jobs.php';

header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html>
<head>
    <title>Image URL Fix Test</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; }
        .test { margin: 20px 0; padding: 15px; border: 1px solid #ddd; border-radius: 5px; }
        .success { background: #d4edda; border-color: #c3e6cb; }
        .error { background: #f8d7da; border-color: #f5c6cb; }
        .info { background: #d1ecf1; border-color: #bee5eb; }
        code { background: #f4f4f4; padding: 2px 6px; border-radius: 3px; }
        img { max-width: 100px; max-height: 100px; border: 1px solid #ddd; margin: 10px 0; }
    </style>
</head>
<body>
    <h1>Image URL Fix Test</h1>
    
    <div class="info">
        <h2>Server Information</h2>
        <p><strong>HTTP_HOST:</strong> <?= htmlspecialchars($_SERVER['HTTP_HOST'] ?? 'not set') ?></p>
        <p><strong>HTTPS:</strong> <?= (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'Yes' : 'No' ?></p>
        <p><strong>Protocol:</strong> <?= (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http' ?></p>
    </div>
    
    <?php
    // Test cases
    $testCases = [
        'NULL value' => null,
        'Empty string' => '',
        'Relative path with slash' => '/uploads/company_logos/1765274392_f778291c07e12d5cb52a.png',
        'Relative path without slash' => 'uploads/company_logos/1765274392_f778291c07e12d5cb52a.png',
        'Full URL (production)' => 'https://www.toptopjobs.com/uploads/company_logos/1765274392_f778291c07e12d5cb52a.png',
        'Full URL with /api/' => 'https://www.toptopjobs.com/api/uploads/company_logos/1765274392_f778291c07e12d5cb52a.png',
        'Localhost URL' => 'http://toptopjobs.local/uploads/company_logos/1765274392_f778291c07e12d5cb52a.png',
        'Data URI' => 'data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSI0OCIgaGVpZ2h0PSI0OCIgdmlld0JveD0iMCAwIDQ4IDQ4Ij48cmVjdCB3aWR0aD0iNDgiIGhlaWdodD0iNDgiIGZpbGw9IiNlNWU3ZWIiLz48dGV4dCB4PSI1MCUiIHk9IjUwJSIgZm9udC1mYW1pbHk9IkFyaWFsLCBzYW5zLXNlcmlmIiBmb250LXNpemU9IjE2IiBmaWxsPSIjOWNhM2FmIiB0ZXh0LWFuY2hvcj0ibWlkZGxlIiBkb21pbmFudC1iYXNlbGluZT0ibWlkZGxlIj5Mb2dvPC90ZXh0Pjwvc3ZnPg==',
    ];
    
    foreach ($testCases as $testName => $testUrl) {
        $result = checkImageUrl($testUrl, 48);
        $isPlaceholder = strpos($result, 'data:image/svg+xml') === 0;
        $isValidUrl = !$isPlaceholder && (strpos($result, 'http://') === 0 || strpos($result, 'https://') === 0 || strpos($result, '/uploads/') !== false);
        
        echo '<div class="test ' . ($isValidUrl ? 'success' : ($isPlaceholder && ($testUrl === null || $testUrl === '') ? 'info' : 'error')) . '">';
        echo '<h3>' . htmlspecialchars($testName) . '</h3>';
        echo '<p><strong>Input:</strong> <code>' . htmlspecialchars($testUrl ?: '(null/empty)') . '</code></p>';
        echo '<p><strong>Output:</strong> <code>' . htmlspecialchars($result) . '</code></p>';
        echo '<p><strong>Type:</strong> ' . ($isPlaceholder ? 'Placeholder' : 'Valid URL') . '</p>';
        
        if (!$isPlaceholder) {
            echo '<p><strong>Preview:</strong></p>';
            echo '<img src="' . htmlspecialchars($result) . '" alt="Test" onerror="this.style.border=\'2px solid red\'; this.alt=\'Failed to load\';">';
        } else {
            echo '<p><strong>Preview:</strong></p>';
            echo '<img src="' . htmlspecialchars($result) . '" alt="Placeholder">';
        }
        
        echo '</div>';
    }
    ?>
    
    <div class="info">
        <h2>Expected Behavior</h2>
        <ul>
            <li>NULL/Empty → Placeholder (green info box)</li>
            <li>Relative paths → Converted to full URL (green success box)</li>
            <li>Full URLs → Returned as-is or fixed (green success box)</li>
            <li>URLs with /api/uploads/ → Fixed to /uploads/ (green success box)</li>
            <li>Localhost URLs → Converted to production domain (green success box)</li>
        </ul>
    </div>
</body>
</html>

