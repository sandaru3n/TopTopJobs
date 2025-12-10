<?php
/**
 * Debug Image URLs
 * 
 * This script helps debug image URL generation and serving
 * Access: /api/debug-images.php
 */

header('Content-Type: text/html; charset=utf-8');

// Load helper
require_once __DIR__ . '/../app/Helpers/image_helper.php';

?>
<!DOCTYPE html>
<html>
<head>
    <title>Image URL Debug Tool</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; max-width: 1200px; margin: 0 auto; background: #f5f5f5; }
        .container { background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        h1 { color: #333; border-bottom: 2px solid #007bff; padding-bottom: 10px; }
        h2 { color: #555; margin-top: 30px; }
        .test-case { background: #f8f9fa; padding: 15px; margin: 10px 0; border-left: 4px solid #007bff; border-radius: 4px; }
        .input { color: #666; font-family: monospace; background: #e9ecef; padding: 5px 10px; border-radius: 3px; }
        .output { color: #28a745; font-family: monospace; background: #d4edda; padding: 5px 10px; border-radius: 3px; margin-top: 5px; }
        .error { color: #dc3545; font-family: monospace; background: #f8d7da; padding: 5px 10px; border-radius: 3px; margin-top: 5px; }
        .info { background: #d1ecf1; border-left: 4px solid #17a2b8; padding: 15px; margin: 20px 0; border-radius: 4px; }
        code { background: #f4f4f4; padding: 2px 6px; border-radius: 3px; }
        table { width: 100%; border-collapse: collapse; margin: 20px 0; }
        th, td { padding: 10px; text-align: left; border: 1px solid #ddd; }
        th { background: #007bff; color: white; }
        tr:nth-child(even) { background: #f8f9fa; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔍 Image URL Debug Tool</h1>
        
        <div class="info">
            <h3>Server Information</h3>
            <ul>
                <li><strong>HTTP_HOST:</strong> <?= htmlspecialchars($_SERVER['HTTP_HOST'] ?? 'Not set') ?></li>
                <li><strong>HTTPS:</strong> <?= (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'Yes' : 'No' ?></li>
                <li><strong>HTTP_X_FORWARDED_PROTO:</strong> <?= htmlspecialchars($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? 'Not set') ?></li>
                <li><strong>SERVER_PORT:</strong> <?= htmlspecialchars($_SERVER['SERVER_PORT'] ?? 'Not set') ?></li>
                <li><strong>SCRIPT_NAME:</strong> <?= htmlspecialchars($_SERVER['SCRIPT_NAME'] ?? 'Not set') ?></li>
                <li><strong>REQUEST_URI:</strong> <?= htmlspecialchars($_SERVER['REQUEST_URI'] ?? 'Not set') ?></li>
            </ul>
        </div>

        <h2>Test Cases</h2>
        
        <?php
        $testCases = [
            'Relative path with leading slash' => '/uploads/company_logos/test.png',
            'Relative path without leading slash' => 'uploads/company_logos/test.png',
            'Path with company_logos' => 'company_logos/test.png',
            'Path with profile_pictures' => 'profile_pictures/test.png',
            'Full localhost URL' => 'http://localhost/uploads/company_logos/test.png',
            'Full toptopjobs.local URL' => 'http://toptopjobs.local/uploads/company_logos/test.png',
            'Full production URL' => 'https://www.toptopjobs.com/uploads/company_logos/test.png',
            'URL with /api/uploads/' => 'https://www.toptopjobs.com/api/uploads/company_logos/test.png',
            'Empty string' => '',
            'NULL' => null,
            'Data URI placeholder' => 'data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSI0OCIgaGVpZ2h0PSI0OCIgdmlld0JveD0iMCAwIDQ4IDQ4Ij48cmVjdCB3aWR0aD0iNDgiIGhlaWdodD0iNDgiIGZpbGw9IiNlNWU3ZWIiLz48dGV4dCB4PSI1MCUiIHk9IjUwJSIgZm9udC1mYW1pbHk9IkFyaWFsLCBzYW5zLXNlcmlmIiBmb250LXNpemU9IjE2IiBmaWxsPSIjOWNhM2FmIiB0ZXh0LWFuY2hvcj0ibWlkZGxlIiBkb21pbmFudC1iYXNlbGluZT0ibWlkZGxlIj5Mb2dvPC90ZXh0Pjwvc3ZnPg==',
        ];
        
        foreach ($testCases as $description => $input) {
            echo '<div class="test-case">';
            echo '<strong>' . htmlspecialchars($description) . '</strong><br>';
            echo '<span class="input">Input: ' . ($input === null ? 'NULL' : htmlspecialchars($input)) . '</span><br>';
            
            try {
                $result = fix_image_url($input, 48);
                if ($result === null) {
                    echo '<span class="output">Output: NULL (frontend will use placeholder)</span>';
                } else {
                    echo '<span class="output">Output: ' . htmlspecialchars($result) . '</span>';
                }
            } catch (Exception $e) {
                echo '<span class="error">Error: ' . htmlspecialchars($e->getMessage()) . '</span>';
            }
            
            echo '</div>';
        }
        ?>
        
        <h2>Upload Path Helper Test</h2>
        <?php
        $uploadTests = [
            'company_logos/test.png' => upload_path('company_logos/test.png'),
            'profile_pictures/user.jpg' => upload_path('profile_pictures/user.jpg'),
            '/uploads/company_logos/test.png' => upload_path('/uploads/company_logos/test.png'),
            'api/uploads/company_logos/test.png' => upload_path('api/uploads/company_logos/test.png'),
        ];
        
        echo '<table>';
        echo '<tr><th>Input</th><th>Output (upload_path)</th></tr>';
        foreach ($uploadTests as $input => $output) {
            echo '<tr>';
            echo '<td><code>' . htmlspecialchars($input) . '</code></td>';
            echo '<td><code>' . htmlspecialchars($output) . '</code></td>';
            echo '</tr>';
        }
        echo '</table>';
        ?>
        
        <h2>Upload URL Helper Test</h2>
        <?php
        $uploadUrlTests = [
            'company_logos/test.png' => upload_url('company_logos/test.png'),
            'profile_pictures/user.jpg' => upload_url('profile_pictures/user.jpg'),
        ];
        
        echo '<table>';
        echo '<tr><th>Input</th><th>Output (upload_url)</th></tr>';
        foreach ($uploadUrlTests as $input => $output) {
            echo '<tr>';
            echo '<td><code>' . htmlspecialchars($input) . '</code></td>';
            echo '<td><code>' . htmlspecialchars($output) . '</code></td>';
            echo '</tr>';
        }
        echo '</table>';
        ?>
        
        <div class="info">
            <h3>📝 Notes</h3>
            <ul>
                <li>All image URLs should be in format: <code>https://www.toptopjobs.com/uploads/...</code></li>
                <li>No <code>/api/</code> prefix should appear in final URLs</li>
                <li>NULL values are returned when no logo exists (frontend handles with placeholder)</li>
                <li>Images are served directly by Apache, bypassing CodeIgniter routing</li>
            </ul>
        </div>
    </div>
</body>
</html>

