<?php
/**
 * Jobs API Endpoint
 * RESTful API for job search and listings
 * 
 * Query Parameters:
 * - q: Search query (title, company, skills)
 * - loc: Location filter
 * - job_type: Comma-separated job types (full-time, part-time, internship, remote)
 * - experience: Comma-separated experience levels (fresher, junior, senior)
 * - salary_min: Minimum salary
 * - date_posted: Comma-separated date filters (24h, 3d, 7d)
 * - company: Company name filter
 * - skills: Comma-separated skills
 * - lat: User latitude (for distance calculation)
 * - lng: User longitude (for distance calculation)
 * - sort: Sort order (relevant, newest, salary_high, popular)
 * - page: Page number (default: 1)
 * - per_page: Results per page (default: 20)
 */

// Enable error reporting for debugging (disable in production)
// In production, log errors but don't display them
error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 1);

// Set error handler to catch and log errors
set_error_handler(function($errno, $errstr, $errfile, $errline) {
    error_log("API Error [$errno]: $errstr in $errfile on line $errline");
    // Don't output error to client in production
    return true;
}, E_ALL);

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

/**
 * Get placeholder image as data URI (no external requests needed)
 */
function getPlaceholderImage($size = 48) {
    $svg = '<svg xmlns="http://www.w3.org/2000/svg" width="' . $size . '" height="' . $size . '" viewBox="0 0 ' . $size . ' ' . $size . '"><rect width="' . $size . '" height="' . $size . '" fill="#e5e7eb"/><text x="50%" y="50%" font-family="Arial, sans-serif" font-size="' . ($size / 3) . '" fill="#9ca3af" text-anchor="middle" dominant-baseline="middle">Logo</text></svg>';
    return 'data:image/svg+xml;base64,' . base64_encode($svg);
}

/**
 * Check and validate image URL
 * Returns validated URL or placeholder if invalid
 * Converts localhost/local domain URLs to current production domain
 * 
 * @param string $url The image URL to check
 * @param int $placeholderSize Size for placeholder if URL is invalid
 * @return string Valid image URL or data URI placeholder
 */
