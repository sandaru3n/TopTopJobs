<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title><?= esc($title) ?> - TopTopJobs</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms"></script>
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
    <div class="min-h-screen flex flex-col">
        <!-- Header -->
        <header class="sticky top-0 z-50 w-full bg-white/80 dark:bg-background-dark/80 backdrop-blur-sm border-b border-gray-200 dark:border-gray-800">
            <div class="container mx-auto px-6">
                <div class="flex h-16 items-center justify-between">
                    <div class="flex items-center gap-4">
                        <a href="<?= base_url('/admin/dashboard') ?>" class="flex items-center gap-2 text-gray-600 dark:text-gray-400 hover:text-primary transition-colors">
                            <span class="material-symbols-outlined">arrow_back</span>
                            <span class="hidden md:inline">Back to Dashboard</span>
                        </a>
                    </div>
                    <div class="flex items-center gap-4">
                        <div class="flex items-center gap-3">
                            <div class="text-right hidden md:block">
                                <p class="text-sm font-medium text-[#111318] dark:text-white"><?= esc($user['name'] ?: $user['email']) ?></p>
                                <p class="text-xs text-gray-500 dark:text-gray-400"><?= esc(ucfirst($user['user_type'])) ?></p>
                            </div>
                            <div class="flex items-center justify-center size-10 rounded-full bg-primary/10 text-primary">
                                <span class="material-symbols-outlined">person</span>
                            </div>
                        </div>
                        <a 
                            href="<?= base_url('logout') ?>" 
                            class="flex items-center gap-2 px-4 py-2 rounded-lg bg-red-500 text-white text-sm font-medium hover:bg-red-600 transition-colors"
                            onclick="return confirm('Are you sure you want to logout?')"
                        >
                            <span class="material-symbols-outlined text-lg">logout</span>
                            <span class="hidden md:inline">Logout</span>
                        </a>
                    </div>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="flex-grow container mx-auto px-6 py-8">
            <?php if (session()->getFlashdata('success')): ?>
                <div class="mb-6 p-4 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg">
                    <p class="text-sm text-green-600 dark:text-green-400"><?= session()->getFlashdata('success') ?></p>
                </div>
            <?php endif; ?>

            <?php if (session()->getFlashdata('error')): ?>
                <div class="mb-6 p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg">
                    <p class="text-sm text-red-600 dark:text-red-400"><?= session()->getFlashdata('error') ?></p>
                </div>
            <?php endif; ?>

            <div class="mb-8">
                <h1 class="text-3xl font-bold text-[#111318] dark:text-white mb-2">Site Settings</h1>
                <p class="text-gray-600 dark:text-gray-400">Manage your site configuration and preferences.</p>
            </div>

            <!-- Settings Form -->
            <div class="bg-white dark:bg-gray-800 rounded-lg p-6 border border-gray-200 dark:border-gray-700">
                <form action="<?= base_url('admin/settings/update') ?>" method="post" enctype="multipart/form-data">
                    <?= csrf_field() ?>

                    <!-- Favicon Upload -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-[#111318] dark:text-white mb-2">
                            Site Favicon
                        </label>
                        <div class="flex items-center gap-4">
                            <?php if (!empty($settings['site_favicon'])): ?>
                                <div class="flex items-center gap-3">
                                    <img src="<?= base_url($settings['site_favicon']) ?>" alt="Current Favicon" class="w-16 h-16 object-contain border border-gray-200 dark:border-gray-700 rounded-lg">
                                    <div>
                                        <p class="text-sm text-gray-600 dark:text-gray-400">Current Favicon</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-500"><?= esc($settings['site_favicon']) ?></p>
                                    </div>
                                </div>
                            <?php else: ?>
                                <div class="flex items-center gap-3">
                                    <div class="w-16 h-16 bg-gray-100 dark:bg-gray-700 rounded-lg flex items-center justify-center">
                                        <span class="material-symbols-outlined text-gray-400">image</span>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-600 dark:text-gray-400">No favicon uploaded</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-500">Upload a favicon (PNG, ICO, JPEG, or SVG)</p>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                        <input 
                            type="file" 
                            name="favicon" 
                            id="favicon" 
                            accept="image/png,image/x-icon,image/vnd.microsoft.icon,image/jpeg,image/svg+xml"
                            class="mt-3 block w-full text-sm text-gray-500 dark:text-gray-400
                                file:mr-4 file:py-2 file:px-4
                                file:rounded-lg file:border-0
                                file:text-sm file:font-semibold
                                file:bg-primary file:text-white
                                hover:file:bg-primary/90
                                file:cursor-pointer
                                border border-gray-300 dark:border-gray-600 rounded-lg
                                bg-white dark:bg-gray-700"
                        />
                        <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">
                            Recommended: 32x32px or 16x16px PNG/ICO file. Maximum size: 2MB
                        </p>
                    </div>

                    <!-- Site Name -->
                    <div class="mb-6">
                        <label for="site_name" class="block text-sm font-medium text-[#111318] dark:text-white mb-2">
                            Site Name
                        </label>
                        <input 
                            type="text" 
                            id="site_name" 
                            name="site_name" 
                            value="<?= esc($settings['site_name'] ?? 'TopTopJobs') ?>"
                            class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-[#111318] dark:text-white focus:outline-none focus:ring-2 focus:ring-primary/50 focus:border-primary transition-colors"
                            placeholder="TopTopJobs"
                        />
                    </div>

                    <!-- Site Description -->
                    <div class="mb-6">
                        <label for="site_description" class="block text-sm font-medium text-[#111318] dark:text-white mb-2">
                            Site Description
                        </label>
                        <textarea 
                            id="site_description" 
                            name="site_description" 
                            rows="3"
                            class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-[#111318] dark:text-white focus:outline-none focus:ring-2 focus:ring-primary/50 focus:border-primary transition-colors"
                            placeholder="Find your dream job"
                        ><?= esc($settings['site_description'] ?? 'Find your dream job') ?></textarea>
                    </div>

                    <!-- Page Meta Settings -->
                    <div class="border-t border-gray-200 dark:border-gray-700 pt-6 mt-6">
                        <h3 class="text-lg font-semibold text-[#111318] dark:text-white mb-4">Page Meta Tags</h3>
                        
                        <!-- Home Page -->
                        <div class="mb-6">
                            <h4 class="text-sm font-medium text-[#111318] dark:text-white mb-3">Home Page (/)</h4>
                            <div class="space-y-3">
                                <div>
                                    <label for="home_title" class="block text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">
                                        Page Title
                                    </label>
                                    <input 
                                        type="text" 
                                        id="home_title" 
                                        name="home_title" 
                                        value="<?= esc($settings['home_title'] ?? 'TopTopJobs - Find Your Dream Job') ?>"
                                        class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-[#111318] dark:text-white focus:outline-none focus:ring-2 focus:ring-primary/50 focus:border-primary transition-colors"
                                        placeholder="TopTopJobs - Find Your Dream Job"
                                    />
                                </div>
                                <div>
                                    <label for="home_description" class="block text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">
                                        Meta Description
                                    </label>
                                    <textarea 
                                        id="home_description" 
                                        name="home_description" 
                                        rows="2"
                                        class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-[#111318] dark:text-white focus:outline-none focus:ring-2 focus:ring-primary/50 focus:border-primary transition-colors"
                                        placeholder="Find your dream job on TopTopJobs. Browse thousands of job listings."
                                    ><?= esc($settings['home_description'] ?? 'Find your dream job on TopTopJobs. Browse thousands of job listings.') ?></textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Jobs Page -->
                        <div class="mb-6">
                            <h4 class="text-sm font-medium text-[#111318] dark:text-white mb-3">Jobs Page (/jobs)</h4>
                            <div class="space-y-3">
                                <div>
                                    <label for="jobs_title" class="block text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">
                                        Page Title
                                    </label>
                                    <input 
                                        type="text" 
                                        id="jobs_title" 
                                        name="jobs_title" 
                                        value="<?= esc($settings['jobs_title'] ?? 'Job Search & Listings - TopTopJobs') ?>"
                                        class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-[#111318] dark:text-white focus:outline-none focus:ring-2 focus:ring-primary/50 focus:border-primary transition-colors"
                                        placeholder="Job Search & Listings - TopTopJobs"
                                    />
                                </div>
                                <div>
                                    <label for="jobs_description" class="block text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">
                                        Meta Description
                                    </label>
                                    <textarea 
                                        id="jobs_description" 
                                        name="jobs_description" 
                                        rows="2"
                                        class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-[#111318] dark:text-white focus:outline-none focus:ring-2 focus:ring-primary/50 focus:border-primary transition-colors"
                                        placeholder="Browse thousands of job listings on TopTopJobs. Search by location, job type, and more."
                                    ><?= esc($settings['jobs_description'] ?? 'Browse thousands of job listings on TopTopJobs. Search by location, job type, and more.') ?></textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Post Job Page -->
                        <div class="mb-6">
                            <h4 class="text-sm font-medium text-[#111318] dark:text-white mb-3">Post Job Page (/post-job)</h4>
                            <div class="space-y-3">
                                <div>
                                    <label for="postjob_title" class="block text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">
                                        Page Title
                                    </label>
                                    <input 
                                        type="text" 
                                        id="postjob_title" 
                                        name="postjob_title" 
                                        value="<?= esc($settings['postjob_title'] ?? 'Post a Job - TopTopJobs') ?>"
                                        class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-[#111318] dark:text-white focus:outline-none focus:ring-2 focus:ring-primary/50 focus:border-primary transition-colors"
                                        placeholder="Post a Job - TopTopJobs"
                                    />
                                </div>
                                <div>
                                    <label for="postjob_description" class="block text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">
                                        Meta Description
                                    </label>
                                    <textarea 
                                        id="postjob_description" 
                                        name="postjob_description" 
                                        rows="2"
                                        class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-[#111318] dark:text-white focus:outline-none focus:ring-2 focus:ring-primary/50 focus:border-primary transition-colors"
                                        placeholder="Post your job listing for free on TopTopJobs. Reach thousands of qualified candidates."
                                    ><?= esc($settings['postjob_description'] ?? 'Post your job listing for free on TopTopJobs. Reach thousands of qualified candidates.') ?></textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="flex justify-end gap-4">
                        <a 
                            href="<?= base_url('admin/dashboard') ?>" 
                            class="px-6 py-2 rounded-lg border border-gray-300 dark:border-gray-600 text-[#111318] dark:text-white hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors"
                        >
                            Cancel
                        </a>
                        <button 
                            type="submit" 
                            class="px-6 py-2 rounded-lg bg-primary text-white font-medium hover:bg-primary/90 transition-colors"
                        >
                            Save Settings
                        </button>
                    </div>
                </form>
            </div>
        </main>

        <!-- Footer -->
        <footer class="bg-white dark:bg-gray-800/50 border-t border-gray-200 dark:border-gray-800 mt-auto">
            <div class="container mx-auto px-6 py-4">
                <p class="text-sm text-gray-500 dark:text-gray-400 text-center">
                    © 2025 TopTopJobs Admin Panel. All rights reserved.
                </p>
            </div>
        </footer>
    </div>
</body>
</html>

