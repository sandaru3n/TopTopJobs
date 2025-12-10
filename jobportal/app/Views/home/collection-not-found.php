<?= view('partials/head', ['title' => 'Collection Not Found - TopTopJobs']) ?>
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
                    <span class="text-[#111318] dark:text-gray-300 text-sm font-medium leading-normal">Collection</span>
                </div>

                <!-- Error Content -->
                <div class="max-w-2xl mx-auto text-center py-16">
                    <div class="mb-8">
                        <span class="material-symbols-outlined text-8xl text-gray-400 dark:text-gray-600 mb-4 inline-block">folder_off</span>
                    </div>
                    
                    <h1 class="text-4xl font-bold text-[#111318] dark:text-white mb-4">Collection Not Found</h1>
                    
                    <p class="text-lg text-gray-600 dark:text-gray-400 mb-6">
                        <?php if (!empty($message)): ?>
                            <?= esc($message) ?>
                        <?php else: ?>
                            The collection you are looking for does not exist or is no longer available.
                        <?php endif; ?>
                    </p>
                    
                    <?php if (!empty($slug)): ?>
                        <p class="text-sm text-gray-500 dark:text-gray-500 mb-8">
                            Collection slug: <code class="bg-gray-100 dark:bg-gray-800 px-2 py-1 rounded"><?= esc($slug) ?></code>
                        </p>
                    <?php endif; ?>
                    
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
            </div>
        </main>
        
        <?= view('partials/footer') ?>
    </div>
</body>
</html>

