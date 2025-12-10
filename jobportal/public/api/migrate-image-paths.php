<?php
/**
 * Migration Script: Convert Full URLs to Relative Paths
 * 
 * This script converts full URLs in the database to relative paths:
 * - http://toptopjobs.local/uploads/company_logos/file.png → /uploads/company_logos/file.png
 * - https://www.toptopjobs.com/uploads/company_logos/file.png → /uploads/company_logos/file.png
 * 
 * Access: /api/migrate-image-paths.php
 * 
 * WARNING: Backup your database before running this!
 */

require_once __DIR__ . '/jobs.php';

header('Content-Type: text/html; charset=utf-8');

// Load helper
require_once __DIR__ . '/../app/Helpers/image_helper.php';

$conn = getDBConnection();

if (!$conn) {
    die('<h1>Error</h1><p>Could not connect to database.</p>');
}

?>
<!DOCTYPE html>
<html>
<head>
    <title>Image Path Migration</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; max-width: 1200px; margin: 0 auto; }
        .success { background: #d4edda; border: 1px solid #c3e6cb; padding: 15px; border-radius: 5px; margin: 10px 0; }
        .error { background: #f8d7da; border: 1px solid #f5c6cb; padding: 15px; border-radius: 5px; margin: 10px 0; }
        .info { background: #d1ecf1; border: 1px solid #bee5eb; padding: 15px; border-radius: 5px; margin: 10px 0; }
        .warning { background: #fff3cd; border: 1px solid #ffeaa7; padding: 15px; border-radius: 5px; margin: 10px 0; }
        table { width: 100%; border-collapse: collapse; margin: 20px 0; }
        th, td { padding: 10px; text-align: left; border: 1px solid #ddd; }
        th { background: #f8f9fa; }
        code { background: #f4f4f4; padding: 2px 6px; border-radius: 3px; font-size: 0.9em; }
        .btn { display: inline-block; padding: 10px 20px; background: #007bff; color: white; text-decoration: none; border-radius: 5px; margin: 10px 5px; }
        .btn:hover { background: #0056b3; }
        .btn-danger { background: #dc3545; }
        .btn-danger:hover { background: #c82333; }
    </style>
</head>
<body>
    <h1>Image Path Migration Tool</h1>
    
    <?php
    $action = $_GET['action'] ?? 'preview';
    
    if ($action === 'preview') {
        // Preview mode - show what will be changed
        ?>
        <div class="warning">
            <h2>⚠️ Preview Mode</h2>
            <p>This will show you what changes will be made. No changes have been applied yet.</p>
        </div>
        
        <?php
        // Get companies with full URLs
        $companiesQuery = "SELECT id, name, logo FROM companies WHERE logo IS NOT NULL AND logo != '' AND (logo LIKE 'http://%' OR logo LIKE 'https://%')";
        $companiesResult = $conn->query($companiesQuery);
        $companies = $companiesResult ? $companiesResult->fetch_all(MYSQLI_ASSOC) : [];
        
        // Get users with full URLs
        $usersQuery = "SELECT id, email, first_name, last_name, profile_picture FROM users WHERE profile_picture IS NOT NULL AND profile_picture != '' AND (profile_picture LIKE 'http://%' OR profile_picture LIKE 'https://%')";
        $usersResult = $conn->query($usersQuery);
        $users = $usersResult ? $usersResult->fetch_all(MYSQLI_ASSOC) : [];
        
        $totalChanges = count($companies) + count($users);
        
        if ($totalChanges === 0) {
            echo '<div class="success"><h2>✓ No Changes Needed</h2><p>All image paths are already in relative format.</p></div>';
        } else {
            echo '<div class="info">';
            echo '<h2>Found ' . $totalChanges . ' records to update:</h2>';
            echo '<ul>';
            echo '<li><strong>Companies:</strong> ' . count($companies) . ' records</li>';
            echo '<li><strong>Users:</strong> ' . count($users) . ' records</li>';
            echo '</ul>';
            echo '</div>';
            
            if (count($companies) > 0) {
                echo '<h2>Companies to Update</h2>';
                echo '<table>';
                echo '<tr><th>ID</th><th>Name</th><th>Current URL</th><th>New Path</th></tr>';
                foreach ($companies as $company) {
                    $newPath = extract_upload_path($company['logo']);
                    echo '<tr>';
                    echo '<td>' . htmlspecialchars($company['id']) . '</td>';
                    echo '<td>' . htmlspecialchars($company['name']) . '</td>';
                    echo '<td><code>' . htmlspecialchars($company['logo']) . '</code></td>';
                    echo '<td><code>' . htmlspecialchars($newPath ?: 'ERROR') . '</code></td>';
                    echo '</tr>';
                }
                echo '</table>';
            }
            
            if (count($users) > 0) {
                echo '<h2>Users to Update</h2>';
                echo '<table>';
                echo '<tr><th>ID</th><th>Email</th><th>Name</th><th>Current URL</th><th>New Path</th></tr>';
                foreach ($users as $user) {
                    $newPath = extract_upload_path($user['profile_picture']);
                    echo '<tr>';
                    echo '<td>' . htmlspecialchars($user['id']) . '</td>';
                    echo '<td>' . htmlspecialchars($user['email']) . '</td>';
                    echo '<td>' . htmlspecialchars(($user['first_name'] ?? '') . ' ' . ($user['last_name'] ?? '')) . '</td>';
                    echo '<td><code>' . htmlspecialchars($user['profile_picture']) . '</code></td>';
                    echo '<td><code>' . htmlspecialchars($newPath ?: 'ERROR') . '</code></td>';
                    echo '</tr>';
                }
                echo '</table>';
            }
            
            echo '<div class="warning">';
            echo '<h2>⚠️ Ready to Migrate?</h2>';
            echo '<p>Click the button below to apply these changes to the database.</p>';
            echo '<a href="?action=migrate" class="btn btn-danger" onclick="return confirm(\'Are you sure you want to update the database? Make sure you have a backup!\')">Apply Migration</a>';
            echo '</div>';
        }
    } elseif ($action === 'migrate') {
        // Migration mode - apply changes
        ?>
        <div class="info">
            <h2>🔄 Running Migration...</h2>
        </div>
        
        <?php
        $updatedCompanies = 0;
        $updatedUsers = 0;
        $errors = [];
        
        // Update companies
        $companiesQuery = "SELECT id, name, logo FROM companies WHERE logo IS NOT NULL AND logo != '' AND (logo LIKE 'http://%' OR logo LIKE 'https://%')";
        $companiesResult = $conn->query($companiesQuery);
        if ($companiesResult) {
            $companies = $companiesResult->fetch_all(MYSQLI_ASSOC);
            foreach ($companies as $company) {
                $newPath = extract_upload_path($company['logo']);
                if ($newPath) {
                    $stmt = $conn->prepare("UPDATE companies SET logo = ? WHERE id = ?");
                    $stmt->bind_param("si", $newPath, $company['id']);
                    if ($stmt->execute()) {
                        $updatedCompanies++;
                    } else {
                        $errors[] = "Company ID {$company['id']}: " . $stmt->error;
                    }
                    $stmt->close();
                } else {
                    $errors[] = "Company ID {$company['id']}: Could not extract path from: " . $company['logo'];
                }
            }
        }
        
        // Update users
        $usersQuery = "SELECT id, email, profile_picture FROM users WHERE profile_picture IS NOT NULL AND profile_picture != '' AND (profile_picture LIKE 'http://%' OR profile_picture LIKE 'https://%')";
        $usersResult = $conn->query($usersQuery);
        if ($usersResult) {
            $users = $usersResult->fetch_all(MYSQLI_ASSOC);
            foreach ($users as $user) {
                $newPath = extract_upload_path($user['profile_picture']);
                if ($newPath) {
                    $stmt = $conn->prepare("UPDATE users SET profile_picture = ? WHERE id = ?");
                    $stmt->bind_param("si", $newPath, $user['id']);
                    if ($stmt->execute()) {
                        $updatedUsers++;
                    } else {
                        $errors[] = "User ID {$user['id']}: " . $stmt->error;
                    }
                    $stmt->close();
                } else {
                    $errors[] = "User ID {$user['id']}: Could not extract path from: " . $user['profile_picture'];
                }
            }
        }
        
        // Show results
        echo '<div class="success">';
        echo '<h2>✓ Migration Complete!</h2>';
        echo '<ul>';
        echo '<li><strong>Companies updated:</strong> ' . $updatedCompanies . '</li>';
        echo '<li><strong>Users updated:</strong> ' . $updatedUsers . '</li>';
        echo '<li><strong>Total updated:</strong> ' . ($updatedCompanies + $updatedUsers) . '</li>';
        echo '</ul>';
        echo '</div>';
        
        if (!empty($errors)) {
            echo '<div class="error">';
            echo '<h2>⚠️ Errors Encountered:</h2>';
            echo '<ul>';
            foreach ($errors as $error) {
                echo '<li>' . htmlspecialchars($error) . '</li>';
            }
            echo '</ul>';
            echo '</div>';
        }
        
        echo '<div class="info">';
        echo '<p><strong>Next Steps:</strong></p>';
        echo '<ol>';
        echo '<li>Clear the cache: Delete files in <code>public/writable/cache/</code></li>';
        echo '<li>Test your site to ensure images load correctly</li>';
        echo '<li>All new uploads will automatically use relative paths</li>';
        echo '</ol>';
        echo '</div>';
        
        echo '<a href="?" class="btn">View Preview Again</a>';
    }
    ?>
    
    <div class="info" style="margin-top: 30px;">
        <h2>How It Works</h2>
        <p>This migration converts full URLs to relative paths:</p>
        <ul>
            <li><code>http://toptopjobs.local/uploads/company_logos/file.png</code> → <code>/uploads/company_logos/file.png</code></li>
            <li><code>https://www.toptopjobs.com/uploads/company_logos/file.png</code> → <code>/uploads/company_logos/file.png</code></li>
            <li><code>http://toptopjobs.local/api/uploads/company_logos/file.png</code> → <code>/uploads/company_logos/file.png</code></li>
        </ul>
        <p><strong>Benefits:</strong></p>
        <ul>
            <li>Works on any domain (local, staging, production)</li>
            <li>Easier to migrate between environments</li>
            <li>URLs are generated dynamically based on current domain</li>
        </ul>
    </div>
</body>
</html>

