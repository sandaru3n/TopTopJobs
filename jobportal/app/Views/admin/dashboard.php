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
                        <div class="size-6 text-primary">
                            <svg fill="none" viewbox="0 0 48 48" xmlns="http://www.w3.org/2000/svg">
                                <path clip-rule="evenodd" d="M24 4H42V17.3333V30.6667H24V44H6V30.6667V17.3333H24V4Z" fill="currentColor" fill-rule="evenodd"></path>
                            </svg>
                        </div>
                        <h2 class="text-xl font-bold leading-tight tracking-[-0.015em] text-[#111318] dark:text-white">TopTopJobs Admin</h2>
                    </div>
                    <div class="flex items-center gap-4">
                        <!-- User Info -->
                        <div class="flex items-center gap-3">
                            <div class="text-right hidden md:block">
                                <p class="text-sm font-medium text-[#111318] dark:text-white"><?= esc($user['name'] ?: $user['email']) ?></p>
                                <p class="text-xs text-gray-500 dark:text-gray-400"><?= esc(ucfirst($user['user_type'])) ?></p>
                            </div>
                            <div class="flex items-center justify-center size-10 rounded-full bg-primary/10 text-primary">
                                <span class="material-symbols-outlined">person</span>
                            </div>
                        </div>
                        <!-- Logout Button -->
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

            <!-- Welcome Section -->
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-[#111318] dark:text-white mb-2">Welcome, <?= esc($user['name'] ?: 'Admin') ?>!</h1>
                <p class="text-gray-600 dark:text-gray-400">Manage your job portal from this dashboard.</p>
            </div>

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <div class="bg-white dark:bg-gray-800 rounded-lg p-6 border border-gray-200 dark:border-gray-700">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Total Jobs</p>
                            <p class="text-2xl font-bold text-[#111318] dark:text-white">0</p>
                        </div>
                        <div class="p-3 bg-blue-100 dark:bg-blue-900/30 rounded-lg">
                            <span class="material-symbols-outlined text-blue-600 dark:text-blue-400">work</span>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-lg p-6 border border-gray-200 dark:border-gray-700">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Total Users</p>
                            <p class="text-2xl font-bold text-[#111318] dark:text-white">0</p>
                        </div>
                        <div class="p-3 bg-green-100 dark:bg-green-900/30 rounded-lg">
                            <span class="material-symbols-outlined text-green-600 dark:text-green-400">people</span>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-lg p-6 border border-gray-200 dark:border-gray-700">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Applications</p>
                            <p class="text-2xl font-bold text-[#111318] dark:text-white">0</p>
                        </div>
                        <div class="p-3 bg-purple-100 dark:bg-purple-900/30 rounded-lg">
                            <span class="material-symbols-outlined text-purple-600 dark:text-purple-400">description</span>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-lg p-6 border border-gray-200 dark:border-gray-700">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Companies</p>
                            <p class="text-2xl font-bold text-[#111318] dark:text-white">0</p>
                        </div>
                        <div class="p-3 bg-orange-100 dark:bg-orange-900/30 rounded-lg">
                            <span class="material-symbols-outlined text-orange-600 dark:text-orange-400">business</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white dark:bg-gray-800 rounded-lg p-6 border border-gray-200 dark:border-gray-700">
                <h2 class="text-xl font-bold text-[#111318] dark:text-white mb-4">Quick Actions</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <a href="<?= base_url('/') ?>" class="flex items-center gap-3 p-4 rounded-lg border border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                        <span class="material-symbols-outlined text-primary">home</span>
                        <div>
                            <p class="font-medium text-[#111318] dark:text-white">View Site</p>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Go to homepage</p>
                        </div>
                    </a>
                    <a href="<?= base_url('jobs') ?>" class="flex items-center gap-3 p-4 rounded-lg border border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                        <span class="material-symbols-outlined text-primary">search</span>
                        <div>
                            <p class="font-medium text-[#111318] dark:text-white">Browse Jobs</p>
                            <p class="text-sm text-gray-500 dark:text-gray-400">View all jobs</p>
                        </div>
                    </a>
                    <a href="#" class="flex items-center gap-3 p-4 rounded-lg border border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                        <span class="material-symbols-outlined text-primary">settings</span>
                        <div>
                            <p class="font-medium text-[#111318] dark:text-white">Settings</p>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Manage settings</p>
                        </div>
                    </a>
                </div>
            </div>
        </main>

        <!-- Footer -->
        <footer class="bg-white dark:bg-gray-800/50 border-t border-gray-200 dark:border-gray-800 mt-auto">
            <div class="container mx-auto px-6 py-4">
                <p class="text-sm text-gray-500 dark:text-gray-400 text-center">
                    © 2024 TopTopJobs Admin Panel. All rights reserved.
                </p>
            </div>
        </footer>
    </div>
</body>
</html>

