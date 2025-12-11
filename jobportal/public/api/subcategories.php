<?php
/**
 * Subcategories API Endpoint
 * Returns subcategories for a given category
 * 
 * Query Parameters:
 * - category_id: Category ID (required)
 */

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Handle preflight requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// Only allow GET requests
if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

/**
 * Get database connection
 */
function getDBConnection() {
    // Try multiple possible paths for env file (production vs development)
    $possibleEnvPaths = [
        __DIR__ . '/../env',           // Development: jobportal/env
        __DIR__ . '/../../env',        // Alternative structure
        dirname(__DIR__, 2) . '/env',  // Using dirname for better path resolution
        dirname(__DIR__, 2) . '/.env', // Also check for .env file
        __DIR__ . '/../.env',          // Check for .env in jobportal folder
    ];
    
    $hostname = 'localhost';
    $username = 'root';
    $password = '';
    $database = 'toptopjobs';
    $port = 3306;
    
    // Try to find and read env file
    $envFile = null;
    foreach ($possibleEnvPaths as $path) {
        if (file_exists($path) && is_readable($path)) {
            $envFile = $path;
            break;
        }
    }
    
    if ($envFile) {
        try {
            $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            if ($lines !== false) {
                foreach ($lines as $line) {
                    $line = trim($line);
                    if (empty($line) || strpos($line, '#') === 0) continue;
                    if (strpos($line, '=') === false) continue;
                    
                    list($key, $value) = explode('=', $line, 2);
                    $key = trim($key);
                    $value = trim($value);
                    // Remove quotes if present
                    $value = trim($value, '"\'');
                    
                    // Handle empty values properly (password can be empty for XAMPP default)
                    if ($key === 'database.default.hostname') $hostname = $value;
                    elseif ($key === 'database.default.username') $username = $value;
                    elseif ($key === 'database.default.password') $password = $value; // Empty is OK
                    elseif ($key === 'database.default.database') $database = $value;
                    elseif ($key === 'database.default.port') $port = (int)$value;
                }
            }
        } catch (Exception $e) {
            error_log('Error reading env file: ' . $e->getMessage());
        }
    }
    
    // Attempt database connection
    try {
        // For XAMPP, empty password is valid, so ensure password is set (even if empty)
        $conn = @new mysqli($hostname, $username, $password, $database, $port);
        
        if ($conn->connect_error) {
            error_log('Database connection failed: ' . $conn->connect_error . ' | Env file: ' . ($envFile ?: 'not found') . ' | User: ' . $username . ' | DB: ' . $database);
            return null;
        }
        
        $conn->set_charset('utf8mb4');
        return $conn;
    } catch (Exception $e) {
        error_log('Database connection exception: ' . $e->getMessage());
        return null;
    }
}

try {
    $categoryId = isset($_GET['category_id']) ? (int)$_GET['category_id'] : 0;
    
    if (!$categoryId) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'message' => 'Category ID is required'
        ]);
        exit;
    }
    
    $conn = getDBConnection();
    $subcategories = [];
    
    if ($conn) {
        // Fetch subcategories for the given category - using same pattern as categories.php
        $stmt = $conn->prepare("
            SELECT id, name, slug, sort_order
            FROM subcategories
            WHERE category_id = ? AND status = 'active'
            ORDER BY sort_order ASC, name ASC
        ");
        
        if ($stmt) {
            $stmt->bind_param('i', $categoryId);
            $stmt->execute();
            $result = $stmt->get_result();
            
            while ($row = $result->fetch_assoc()) {
                $subcategories[] = [
                    'id' => (int)$row['id'],
                    'name' => $row['name'],
                    'slug' => $row['slug'],
                    'sort_order' => (int)$row['sort_order']
                ];
            }
            
            $stmt->close();
        }
        
        $conn->close();
    }
    
    echo json_encode([
        'success' => true,
        'subcategories' => $subcategories,
        'count' => count($subcategories)
    ]);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Error fetching subcategories: ' . $e->getMessage()
    ]);
}

