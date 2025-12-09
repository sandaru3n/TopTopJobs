<?= view('partials/head', ['title' => 'Job Portal Home Page - JobFind']) ?>
<body class="font-display bg-background-light dark:bg-background-dark text-[#111318] dark:text-gray-200">
    <div class="relative flex min-h-screen w-full flex-col">
        <?= view('partials/header') ?>

        <main class="flex-grow">
            <!-- Filter Pills Section -->
            <div id="filterPillsSection" class="sticky top-14 md:top-16 z-40 bg-white/90 dark:bg-background-dark/90 backdrop-blur-sm py-3 md:py-4 border-b border-gray-200 dark:border-gray-800 transition-transform duration-300 ease-in-out">
                <div class="container mx-auto px-4 md:px-6">
                    <div class="flex gap-2 flex-nowrap items-center whitespace-nowrap overflow-x-auto">
                        <button 
                            data-filter="full-time" 
                            class="filter-pill active flex h-9 shrink-0 items-center justify-center gap-x-2 rounded-full bg-primary/10 dark:bg-primary/20 px-4 text-primary dark:text-white text-sm font-medium leading-normal hover:bg-primary/20 dark:hover:bg-primary/30 transition-colors whitespace-nowrap"
                        >
                            <p class="whitespace-nowrap">Full-time</p>
                        </button>
                        <button 
                            data-filter="part-time" 
                            class="filter-pill flex h-9 shrink-0 items-center justify-center gap-x-2 rounded-full bg-gray-200/80 dark:bg-gray-800 px-4 text-[#111318] dark:text-gray-300 text-sm font-medium leading-normal hover:bg-gray-300/80 dark:hover:bg-gray-700 transition-colors whitespace-nowrap"
                        >
                            <p class="whitespace-nowrap">Part-time</p>
                        </button>
                        <button 
                            data-filter="remote" 
                            class="filter-pill flex h-9 shrink-0 items-center justify-center gap-x-2 rounded-full bg-gray-200/80 dark:bg-gray-800 px-4 text-[#111318] dark:text-gray-300 text-sm font-medium leading-normal hover:bg-gray-300/80 dark:hover:bg-gray-700 transition-colors whitespace-nowrap"
                        >
                            <p class="whitespace-nowrap">Remote</p>
                        </button>
                        <button 
                            data-filter="internship" 
                            class="filter-pill flex h-9 shrink-0 items-center justify-center gap-x-2 rounded-full bg-gray-200/80 dark:bg-gray-800 px-4 text-[#111318] dark:text-gray-300 text-sm font-medium leading-normal hover:bg-gray-300/80 dark:hover:bg-gray-700 transition-colors whitespace-nowrap"
                        >
                            <p class="whitespace-nowrap">Internship</p>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Job Listings Section -->
            <div class="container mx-auto px-4 md:px-6 py-4 md:py-6">
                <div class="flex flex-wrap justify-between gap-4 mb-6 items-baseline">
                    <p class="text-[#111318] dark:text-white tracking-light text-[32px] font-bold leading-tight min-w-72">Recent Job Postings</p>
                    <p id="resultsCount" class="text-gray-500 dark:text-gray-400 text-sm">Showing 1-12 of 2,456 results</p>
                </div>
                <div id="jobGrid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <!-- Job cards will be loaded here -->
                </div>
                <!-- Pagination -->
                <div class="flex justify-center items-center gap-2 mt-8">
                    <button class="pagination-btn flex items-center justify-center size-10 rounded-full text-gray-500 dark:text-gray-400 hover:bg-gray-200 dark:hover:bg-gray-800 transition-colors">
                        <span class="material-symbols-outlined text-xl">chevron_left</span>
                    </button>
                    <button class="pagination-btn active flex items-center justify-center size-10 rounded-full text-white bg-primary">1</button>
                    <button class="pagination-btn flex items-center justify-center size-10 rounded-full text-gray-600 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-800 transition-colors">2</button>
                    <button class="pagination-btn flex items-center justify-center size-10 rounded-full text-gray-600 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-800 transition-colors">3</button>
                    <span class="text-gray-500 dark:text-gray-400">...</span>
                    <button class="pagination-btn flex items-center justify-center size-10 rounded-full text-gray-600 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-800 transition-colors">10</button>
                    <button class="pagination-btn flex items-center justify-center size-10 rounded-full text-gray-500 dark:text-gray-400 hover:bg-gray-200 dark:hover:bg-gray-800 transition-colors">
                        <span class="material-symbols-outlined text-xl">chevron_right</span>
                    </button>
                </div>
            </div>
        </main>

        <?= view('partials/footer') ?>
    </div>

    <script>
        const baseUrl = '<?= base_url() ?>';
        
        // Load jobs on page load
        document.addEventListener('DOMContentLoaded', () => {
            loadJobs();
            setupEventListeners();
        });

        function setupEventListeners() {
            // Filter pills - navigate to jobs page with filter
            document.querySelectorAll('.filter-pill').forEach(btn => {
                btn.addEventListener('click', (e) => {
                    e.preventDefault();
                    const filter = btn.dataset.filter;
                    if (filter) {
                        // Toggle the active state
                        toggleFilter(btn);
                        
                        // Get all active filters
                        const activeFilters = Array.from(document.querySelectorAll('.filter-pill.active'))
                            .map(b => b.dataset.filter)
                            .filter(f => f);
                        
                        // Navigate to jobs page with filters
                        let url = '/jobs?';
                        if (activeFilters.length > 0) url += `job_type=${activeFilters.join(',')}&`;
                        window.location.href = url.replace(/&$/, '') || '/jobs';
                    }
                });
            });
        }

        function toggleFilter(btn) {
            btn.classList.toggle('active');
            if (btn.classList.contains('active')) {
                btn.classList.remove('bg-gray-200/80', 'dark:bg-gray-800', 'text-[#111318]', 'dark:text-gray-300');
                btn.classList.add('bg-primary/10', 'dark:bg-primary/20', 'text-primary', 'dark:text-white');
            } else {
                btn.classList.remove('bg-primary/10', 'dark:bg-primary/20', 'text-primary', 'dark:text-white');
                btn.classList.add('bg-gray-200/80', 'dark:bg-gray-800', 'text-[#111318]', 'dark:text-gray-300');
            }
        }

        async function loadJobs() {
            try {
                const response = await fetch(`${baseUrl.replace(/\/$/, '')}/api/jobs.php?page=1&per_page=12`);
                const data = await response.json();

                if (data.success && data.jobs) {
                    renderJobs(data.jobs);
                    updateResultsCount(data.total);
                }
            } catch (error) {
                console.error('Error loading jobs:', error);
            }
        }

        function renderJobs(jobs) {
            const container = document.getElementById('jobGrid');
            container.innerHTML = jobs.map(job => `
                <div class="bg-white dark:bg-gray-800/50 rounded-lg p-6 flex flex-col gap-4 border border-gray-200 dark:border-gray-700/50 hover:shadow-lg hover:border-primary/50 dark:hover:border-primary/50 transition-all duration-300 cursor-pointer" onclick="window.location.href='<?= base_url('job') ?>/${job.slug || (job.company_name.toLowerCase().replace(/[^a-z0-9]+/g, '-') + '-' + job.title.toLowerCase().replace(/[^a-z0-9]+/g, '-') + '-' + job.id)}/'">
                    <div class="flex items-start justify-between">
                        <div class="flex items-center gap-4">
                            <img class="h-12 w-12 rounded-full object-cover" alt="${job.company_name} logo" src="${job.company_logo || 'https://via.placeholder.com/48'}" onerror="this.src='https://via.placeholder.com/48'"/>
                            <div>
                                <h3 class="text-lg font-bold text-[#111318] dark:text-white">${job.title}</h3>
                                <p class="text-sm text-gray-600 dark:text-gray-400">${job.company_name}</p>
                            </div>
                        </div>
                        ${job.badge ? `<span class="inline-flex items-center rounded-full ${job.badge_class || 'bg-green-100 dark:bg-green-900/50'} px-2.5 py-0.5 text-xs font-medium ${job.badge === 'New' ? 'text-green-800 dark:text-green-300' : 'text-orange-800 dark:text-orange-300'}">${job.badge}</span>` : ''}
                    </div>
                    <p class="text-sm text-gray-500 dark:text-gray-400 leading-relaxed">${job.description || ''}</p>
                    <div class="flex flex-wrap gap-2">
                        ${(job.skills || []).slice(0, 3).map(skill => `
                            <span class="inline-flex items-center rounded-full bg-background-light dark:bg-gray-700 px-3 py-1 text-xs font-medium text-gray-600 dark:text-gray-300">${skill}</span>
                        `).join('')}
                    </div>
                    <div class="flex items-center justify-between text-xs text-gray-400 dark:text-gray-500 pt-4 border-t border-gray-200 dark:border-gray-700/50 mt-auto">
                        <span>${job.location}</span>
                        <span>Posted ${getTimeAgo(job.posted_at)}</span>
                    </div>
                </div>
            `).join('');
        }

        function updateResultsCount(total) {
            document.getElementById('resultsCount').textContent = `Showing 1-12 of ${total.toLocaleString()} results`;
        }

        function getTimeAgo(dateString) {
            const now = new Date();
            const date = new Date(dateString);
            const diffMs = now - date;
            const diffMins = Math.floor(diffMs / 60000);
            const diffHours = Math.floor(diffMs / 3600000);
            const diffDays = Math.floor(diffMs / 86400000);

            if (diffMins < 60) return `${diffMins} min ago`;
            if (diffHours < 24) return `${diffHours} hour${diffHours > 1 ? 's' : ''} ago`;
            if (diffDays < 7) return `${diffDays} day${diffDays > 1 ? 's' : ''} ago`;
            return `${Math.floor(diffDays / 7)} week${Math.floor(diffDays / 7) > 1 ? 's' : ''} ago`;
        }
    </script>
</body>
</html>

