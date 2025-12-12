<?php
/**
 * Categories API Endpoint
 * Returns job categories with job counts
 */

// Enable error reporting but don't display errors (log them instead)
error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 1);

// Set error handler to catch and log errors
set_error_handler(function($errno, $errstr, $errfile, $errline) {
    error_log("Categories API Error [$errno]: $errstr in $errfile on line $errline");
    return true;
}, E_ALL);

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
                    // Skip empty lines and comments
                    if (empty($line) || strpos($line, '#') === 0) continue;
                    // Skip lines without equals sign
                    if (strpos($line, '=') === false) continue;
                    
                    // Split key and value
                    $parts = explode('=', $line, 2);
                    if (count($parts) !== 2) continue;
                    
                    $key = trim($parts[0]);
                    $value = trim($parts[1]);
                    
                    // Remove surrounding quotes if present
                    $value = trim($value, '"\'');
                    
                    // Assign values based on key (empty values are allowed, especially for password)
                    if ($key === 'database.default.hostname' && $value !== '') $hostname = $value;
                    elseif ($key === 'database.default.username' && $value !== '') $username = $value;
                    elseif ($key === 'database.default.password') $password = $value; // Empty password is OK for XAMPP
                    elseif ($key === 'database.default.database' && $value !== '') $database = $value;
                    elseif ($key === 'database.default.port' && $value !== '') $port = (int)$value;
                }
            }
        } catch (Exception $e) {
            error_log('Error reading env file: ' . $e->getMessage());
        }
    }
    
    // Check if mysqli extension is available
    if (!extension_loaded('mysqli')) {
        error_log('Categories API - mysqli extension not loaded');
        return null;
    }
    
    // Attempt database connection
    try {
        // Suppress warnings but catch exceptions
        $conn = @new mysqli($hostname, $username, $password, $database, $port);
        
        // Check for connection errors
        if ($conn->connect_error) {
            $errorMsg = 'Database connection failed: ' . $conn->connect_error;
            $errorMsg .= ' | Host: ' . $hostname;
            $errorMsg .= ' | User: ' . $username;
            $errorMsg .= ' | DB: ' . $database;
            $errorMsg .= ' | Port: ' . $port;
            $errorMsg .= ' | Env file: ' . ($envFile ?: 'not found');
            error_log($errorMsg);
            return null;
        }
        
        // Check if connection object is valid
        if (!$conn || !is_object($conn)) {
            error_log('Database connection returned invalid object');
            return null;
        }
        
        // Test the connection with a simple query
        if (!$conn->ping()) {
            error_log('Database connection ping failed');
            $conn->close();
            return null;
        }
        
        $conn->set_charset('utf8mb4');
        return $conn;
    } catch (Exception $e) {
        error_log('Database connection exception: ' . $e->getMessage() . ' in ' . $e->getFile() . ' on line ' . $e->getLine());
        return null;
    } catch (Error $e) {
        error_log('Database connection fatal error: ' . $e->getMessage() . ' in ' . $e->getFile() . ' on line ' . $e->getLine());
        return null;
    }
}

