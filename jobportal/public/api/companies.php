<?php
/**
 * Companies API Endpoint
 * RESTful API for company search and autocomplete
 * 
 * Query Parameters:
 * - q: Search query (company name)
 * - limit: Maximum number of results (default: 10)
 */

// Enable error reporting for debugging (disable in production)
error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 1);

// Set error handler
set_error_handler(function($errno, $errstr, $errfile, $errline) {
    error_log("API Error [$errno]: $errstr in $errfile on line $errline");
    return true;
}, E_ALL);

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

/**
 * Get database connection
 */
function getDBConnection() {
    // Try multiple possible paths for env file
    $possibleEnvPaths = [
        __DIR__ . '/../env',
        __DIR__ . '/../../env',
        dirname(__DIR__, 2) . '/env',
        dirname(__DIR__, 2) . '/.env',
        __DIR__ . '/../.env',
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
                    $value = trim($value, '"\'');
                    
                    if ($key === 'database.default.hostname') $hostname = $value;
                    elseif ($key === 'database.default.username') $username = $value;
                    elseif ($key === 'database.default.password') $password = $value;
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
        $conn = @new mysqli($hostname, $username, $password, $database, $port);
        
        if ($conn->connect_error) {
            error_log('Database connection failed: ' . $conn->connect_error);
            return null;
        }
        
        $conn->set_charset('utf8mb4');
        return $conn;
    } catch (Exception $e) {
        error_log('Database connection exception: ' . $e->getMessage());
        return null;
    }
}

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

try {
    $query = isset($_GET['q']) ? trim($_GET['q']) : '';
    $limit = isset($_GET['limit']) ? min(20, max(1, (int)$_GET['limit'])) : 10;
    
    $companies = [];
    
    if (!empty($query) && strlen($query) >= 2) {
        $conn = getDBConnection();
        
        if ($conn) {
            try {
                // Search companies by name (case-insensitive, partial match)
                $searchQuery = $conn->real_escape_string($query);
                $sql = "
                    SELECT 
                        id,
                        name,
                        logo,
                        website,
                        industry,
                        description
                    FROM companies
                    WHERE name LIKE ? 
                    ORDER BY 
                        CASE 
                            WHEN name = ? THEN 1
                            WHEN name LIKE ? THEN 2
                            ELSE 3
                        END,
                        name ASC
                    LIMIT ?
                ";
                
                $stmt = $conn->prepare($sql);
                if ($stmt) {
                    $searchPattern = '%' . $searchQuery . '%';
                    $exactMatch = $searchQuery;
                    $startMatch = $searchQuery . '%';
                    
                    $stmt->bind_param('sssi', $searchPattern, $exactMatch, $startMatch, $limit);
                    
                    if ($stmt->execute()) {
                        $result = $stmt->get_result();
                        while ($row = $result->fetch_assoc()) {
                            // Format company logo URL
                            $logo = null;
                            if (!empty($row['logo'])) {
                                $logo = $row['logo'];
                                // Convert to absolute URL if relative
                                if (strpos($logo, '/') === 0) {
                                    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
                                    $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
                                    $logo = $protocol . '://' . $host . $logo;
                                }
                            }
                            
                            $companies[] = [
                                'id' => (int)$row['id'],
                                'name' => $row['name'],
                                'logo' => $logo,
                                'website' => $row['website'],
                                'industry' => $row['industry'],
                                'description' => $row['description']
                            ];
                        }
                    }
                    $stmt->close();
                }
                
                $conn->close();
            } catch (Exception $e) {
                error_log('Database query error: ' . $e->getMessage());
                if ($conn) {
                    @$conn->close();
                }
            }
        }
    }
    
    echo json_encode([
        'success' => true,
        'companies' => $companies,
        'count' => count($companies)
    ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    
} catch (Exception $e) {
    error_log('API Error: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Error searching companies',
        'companies' => [],
        'count' => 0
    ]);
}

