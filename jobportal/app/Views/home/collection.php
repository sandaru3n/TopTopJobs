<?php
// Build custom head with SEO meta tags
$headData = [
    'title' => esc($metaTags['title'] ?? $collection['site_title'] ?? 'Collection - TopTopJobs'),
    'meta_description' => esc($metaTags['description'] ?? ''),
    'meta_keywords' => esc($metaTags['keywords'] ?? ''),
    'og_title' => esc($metaTags['og:title'] ?? ''),
    'og_description' => esc($metaTags['og:description'] ?? ''),
    'og_type' => esc($metaTags['og:type'] ?? 'website'),
    'og_url' => esc($metaTags['og:url'] ?? current_url()),
    'twitter_card' => esc($metaTags['twitter:card'] ?? 'summary'),
    'twitter_title' => esc($metaTags['twitter:title'] ?? ''),
    'twitter_description' => esc($metaTags['twitter:description'] ?? ''),
    'canonical_url' => esc($metaTags['og:url'] ?? current_url()),
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-017G3E1N5V"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());

      gtag('config', 'G-017G3E1N5V');
    </script>
    <title><?= $headData['title'] ?></title>
    
    <!-- SEO Meta Tags -->
    <?php if (!empty($headData['meta_description'])): ?>
    <meta name="description" content="<?= $headData['meta_description'] ?>">
    <?php endif; ?>
    
    <?php if (!empty($headData['meta_keywords'])): ?>
    <meta name="keywords" content="<?= $headData['meta_keywords'] ?>">
    <?php endif; ?>
    
    <!-- Open Graph Meta Tags -->
    <?php if (!empty($headData['og_title'])): ?>
    <meta property="og:title" content="<?= $headData['og_title'] ?>">
    <?php endif; ?>
    
    <?php if (!empty($headData['og_description'])): ?>
    <meta property="og:description" content="<?= $headData['og_description'] ?>">
    <?php endif; ?>
    
    <?php if (!empty($headData['og_type'])): ?>
    <meta property="og:type" content="<?= $headData['og_type'] ?>">
    <?php endif; ?>
    
    <?php if (!empty($headData['og_url'])): ?>
    <meta property="og:url" content="<?= $headData['og_url'] ?>">
    <?php endif; ?>
    
    <!-- Twitter Card Meta Tags -->
    <?php if (!empty($headData['twitter_card'])): ?>
    <meta name="twitter:card" content="<?= $headData['twitter_card'] ?>">
    <?php endif; ?>
    
    <?php if (!empty($headData['twitter_title'])): ?>
    <meta name="twitter:title" content="<?= $headData['twitter_title'] ?>">
    <?php endif; ?>
    
    <?php if (!empty($headData['twitter_description'])): ?>
    <meta name="twitter:description" content="<?= $headData['twitter_description'] ?>">
    <?php endif; ?>
    
    <!-- Canonical URL -->
    <?php if (!empty($headData['canonical_url'])): ?>
    <link rel="canonical" href="<?= $headData['canonical_url'] ?>">
    <?php endif; ?>
    
    <!-- Favicon -->
    <link rel="apple-touch-icon" sizes="180x180" href="<?= base_url('apple-touch-icon.png') ?>">
    <link rel="icon" type="image/png" sizes="32x32" href="<?= base_url('favicon-32x32.png') ?>">
    <link rel="icon" type="image/png" sizes="16x16" href="<?= base_url('favicon-16x16.png') ?>">
    <link rel="manifest" href="<?= base_url('site.webmanifest') ?>">
    
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
</head>
<body class="font-display bg-background-light dark:bg-background-dark text-[#111318] dark:text-gray-200">
    <div class="relative flex h-auto min-h-screen w-full flex-col group/design-root overflow-x-hidden">
        <?= view('partials/header') ?>

        <main class="layout-container flex h-full grow flex-col">
            <div class="container mx-auto px-4 py-8">
                <!-- Breadcrumbs -->
                <div class="flex flex-wrap gap-2 mb-8">
                    <a class="text-primary/80 dark:text-primary/60 text-sm font-medium leading-normal hover:text-primary dark:hover:text-primary/80 transition-colors" href="<?= base_url('/') ?>">Home</a>
                    <span class="text-primary/50 text-sm font-medium leading-normal">/</span>
                    <a class="text-primary/80 dark:text-primary/60 text-sm font-medium leading-normal hover:text-primary dark:hover:text-primary/80 transition-colors" href="<?= base_url('jobs') ?>">Jobs</a>
                    <span class="text-primary/50 text-sm font-medium leading-normal">/</span>
                    <span class="text-[#111318] dark:text-gray-300 text-sm font-medium leading-normal"><?= esc($collection['name']) ?></span>
                </div>

                <!-- Collection Header -->
                <div class="mb-8">
                    <?php if (isset($isNotFound) && $isNotFound): ?>
                        <div class="mb-6 p-4 bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg">
                            <p class="text-sm text-yellow-800 dark:text-yellow-300">
                                <span class="material-symbols-outlined align-middle mr-2">warning</span>
                                Collection not found. The collection with slug "<code class="bg-yellow-100 dark:bg-yellow-900 px-2 py-1 rounded"><?= esc($collection['slug'] ?? '') ?></code>" does not exist.
                            </p>
                        </div>
                    <?php elseif (isset($isInactive) && $isInactive): ?>
                        <div class="mb-6 p-4 bg-orange-50 dark:bg-orange-900/20 border border-orange-200 dark:border-orange-800 rounded-lg">
                            <p class="text-sm text-orange-800 dark:text-orange-300">
                                <span class="material-symbols-outlined align-middle mr-2">lock</span>
                                This collection is currently unavailable. It may be temporarily disabled or under maintenance.
                            </p>
                        </div>
                    <?php endif; ?>
                    
                    <h1 class="text-4xl font-bold text-[#111318] dark:text-white mb-4"><?= esc($collection['name'] ?? 'Collection') ?></h1>
                    <?php if (!empty($collection['description'])): ?>
                        <p class="text-lg text-gray-600 dark:text-gray-400 mb-4"><?= esc($collection['description']) ?></p>
                    <?php endif; ?>
                    <div class="flex items-center gap-4 text-sm text-gray-500 dark:text-gray-400">
                        <span class="flex items-center gap-1">
                            <span class="material-symbols-outlined text-base">work</span>
                            <?= count($jobs ?? []) ?> <?= count($jobs ?? []) === 1 ? 'Job' : 'Jobs' ?>
                        </span>
                    </div>
                </div>

                <!-- Jobs Grid -->
                <?php if (empty($jobs)): ?>
                    <div class="text-center py-12">
                        <span class="material-symbols-outlined text-6xl text-gray-400 dark:text-gray-600 mb-4">work_off</span>
                        <h3 class="text-xl font-semibold text-gray-700 dark:text-gray-300 mb-2">
                            <?php if (isset($isNotFound) && $isNotFound): ?>
                                Collection Not Found
                            <?php elseif (isset($isInactive) && $isInactive): ?>
                                Collection Unavailable
                            <?php else: ?>
                                No jobs in this collection
                            <?php endif; ?>
                        </h3>
                        <p class="text-sm text-gray-500 dark:text-gray-500 mb-6">
                            <?php if (isset($isNotFound) && $isNotFound): ?>
                                The collection you are looking for does not exist. Please check the URL and try again.
                            <?php elseif (isset($isInactive) && $isInactive): ?>
                                This collection is currently unavailable. Check back later.
                            <?php else: ?>
                                Check back later for new job postings.
                            <?php endif; ?>
                        </p>
                        <div class="flex flex-col sm:flex-row gap-4 justify-center">
                            <a href="<?= base_url('jobs') ?>" class="inline-flex items-center justify-center gap-2 px-6 py-3 rounded-lg bg-primary text-white font-medium hover:bg-blue-700 transition-colors">
                                <span class="material-symbols-outlined">search</span>
                                Browse All Jobs
                            </a>
                            <a href="<?= base_url('/') ?>" class="inline-flex items-center justify-center gap-2 px-6 py-3 rounded-lg border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 font-medium hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                <span class="material-symbols-outlined">home</span>
                                Go to Homepage
                            </a>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <?php 
                        helper('image');
                        foreach ($jobs as $job): 
                            $jobSlug = $job['slug'] ?? '';
                            $jobUrl = base_url('/job/' . $jobSlug);
                            $companyLogo = fix_image_url($job['company_logo'] ?? null);
                        ?>
                            <div class="bg-white dark:bg-gray-800/50 rounded-lg p-6 flex flex-col gap-4 border border-gray-200 dark:border-gray-700/50 hover:shadow-lg hover:border-primary/50 dark:hover:border-primary/50 transition-all duration-300 cursor-pointer" onclick="window.location.href='<?= $jobUrl ?>'">
                                <div class="flex items-start justify-between">
                                    <div class="flex items-start gap-4 flex-1">
                                        <?php if ($companyLogo): ?>
                                            <img src="<?= esc($companyLogo) ?>" alt="<?= esc($job['company_name']) ?> Logo" class="w-12 h-12 rounded-lg object-cover" onerror="this.style.display='none'">
                                        <?php endif; ?>
                                        <div class="flex-1">
                                            <h3 class="text-lg font-semibold text-[#111318] dark:text-white mb-1 line-clamp-2"><?= esc($job['title']) ?></h3>
                                            <p class="text-sm text-gray-600 dark:text-gray-400"><?= esc($job['company_name']) ?></p>
                                        </div>
                                    </div>
                                </div>
                                
                                <p class="text-sm text-gray-500 dark:text-gray-400 leading-relaxed line-clamp-3"><?= esc(strip_tags($job['description'] ?? '')) ?></p>
                                
                                <div class="flex flex-wrap gap-2">
                                    <?php 
                                    // Get category and subcategory if available
                                    $categoryName = $job['category_name'] ?? null;
                                    $subcategoryName = $job['subcategory_name'] ?? null;
                                    
                                    // Category color mapping
                                    $categoryColors = [
                                        'IT & Software' => 'bg-indigo-100 dark:bg-indigo-900/30 text-indigo-700 dark:text-indigo-400',
                                        'Marketing & Advertising' => 'bg-pink-100 dark:bg-pink-900/30 text-pink-700 dark:text-pink-400',
                                        'Sales' => 'bg-orange-100 dark:bg-orange-900/30 text-orange-700 dark:text-orange-400',
                                        'Customer Service' => 'bg-teal-100 dark:bg-teal-900/30 text-teal-700 dark:text-teal-400',
                                        'Finance & Accounting' => 'bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400',
                                        'Engineering' => 'bg-cyan-100 dark:bg-cyan-900/30 text-cyan-700 dark:text-cyan-400',
                                        'Design & Creative' => 'bg-rose-100 dark:bg-rose-900/30 text-rose-700 dark:text-rose-400',
                                        'Healthcare & Medical' => 'bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400',
                                        'Education & Training' => 'bg-amber-100 dark:bg-amber-900/30 text-amber-700 dark:text-amber-400',
                                        'Hospitality & Tourism' => 'bg-yellow-100 dark:bg-yellow-900/30 text-yellow-700 dark:text-yellow-400',
                                        'Logistics & Supply Chain' => 'bg-lime-100 dark:bg-lime-900/30 text-lime-700 dark:text-lime-400',
                                        'Construction & Skilled Trades' => 'bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400',
                                        'Human Resources' => 'bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400',
                                        'Legal' => 'bg-violet-100 dark:bg-violet-900/30 text-violet-700 dark:text-violet-400',
                                        'Media, Writing & Communications' => 'bg-fuchsia-100 dark:bg-fuchsia-900/30 text-fuchsia-700 dark:text-fuchsia-400',
                                        'Manufacturing & Production' => 'bg-slate-100 dark:bg-slate-900/30 text-slate-700 dark:text-slate-400',
                                        'Real Estate' => 'bg-sky-100 dark:bg-sky-900/30 text-sky-700 dark:text-sky-400',
                                        'Retail' => 'bg-purple-100 dark:bg-purple-900/30 text-purple-700 dark:text-purple-400',
                                        'Agriculture & Environment' => 'bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400',
                                        'Security & Armed Forces' => 'bg-zinc-100 dark:bg-zinc-900/30 text-zinc-700 dark:text-zinc-400',
                                        'Administrative & Office' => 'bg-neutral-100 dark:bg-neutral-900/30 text-neutral-700 dark:text-neutral-400',
                                        'Creative, Entertainment & Arts' => 'bg-pink-100 dark:bg-pink-900/30 text-pink-700 dark:text-pink-400'
                                    ];
                                    
                                    // Subcategory colors array for hash-based assignment
                                    $subcategoryColors = [
                                        'bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400',
                                        'bg-indigo-100 dark:bg-indigo-900/30 text-indigo-700 dark:text-indigo-400',
                                        'bg-purple-100 dark:bg-purple-900/30 text-purple-700 dark:text-purple-400',
                                        'bg-pink-100 dark:bg-pink-900/30 text-pink-700 dark:text-pink-400',
                                        'bg-rose-100 dark:bg-rose-900/30 text-rose-700 dark:text-rose-400',
                                        'bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400',
                                        'bg-orange-100 dark:bg-orange-900/30 text-orange-700 dark:text-orange-400',
                                        'bg-amber-100 dark:bg-amber-900/30 text-amber-700 dark:text-amber-400',
                                        'bg-yellow-100 dark:bg-yellow-900/30 text-yellow-700 dark:text-yellow-400',
                                        'bg-lime-100 dark:bg-lime-900/30 text-lime-700 dark:text-lime-400',
                                        'bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400',
                                        'bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400',
                                        'bg-teal-100 dark:bg-teal-900/30 text-teal-700 dark:text-teal-400',
                                        'bg-cyan-100 dark:bg-cyan-900/30 text-cyan-700 dark:text-cyan-400',
                                        'bg-sky-100 dark:bg-sky-900/30 text-sky-700 dark:text-sky-400',
                                        'bg-violet-100 dark:bg-violet-900/30 text-violet-700 dark:text-violet-400',
                                        'bg-fuchsia-100 dark:bg-fuchsia-900/30 text-fuchsia-700 dark:text-fuchsia-400',
                                        'bg-slate-100 dark:bg-slate-900/30 text-slate-700 dark:text-slate-400',
                                        'bg-zinc-100 dark:bg-zinc-900/30 text-zinc-700 dark:text-zinc-400',
                                        'bg-neutral-100 dark:bg-neutral-900/30 text-neutral-700 dark:text-neutral-400'
                                    ];
                                    
                                    // Get subcategory color based on hash
                                    $getSubcategoryColor = function($name) use ($subcategoryColors) {
                                        $hash = crc32($name);
                                        $index = abs($hash) % count($subcategoryColors);
                                        return $subcategoryColors[$index];
                                    };
                                    
                                    // If category/subcategory exist, show them
                                    if ($categoryName): ?>
                                        <span class="inline-flex items-center rounded-full <?= $categoryColors[$categoryName] ?? 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300' ?> px-3 py-1 text-xs font-medium">
                                            <?= esc($categoryName) ?>
                                        </span>
                                    <?php endif; ?>
                                    
                                    <?php if ($subcategoryName): ?>
                                        <span class="inline-flex items-center rounded-full <?= $getSubcategoryColor($subcategoryName) ?> px-3 py-1 text-xs font-medium">
                                            <?= esc($subcategoryName) ?>
                                        </span>
                                    <?php endif; ?>
                                    
                                    <?php if (!empty($job['job_type'])): ?>
                                        <span class="inline-flex items-center rounded-full bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400 px-3 py-1 text-xs font-medium">
                                            <?= esc(ucfirst(str_replace('-', ' ', $job['job_type']))) ?>
                                        </span>
                                    <?php endif; ?>
                                    <?php if (!empty($job['location'])): ?>
                                        <span class="inline-flex items-center rounded-full bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 px-3 py-1 text-xs font-medium">
                                            <span class="material-symbols-outlined text-sm mr-1">location_on</span>
                                            <?= esc($job['location']) ?>
                                        </span>
                                    <?php endif; ?>
                                </div>
                                
                                <div class="flex items-center justify-between text-xs text-gray-400 dark:text-gray-500 pt-4 border-t border-gray-200 dark:border-gray-700/50 mt-auto">
                                    <span><?= esc($job['location']) ?></span>
                                    <?php if (!empty($job['posted_at'])): ?>
                                        <span>Posted <?= date('M d, Y', strtotime($job['posted_at'])) ?></span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </main>
        
        <?= view('partials/footer') ?>
    </div>

</body>
</html>

