<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <?php
    // Load site settings
    $siteSettingsModel = new \App\Models\SiteSettingsModel();
    
    // Detect current page from URI using CodeIgniter's URI service
    $uri = service('uri');
    $uriPath = trim($uri->getPath(), '/');
    
    // Determine page type
    $pageType = 'home';
    // Check for jobs page - exact match 'jobs' or starts with 'jobs/'
    if ($uriPath === 'jobs' || strpos($uriPath, 'jobs/') === 0) {
        // Make sure it's not a job detail page (which has 'job/' not 'jobs')
        if (strpos($uriPath, 'job/') === false) {
            $pageType = 'jobs';
        }
    } elseif ($uriPath === 'post-job' || strpos($uriPath, 'post-job') !== false) {
        $pageType = 'postjob';
    } elseif ($uriPath === 'about' || strpos($uriPath, 'about') === 0) {
        $pageType = 'about';
    } elseif ($uriPath === 'contact' || strpos($uriPath, 'contact') === 0) {
        $pageType = 'contact';
    } elseif ($uriPath === 'terms' || strpos($uriPath, 'terms') === 0) {
        $pageType = 'terms';
    } elseif ($uriPath === 'privacy' || strpos($uriPath, 'privacy') === 0) {
        $pageType = 'privacy';
    }
    
    // Get page-specific title and description from settings first
    // Only use passed $title/$meta_description if explicitly provided (not null)
    $pageTitle = null;
    $pageDescription = null;
    
    // Get settings-based title and description for current page
    switch ($pageType) {
        case 'jobs':
            $pageTitle = $siteSettingsModel->getSetting('jobs_title', 'Job Search & Listings - TopTopJobs');
            $pageDescription = $siteSettingsModel->getSetting('jobs_description', 'Browse thousands of job listings on TopTopJobs. Search by location, job type, and more.');
            break;
        case 'postjob':
            $pageTitle = $siteSettingsModel->getSetting('postjob_title', 'Post a Job - TopTopJobs');
            $pageDescription = $siteSettingsModel->getSetting('postjob_description', 'Post your job listing for free on TopTopJobs. Reach thousands of qualified candidates.');
            break;
        case 'about':
            $pageTitle = $siteSettingsModel->getSetting('about_title', 'About Us - TopTopJobs');
            $pageDescription = $siteSettingsModel->getSetting('about_description', 'Learn more about TopTopJobs and our mission to connect talent with opportunity.');
            break;
        case 'contact':
            $pageTitle = $siteSettingsModel->getSetting('contact_title', 'Contact Us - TopTopJobs');
            $pageDescription = $siteSettingsModel->getSetting('contact_description', 'Get in touch with the TopTopJobs team for support or inquiries.');
            break;
        case 'terms':
            $pageTitle = $siteSettingsModel->getSetting('terms_title', 'Terms & Conditions - TopTopJobs');
            $pageDescription = $siteSettingsModel->getSetting('terms_description', 'Review the terms and conditions for using TopTopJobs.');
            break;
        case 'privacy':
            $pageTitle = $siteSettingsModel->getSetting('privacy_title', 'Privacy Policy - TopTopJobs');
            $pageDescription = $siteSettingsModel->getSetting('privacy_description', 'Read how TopTopJobs handles your data and privacy.');
            break;
        default:
            $pageTitle = $siteSettingsModel->getSetting('home_title', 'TopTopJobs - Find Your Dream Job');
            $pageDescription = $siteSettingsModel->getSetting('home_description', 'Find your dream job on TopTopJobs. Browse thousands of job listings.');
            break;
    }
    
    // Override with explicitly passed values if provided
    if (isset($title) && $title !== null) {
        $pageTitle = $title;
    }
    if (isset($meta_description) && $meta_description !== null) {
        $pageDescription = $meta_description;
    }
    ?>
    <title><?= esc($pageTitle) ?></title>
    <?php if (!empty($pageDescription)): ?>
        <meta name="description" content="<?= esc($pageDescription) ?>">
    <?php endif; ?>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    <style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
    </style>
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "primary": "#2b6cee",
                        "background-light": "#f6f6f8",
                        "background-dark": "#101622",
                    },
                    fontFamily: {
                        "display": ["Inter", "sans-serif"]
                    },
                    borderRadius: {"DEFAULT": "1rem", "lg": "2rem", "xl": "3rem", "full": "9999px"},
                },
            },
        }
    </script>
    <style>
        /* Profile Dropdown Styles (Desktop only) */
        #profileDropdown {
            animation: fadeIn 0.2s ease-in-out;
        }
        
        #profileMenuBtn {
            pointer-events: auto !important;
            cursor: pointer !important;
            z-index: 50;
            position: relative;
        }
        
        /* Hide profile dropdown on mobile */
        @media (max-width: 768px) {
            #profileMenuBtn,
            #profileDropdown {
                display: none !important;
            }
        }
        
        /* Mobile Menu Styles */
        #mobileMenu {
            will-change: transform;
        }
        
        #mobileMenu:not(.translate-x-full) {
            transform: translateX(0);
        }
        
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        /* Header Scroll Hide/Show */
        #mainHeader {
            will-change: transform;
            transform: translateY(0);
        }
        
        /* Filter Pills Scroll Hide/Show */
        #filterPillsSection {
            will-change: transform;
            transform: translateY(0);
        }
        
        /* Filter Pills - Keep on one line */
        #filterPillsSection .flex {
            flex-wrap: nowrap !important;
        }
        
        /* Hide scrollbar for filter pills */
        #filterPillsSection .overflow-x-auto {
            -ms-overflow-style: none;  /* IE and Edge */
            scrollbar-width: none;  /* Firefox */
        }
        
        #filterPillsSection .overflow-x-auto::-webkit-scrollbar {
            display: none;  /* Chrome, Safari and Opera */
        }
        
        /* Custom Scrollbar Styles */
        /* For Webkit browsers (Chrome, Safari, Edge) */
        ::-webkit-scrollbar {
            width: 12px;
            height: 12px;
        }
        
        ::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }
        
        .dark ::-webkit-scrollbar-track {
            background: #1f2937;
        }
        
        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 10px;
            border: 2px solid #f1f1f1;
        }
        
        .dark ::-webkit-scrollbar-thumb {
            background: #4b5563;
            border: 2px solid #1f2937;
        }
        
        ::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
        
        .dark ::-webkit-scrollbar-thumb:hover {
            background: #6b7280;
        }
        
        /* For Firefox */
        * {
            scrollbar-width: thin;
            scrollbar-color: #cbd5e1 #f1f1f1;
        }
        
        .dark * {
            scrollbar-color: #4b5563 #1f2937;
        }
    </style>
    <?php if (!empty($css_file)): ?>
        <link rel="stylesheet" href="<?= base_url($css_file) ?>">
    <?php endif; ?>
    <!-- Favicon -->
    <link rel="apple-touch-icon" sizes="180x180" href="<?= base_url('apple-touch-icon.png') ?>">
    <link rel="icon" type="image/png" sizes="32x32" href="<?= base_url('favicon-32x32.png') ?>">
    <link rel="icon" type="image/png" sizes="16x16" href="<?= base_url('favicon-16x16.png') ?>">
    <link rel="manifest" href="<?= base_url('site.webmanifest') ?>">
</head>

