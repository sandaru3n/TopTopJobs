<?= view('partials/head', ['title' => 'Saved Jobs - JobFind']) ?>
<body class="font-display bg-background-light dark:bg-background-dark text-[#111318] dark:text-gray-200">
    <div class="relative flex min-h-screen w-full flex-col">
        <?= view('partials/header') ?>

        <main class="flex-grow container mx-auto px-4 md:px-6 py-8 md:py-12">
            <div class="max-w-6xl mx-auto">
                <div class="mb-8">
                    <h1 class="text-3xl font-bold text-[#111318] dark:text-white mb-2">Saved Jobs</h1>
                    <p class="text-gray-600 dark:text-gray-400">View and manage your saved job listings</p>
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
                        <span class="material-symbols-outlined text-6xl text-gray-400 dark:text-gray-600 mb-4">bookmark_border</span>
                        <h3 class="text-xl font-bold text-[#111318] dark:text-white mb-2">No Saved Jobs Yet</h3>
                        <p class="text-gray-600 dark:text-gray-400 mb-6">Start browsing jobs and save the ones you're interested in</p>
                        <a 
                            href="<?= base_url('jobs') ?>" 
                            class="inline-flex items-center gap-2 px-6 py-2.5 rounded-full font-bold transition-colors"
                            style="background-color: #2bee79; color: #0e2016;"
                            onmouseover="this.style.backgroundColor='#25d46a'"
                            onmouseout="this.style.backgroundColor='#2bee79'"
                        >
                            <span class="material-symbols-outlined">search</span>
                            <span>Browse Jobs</span>
                        </a>
                    </div>
                <?php else: ?>
                    <div class="grid grid-cols-1 gap-4">
                        <?php foreach ($jobs as $job): ?>
                            <div class="bg-white dark:bg-gray-800/50 rounded-lg p-6 border border-gray-200 dark:border-gray-700/50 hover:shadow-md transition-shadow">
                                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                                    <div class="flex items-start gap-4 flex-1">
                                        <img 
                                            src="<?= esc($job['company_logo'] ?? 'https://via.placeholder.com/60') ?>" 
                                            alt="<?= esc($job['company_name']) ?>" 
                                            class="w-16 h-16 rounded-lg object-cover"
                                            onerror="this.src='https://via.placeholder.com/60'"
                                        />
                                        <div class="flex-1">
                                            <h3 class="text-lg font-bold text-[#111318] dark:text-white mb-1">
                                                <a href="<?= base_url('job/' . $job['slug']) ?>" class="hover:text-primary transition-colors">
                                                    <?= esc($job['title']) ?>
                                                </a>
                                            </h3>
                                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">
                                                <?= esc($job['company_name']) ?> • <?= esc($job['location']) ?>
                                            </p>
                                            <div class="flex flex-wrap gap-2 mb-2">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-300">
                                                    <?= esc(ucfirst(str_replace('-', ' ', $job['job_type']))) ?>
                                                </span>
                                                <?php if ($job['saved_at']): ?>
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium text-gray-500 dark:text-gray-400">
                                                        Saved <?= date('M d, Y', strtotime($job['saved_at'])) ?>
                                                    </span>
                                                <?php endif; ?>
                                            </div>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                                Posted <?= date('M d, Y', strtotime($job['posted_at'])) ?>
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
                                        <button 
                                            onclick="toggleSaveJob(<?= $job['id'] ?>, this)"
                                            class="px-4 py-2 rounded-lg font-bold text-sm transition-colors"
                                            style="background-color: #2bee79; color: #0e2016;"
                                            onmouseover="this.style.backgroundColor='#25d46a'"
                                            onmouseout="this.style.backgroundColor='#2bee79'"
                                        >
                                            Remove
                                        </button>
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

    <script>
        async function toggleSaveJob(jobId, button) {
            try {
                const response = await fetch('<?= base_url('api/toggle-save-job') ?>', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({ job_id: jobId })
                });

                const data = await response.json();

                if (data.success) {
                    if (!data.saved) {
                        // Job was unsaved, remove the card
                        button.closest('.bg-white').style.transition = 'opacity 0.3s';
                        button.closest('.bg-white').style.opacity = '0';
                        setTimeout(() => {
                            button.closest('.bg-white').remove();
                            // Check if no jobs left
                            const jobsContainer = document.querySelector('.grid.grid-cols-1');
                            if (jobsContainer && jobsContainer.children.length === 0) {
                                location.reload();
                            }
                        }, 300);
                    }
                } else {
                    alert(data.message || 'An error occurred');
                }
            } catch (error) {
                console.error('Error toggling save job:', error);
                alert('An error occurred. Please try again.');
            }
        }
    </script>
</body>
</html>

