<?= view('partials/head', ['title' => 'Manage Jobs - TopTopJobs']) ?>
<body class="font-display bg-background-light dark:bg-background-dark text-[#111318] dark:text-gray-200">
    <div class="relative flex min-h-screen w-full flex-col">
        <?= view('partials/header') ?>

        <main class="flex-grow container mx-auto px-4 md:px-6 py-8 md:py-12">
            <div class="max-w-6xl mx-auto">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-8">
                    <div>
                        <h1 class="text-3xl font-bold text-[#111318] dark:text-white mb-2">Manage Jobs</h1>
                        <p class="text-gray-600 dark:text-gray-400">View and edit your posted jobs</p>
                    </div>
                    <a 
                        href="<?= base_url('post-job') ?>" 
                        class="inline-flex items-center gap-2 px-6 py-2.5 rounded-full font-bold transition-colors"
                        style="background-color: #2bee79; color: #0e2016;"
                        onmouseover="this.style.backgroundColor='#25d46a'"
                        onmouseout="this.style.backgroundColor='#2bee79'"
                    >
                        <span class="material-symbols-outlined">add</span>
                        <span>Post New Job</span>
                    </a>
                </div>

                <!-- Success/Error Messages -->
                <?php if (session()->getFlashdata('success')): ?>
                    <div class="mb-6 p-4 bg-green-100 dark:bg-green-900/50 border border-green-400 dark:border-green-700 text-green-700 dark:text-green-300 rounded-lg">
                        <?= esc(session()->getFlashdata('success')) ?>
                    </div>
                <?php endif; ?>

                <?php if (session()->getFlashdata('error')): ?>
                    <div class="mb-6 p-4 bg-red-100 dark:bg-red-900/50 border border-red-400 dark:border-red-700 text-red-700 dark:text-red-300 rounded-lg">
                        <?= esc(session()->getFlashdata('error')) ?>
                    </div>
                <?php endif; ?>

                <!-- Jobs List -->
                <?php if (empty($jobs)): ?>
                    <div class="bg-white dark:bg-gray-800/50 rounded-lg p-12 border border-gray-200 dark:border-gray-700/50 text-center">
                        <span class="material-symbols-outlined text-6xl text-gray-400 dark:text-gray-600 mb-4">work_off</span>
                        <h3 class="text-xl font-bold text-[#111318] dark:text-white mb-2">No Jobs Posted Yet</h3>
                        <p class="text-gray-600 dark:text-gray-400 mb-6">Start by posting your first job listing</p>
                        <a 
                            href="<?= base_url('post-job') ?>" 
                            class="inline-flex items-center gap-2 px-6 py-2.5 rounded-full font-bold transition-colors"
                            style="background-color: #2bee79; color: #0e2016;"
                            onmouseover="this.style.backgroundColor='#25d46a'"
                            onmouseout="this.style.backgroundColor='#2bee79'"
                        >
                            <span class="material-symbols-outlined">add</span>
                            <span>Post Your First Job</span>
                        </a>
                    </div>
                <?php else: ?>
                    <div class="grid grid-cols-1 gap-4">
                        <?php foreach ($jobs as $job): ?>
                            <div class="bg-white dark:bg-gray-800/50 rounded-lg p-6 border border-gray-200 dark:border-gray-700/50 hover:shadow-md transition-shadow">
                                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                                    <div class="flex items-start gap-4 flex-1">
                                        <img 
                                            src="<?= esc($job['company_logo'] ?? 'data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSI2MCIgaGVpZ2h0PSI2MCIgdmlld0JveD0iMCAwIDYwIDYwIj48cmVjdCB3aWR0aD0iNjAiIGhlaWdodD0iNjAiIGZpbGw9IiNlNWU3ZWIiLz48dGV4dCB4PSI1MCUiIHk9IjUwJSIgZm9udC1mYW1pbHk9IkFyaWFsLCBzYW5zLXNlcmlmIiBmb250LXNpemU9IjIwIiBmaWxsPSIjOWNhM2FmIiB0ZXh0LWFuY2hvcj0ibWlkZGxlIiBkb21pbmFudC1iYXNlbGluZT0ibWlkZGxlIj5Mb2dvPC90ZXh0Pjwvc3ZnPg==') ?>" 
                                            alt="<?= esc($job['company_name']) ?>" 
                                            class="w-16 h-16 rounded-lg object-cover"
                                            onerror="this.src='data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSI2MCIgaGVpZ2h0PSI2MCIgdmlld0JveD0iMCAwIDYwIDYwIj48cmVjdCB3aWR0aD0iNjAiIGhlaWdodD0iNjAiIGZpbGw9IiNlNWU3ZWIiLz48dGV4dCB4PSI1MCUiIHk9IjUwJSIgZm9udC1mYW1pbHk9IkFyaWFsLCBzYW5zLXNlcmlmIiBmb250LXNpemU9IjIwIiBmaWxsPSIjOWNhM2FmIiB0ZXh0LWFuY2hvcj0ibWlkZGxlIiBkb21pbmFudC1iYXNlbGluZT0ibWlkZGxlIj5Mb2dvPC90ZXh0Pjwvc3ZnPg=='"
                                        />
                                        <div class="flex-1">
                                            <h3 class="text-lg font-bold text-[#111318] dark:text-white mb-1">
                                                <?= esc($job['title']) ?>
                                            </h3>
                                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">
                                                <?= esc($job['company_name']) ?> • <?= esc($job['location']) ?>
                                            </p>
                                            <div class="flex flex-wrap gap-2 mb-2">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-300">
                                                    <?= esc(ucfirst(str_replace('-', ' ', $job['job_type']))) ?>
                                                </span>
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium <?= $job['status'] === 'active' ? 'bg-green-100 dark:bg-green-900/50 text-green-800 dark:text-green-300' : 'bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-300' ?>">
                                                    <?= esc(ucfirst($job['status'])) ?>
                                                </span>
                                            </div>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                                Posted <?= date('M d, Y', strtotime($job['posted_at'])) ?>
                                                <?php if ($job['expires_at']): ?>
                                                    • Expires <?= date('M d, Y', strtotime($job['expires_at'])) ?>
                                                <?php endif; ?>
                                            </p>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <a 
                                            href="<?= base_url('job/' . $job['slug']) ?>" 
                                            target="_blank"
                                            class="px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 text-sm font-medium text-[#111318] dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors"
                                        >
                                            View
                                        </a>
                                        <a 
                                            href="<?= base_url('edit-job/' . $job['id']) ?>" 
                                            class="px-4 py-2 rounded-lg font-bold text-sm transition-colors"
                                            style="background-color: #2bee79; color: #0e2016;"
                                            onmouseover="this.style.backgroundColor='#25d46a'"
                                            onmouseout="this.style.backgroundColor='#2bee79'"
                                        >
                                            Edit
                                        </a>
                                        <form 
                                            action="<?= base_url('delete-job/' . $job['id']) ?>" 
                                            method="POST" 
                                            class="inline"
                                            onsubmit="return confirm('Are you sure you want to delete this job? This action cannot be undone.');"
                                        >
                                            <?= csrf_field() ?>
                                            <button 
                                                type="submit"
                                                class="px-4 py-2 rounded-lg border border-red-300 dark:border-red-600 text-sm font-medium text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors"
                                            >
                                                Delete
                                            </button>
                                        </form>
                                    </div>
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

