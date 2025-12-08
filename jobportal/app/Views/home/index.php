<?= view('partials/head', ['title' => 'Job Portal Home Page - JobFind']) ?>
<body class="font-display bg-background-light dark:bg-background-dark text-[#111318] dark:text-gray-200">
    <div class="relative flex min-h-screen w-full flex-col">
        <?= view('partials/header') ?>

        <main class="flex-grow">
            <!-- Search and Filter Section -->
            <div class="sticky top-16 z-40 bg-white/90 dark:bg-background-dark/90 backdrop-blur-sm py-6 border-b border-gray-200 dark:border-gray-800">
                <div class="container mx-auto px-6">
                    <div class="flex flex-col gap-4">
                        <div class="flex w-full flex-wrap items-center gap-3">
                            <label class="relative flex flex-col min-w-40 flex-1">
                                <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 dark:text-gray-500">search</span>
                                <input 
                                    id="searchJobInput"
                                    class="form-input flex w-full min-w-0 flex-1 resize-none overflow-hidden rounded-full text-[#111318] dark:text-white focus:outline-0 focus:ring-2 focus:ring-primary/50 border-none bg-background-light dark:bg-gray-800 h-14 placeholder:text-[#616f89] dark:placeholder:text-gray-500 pl-12 pr-4 text-base font-normal leading-normal" 
                                    placeholder="Job title, skill, or company" 
                                    value=""
                                />
                            </label>
                            <label class="relative flex flex-col min-w-40 flex-1">
                                <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 dark:text-gray-500">location_on</span>
                                <input 
                                    id="searchLocationInput"
                                    class="form-input flex w-full min-w-0 flex-1 resize-none overflow-hidden rounded-full text-[#111318] dark:text-white focus:outline-0 focus:ring-2 focus:ring-primary/50 border-none bg-background-light dark:bg-gray-800 h-14 placeholder:text-[#616f89] dark:placeholder:text-gray-500 pl-12 pr-4 text-base font-normal leading-normal" 
                                    placeholder="City or remote" 
                                    value=""
                                />
                            </label>
                            <a 
                                href="/jobs" 
                                id="searchBtn"
                                class="flex min-w-[120px] cursor-pointer items-center justify-center overflow-hidden rounded-full h-14 px-6 bg-primary text-white text-base font-bold leading-normal tracking-[0.015em] hover:bg-primary/90 transition-colors"
                            >
                                <span class="truncate">Search</span>
                            </a>
                        </div>
                        <div class="flex gap-2 flex-wrap">
                            <button 
                                data-filter="full-time" 
                                class="filter-pill active flex h-9 shrink-0 items-center justify-center gap-x-2 rounded-full bg-primary/10 dark:bg-primary/20 px-4 text-primary dark:text-white text-sm font-medium leading-normal hover:bg-primary/20 dark:hover:bg-primary/30 transition-colors"
                            >
                                <p>Full-time</p>
                            </button>
                            <button 
                                data-filter="part-time" 
                                class="filter-pill flex h-9 shrink-0 items-center justify-center gap-x-2 rounded-full bg-gray-200/80 dark:bg-gray-800 px-4 text-[#111318] dark:text-gray-300 text-sm font-medium leading-normal hover:bg-gray-300/80 dark:hover:bg-gray-700 transition-colors"
                            >
                                <p>Part-time</p>
                            </button>
                            <button 
                                data-filter="remote" 
                                class="filter-pill flex h-9 shrink-0 items-center justify-center gap-x-2 rounded-full bg-gray-200/80 dark:bg-gray-800 px-4 text-[#111318] dark:text-gray-300 text-sm font-medium leading-normal hover:bg-gray-300/80 dark:hover:bg-gray-700 transition-colors"
                            >
                                <p>Remote</p>
                            </button>
                            <button 
                                data-filter="contract" 
                                class="filter-pill flex h-9 shrink-0 items-center justify-center gap-x-2 rounded-full bg-gray-200/80 dark:bg-gray-800 px-4 text-[#111318] dark:text-gray-300 text-sm font-medium leading-normal hover:bg-gray-300/80 dark:hover:bg-gray-700 transition-colors"
                            >
                                <p>Contract</p>
                            </button>
                            <button 
                                data-filter="internship" 
                                class="filter-pill flex h-9 shrink-0 items-center justify-center gap-x-2 rounded-full bg-gray-200/80 dark:bg-gray-800 px-4 text-[#111318] dark:text-gray-300 text-sm font-medium leading-normal hover:bg-gray-300/80 dark:hover:bg-gray-700 transition-colors"
                            >
                                <p>Internship</p>
                            </button>
                            <a 
                                href="/jobs" 
                                class="flex h-9 shrink-0 items-center justify-center gap-x-2 rounded-full border border-gray-300 dark:border-gray-700 px-4 text-[#111318] dark:text-gray-300 text-sm font-medium leading-normal hover:bg-gray-200/50 dark:hover:bg-gray-800 transition-colors"
                            >
                                <span class="material-symbols-outlined text-base">tune</span>
                                <p>All Filters</p>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Job Listings Section -->
            <div class="container mx-auto px-6 py-10">
                <div class="flex flex-wrap justify-between gap-4 mb-8 items-baseline">
                    <p class="text-[#111318] dark:text-white tracking-light text-[32px] font-bold leading-tight min-w-72">Recent Job Postings</p>
                    <p id="resultsCount" class="text-gray-500 dark:text-gray-400 text-sm">Showing 1-12 of 2,456 results</p>
                </div>
                <div id="jobGrid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <!-- Job cards will be loaded here -->
                </div>
                <!-- Pagination -->
                <div class="flex justify-center items-center gap-2 mt-12">
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
            // Filter pills
            document.querySelectorAll('.filter-pill').forEach(btn => {
                btn.addEventListener('click', (e) => {
                    e.preventDefault();
                    const filter = btn.dataset.filter;
                    if (filter) {
                        toggleFilter(btn);
                    }
                });
            });

            // Search button
            document.getElementById('searchBtn').addEventListener('click', (e) => {
                const job = document.getElementById('searchJobInput').value;
                const location = document.getElementById('searchLocationInput').value;
                const activeFilters = Array.from(document.querySelectorAll('.filter-pill.active'))
                    .map(btn => btn.dataset.filter)
                    .filter(f => f);
                
                let url = '/jobs?';
                if (job) url += `q=${encodeURIComponent(job)}&`;
                if (location) url += `loc=${encodeURIComponent(location)}&`;
                if (activeFilters.length > 0) url += `job_type=${activeFilters.join(',')}&`;
                
                window.location.href = url.replace(/&$/, '');
            });

            // Enter key on search inputs
            ['searchJobInput', 'searchLocationInput'].forEach(id => {
                document.getElementById(id).addEventListener('keypress', (e) => {
                    if (e.key === 'Enter') {
                        document.getElementById('searchBtn').click();
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
                <div class="bg-white dark:bg-gray-800/50 rounded-lg p-6 flex flex-col gap-4 border border-gray-200 dark:border-gray-700/50 hover:shadow-lg hover:border-primary/50 dark:hover:border-primary/50 transition-all duration-300 cursor-pointer" onclick="window.location.href='/jobs?id=${job.id}'">
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