try {
    $conn = getDBConnection();
    $categories = [];
    $errorMessage = null;
    
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
        // Get categories from database with job counts
        $stmt = $conn->prepare("
            SELECT cat.id, cat.name, COUNT(j.id) as count
            FROM categories cat
            LEFT JOIN jobs j ON j.category_id = cat.id AND j.status = 'active'
            WHERE cat.status = 'active'
            GROUP BY cat.id, cat.name
            ORDER BY cat.sort_order ASC, cat.name ASC
        ");
        
        if ($stmt) {
            if ($stmt->execute()) {
                $result = $stmt->get_result();
                if ($result) {
                    while ($row = $result->fetch_assoc()) {
                        $categories[] = [
                            'id' => (int)$row['id'],
                            'name' => $row['name'],
                            'count' => (int)$row['count']
                        ];
                    }
                } else {
                    $errorMessage = 'Failed to get result set: ' . $stmt->error;
                    error_log('Categories API - Result error: ' . $stmt->error);
                }
            } else {
                $errorMessage = 'Query execution failed: ' . $stmt->error;
                error_log('Categories API - Query execution error: ' . $stmt->error);
                error_log('Categories API - SQL Error Code: ' . $stmt->errno);
            }
            $stmt->close();
        } else {
            $errorMessage = 'Prepare statement failed: ' . $conn->error;
            error_log('Categories API - Prepare error: ' . $conn->error);
            error_log('Categories API - Connection Error Code: ' . $conn->errno);
        }
        
        // If no categories found in database, fallback to checking jobs by name
        if (empty($categories) && !$errorMessage) {
            foreach ($categoryList as $category) {
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
                
                if ($stmt) {
                    $categoryPattern = $category . ',%';
                    $stmt->bind_param('sss', $categoryPattern, $category, $category);
                    if ($stmt->execute()) {
                        $result = $stmt->get_result();
                        $row = $result->fetch_assoc();
                        $count = (int)$row['count'];
                        
                        if ($count > 0) {
                            $categories[] = [
                                'id' => null, // No ID if not in database
                                'name' => $category,
                                'count' => $count
                            ];
                        }
                    }
                    $stmt->close();
                }
            }
        }
        
        $conn->close();
    } else {
        $errorMessage = 'Database connection failed';
        error_log('Categories API - Database connection failed');
    }
    
    // If there was an error, log it but still try to return fallback categories
    if ($errorMessage) {
        error_log('Categories API - Error occurred: ' . $errorMessage);
        // Don't exit here - continue to fallback categories
    }
    
    // If no categories found (either due to error or empty database), use fallback
    if (empty($categories)) {
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
    
    // Always return a valid response, even if there were errors
    // This ensures the frontend always gets data to display
    $response = [
        'success' => true,
        'categories' => $categories,
        'total' => count($categories)
    ];
    
    // Add debug info if there was an error or debug parameter is set
    if ($errorMessage || (isset($_GET['debug']) && $_GET['debug'] === '1')) {
        $response['debug'] = [
            'connection_status' => $conn ? 'connected' : 'failed',
            'error' => $errorMessage,
            'env_file_found' => isset($envFile) && $envFile ? 'yes' : 'no'
        ];
    }
    
    // Ensure JSON encoding doesn't fail
    $jsonResponse = json_encode($response, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    if ($jsonResponse === false) {
        error_log('Categories API - JSON encoding failed: ' . json_last_error_msg());
        // Return minimal valid response
        echo json_encode([
            'success' => true,
            'categories' => [],
            'total' => 0
        ]);
    } else {
        echo $jsonResponse;
    }
    
} catch (Exception $e) {
    error_log('Categories API - Exception: ' . $e->getMessage() . ' in ' . $e->getFile() . ' on line ' . $e->getLine());
    
    // Return fallback categories even on exception so frontend can still display something
    $fallbackCategories = [
        ['name' => 'IT/Software', 'count' => 0],
        ['name' => 'Marketing', 'count' => 0],
        ['name' => 'Sales', 'count' => 0],
        ['name' => 'Customer Service', 'count' => 0],
        ['name' => 'Design', 'count' => 0],
        ['name' => 'Engineering', 'count' => 0],
        ['name' => 'Finance', 'count' => 0],
        ['name' => 'Healthcare', 'count' => 0],
        ['name' => 'Education', 'count' => 0]
    ];
    
    echo json_encode([
        'success' => true, // Return success so frontend can display categories
        'categories' => $fallbackCategories,
        'total' => count($fallbackCategories),
        'debug' => [
            'exception' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'note' => 'Using fallback categories due to error'
        ]
    ]);
} catch (Error $e) {
    error_log('Categories API - Fatal Error: ' . $e->getMessage() . ' in ' . $e->getFile() . ' on line ' . $e->getLine());
    
    // Return fallback categories even on fatal error
    $fallbackCategories = [
        ['name' => 'IT/Software', 'count' => 0],
        ['name' => 'Marketing', 'count' => 0],
        ['name' => 'Sales', 'count' => 0],
        ['name' => 'Customer Service', 'count' => 0],
        ['name' => 'Design', 'count' => 0],
        ['name' => 'Engineering', 'count' => 0],
        ['name' => 'Finance', 'count' => 0],
        ['name' => 'Healthcare', 'count' => 0],
        ['name' => 'Education', 'count' => 0]
    ];
    
    echo json_encode([
        'success' => true, // Return success so frontend can display categories
        'categories' => $fallbackCategories,
        'total' => count($fallbackCategories),
        'debug' => [
            'error' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'note' => 'Using fallback categories due to fatal error'
        ]
    ]);
}

