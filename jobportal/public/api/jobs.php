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

// Get query parameters
$query = $_GET['q'] ?? '';
$location = $_GET['loc'] ?? '';
$jobTypes = isset($_GET['job_type']) ? explode(',', $_GET['job_type']) : [];
$experiences = isset($_GET['experience']) ? explode(',', $_GET['experience']) : [];
$salaryMin = isset($_GET['sal_min']) ? (int)$_GET['sal_min'] : 0;
$datePosted = isset($_GET['date_posted']) ? explode(',', $_GET['date_posted']) : [];
$company = $_GET['company'] ?? '';
$skills = isset($_GET['skills']) ? explode(',', $_GET['skills']) : [];
$userLat = isset($_GET['lat']) ? (float)$_GET['lat'] : null;
$userLng = isset($_GET['lng']) ? (float)$_GET['lng'] : null;
$sort = $_GET['sort'] ?? 'relevant';
$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$perPage = isset($_GET['per_page']) ? min(100, max(1, (int)$_GET['per_page'])) : 20;

// Cache key for this search
$cacheKey = md5(serialize([
    'q' => $query,
    'loc' => $location,
    'job_type' => $jobTypes,
    'experience' => $experiences,
    'sal_min' => $salaryMin,
    'date_posted' => $datePosted,
    'company' => $company,
    'skills' => $skills,
    'sort' => $sort,
    'page' => $page
]));

// Check cache (5 minutes)
$cacheFile = __DIR__ . '/../writable/cache/jobs_' . $cacheKey . '.json';
if (file_exists($cacheFile) && (time() - filemtime($cacheFile)) < 300) {
    $cached = json_decode(file_get_contents($cacheFile), true);
    if ($cached) {
        echo json_encode($cached);
        exit;
    }
}

// Mock job data (Replace with actual database query)
$allJobs = getMockJobs();

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

// Format response
$response = [
    'success' => true,
    'jobs' => $paginatedJobs,
    'total' => $total,
    'page' => $page,
    'per_page' => $perPage,
    'has_more' => $hasMore
];

// Cache response
if (!is_dir(dirname($cacheFile))) {
    mkdir(dirname($cacheFile), 0755, true);
}
file_put_contents($cacheFile, json_encode($response));

echo json_encode($response);

/**
 * Get mock job data
 * Replace this with actual database query
 */
