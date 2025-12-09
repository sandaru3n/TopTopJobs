<?= view('partials/head', ['title' => 'Job Portal Home Page - TopTopJobs']) ?>
<style>
    /* Ensure line-clamp-3 works for job descriptions */
    .line-clamp-3 {
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
        text-overflow: ellipsis;
    }
</style>
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
                    <p id="resultsCount" class="text-gray-500 dark:text-gray-400 text-sm">Loading...</p>
                </div>
                <div id="jobGrid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <!-- Job cards will be loaded here -->
                </div>
                <!-- Pagination -->
                <div id="paginationContainer" class="flex justify-center items-center gap-2 mt-8">
                    <!-- Pagination buttons will be loaded here -->
                </div>
            </div>

            <!-- Job Categories Section -->
            <div class="container mx-auto px-4 md:px-6 py-8 md:py-12">
                <div class="flex flex-wrap justify-between items-center gap-4 mb-6">
                    <h2 class="text-[#111318] dark:text-white tracking-light text-[32px] font-bold leading-tight">Job Categories</h2>
                    <div class="flex items-center gap-3">
                        <span class="text-sm text-gray-600 dark:text-gray-400">Sort by</span>
                        <select id="categorySort" class="px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-[#111318] dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-primary/50 cursor-pointer">
                            <option value="name-asc">A to Z</option>
                            <option value="name-desc">Z to A</option>
                            <option value="count-desc">Most Jobs</option>
                            <option value="count-asc">Least Jobs</option>
                        </select>
                        <div class="flex items-center gap-2">
                            <button id="categoryPrevBtn" class="flex items-center justify-center size-10 rounded-full text-gray-400 dark:text-gray-500 hover:bg-gray-200 dark:hover:bg-gray-800 transition-colors disabled:opacity-50 disabled:cursor-not-allowed" disabled>
                                <span class="material-symbols-outlined text-xl">chevron_left</span>
                            </button>
                            <button id="categoryNextBtn" class="flex items-center justify-center size-10 rounded-full text-primary hover:bg-primary/10 dark:hover:bg-primary/20 transition-colors">
                                <span class="material-symbols-outlined text-xl">chevron_right</span>
                            </button>
                        </div>
                    </div>
                </div>
                <div id="categoryGrid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
                    <!-- Categories will be loaded here -->
                </div>
                <div id="categoryPagination" class="flex justify-center items-center gap-2">
                    <!-- Pagination dots will be loaded here -->
                </div>
            </div>
        </main>

        <?= view('partials/footer') ?>
    </div>

    <script>
        const baseUrl = '<?= base_url() ?>';
        
        // Category state
        let categoriesData = [];
        let currentCategoryPage = 1;
        const categoriesPerPage = 15;
        let categorySortBy = 'name-asc';

        // Job pagination state
        let currentJobPage = 1;
        const jobsPerPage = 21;
        let totalJobs = 0;
        let totalJobPages = 1;

        // Load jobs on page load
        document.addEventListener('DOMContentLoaded', () => {
            // Get page from URL parameter
            const urlParams = new URLSearchParams(window.location.search);
            currentJobPage = parseInt(urlParams.get('page')) || 1;
            
            loadJobs();
            loadCategories();
            setupEventListeners();
            
            // Category navigation event listeners
            const prevBtn = document.getElementById('categoryPrevBtn');
            const nextBtn = document.getElementById('categoryNextBtn');
            const sortSelect = document.getElementById('categorySort');

            if (prevBtn) {
                prevBtn.addEventListener('click', () => {
                    if (currentCategoryPage > 1) {
                        currentCategoryPage--;
                        renderCategories();
                    }
                });
            }

            if (nextBtn) {
                nextBtn.addEventListener('click', () => {
                    const totalPages = Math.ceil(categoriesData.length / categoriesPerPage);
                    if (currentCategoryPage < totalPages) {
                        currentCategoryPage++;
                        renderCategories();
                    }
                });
            }

            if (sortSelect) {
                sortSelect.addEventListener('change', (e) => {
                    categorySortBy = e.target.value;
                    currentCategoryPage = 1;
                    sortCategories();
                    renderCategories();
                });
            }
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
                const response = await fetch(`${baseUrl.replace(/\/$/, '')}/api/jobs.php?page=${currentJobPage}&per_page=${jobsPerPage}`);
                const data = await response.json();

                if (data.success && data.jobs) {
                    totalJobs = data.total || 0;
                    totalJobPages = Math.ceil(totalJobs / jobsPerPage);
                    renderJobs(data.jobs);
                    updateResultsCount();
                    renderPagination();
                }
            } catch (error) {
                console.error('Error loading jobs:', error);
            }
        }

        // Get category color class
        function getCategoryColor(category) {
            if (!category) return 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300';
            
            const colorMap = {
                'Cashier': 'bg-blue-100 dark:bg-blue-900/50 text-blue-800 dark:text-blue-300',
                'Data Entry': 'bg-purple-100 dark:bg-purple-900/50 text-purple-800 dark:text-purple-300',
                'IT/Software': 'bg-indigo-100 dark:bg-indigo-900/50 text-indigo-800 dark:text-indigo-300',
                'Marketing': 'bg-pink-100 dark:bg-pink-900/50 text-pink-800 dark:text-pink-300',
                'Sales': 'bg-orange-100 dark:bg-orange-900/50 text-orange-800 dark:text-orange-300',
                'Customer Service': 'bg-teal-100 dark:bg-teal-900/50 text-teal-800 dark:text-teal-300',
                'Design': 'bg-rose-100 dark:bg-rose-900/50 text-rose-800 dark:text-rose-300',
                'Engineering': 'bg-cyan-100 dark:bg-cyan-900/50 text-cyan-800 dark:text-cyan-300',
                'Finance': 'bg-emerald-100 dark:bg-emerald-900/50 text-emerald-800 dark:text-emerald-300',
                'Healthcare': 'bg-red-100 dark:bg-red-900/50 text-red-800 dark:text-red-300',
                'Education': 'bg-amber-100 dark:bg-amber-900/50 text-amber-800 dark:text-amber-300',
                'Other': 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300'
            };
            
            return colorMap[category] || 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300';
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
                    <div class="flex flex-wrap gap-2">
                        <span class="inline-flex items-center rounded-full ${getCategoryColor(job.category || 'Other')} px-3 py-1 text-xs font-medium">${job.category || 'Other'}</span>
                    </div>
                    <p class="text-sm text-gray-500 dark:text-gray-400 leading-relaxed line-clamp-3">${job.description || ''}</p>
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

        function updateResultsCount() {
            const start = ((currentJobPage - 1) * jobsPerPage) + 1;
            const end = Math.min(currentJobPage * jobsPerPage, totalJobs);
            const countEl = document.getElementById('resultsCount');
            if (countEl) {
                countEl.textContent = `Showing ${start}-${end} of ${totalJobs.toLocaleString()} results`;
            }
        }

        function renderPagination() {
            const container = document.getElementById('paginationContainer');
            if (!container) return;

            if (totalJobPages <= 1) {
                container.innerHTML = '';
                return;
            }

            let paginationHTML = '';

            // Previous button
            paginationHTML += `
                <button 
                    id="prevPageBtn"
                    class="pagination-btn flex items-center justify-center size-10 rounded-full text-gray-500 dark:text-gray-400 hover:bg-gray-200 dark:hover:bg-gray-800 transition-colors ${currentJobPage === 1 ? 'opacity-50 cursor-not-allowed' : ''}"
                    ${currentJobPage === 1 ? 'disabled' : ''}
                    onclick="goToPage(${currentJobPage - 1})"
                >
                    <span class="material-symbols-outlined text-xl">chevron_left</span>
                </button>
            `;

            // Page numbers
            const maxVisiblePages = 7;
            let startPage = Math.max(1, currentJobPage - Math.floor(maxVisiblePages / 2));
            let endPage = Math.min(totalJobPages, startPage + maxVisiblePages - 1);

            if (endPage - startPage < maxVisiblePages - 1) {
                startPage = Math.max(1, endPage - maxVisiblePages + 1);
            }

            if (startPage > 1) {
                paginationHTML += `
                    <button 
                        class="pagination-btn flex items-center justify-center size-10 rounded-full text-gray-600 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-800 transition-colors"
                        onclick="goToPage(1)"
                    >1</button>
                `;
                if (startPage > 2) {
                    paginationHTML += `<span class="text-gray-500 dark:text-gray-400">...</span>`;
                }
            }

            for (let i = startPage; i <= endPage; i++) {
                const isActive = i === currentJobPage;
                paginationHTML += `
                    <button 
                        class="pagination-btn flex items-center justify-center size-10 rounded-full transition-colors ${
                            isActive 
                                ? 'text-white bg-primary' 
                                : 'text-gray-600 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-800'
                        }"
                        onclick="goToPage(${i})"
                    >${i}</button>
                `;
            }

            if (endPage < totalJobPages) {
                if (endPage < totalJobPages - 1) {
                    paginationHTML += `<span class="text-gray-500 dark:text-gray-400">...</span>`;
                }
                paginationHTML += `
                    <button 
                        class="pagination-btn flex items-center justify-center size-10 rounded-full text-gray-600 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-800 transition-colors"
                        onclick="goToPage(${totalJobPages})"
                    >${totalJobPages}</button>
                `;
            }

            // Next button
            paginationHTML += `
                <button 
                    id="nextPageBtn"
                    class="pagination-btn flex items-center justify-center size-10 rounded-full text-gray-500 dark:text-gray-400 hover:bg-gray-200 dark:hover:bg-gray-800 transition-colors ${currentJobPage === totalJobPages ? 'opacity-50 cursor-not-allowed' : ''}"
                    ${currentJobPage === totalJobPages ? 'disabled' : ''}
                    onclick="goToPage(${currentJobPage + 1})"
                >
                    <span class="material-symbols-outlined text-xl">chevron_right</span>
                </button>
            `;

            container.innerHTML = paginationHTML;
        }

        function goToPage(page) {
            if (page < 1 || page > totalJobPages || page === currentJobPage) return;
            
            currentJobPage = page;
            
            // Update URL without reload
            const url = new URL(window.location);
            if (page === 1) {
                url.searchParams.delete('page');
            } else {
                url.searchParams.set('page', page);
            }
            window.history.pushState({ page }, '', url);
            
            // Scroll to top of job listings
            document.getElementById('jobGrid')?.scrollIntoView({ behavior: 'smooth', block: 'start' });
            
            // Load jobs for the new page
            loadJobs();
        }

        function getTimeAgo(dateString) {
            const now = new Date();
            const date = new Date(dateString);
            const diffMs = now - date;
            const diffSecs = Math.floor(diffMs / 1000);
            const diffMins = Math.floor(diffMs / 60000);
            const diffHours = Math.floor(diffMs / 3600000);
            const diffDays = Math.floor(diffMs / 86400000);
            const diffWeeks = Math.floor(diffDays / 7);
            const diffMonths = Math.floor(diffDays / 30);

            if (diffSecs < 60) return 'just now';
            if (diffMins < 60) return `${diffMins} min ago`;
            if (diffHours < 24) return `${diffHours} hour${diffHours > 1 ? 's' : ''} ago`;
            if (diffDays < 7) return `${diffDays} day${diffDays > 1 ? 's' : ''} ago`;
            if (diffDays < 30) return `${diffWeeks} week${diffWeeks > 1 ? 's' : ''} ago`;
            if (diffDays < 365) return `${diffMonths} month${diffMonths > 1 ? 's' : ''} ago`;
            return `${Math.floor(diffDays / 365)} year${Math.floor(diffDays / 365) > 1 ? 's' : ''} ago`;
        }

        // Load categories
        async function loadCategories() {
            try {
                const response = await fetch(`${baseUrl.replace(/\/$/, '')}/api/categories.php`);
                const data = await response.json();

                if (data.success && data.categories) {
                    categoriesData = data.categories;
                    sortCategories();
                    renderCategories();
                }
            } catch (error) {
                console.error('Error loading categories:', error);
            }
        }

        // Sort categories
        function sortCategories() {
            const [sortField, sortOrder] = categorySortBy.split('-');
            
            categoriesData.sort((a, b) => {
                if (sortField === 'name') {
                    return sortOrder === 'asc' 
                        ? a.name.localeCompare(b.name)
                        : b.name.localeCompare(a.name);
                } else {
                    return sortOrder === 'desc'
                        ? b.count - a.count
                        : a.count - b.count;
                }
            });
        }

        // Render categories
        function renderCategories() {
            const container = document.getElementById('categoryGrid');
            const startIndex = (currentCategoryPage - 1) * categoriesPerPage;
            const endIndex = startIndex + categoriesPerPage;
            const pageCategories = categoriesData.slice(startIndex, endIndex);

            container.innerHTML = pageCategories.map(category => `
                <div class="bg-white dark:bg-gray-800/50 rounded-lg p-6 border border-gray-200 dark:border-gray-700/50 hover:shadow-md hover:border-primary/50 dark:hover:border-primary/50 transition-all duration-300 cursor-pointer" onclick="window.location.href='<?= base_url('jobs') ?>?category=${encodeURIComponent(category.name)}'">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-bold text-[#111318] dark:text-white">${category.name}</h3>
                        <div class="text-right">
                            <p class="text-2xl font-bold text-[#111318] dark:text-white">${category.count}</p>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Job Posts</p>
                        </div>
                    </div>
                </div>
            `).join('');

            // Update pagination
            updateCategoryPagination();
            updateCategoryNavigation();
        }

        // Update category pagination dots
        function updateCategoryPagination() {
            const totalPages = Math.ceil(categoriesData.length / categoriesPerPage);
            const container = document.getElementById('categoryPagination');
            
            container.innerHTML = Array.from({ length: totalPages }, (_, i) => {
                const pageNum = i + 1;
                const isActive = pageNum === currentCategoryPage;
                return `
                    <button 
                        class="w-2 h-2 rounded-full transition-colors ${isActive ? 'bg-primary' : 'bg-gray-300 dark:bg-gray-600'}"
                        onclick="goToCategoryPage(${pageNum})"
                        aria-label="Go to page ${pageNum}"
                    ></button>
                `;
            }).join('');
        }

        // Update category navigation buttons
        function updateCategoryNavigation() {
            const totalPages = Math.ceil(categoriesData.length / categoriesPerPage);
            const prevBtn = document.getElementById('categoryPrevBtn');
            const nextBtn = document.getElementById('categoryNextBtn');

            prevBtn.disabled = currentCategoryPage === 1;
            nextBtn.disabled = currentCategoryPage === totalPages;

            if (currentCategoryPage === 1) {
                prevBtn.classList.add('text-gray-400', 'dark:text-gray-500');
                prevBtn.classList.remove('text-primary');
            } else {
                prevBtn.classList.remove('text-gray-400', 'dark:text-gray-500');
                prevBtn.classList.add('text-primary');
            }

            if (currentCategoryPage === totalPages) {
                nextBtn.classList.add('text-gray-400', 'dark:text-gray-500');
                nextBtn.classList.remove('text-primary');
            } else {
                nextBtn.classList.remove('text-gray-400', 'dark:text-gray-500');
                nextBtn.classList.add('text-primary');
            }
        }

        // Go to category page
        function goToCategoryPage(page) {
            currentCategoryPage = page;
            renderCategories();
        }

    </script>
</body>
</html>