function checkImageUrl($url, $placeholderSize = 48) {
    // If empty or null, return null (not placeholder) so frontend can handle it
    if (empty($url) || $url === null) {
        return null;
    }
    
    // Convert to string to ensure we're working with a string
    $url = (string)$url;
    $url = trim($url);
    
    // If empty after trimming, return null (not placeholder)
    if ($url === '') {
        return null;
    }
    
    // If it's already a data URI placeholder, return null (not placeholder) so frontend can handle it
    // But if it's a valid data URI image (not our placeholder), return as-is
    if (strpos($url, 'data:') === 0) {
        // Check if it's our placeholder SVG - if so, return null
        if (strpos($url, 'data:image/svg+xml') !== false && strpos($url, 'Logo') !== false) {
            return null;
        }
        // Otherwise, it's a valid data URI image, return as-is
        return $url;
    }
    
    // If it's a via.placeholder.com URL, return null
    if (strpos($url, 'via.placeholder.com') !== false) {
        return null;
    }
    
    // Remove /api/ prefix from uploads paths FIRST
    if (stripos($url, '/api/uploads/') !== false) {
        $url = str_ireplace('/api/uploads/', '/uploads/', $url);
    }
    
    // Determine current domain and protocol
    $isHttps = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || 
               (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https') ||
               (!empty($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == 443) ||
               (isset($_SERVER['HTTP_HOST']) && (stripos($_SERVER['HTTP_HOST'], 'toptopjobs.com') !== false || stripos($_SERVER['HTTP_HOST'], 'www.') !== false));
    
    $protocol = $isHttps ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
    
    // PRIORITY 1: If it's a relative path (starts with /), convert to absolute URL
    // This handles: /uploads/company_logos/file.png
    // NO basePath needed - images are served directly from root
    if (strpos($url, '/') === 0) {
        $path = ltrim($url, '/');
        // Simple URL: protocol://host/path (no basePath for uploads)
        return $protocol . '://' . $host . '/' . $path;
    }
    
    // PRIORITY 2: If it looks like a path without leading slash, add it and convert
    // This handles: uploads/company_logos/file.png
    if (stripos($url, 'uploads/') === 0 || stripos($url, 'company_logos/') !== false || stripos($url, 'profile_pictures/') !== false) {
        // Ensure it starts with uploads/
        if (stripos($url, 'uploads/') !== 0) {
            if (stripos($url, 'company_logos/') !== false) {
                $url = 'uploads/' . $url;
            } elseif (stripos($url, 'profile_pictures/') !== false) {
                $url = 'uploads/' . $url;
            }
        }
        // Simple URL: protocol://host/path (no basePath for uploads)
        return $protocol . '://' . $host . '/' . $url;
    }
    
    // PRIORITY 3: If it contains localhost, 127.0.0.1, or toptopjobs.local, convert to current domain
    if (stripos($url, 'localhost') !== false || 
        stripos($url, '127.0.0.1') !== false || 
        stripos($url, 'toptopjobs.local') !== false ||
        stripos($url, '.local/') !== false) {
        // Extract the path from the URL
        $parsed = parse_url($url);
        $path = isset($parsed['path']) ? $parsed['path'] : $url;
        
        // Remove domain parts and get just the path
        $path = preg_replace('#https?://[^/]+/?#i', '', $path);
        $path = ltrim($path, '/');
        
        // Rebuild URL with current domain (no basePath for uploads)
        return $protocol . '://' . $host . '/' . $path;
    }
    
    // PRIORITY 4: If it's a valid absolute URL (http/https), fix /api/uploads/ and convert HTTP to HTTPS
    // Check if it starts with http:// or https:// first (faster check)
    if ((stripos($url, 'http://') === 0 || stripos($url, 'https://') === 0)) {
        // Remove /api/ prefix from uploads if present
        if (stripos($url, '/api/uploads/') !== false) {
            $url = str_ireplace('/api/uploads/', '/uploads/', $url);
        }
        // Convert HTTP to HTTPS to prevent mixed content warnings on production
        if ($isHttps && stripos($url, 'http://') === 0) {
            $url = str_replace('http://', 'https://', $url);
        }
        return $url;
    }
    
    // If we can't determine, return null (not placeholder) so frontend can handle it
    return null;
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
            // Return null if connection fails
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

// Get query parameters
// Wrap everything in try-catch to ensure we always return valid JSON
try {
    // Check if requesting a single job by ID or slug
$jobId = isset($_GET['id']) ? (int)$_GET['id'] : null;
$jobSlug = isset($_GET['slug']) ? trim($_GET['slug']) : null;

// Log the request for debugging
error_log('API Request - ID: ' . ($jobId ?: 'null') . ', Slug: ' . ($jobSlug ?: 'null'));

if ($jobId || $jobSlug) {
    $conn = getDBConnection();
    $job = null;
    
    if ($conn) {
        try {
            if ($jobId) {
                // Find by ID
                $stmt = $conn->prepare("
                    SELECT j.*, c.name as company_name, c.logo as company_logo, c.description as company_description, c.rating as company_rating, c.website as company_website, c.maps_url as company_maps_url, c.industry as company_industry, cat.name as category_name, subcat.name as subcategory_name
                    FROM jobs j
                    INNER JOIN companies c ON j.company_id = c.id
                    LEFT JOIN categories cat ON j.category_id = cat.id
                    LEFT JOIN subcategories subcat ON j.subcategory_id = subcat.id
                    WHERE j.id = ? AND j.status = 'active'
                ");
                if ($stmt) {
                    $stmt->bind_param('i', $jobId);
                    if ($stmt->execute()) {
                        $result = $stmt->get_result();
                        $job = $result->fetch_assoc();
                    } else {
                        error_log('Query execution failed: ' . $stmt->error);
                    }
                    $stmt->close();
                } else {
                    error_log('Prepare statement failed: ' . $conn->error);
                }
            } elseif ($jobSlug) {
                error_log('Looking up job by slug: ' . $jobSlug);
                
                // Find by slug (extract ID from slug if needed)
                if (preg_match('/-(\d+)$/', $jobSlug, $matches)) {
                    $extractedId = (int)$matches[1];
                    error_log('Extracted ID from slug: ' . $extractedId);
                    
                    // First try with active status
                    $stmt = $conn->prepare("
                        SELECT j.*, c.name as company_name, c.logo as company_logo, c.description as company_description, c.rating as company_rating, c.website as company_website, c.maps_url as company_maps_url, c.industry as company_industry
                        FROM jobs j
                        INNER JOIN companies c ON j.company_id = c.id
                        WHERE j.id = ? AND j.status = 'active'
                    ");
                    if ($stmt) {
                        $stmt->bind_param('i', $extractedId);
                        if ($stmt->execute()) {
                            $result = $stmt->get_result();
                            $job = $result->fetch_assoc();
                            if ($job) {
                                error_log('Job found by extracted ID: ' . $extractedId);
                            } else {
                                error_log('No active job found with ID: ' . $extractedId . ', trying without status filter...');
                                // Try without status filter as fallback
                                $stmt->close();
                                $stmt = $conn->prepare("
                                    SELECT j.*, c.name as company_name, c.logo as company_logo, c.description as company_description, c.rating as company_rating, c.website as company_website, c.maps_url as company_maps_url, c.industry as company_industry
                                    FROM jobs j
                                    INNER JOIN companies c ON j.company_id = c.id
                                    WHERE j.id = ?
                                ");
                                if ($stmt) {
                                    $stmt->bind_param('i', $extractedId);
                                    if ($stmt->execute()) {
                                        $result = $stmt->get_result();
                                        $job = $result->fetch_assoc();
                                        if ($job) {
                                            error_log('Job found by extracted ID (without status filter): ' . $extractedId);
                                        }
                                    }
                                    $stmt->close();
                                }
                            }
                        } else {
                            error_log('Query execution failed: ' . $stmt->error);
                        }
                        if (!$job) {
                            $stmt->close();
                        }
                    } else {
                        error_log('Prepare statement failed: ' . $conn->error);
                    }
                }
                
                // If not found by ID extraction, try to match by slug directly
                if (!$job) {
                    error_log('Trying to match by slug directly: ' . $jobSlug);
                    // First try with active status
                    $stmt = $conn->prepare("
                        SELECT j.*, c.name as company_name, c.logo as company_logo, c.description as company_description, c.rating as company_rating, c.website as company_website, c.maps_url as company_maps_url, c.industry as company_industry, cat.name as category_name, subcat.name as subcategory_name
                        FROM jobs j
                        INNER JOIN companies c ON j.company_id = c.id
                        LEFT JOIN categories cat ON j.category_id = cat.id
                        LEFT JOIN subcategories subcat ON j.subcategory_id = subcat.id
                        WHERE j.slug = ? AND j.status = 'active'
                    ");
                    if ($stmt) {
                        $stmt->bind_param('s', $jobSlug);
                        if ($stmt->execute()) {
                            $result = $stmt->get_result();
                            $job = $result->fetch_assoc();
                            if ($job) {
                                error_log('Job found by slug: ' . $jobSlug);
                            } else {
                                error_log('No active job found with slug: ' . $jobSlug . ', trying without status filter...');
                                // Try without status filter as fallback
                                $stmt->close();
                                $stmt = $conn->prepare("
                                    SELECT j.*, c.name as company_name, c.logo as company_logo, c.description as company_description, c.rating as company_rating, c.website as company_website, c.maps_url as company_maps_url, c.industry as company_industry, cat.name as category_name, subcat.name as subcategory_name
                                    FROM jobs j
                                    INNER JOIN companies c ON j.company_id = c.id
                                    LEFT JOIN categories cat ON j.category_id = cat.id
                                    LEFT JOIN subcategories subcat ON j.subcategory_id = subcat.id
                                    WHERE j.slug = ?
                                ");
                                if ($stmt) {
                                    $stmt->bind_param('s', $jobSlug);
                                    if ($stmt->execute()) {
                                        $result = $stmt->get_result();
                                        $job = $result->fetch_assoc();
                                        if ($job) {
                                            error_log('Job found by slug (without status filter): ' . $jobSlug);
                                        } else {
                                            error_log('No job found with slug: ' . $jobSlug);
                                        }
                                    }
                                    $stmt->close();
                                }
                            }
                        } else {
                            error_log('Query execution failed: ' . $stmt->error);
                        }
                        if (!$job) {
                            $stmt->close();
                        }
                    } else {
                        error_log('Prepare statement failed: ' . $conn->error);
                    }
                }
            }
            $conn->close();
        } catch (Exception $e) {
            error_log('Database query error: ' . $e->getMessage());
            if ($conn) {
                $conn->close();
            }
        }
    }
    
    // No fallback - only database data
    if (!$job) {
        error_log('Job not found in database. ID: ' . ($jobId ?: 'null') . ', Slug: ' . ($jobSlug ?: 'null'));
    }
    
    if ($job) {
        // Format job data if from database
        try {
            if (isset($job['company_name'])) {
                $job = formatJobData($job);
            }
        } catch (Exception $e) {
            error_log('Error formatting job data: ' . $e->getMessage());
            // No fallback - job will be null if formatting fails
            $job = null;
        }
        
        // Ensure slug is set
        if (!isset($job['slug'])) {
            try {
                $job['slug'] = generateJobSlug($job['company_name'], $job['title'], $job['id']);
            } catch (Exception $e) {
                error_log('Error generating slug: ' . $e->getMessage());
                $job['slug'] = 'job-' . ($job['id'] ?? 'unknown');
            }
        }
        
        // Get company description if available
        if (!isset($job['company_description'])) {
            $job['company_description'] = getCompanyDescription($job['company_name'] ?? '');
        }
        
        // Format responsibilities and requirements if they're strings
        if (isset($job['responsibilities']) && is_string($job['responsibilities'])) {
            $job['responsibilities'] = array_filter(array_map('trim', explode("\n", $job['responsibilities'])));
        }
        if (isset($job['requirements']) && is_string($job['requirements'])) {
            $job['requirements'] = array_filter(array_map('trim', explode("\n", $job['requirements'])));
        }
        
        // Parse skills from skills_required
        if (!empty($job['skills_required']) && !isset($job['skills'])) {
            $job['skills'] = is_string($job['skills_required']) 
                ? explode(',', $job['skills_required']) 
                : $job['skills_required'];
        } elseif (!isset($job['skills'])) {
            $job['skills'] = [];
        }
        
        error_log('API Success - Job found: ID=' . $job['id'] . ', Slug=' . ($job['slug'] ?? 'N/A'));
        echo json_encode([
            'success' => true,
            'job' => $job
        ]);
    } else {
        // Log why job wasn't found
        error_log('API 404 - Job not found. Requested ID: ' . ($jobId ?: 'null') . ', Slug: ' . ($jobSlug ?: 'null'));
        error_log('API 404 - Database connection: ' . ($conn ? 'connected' : 'failed'));
        
        // Return 404 with helpful message
        http_response_code(404);
        echo json_encode([
            'success' => false,
            'message' => 'Job not found',
            'requested_id' => $jobId,
            'requested_slug' => $jobSlug,
            'debug' => [
                'database_connected' => $conn ? true : false,
                'id_provided' => $jobId ? true : false,
                'slug_provided' => $jobSlug ? true : false
            ]
        ]);
    }
    exit;
}

$query = $_GET['q'] ?? '';
$location = $_GET['loc'] ?? '';
$jobTypes = isset($_GET['job_type']) && !empty($_GET['job_type']) ? array_filter(array_map('trim', explode(',', $_GET['job_type']))) : [];
$experiences = isset($_GET['experience']) && !empty($_GET['experience']) ? array_filter(array_map('trim', explode(',', $_GET['experience']))) : [];
$salaryMin = isset($_GET['sal_min']) ? (int)$_GET['sal_min'] : 0;
$datePosted = isset($_GET['date_posted']) && !empty($_GET['date_posted']) ? array_filter(array_map('trim', explode(',', $_GET['date_posted']))) : [];
$company = $_GET['company'] ?? '';
$skills = isset($_GET['skills']) && !empty($_GET['skills']) ? array_filter(array_map('trim', explode(',', $_GET['skills']))) : [];
$categories = isset($_GET['category']) && !empty($_GET['category']) ? array_filter(array_map('trim', explode(',', $_GET['category']))) : [];
$userLat = isset($_GET['lat']) ? (float)$_GET['lat'] : null;
$userLng = isset($_GET['lng']) ? (float)$_GET['lng'] : null;
$sort = $_GET['sort'] ?? 'relevant';
$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$perPage = isset($_GET['per_page']) ? min(100, max(1, (int)$_GET['per_page'])) : 20;

// No caching - always fetch fresh data

// Fetch jobs from database (with error handling)
try {
    $allJobs = getJobsFromDatabase();
} catch (Exception $e) {
    error_log('Error fetching jobs from database: ' . $e->getMessage());
    $allJobs = []; // Return empty array on error
}

// Apply filters
$filteredJobs = filterJobs($allJobs, [
    'query' => $query,
    'location' => $location,
    'job_types' => $jobTypes,
    'experiences' => $experiences,
    'salary_min' => $salaryMin,
    'date_posted' => $datePosted,
    'company' => $company,
    'skills' => $skills,
    'categories' => $categories,
    'user_lat' => $userLat,
    'user_lng' => $userLng
]);

// Sort jobs
$filteredJobs = sortJobs($filteredJobs, $sort);

// Calculate distance if user location provided
if ($userLat && $userLng) {
    foreach ($filteredJobs as &$job) {
        if (isset($job['latitude']) && isset($job['longitude'])) {
            $job['distance'] = round(calculateDistance(
                $userLat,
                $userLng,
                $job['latitude'],
                $job['longitude']
            ), 1);
        }
    }
    unset($job);
}

// Paginate
$total = count($filteredJobs);
$offset = ($page - 1) * $perPage;
$paginatedJobs = array_slice($filteredJobs, $offset, $perPage);
$hasMore = ($offset + $perPage) < $total;

// Format response - always return valid structure even if no jobs
$response = [
    'success' => true,
    'jobs' => $paginatedJobs ?: [],
    'total' => $total ?: 0,
    'page' => $page,
    'per_page' => $perPage,
    'has_more' => $hasMore ?: false
];

// Ensure JSON encoding doesn't fail
$jsonResponse = json_encode($response, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
if ($jsonResponse === false) {
    error_log('JSON encoding failed: ' . json_last_error_msg());
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Error encoding response',
        'jobs' => [],
        'total' => 0,
        'page' => 1,
        'per_page' => 20,
        'has_more' => false
    ]);
} else {
    echo $jsonResponse;
}

} catch (Exception $e) {
    // Log the full error for debugging
    error_log('API Error: ' . $e->getMessage() . ' in ' . $e->getFile() . ' on line ' . $e->getLine());
    error_log('Stack trace: ' . $e->getTraceAsString());
    
    // Return error response - no mock data fallback
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Database connection error. Please try again later.',
        'jobs' => [],
        'total' => 0,
        'page' => 1,
        'per_page' => 21,
        'has_more' => false
    ]);
    exit;
} catch (Error $e) {
    // Catch PHP 7+ errors (TypeError, ParseError, etc.)
    error_log('API Fatal Error: ' . $e->getMessage() . ' in ' . $e->getFile() . ' on line ' . $e->getLine());
    error_log('Stack trace: ' . $e->getTraceAsString());
    
    // Return error response - no mock data fallback
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'System error. Please try again later.',
        'jobs' => [],
        'total' => 0,
        'page' => 1,
        'per_page' => 21,
        'has_more' => false
    ]);
    exit;
}

/**
 * Generate slug from text
 */
function generateSlug($text) {
    // Convert to lowercase
    $text = strtolower($text);
    // Replace spaces and special characters with hyphens
    $text = preg_replace('/[^a-z0-9]+/', '-', $text);
    // Remove leading/trailing hyphens
    $text = trim($text, '-');
    return $text;
}

/**
 * Generate job slug from company name, title, and ID
 */
function generateJobSlug($companyName, $title, $id) {
    $companySlug = generateSlug($companyName);
    $titleSlug = generateSlug($title);
    return $companySlug . '-' . $titleSlug . '-' . $id;
}

/**
 * Fetch jobs from database
 */
function getJobsFromDatabase() {
    $jobs = [];
    
    try {
        $conn = getDBConnection();
        
        if ($conn) {
            try {
                $query = "
                    SELECT 
                        j.*,
                        c.name as company_name,
                        c.logo as company_logo,
                        c.rating as company_rating,
                        c.description as company_description,
                        c.website as company_website,
                        c.maps_url as company_maps_url,
                        c.industry as company_industry,
                        cat.name as category_name,
                        subcat.name as subcategory_name
                    FROM jobs j
                    INNER JOIN companies c ON j.company_id = c.id
                    LEFT JOIN categories cat ON j.category_id = cat.id
                    LEFT JOIN subcategories subcat ON j.subcategory_id = subcat.id
                    WHERE j.status = 'active'
                    ORDER BY j.posted_at DESC
                ";
                
                $result = $conn->query($query);
                
                if ($result && $result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        try {
                            $jobs[] = formatJobData($row);
                        } catch (Exception $e) {
                            error_log('Error formatting job data: ' . $e->getMessage());
                            // Skip this job and continue
                            continue;
                        }
                    }
                }
                
                $conn->close();
            } catch (Exception $e) {
                error_log('Database query error: ' . $e->getMessage());
                if ($conn) {
                    @$conn->close();
                }
            }
        }
    } catch (Exception $e) {
        error_log('Database connection error: ' . $e->getMessage());
    }
    
    // Return empty array if no database connection or no jobs found
    // No mock data fallback - only database data
    return $jobs;
}

/**
 * Map database experience levels to frontend filter values
 */
function mapExperienceLevel($dbLevel) {
    $mapping = [
        'fresher' => 'fresher',
        'junior' => 'junior',
        'mid' => 'junior',      // Map mid to junior for frontend
        'senior' => 'senior',
        'lead' => 'senior'       // Map lead to senior for frontend
    ];
    return $mapping[$dbLevel] ?? $dbLevel;
}

/**
 * Format job data for API response
 */
function formatJobData($job) {
    // Calculate salary (use salary_min if available, otherwise salary)
    $salary = null;
    if (!empty($job['salary_min'])) {
        $salary = (float)$job['salary_min'];
    }
    
    // Determine badge
    $badge = null;
    $badgeClass = null;
    $postedDate = new DateTime($job['posted_at']);
    $now = new DateTime();
    $daysDiff = $now->diff($postedDate)->days;
    
    if ($daysDiff <= 2) {
        $badge = 'New';
        $badgeClass = 'bg-green-100 text-green-800';
    } elseif (!empty($job['urgent']) && $job['urgent'] == 1) {
        $badge = 'Urgent';
        $badgeClass = 'bg-orange-100 text-orange-800';
    }
    
    // Get category and subcategory from database joins
    $category = $job['category_name'] ?? null;
    $subcategory = $job['subcategory_name'] ?? null;
    
    // Fallback: Parse skills and extract category if not in database
    $skills = [];
    $categoryList = ['Cashier', 'Data Entry', 'IT/Software', 'Marketing', 'Sales', 'Customer Service', 'Design', 'Engineering', 'Finance', 'Healthcare', 'Education', 'Other'];
    
    if (!$category && !empty($job['skills_required'])) {
        if (is_string($job['skills_required'])) {
            $parsed = array_map('trim', explode(',', $job['skills_required']));
            // Check if first item is a category
            if (!empty($parsed) && in_array($parsed[0], $categoryList)) {
                $category = $parsed[0];
                $skills = array_slice($parsed, 1); // Rest are skills
            } else {
                $skills = $parsed;
            }
        } else {
            $skills = $job['skills_required'];
        }
    } else if (!empty($job['skills_required'])) {
        // Category exists from DB, just parse skills
        if (is_string($job['skills_required'])) {
            $skills = array_map('trim', explode(',', $job['skills_required']));
        } else {
            $skills = $job['skills_required'];
        }
    }
    
    // Also check company industry as fallback for category
    if (!$category && !empty($job['company_industry'])) {
        $category = $job['company_industry'];
    }
    
    // Default to "Other" if no category found
    if (!$category) {
        $category = 'Other';
    }
    
    // Get description (remove application info if present)
    $description = $job['description'] ?? '';
    if (strpos($description, '--- Application Information ---') !== false) {
        $description = trim(explode('--- Application Information ---', $description)[0]);
    }
    
    // Check and validate company logo URL
    $originalLogo = $job['company_logo'] ?? null;
    
    // Process logo URL - checkImageUrl will return null if empty/null/invalid (frontend will handle with placeholder)
    $companyLogo = checkImageUrl($originalLogo, 48);
    
    // Debug: Log if a valid-looking URL was converted to null (shouldn't happen for valid paths)
    if (!empty($originalLogo) && 
        $originalLogo !== null && 
        trim($originalLogo) !== '' &&
        $companyLogo === null &&
        strpos($originalLogo, 'data:') !== 0) {
        // Log the conversion for debugging
        error_log("⚠ Logo URL converted to null - Job ID: " . ($job['id'] ?? 'unknown') . ", Original: {$originalLogo}");
        
        // Try to fix it manually - check if it's a relative path we missed
        $trimmed = trim($originalLogo);
        if (strpos($trimmed, '/') === 0 || stripos($trimmed, 'uploads/') !== false || stripos($trimmed, 'company_logos/') !== false) {
            // It looks like a valid path, try checkImageUrl again with the trimmed version
            $retryLogo = checkImageUrl($trimmed, 48);
            if ($retryLogo !== null) {
                $companyLogo = $retryLogo;
                error_log("✓ Fixed logo URL on retry: {$companyLogo}");
            }
        }
    }
    
    // Map experience level for frontend compatibility
    $experienceLevel = $job['experience_level'] ?? '';
    $mappedExperience = mapExperienceLevel($experienceLevel);
    
    return [
        'id' => (int)$job['id'],
        'title' => $job['title'],
        'slug' => $job['slug'],
        'company_name' => $job['company_name'],
        'company_logo' => $companyLogo,
        'company_rating' => !empty($job['company_rating']) ? (float)$job['company_rating'] : null,
        'location' => $job['location'],
        'latitude' => !empty($job['latitude']) ? (float)$job['latitude'] : null,
        'longitude' => !empty($job['longitude']) ? (float)$job['longitude'] : null,
        'job_type' => $job['job_type'],
        'experience' => $mappedExperience,  // Use mapped experience for frontend filters
        'experience_level' => $experienceLevel,  // Keep original for reference
        'min_experience' => isset($job['min_experience']) ? (int)$job['min_experience'] : null,  // Min experience in years
        'salary' => $salary,
        'salary_min' => !empty($job['salary_min']) ? (float)$job['salary_min'] : null,
        'salary_max' => !empty($job['salary_max']) ? (float)$job['salary_max'] : null,
        'is_remote' => !empty($job['is_remote']) ? (int)$job['is_remote'] : 0,
        'skills' => $skills,
        'category' => $category,
        'subcategory' => $subcategory,
        'description' => $description,
        'responsibilities' => !empty($job['responsibilities']) ? (is_string($job['responsibilities']) ? array_filter(array_map('trim', explode("\n", $job['responsibilities']))) : $job['responsibilities']) : null,
        'requirements' => !empty($job['requirements']) ? (is_string($job['requirements']) ? array_filter(array_map('trim', explode("\n", $job['requirements']))) : $job['requirements']) : null,
        'image' => !empty($job['image']) ? checkImageUrl($job['image'], null) : null, // Job image
        'posted_at' => !empty($job['posted_at']) ? date('c', strtotime($job['posted_at'])) : null, // ISO 8601 format
        'expires_at' => !empty($job['expires_at']) ? date('c', strtotime($job['expires_at'])) : null, // ISO 8601 format
        'badge' => $badge,
        'badge_class' => $badgeClass,
        'company_description' => $job['company_description'] ?? null,
        'company_website' => $job['company_website'] ?? null,
        'company_maps_url' => $job['company_maps_url'] ?? null,
        'application_email' => $job['application_email'] ?? null,
        'application_url' => $job['application_url'] ?? null,
        'application_phone' => $job['application_phone'] ?? null,
    ];
}


/**
 * Filter jobs based on criteria
 */
function filterJobs($jobs, $filters) {
    $filtered = $jobs;

    // Full-text search on title, company, skills
    if (!empty($filters['query'])) {
        $query = strtolower(trim($filters['query']));
        $filtered = array_filter($filtered, function($job) use ($query) {
            $searchText = strtolower(
                ($job['title'] ?? '') . ' ' . 
                ($job['company_name'] ?? '') . ' ' . 
                implode(' ', $job['skills'] ?? [])
            );
            return strpos($searchText, $query) !== false;
        });
    }

    // Location filter
    if (!empty($filters['location'])) {
        $location = strtolower(trim($filters['location']));
        $filtered = array_filter($filtered, function($job) use ($location) {
            // Check if location matches the job location string
            $locationMatch = strpos(strtolower($job['location'] ?? ''), $location) !== false;
            
            // Check if it's a remote job (either job_type is 'remote' or is_remote flag is set)
            $remoteMatch = ($location === 'remote' && (
                ($job['job_type'] ?? '') === 'remote' || 
                (!empty($job['is_remote']) && $job['is_remote'] == 1)
            ));
            
            return $locationMatch || $remoteMatch;
        });
    }

    // Job type filter
    if (!empty($filters['job_types'])) {
        $filtered = array_filter($filtered, function($job) use ($filters) {
            $jobType = $job['job_type'] ?? '';
            $isRemote = (!empty($job['is_remote']) && $job['is_remote'] == 1);
            
            // Check each filter type
            foreach ($filters['job_types'] as $filterType) {
                // Handle remote filter specially - check both job_type and is_remote flag
                if ($filterType === 'remote') {
                    if ($jobType === 'remote' || $isRemote) {
                        return true;
                    }
                } else {
                    // For other job types, check exact match
                    if ($jobType === $filterType) {
                        return true;
                    }
                }
            }
            
            return false;
        });
    }

    // Experience filter
    if (!empty($filters['experiences'])) {
        $filtered = array_filter($filtered, function($job) use ($filters) {
            $jobExperience = $job['experience'] ?? $job['experience_level'] ?? '';
            // Map database experience level to frontend filter value
            $mappedExperience = mapExperienceLevel($jobExperience);
            return in_array($mappedExperience, $filters['experiences']);
        });
    }

    // Salary filter
    if ($filters['salary_min'] > 0) {
        $filtered = array_filter($filtered, function($job) use ($filters) {
            // Check both salary and salary_min fields
            $jobSalary = $job['salary'] ?? $job['salary_min'] ?? 0;
            return $jobSalary >= $filters['salary_min'];
        });
    }

    // Date posted filter
    if (!empty($filters['date_posted'])) {
        $now = time();
        $filtered = array_filter($filtered, function($job) use ($filters, $now) {
            if (empty($job['posted_at'])) {
                return false;
            }
            
            $postedTime = strtotime($job['posted_at']);
            if ($postedTime === false) {
                return false;
            }
            
            $diff = $now - $postedTime;
            
            foreach ($filters['date_posted'] as $filter) {
                if ($filter === '24h' && $diff <= 86400) return true;
                if ($filter === '3d' && $diff <= 259200) return true;
                if ($filter === '7d' && $diff <= 604800) return true;
            }
            return false;
        });
    }

    // Company filter
    if (!empty($filters['company'])) {
        $company = strtolower(trim($filters['company']));
        $filtered = array_filter($filtered, function($job) use ($company) {
            return strpos(strtolower($job['company_name'] ?? ''), $company) !== false;
        });
    }

    // Skills filter
    if (!empty($filters['skills'])) {
        $filtered = array_filter($filtered, function($job) use ($filters) {
            $jobSkills = array_map('strtolower', $job['skills'] ?? []);
            $filterSkills = array_map('strtolower', $filters['skills']);
            return !empty(array_intersect($jobSkills, $filterSkills));
        });
    }

    // Category filter - use the category field directly from formatted data
    if (!empty($filters['categories'])) {
        $filtered = array_filter($filtered, function($job) use ($filters) {
            $jobCategory = $job['category'] ?? 'Other';
            return in_array($jobCategory, $filters['categories']);
        });
    }

    return array_values($filtered);
}

/**
 * Sort jobs
 */
function sortJobs($jobs, $sortBy) {
    switch ($sortBy) {
        case 'newest':
            usort($jobs, function($a, $b) {
                return strtotime($b['posted_at']) - strtotime($a['posted_at']);
            });
            break;
        case 'salary_high':
            usort($jobs, function($a, $b) {
                return ($b['salary'] ?? 0) - ($a['salary'] ?? 0);
            });
            break;
        case 'popular':
            // Sort by rating, then by posted date
            usort($jobs, function($a, $b) {
                $ratingDiff = ($b['company_rating'] ?? 0) - ($a['company_rating'] ?? 0);
                if ($ratingDiff !== 0) return $ratingDiff;
                return strtotime($b['posted_at']) - strtotime($a['posted_at']);
            });
            break;
        case 'relevant':
        default:
            // Keep original order (could be enhanced with relevance scoring)
            break;
    }
    return $jobs;
}

/**
 * Calculate distance between two coordinates (Haversine formula)
 * Returns distance in kilometers
 */
function calculateDistance($lat1, $lon1, $lat2, $lon2) {
    $earthRadius = 6371; // Earth's radius in kilometers

    $dLat = deg2rad($lat2 - $lat1);
    $dLon = deg2rad($lon2 - $lon1);

    $a = sin($dLat / 2) * sin($dLat / 2) +
         cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
         sin($dLon / 2) * sin($dLon / 2);

    $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
    $distance = $earthRadius * $c;

    return $distance;
}

/**
 * Get company description
 */
function getCompanyDescription($companyName) {
    $descriptions = [
        'Google' => 'Google is a multinational technology company specializing in Internet-related services and products. We are on a mission to organize the world\'s information and make it universally accessible and useful.',
        'Meta' => 'Meta builds technologies that help people connect, find communities, and grow businesses. We\'re moving beyond 2D screens toward immersive experiences in the metaverse.',
        'Spotify' => 'Spotify is a digital music, podcast, and video service that gives you access to millions of songs and other content from creators all over the world.',
        'Apple' => 'Apple designs and creates iPhone, iPad, Mac, Apple Watch, and Apple TV, along with software including iOS, macOS, watchOS, and tvOS.',
        'Microsoft' => 'Microsoft enables digital transformation for the era of an intelligent cloud and an intelligent edge. Our mission is to empower every person and every organization on the planet to achieve more.',
        'Amazon' => 'Amazon is guided by four principles: customer obsession rather than competitor focus, passion for invention, commitment to operational excellence, and long-term thinking.',
        'Netflix' => 'Netflix is the world\'s leading streaming entertainment service with over 200 million paid memberships in over 190 countries enjoying TV series, documentaries and feature films.',
        'Figma' => 'Figma is the leading collaborative design tool, helping teams create, test, and ship better designs from start to finish. We are on a mission to make design accessible to everyone.'
    ];
    
    return $descriptions[$companyName] ?? 'A leading company in the technology industry, committed to innovation and excellence.';
}

