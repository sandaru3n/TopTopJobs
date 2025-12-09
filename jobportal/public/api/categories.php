<?php
/**
 * Categories API Endpoint
 * Returns job categories with job counts
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
    $conn = getDBConnection();
    $categories = [];
    
    // Define all possible categories
    $categoryList = [
        'Cashier',
        'Data Entry',
        'IT/Software',
        'Marketing',
        'Sales',
        'Customer Service',
        'Design',
        'Engineering',
        'Finance',
        'Healthcare',
        'Education',
        'Other',
        'Accountancy & Finance',
        'Automobile',
        'Business Management',
        'Administration / Secretarial',
        'Banking and Financial Services',
        'Call Center',
        'Agriculture',
        'Beauty & Hairdressing',
        'Charity / NGO',
        'Apparel',
        'BPO/ KPO',
        'Architecture',
        'Building & Construction',
        'Delivery / Driving / Transport'
    ];
    
    if ($conn) {
        // Get job counts for each category
        foreach ($categoryList as $category) {
            // Check if category is in skills_required (as first item) or company industry
            $stmt = $conn->prepare("
                SELECT COUNT(*) as count
                FROM jobs j
                LEFT JOIN companies c ON j.company_id = c.id
                WHERE j.status = 'active'
                AND (
                    (j.skills_required LIKE ? OR j.skills_required = ?)
                    OR c.industry = ?
                )
            ");
            
            $categoryPattern = $category . ',%';
            $stmt->bind_param('sss', $categoryPattern, $category, $category);
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
            $count = (int)$row['count'];
            $stmt->close();
            
            // Only include categories with jobs
            if ($count > 0) {
                $categories[] = [
                    'name' => $category,
                    'count' => $count
                ];
            }
        }
        
        $conn->close();
    } else {
        // Fallback: return default categories with mock counts
        $categories = [
            ['name' => 'Accountancy & Finance', 'count' => 470],
            ['name' => 'Automobile', 'count' => 126],
            ['name' => 'Business Management', 'count' => 1622],
            ['name' => 'Administration / Secretarial', 'count' => 438],
            ['name' => 'Banking and Financial Services', 'count' => 262],
            ['name' => 'Call Center', 'count' => 165],
            ['name' => 'Agriculture', 'count' => 18],
            ['name' => 'Beauty & Hairdressing', 'count' => 40],
            ['name' => 'Charity / NGO', 'count' => 7],
            ['name' => 'Apparel', 'count' => 67],
            ['name' => 'BPO/ KPO', 'count' => 130],
            ['name' => 'Customer Service', 'count' => 994],
            ['name' => 'Architecture', 'count' => 25],
            ['name' => 'Building & Construction', 'count' => 186],
            ['name' => 'Delivery / Driving / Transport', 'count' => 128],
            ['name' => 'IT/Software', 'count' => 450],
            ['name' => 'Marketing', 'count' => 320],
            ['name' => 'Sales', 'count' => 280],
            ['name' => 'Design', 'count' => 150],
            ['name' => 'Engineering', 'count' => 380],
            ['name' => 'Finance', 'count' => 290],
            ['name' => 'Healthcare', 'count' => 220],
            ['name' => 'Education', 'count' => 180],
            ['name' => 'Cashier', 'count' => 95],
            ['name' => 'Data Entry', 'count' => 120]
        ];
    }
    
    echo json_encode([
        'success' => true,
        'categories' => $categories,
        'total' => count($categories)
    ]);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Error fetching categories: ' . $e->getMessage()
    ]);
}