function getMockJobs() {
    return [
        [
            'id' => 1,
            'title' => 'Senior Product Designer',
            'company_name' => 'Google',
            'company_logo' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuB05iY8MHCloko0xXgRy_Jczz3KCqK0j41JrpKtPrLoEFSBFfS3RRHpNwzjo4352pEft_-EM62Omi8fugVrYLNxKrOsfEO5ZP6w9WUGuZZMWAuQs87m3zlh7lr-j_KpkSIAdOUXj7Uyz_BxbAn456x3WlhcmsufhjVi8jlruQLLjoOKsTE-K0ERqPW3aIXAbIXW8nLj0joDAxMs4LQsueuixWEizOvt6Hc_WHFPI-fgqEFcM-OkXqbqruu1W-l7ZNGeaz-xtRB17OU',
            'company_rating' => 4.5,
            'location' => 'Mountain View, CA',
            'latitude' => 37.4220,
            'longitude' => -122.0841,
            'job_type' => 'full-time',
            'experience' => 'senior',
            'salary' => 15000000,
            'skills' => ['Design', 'UI/UX', 'Figma', 'Prototyping'],
            'description' => "We're looking for a creative and passionate product designer to join our team and help shape the future of our products.",
            'posted_at' => date('Y-m-d H:i:s', strtotime('-2 days')),
            'badge' => 'New',
            'badge_class' => 'bg-green-100 text-green-800'
        ],
        [
            'id' => 2,
            'title' => 'Backend Engineer (PHP)',
            'company_name' => 'Meta',
            'company_logo' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuCa4i9YIcvfi-4ogR9bYPtb6EJMcZ8KfKUSIiSqiXRRJ3jCbf5rdnslYZNneZtbu6y43LO2fS3xzUfDQErXrK9H0LaCLOoNVZ5kfDwXVkQYE6KYUyvX77gLNFrVcfKuUnUSDq-m5bzJ1MBZP07bfb7uuDtHjgZZ5o8CjvB1Mj0HChB1AF-HBDsjY-Ecyst_57BtODR9uqGxFLCw6b2Fh-3ydN3CDzDGN34kd7W_uavR3nMaQ-nhElLHY3Q6rkqlv0zlgsIHBn5nvI0',
            'company_rating' => 4.7,
            'location' => 'Menlo Park, CA',
            'latitude' => 37.4530,
            'longitude' => -122.1817,
            'job_type' => 'full-time',
            'experience' => 'junior',
            'salary' => 8000000,
            'skills' => ['PHP', 'MySQL', 'Laravel', 'API'],
            'description' => 'Join our infrastructure team to build and scale the next generation of our platform using PHP 8+ and modern frameworks.',
            'posted_at' => date('Y-m-d H:i:s', strtotime('-5 days')),
            'badge' => null
        ],
        [
            'id' => 3,
            'title' => 'Data Analyst Intern',
            'company_name' => 'Spotify',
            'company_logo' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuCQsPRlVdF3rN3bKlU8wxZtnvbjdk5DNq4DlRb_JCSH3qOCzaHxtyplssUPOFlQAwvq6pVcnSx1QmYwF68l57sHCFdV84ClRyXCzL0pKb7X2nIOmfcEntKcn8SGFGlJItZ4lKsNSIfAFpikh2D8ogZa-76swsmJK1ck4_XPjdYClAxG0bB29yURje5XPKJspi5wSXAmyDEjhrJ-DrbDKQ6V5_133Ar5VEPEqIBToz7WDCjDd-iWk5iXJyHWiDTzVGp02RQO1Gy-h9M',
            'company_rating' => 4.3,
            'location' => 'New York, NY',
            'latitude' => 40.7128,
            'longitude' => -74.0060,
            'job_type' => 'internship',
            'experience' => 'fresher',
            'salary' => 3000000,
            'skills' => ['Analytics', 'SQL', 'Python'],
            'description' => 'An exciting opportunity for a student or recent graduate to work with our data science team on user behavior analysis.',
            'posted_at' => date('Y-m-d H:i:s', strtotime('-7 days')),
            'badge' => null
        ],
        [
            'id' => 4,
            'title' => 'Frontend Developer',
            'company_name' => 'Amazon',
            'company_logo' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuCbPYBEnGDCgg5AuErg8Ad1-82nyneAu2AfDt4vaL-Sb5V6alib6oYn-x2ana1u7rB6knYikdgAICW-02xN1qPS5C1sBWZQR5SbsomyWuq0PWcSLWQngi4oyO_L6zkA0AJ47HG4x1EE_WnZhW0Q5ToBetjzUwBE1aDA9KPpZyR9SWxkTf7bBrTeSXBUpR98uVRt14E4D8NRGanAWd4p6ZOX5ref_jNMLfEiRaxfWXuFWdMN-gfc_BuzwxA9WXt5Og3kwsQxtQM-QyY',
            'company_rating' => 4.2,
            'location' => 'Seattle, WA',
            'latitude' => 47.6062,
            'longitude' => -122.3321,
            'job_type' => 'contract',
            'experience' => 'junior',
            'salary' => 7000000,
            'skills' => ['Bootstrap 5', 'JavaScript', 'React', 'Frontend'],
            'description' => 'Build beautiful and responsive user interfaces using Bootstrap 5 and modern JavaScript frameworks for AWS services.',
            'posted_at' => date('Y-m-d H:i:s', strtotime('-3 days')),
            'badge' => null
        ],
        [
            'id' => 5,
            'title' => 'DevOps Engineer',
            'company_name' => 'Slack',
            'company_logo' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuC6dCnph3Osdogd3AI2I8gtmgR4Nyk3QNY8GcxYg2wiseVuZgqpE3tisH3Sj-F1Ks5SAUJYq6FsLBtLWfjOxe2DNPnErv5aDYg5_yDJgNJl0CnKhLdmvpfF8Ss7HTOYPfQlgDTF8S2_cqGsRGp21QnadsR0ev86n3xoJb0v22ME7ilNwWiHMfnPpB_dJ4--1zA_oqVTcBVsTLQOvCA0G1oph0I7KDcRZxCAITomTFMk2reXTFbn8LvjJU51uuKcZZvLFVU8nxRFRfU',
            'company_rating' => 4.6,
            'location' => 'San Francisco, CA',
            'latitude' => 37.7749,
            'longitude' => -122.4194,
            'job_type' => 'full-time',
            'experience' => 'senior',
            'salary' => 12000000,
            'skills' => ['CI/CD', 'Docker', 'Kubernetes', 'AWS'],
            'description' => 'Help maintain and improve our CI/CD pipelines, ensuring our services are reliable and scalable for millions of users.',
            'posted_at' => date('Y-m-d H:i:s', strtotime('-1 day')),
            'badge' => 'Urgent',
            'badge_class' => 'bg-orange-100 text-orange-800'
        ],
        [
            'id' => 6,
            'title' => 'Marketing Manager',
            'company_name' => 'Shopify',
            'company_logo' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuAPEpuUQWTh6vxomG4Yb6m5TEd75ohHpmHO11hDa3ACXjcdfAyZpafbzUlgzqP0E_MDHfRWDj_wOdhTGFWrVxVRApC1PKZksihRcqNVMYmkMNK3zLdDgv9x2I6ln4e3rxevAYjXaXhWzUSIX2rFUZoxvz9dmXYk6lMWAMQDE-PNJe4GCK_xz85hFMJ0M1hlJxT9JtY5P3mKJ4Y9GJoZz1fbHW1iOMmXtBK_mC99xxfCQjdHoPyNZ0MkxwjbYD_Fn2CzXGtDFRasDq4',
            'company_rating' => 4.4,
            'location' => 'Ottawa, ON',
            'latitude' => 45.4215,
            'longitude' => -75.6972,
            'job_type' => 'part-time',
            'experience' => 'junior',
            'salary' => 5000000,
            'skills' => ['Marketing', 'SEO', 'Content'],
            'description' => 'Lead our growth marketing initiatives and develop campaigns to attract new merchants to the Shopify platform.',
            'posted_at' => date('Y-m-d H:i:s', strtotime('-8 days')),
            'badge' => null
        ],
        [
            'id' => 7,
            'title' => 'Full Stack Developer (Remote)',
            'company_name' => 'Microsoft',
            'company_logo' => 'https://via.placeholder.com/48',
            'company_rating' => 4.8,
            'location' => 'Remote',
            'latitude' => null,
            'longitude' => null,
            'job_type' => 'remote',
            'experience' => 'senior',
            'salary' => 14000000,
            'skills' => ['Node.js', 'React', 'TypeScript', 'MongoDB'],
            'description' => 'Build scalable web applications using modern technologies. Work remotely with a global team.',
            'posted_at' => date('Y-m-d H:i:s', strtotime('-12 hours')),
            'badge' => 'New',
            'badge_class' => 'bg-green-100 text-green-800'
        ],
        [
            'id' => 8,
            'title' => 'PHP Developer',
            'company_name' => 'TechCorp',
            'company_logo' => 'https://via.placeholder.com/48',
            'company_rating' => 4.1,
            'location' => 'Mumbai, India',
            'latitude' => 19.0760,
            'longitude' => 72.8777,
            'job_type' => 'full-time',
            'experience' => 'junior',
            'salary' => 6000000,
            'skills' => ['PHP', 'Laravel', 'MySQL', 'REST API'],
            'description' => 'We are looking for a PHP developer with experience in Laravel framework to join our growing team.',
            'posted_at' => date('Y-m-d H:i:s', strtotime('-6 hours')),
            'badge' => 'New',
            'badge_class' => 'bg-green-100 text-green-800'
        ]
    ];
}

