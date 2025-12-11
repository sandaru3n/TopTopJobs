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
                        <a href="<?= base_url('/admin/collections') ?>" class="text-sm text-gray-600 dark:text-gray-400 hover:text-primary">Back to Collections</a>
                        <a href="<?= base_url('logout') ?>" class="flex items-center gap-2 px-4 py-2 rounded-lg bg-red-500 text-white text-sm font-medium hover:bg-red-600 transition-colors">
                            <span class="material-symbols-outlined text-lg">logout</span>
                            <span class="hidden md:inline">Logout</span>
                        </a>
                    </div>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="flex-grow container mx-auto px-6 py-8 max-w-4xl">
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

            <?php if (session()->getFlashdata('errors')): ?>
                <div class="mb-6 p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg">
                    <ul class="list-disc list-inside text-sm text-red-600 dark:text-red-400">
                        <?php foreach (session()->getFlashdata('errors') as $error): ?>
                            <li><?= esc($error) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <!-- Header Section -->
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-[#111318] dark:text-white mb-2">
                    <?= $collection ? 'Edit Collection' : 'Create Collection' ?>
                </h1>
                <p class="text-gray-600 dark:text-gray-400"><?= $collection ? 'Update collection details and SEO settings.' : 'Create a new collection page with custom SEO meta tags.' ?></p>
            </div>

            <!-- Form -->
            <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6">
                <form method="POST" action="<?= $collection ? base_url('/admin/collections/' . $collection['id'] . '/update') : base_url('/admin/collections/store') ?>" id="collectionForm">
                    <?= csrf_field() ?>
                    <?php if ($collection): ?>
                        <input type="hidden" name="collection_id" value="<?= esc($collection['id']) ?>">
                    <?php endif; ?>
                    
                    <!-- Collection Name -->
                    <div class="mb-6">
                        <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Collection Name <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="text" 
                            id="name" 
                            name="name" 
                            value="<?= old('name', $collection['name'] ?? '') ?>" 
                            required
                            maxlength="255"
                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-[#111318] dark:text-white focus:ring-2 focus:ring-primary focus:border-transparent"
                            placeholder="e.g., Remote Jobs, Tech Jobs, etc."
                        />
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">A descriptive name for this collection.</p>
                    </div>

                    <!-- Site Title -->
                    <div class="mb-6">
                        <label for="site_title" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Site Title (SEO) <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="text" 
                            id="site_title" 
                            name="site_title" 
                            value="<?= old('site_title', $collection['site_title'] ?? '') ?>" 
                            required
                            maxlength="255"
                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-[#111318] dark:text-white focus:ring-2 focus:ring-primary focus:border-transparent"
                            placeholder="e.g., Remote Jobs - Find Your Dream Remote Position | TopTopJobs"
                        />
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">This will appear in browser tabs and search engine results (50-60 characters recommended).</p>
                    </div>

                    <!-- Meta Description -->
                    <div class="mb-6">
                        <label for="meta_description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Meta Description (SEO)
                        </label>
                        <textarea 
                            id="meta_description" 
                            name="meta_description" 
                            rows="3"
                            maxlength="500"
                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-[#111318] dark:text-white focus:ring-2 focus:ring-primary focus:border-transparent"
                            placeholder="A compelling description that appears in search engine results (150-160 characters recommended)."
                        ><?= old('meta_description', $collection['meta_description'] ?? '') ?></textarea>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">This description appears in search engine results (150-160 characters recommended).</p>
                    </div>

                    <!-- Meta Keywords -->
                    <div class="mb-6">
                        <label for="meta_keywords" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Meta Keywords
                        </label>
                        <input 
                            type="text" 
                            id="meta_keywords" 
                            name="meta_keywords" 
                            value="<?= old('meta_keywords', $collection['meta_keywords'] ?? '') ?>" 
                            maxlength="500"
                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-[#111318] dark:text-white focus:ring-2 focus:ring-primary focus:border-transparent"
                            placeholder="remote jobs, work from home, tech jobs, software engineer"
                        />
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Comma-separated keywords for SEO (optional but recommended).</p>
                    </div>

                    <!-- Description -->
                    <div class="mb-6">
                        <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Description
                        </label>
                        <textarea 
                            id="description" 
                            name="description" 
                            rows="4"
                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-[#111318] dark:text-white focus:ring-2 focus:ring-primary focus:border-transparent"
                            placeholder="A detailed description of this collection that will be displayed on the collection page."
                        ><?= old('description', $collection['description'] ?? '') ?></textarea>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Optional description that appears on the collection page.</p>
                    </div>

                    <!-- Status -->
                    <div class="mb-6">
                        <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Status <span class="text-red-500">*</span>
                        </label>
                        <select 
                            id="status" 
                            name="status" 
                            required
                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-[#111318] dark:text-white focus:ring-2 focus:ring-primary focus:border-transparent"
                        >
                            <option value="active" <?= old('status', $collection['status'] ?? 'active') === 'active' ? 'selected' : '' ?>>Active</option>
                            <option value="inactive" <?= old('status', $collection['status'] ?? '') === 'inactive' ? 'selected' : '' ?>>Inactive</option>
                        </select>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Only active collections are visible to the public.</p>
                    </div>

                    <!-- Form Actions -->
                    <div class="flex items-center gap-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                        <button 
                            type="submit" 
                            id="submitBtn"
                            class="flex items-center gap-2 px-6 py-3 rounded-lg bg-primary text-white font-medium hover:bg-blue-700 transition-colors"
                        >
                            <span class="material-symbols-outlined">save</span>
                            <?= $collection ? 'Update Collection' : 'Create Collection' ?>
                        </button>
                        <a 
                            href="<?= base_url('/admin/collections') ?>" 
                            class="px-6 py-3 rounded-lg border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 font-medium hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors"
                        >
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </main>

        <script>
            // Form submission handler - ensure form submits correctly
            document.addEventListener('DOMContentLoaded', function() {
                const form = document.getElementById('collectionForm');
                const submitBtn = document.getElementById('submitBtn');
                
                if (form && submitBtn) {
                    form.addEventListener('submit', function(e) {
                        // Check if form is valid
                        if (!form.checkValidity()) {
                            e.preventDefault();
                            form.reportValidity();
                            return false;
                        }
                        
                        // Disable button to prevent double submission
                        submitBtn.disabled = true;
                        submitBtn.innerHTML = '<span class="material-symbols-outlined">hourglass_empty</span> Processing...';
                        
                        // Allow form to submit
                        return true;
                    });
                }
            });
        </script>

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

