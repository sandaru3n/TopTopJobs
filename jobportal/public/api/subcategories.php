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
    $envFile = __DIR__ . '/../env';
    $hostname = 'localhost';
    $username = 'root';
    $password = '';
    $database = 'toptopjobs';
    $port = 3306;
    
    if (file_exists($envFile)) {
        $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            $line = trim($line);
            if (empty($line) || strpos($line, '#') === 0) continue;
            if (strpos($line, '=') === false) continue;
            
            list($key, $value) = explode('=', $line, 2);
            $key = trim($key);
            $value = trim($value);
            $value = trim($value, '"\'');
            
            if ($key === 'database.default.hostname') $hostname = $value;
            elseif ($key === 'database.default.username') $username = $value;
            elseif ($key === 'database.default.password') $password = $value;
            elseif ($key === 'database.default.database') $database = $value;
            elseif ($key === 'database.default.port') $port = (int)$value;
        }
    }
    
    $conn = @new mysqli($hostname, $username, $password, $database, $port);
    
    if ($conn->connect_error) {
        error_log('Database connection failed: ' . $conn->connect_error);
        return null;
    }
    
    $conn->set_charset('utf8mb4');
    return $conn;
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
    
    if (!$conn) {
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'message' => 'Database connection failed'
        ]);
        exit;
    }
    
    // Fetch subcategories for the given category
    $stmt = $conn->prepare("
        SELECT id, name, slug, description, sort_order
        FROM subcategories
        WHERE category_id = ? AND status = 'active'
        ORDER BY sort_order ASC, name ASC
    ");
    
    if (!$stmt) {
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'message' => 'Database query preparation failed: ' . $conn->error
        ]);
        $conn->close();
        exit;
    }
    
    $stmt->bind_param('i', $categoryId);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $subcategories = [];
    while ($row = $result->fetch_assoc()) {
        $subcategories[] = [
            'id' => (int)$row['id'],
            'name' => $row['name'],
            'slug' => $row['slug'],
            'description' => $row['description'],
            'sort_order' => (int)$row['sort_order']
        ];
    }
    
    $stmt->close();
    $conn->close();
    
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