/**
 * Filter jobs based on criteria
 */
function filterJobs($jobs, $filters) {
    $filtered = $jobs;

    // Full-text search on title, company, skills
    if (!empty($filters['query'])) {
        $query = strtolower($filters['query']);
        $filtered = array_filter($filtered, function($job) use ($query) {
            $searchText = strtolower(
                $job['title'] . ' ' . 
                $job['company_name'] . ' ' . 
                implode(' ', $job['skills'])
            );
            return strpos($searchText, $query) !== false;
        });
    }

    // Location filter
    if (!empty($filters['location'])) {
        $location = strtolower($filters['location']);
        $filtered = array_filter($filtered, function($job) use ($location) {
            return strpos(strtolower($job['location']), $location) !== false || 
                   ($location === 'remote' && $job['job_type'] === 'remote');
        });
    }

    // Job type filter
    if (!empty($filters['job_types'])) {
        $filtered = array_filter($filtered, function($job) use ($filters) {
            return in_array($job['job_type'], $filters['job_types']);
        });
    }

    // Experience filter
    if (!empty($filters['experiences'])) {
        $filtered = array_filter($filtered, function($job) use ($filters) {
            return in_array($job['experience'], $filters['experiences']);
        });
    }

    // Salary filter
    if ($filters['salary_min'] > 0) {
        $filtered = array_filter($filtered, function($job) use ($filters) {
            return $job['salary'] >= $filters['salary_min'];
        });
    }

    // Date posted filter
    if (!empty($filters['date_posted'])) {
        $now = time();
        $filtered = array_filter($filtered, function($job) use ($filters, $now) {
            $postedTime = strtotime($job['posted_at']);
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
        $company = strtolower($filters['company']);
        $filtered = array_filter($filtered, function($job) use ($company) {
            return strpos(strtolower($job['company_name']), $company) !== false;
        });
    }

    // Skills filter
    if (!empty($filters['skills'])) {
        $filtered = array_filter($filtered, function($job) use ($filters) {
            $jobSkills = array_map('strtolower', $job['skills']);
            $filterSkills = array_map('strtolower', $filters['skills']);
            return !empty(array_intersect($jobSkills, $filterSkills));
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

