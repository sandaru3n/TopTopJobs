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
                        <a href="<?= base_url('/admin/dashboard') ?>" class="size-6 text-primary">
                            <svg fill="none" viewbox="0 0 48 48" xmlns="http://www.w3.org/2000/svg">
                                <path clip-rule="evenodd" d="M24 4H42V17.3333V30.6667H24V44H6V30.6667V17.3333H24V4Z" fill="currentColor" fill-rule="evenodd"></path>
                            </svg>
                        </a>
                        <h2 class="text-xl font-bold leading-tight tracking-[-0.015em] text-[#111318] dark:text-white">TopTopJobs Admin</h2>
                    </div>
                    <div class="flex items-center gap-4">
                        <a href="<?= base_url('/admin/dashboard') ?>" class="text-sm text-gray-600 dark:text-gray-400 hover:text-primary">Dashboard</a>
                        <a href="<?= base_url('logout') ?>" class="flex items-center gap-2 px-4 py-2 rounded-lg bg-red-500 text-white text-sm font-medium hover:bg-red-600 transition-colors">
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
                    <p class="text-sm text-green-600 dark:text-green-400"><?= esc(session()->getFlashdata('success')) ?></p>
                </div>
            <?php endif; ?>

            <?php if (session()->getFlashdata('error')): ?>
                <div class="mb-6 p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg">
                    <p class="text-sm text-red-600 dark:text-red-400"><?= esc(session()->getFlashdata('error')) ?></p>
                </div>
            <?php endif; ?>

            <!-- Header Section -->
            <div class="mb-8 flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-[#111318] dark:text-white mb-2">Manage Collections</h1>
                    <p class="text-gray-600 dark:text-gray-400">Create and manage job collection pages with custom SEO settings.</p>
                </div>
                <a href="<?= base_url('/admin/collections/create') ?>" class="flex items-center gap-2 px-6 py-3 rounded-lg bg-primary text-white font-medium hover:bg-blue-700 transition-colors">
                    <span class="material-symbols-outlined">add</span>
                    Create Collection
                </a>
            </div>

            <!-- Collections Table -->
            <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
                <?php if (empty($collections)): ?>
                    <div class="p-12 text-center">
                        <span class="material-symbols-outlined text-6xl text-gray-400 mb-4">folder_off</span>
                        <p class="text-gray-600 dark:text-gray-400 mb-4">No collections found.</p>
                        <a href="<?= base_url('/admin/collections/create') ?>" class="inline-flex items-center gap-2 px-6 py-3 rounded-lg bg-primary text-white font-medium hover:bg-blue-700 transition-colors">
                            <span class="material-symbols-outlined">add</span>
                            Create Your First Collection
                        </a>
                    </div>
                <?php else: ?>
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50 dark:bg-gray-900">
                                <tr>
                                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700 dark:text-gray-300">Name</th>
                                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700 dark:text-gray-300">Slug</th>
                                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700 dark:text-gray-300">Site Title</th>
                                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700 dark:text-gray-300">Jobs</th>
                                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700 dark:text-gray-300">Status</th>
                                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700 dark:text-gray-300">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                <?php foreach ($collections as $collection): ?>
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                                        <td class="px-6 py-4">
                                            <div class="font-medium text-[#111318] dark:text-white"><?= esc($collection['name']) ?></div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <code class="text-sm text-gray-600 dark:text-gray-400"><?= esc($collection['slug']) ?></code>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="text-sm text-gray-600 dark:text-gray-400"><?= esc($collection['site_title']) ?></div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400 text-sm font-medium">
                                                <span class="material-symbols-outlined text-base">work</span>
                                                <?= esc($collection['job_count'] ?? 0) ?>
                                            </span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium <?= $collection['status'] === 'active' ? 'bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-400' ?>">
                                                <?= esc(ucfirst($collection['status'])) ?>
                                            </span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-2">
                                                <a href="<?= base_url('/collection/' . $collection['slug']) ?>" target="_blank" class="p-2 text-gray-600 dark:text-gray-400 hover:text-primary transition-colors" title="View Collection">
                                                    <span class="material-symbols-outlined">open_in_new</span>
                                                </a>
                                                <a href="<?= base_url('/admin/collections/' . $collection['id'] . '/jobs') ?>" class="p-2 text-gray-600 dark:text-gray-400 hover:text-primary transition-colors" title="Manage Jobs">
                                                    <span class="material-symbols-outlined">settings</span>
                                                </a>
                                                <a href="<?= base_url('/admin/collections/' . $collection['id'] . '/edit') ?>" class="p-2 text-gray-600 dark:text-gray-400 hover:text-blue-600 transition-colors" title="Edit">
                                                    <span class="material-symbols-outlined">edit</span>
                                                </a>
                                                <form method="POST" action="<?= base_url('/admin/collections/' . $collection['id'] . '/delete') ?>" class="inline" onsubmit="return confirm('Are you sure you want to delete this collection? This action cannot be undone.');">
                                                    <?= csrf_field() ?>
                                                    <button type="submit" class="p-2 text-gray-600 dark:text-gray-400 hover:text-red-600 transition-colors" title="Delete">
                                                        <span class="material-symbols-outlined">delete</span>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
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

