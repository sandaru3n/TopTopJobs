<?= view('partials/head', ['title' => 'Job Search & Listings - TopTopJobs', 'css_file' => 'css/jobs.css']) ?>
<body class="font-display bg-background-light dark:bg-background-dark text-[#111318] dark:text-gray-200">
    <div class="relative flex min-h-screen w-full flex-col">
        <?= view('partials/header') ?>

        <main class="flex-grow flex flex-col md:flex-row">
            <!-- Filter Panel (Left) -->
            <aside id="filterPanel" class="hidden md:block sticky top-14 md:top-16 w-full md:w-80 lg:w-96 bg-white dark:bg-gray-800/50 border-r border-gray-200 dark:border-gray-700 p-6 h-[calc(100vh-4rem)] overflow-y-auto">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-xl font-bold text-[#111318] dark:text-white">Filters</h2>
                    <div class="flex items-center gap-2">
                        <button id="filterToggle" class="md:hidden flex items-center justify-center size-8 rounded-full bg-primary/10 dark:bg-primary/20 text-primary dark:text-white hover:bg-primary/20 dark:hover:bg-primary/30 transition-colors cursor-pointer">
                            <span class="material-symbols-outlined text-lg">tune</span>
                        </button>
                        <button id="closeFilters" class="md:hidden flex items-center justify-center size-8 rounded-full hover:bg-gray-100 dark:hover:bg-gray-700 cursor-pointer transition-colors">
                            <span class="material-symbols-outlined text-lg">close</span>
                        </button>
                    </div>
                </div>

                <!-- Job Type -->
                <div class="mb-4">
                    <h3 class="text-sm font-semibold text-[#111318] dark:text-white mb-2">Job Type</h3>
                    <div class="flex flex-wrap gap-2">
                        <label class="flex items-center gap-2 cursor-pointer shrink-0 hover:opacity-80 transition-opacity">
                            <input type="checkbox" name="job_type" value="full-time" class="rounded border-gray-300 text-primary focus:ring-primary cursor-pointer">
                            <span class="text-sm text-gray-700 dark:text-gray-300 whitespace-nowrap">Full-time</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer shrink-0 hover:opacity-80 transition-opacity">
                            <input type="checkbox" name="job_type" value="part-time" class="rounded border-gray-300 text-primary focus:ring-primary cursor-pointer">
                            <span class="text-sm text-gray-700 dark:text-gray-300 whitespace-nowrap">Part-time</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer shrink-0 hover:opacity-80 transition-opacity">
                            <input type="checkbox" name="job_type" value="internship" class="rounded border-gray-300 text-primary focus:ring-primary cursor-pointer">
                            <span class="text-sm text-gray-700 dark:text-gray-300 whitespace-nowrap">Internship</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer shrink-0 hover:opacity-80 transition-opacity">
                            <input type="checkbox" name="job_type" value="remote" class="rounded border-gray-300 text-primary focus:ring-primary cursor-pointer">
                            <span class="text-sm text-gray-700 dark:text-gray-300 whitespace-nowrap">Remote</span>
                        </label>
                    </div>
                </div>

                <!-- Experience -->
                <div class="mb-4">
                    <h3 class="text-sm font-semibold text-[#111318] dark:text-white mb-2">Experience</h3>
                    <div class="flex flex-wrap gap-2">
                        <label class="flex items-center gap-2 cursor-pointer shrink-0 hover:opacity-80 transition-opacity">
                            <input type="checkbox" name="experience" value="fresher" class="rounded border-gray-300 text-primary focus:ring-primary cursor-pointer">
                            <span class="text-sm text-gray-700 dark:text-gray-300 whitespace-nowrap">Fresher (0-1 yrs)</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer shrink-0 hover:opacity-80 transition-opacity">
                            <input type="checkbox" name="experience" value="junior" class="rounded border-gray-300 text-primary focus:ring-primary cursor-pointer">
                            <span class="text-sm text-gray-700 dark:text-gray-300 whitespace-nowrap">Junior (2-4 yrs)</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer shrink-0 hover:opacity-80 transition-opacity">
                            <input type="checkbox" name="experience" value="senior" class="rounded border-gray-300 text-primary focus:ring-primary cursor-pointer">
                            <span class="text-sm text-gray-700 dark:text-gray-300 whitespace-nowrap">Senior (5+ yrs)</span>
                        </label>
                    </div>
                </div>

                <!-- Salary Range -->
                <div class="mb-4">
                    <h3 class="text-sm font-semibold text-[#111318] dark:text-white mb-2">Salary Range</h3>
                    <input 
                        type="range" 
                        id="salaryRange" 
                        min="0" 
                        max="200000" 
                        step="5000" 
                        value="0" 
                        class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer dark:bg-gray-700"
                    />
                    <div class="flex justify-between mt-2">
                        <span id="salaryMin" class="text-xs text-gray-600 dark:text-gray-400">$0</span>
                        <span id="salaryMax" class="text-xs text-gray-600 dark:text-gray-400">$200K+</span>
                    </div>
                    <div id="salaryDisplay" class="text-sm font-medium text-primary mt-2 text-center">$0 - $200K+</div>
                </div>

                <!-- Date Posted -->
                <div class="mb-4">
                    <h3 class="text-sm font-semibold text-[#111318] dark:text-white mb-2">Date Posted</h3>
                    <div class="flex flex-wrap gap-2">
                        <label class="flex items-center gap-2 cursor-pointer shrink-0 hover:opacity-80 transition-opacity">
                            <input type="checkbox" name="date_posted" value="24h" class="rounded border-gray-300 text-primary focus:ring-primary cursor-pointer">
                            <span class="text-sm text-gray-700 dark:text-gray-300 whitespace-nowrap">Last 24 hours</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer shrink-0 hover:opacity-80 transition-opacity">
                            <input type="checkbox" name="date_posted" value="3d" class="rounded border-gray-300 text-primary focus:ring-primary cursor-pointer">
                            <span class="text-sm text-gray-700 dark:text-gray-300 whitespace-nowrap">Last 3 days</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer shrink-0 hover:opacity-80 transition-opacity">
                            <input type="checkbox" name="date_posted" value="7d" class="rounded border-gray-300 text-primary focus:ring-primary cursor-pointer">
                            <span class="text-sm text-gray-700 dark:text-gray-300 whitespace-nowrap">Last week</span>
                        </label>
                    </div>
                </div>

                <!-- Clear Filters -->
                <button id="clearFilters" class="w-full py-2 px-4 rounded-lg border border-gray-300 dark:border-gray-700 text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors cursor-pointer">
                    Clear All Filters
                </button>
            </aside>

            <!-- Mobile Filter Sheet -->
            <div id="mobileFilterSheet" class="fixed inset-x-0 bottom-0 bg-white dark:bg-gray-800 rounded-t-3xl shadow-2xl transform translate-y-full transition-transform duration-300 z-50 max-h-[80vh] overflow-y-auto">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-xl font-bold text-[#111318] dark:text-white">Filters</h2>
                        <button id="closeMobileFilters" class="flex items-center justify-center size-8 rounded-full hover:bg-gray-100 dark:hover:bg-gray-700 cursor-pointer transition-colors">
                            <span class="material-symbols-outlined text-lg">close</span>
                        </button>
                    </div>
                    
                    <!-- Job Type -->
                    <div class="mb-4">
                        <h3 class="text-sm font-semibold text-[#111318] dark:text-white mb-2">Job Type</h3>
                        <div class="flex flex-wrap gap-2">
                            <label class="flex items-center gap-2 cursor-pointer shrink-0 hover:opacity-80 transition-opacity">
                                <input type="checkbox" name="job_type_mobile" value="full-time" class="rounded border-gray-300 text-primary focus:ring-primary cursor-pointer">
                                <span class="text-sm text-gray-700 dark:text-gray-300 whitespace-nowrap">Full-time</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer shrink-0 hover:opacity-80 transition-opacity">
                                <input type="checkbox" name="job_type_mobile" value="part-time" class="rounded border-gray-300 text-primary focus:ring-primary cursor-pointer">
                                <span class="text-sm text-gray-700 dark:text-gray-300 whitespace-nowrap">Part-time</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer shrink-0 hover:opacity-80 transition-opacity">
                                <input type="checkbox" name="job_type_mobile" value="internship" class="rounded border-gray-300 text-primary focus:ring-primary cursor-pointer">
                                <span class="text-sm text-gray-700 dark:text-gray-300 whitespace-nowrap">Internship</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer shrink-0 hover:opacity-80 transition-opacity">
                                <input type="checkbox" name="job_type_mobile" value="remote" class="rounded border-gray-300 text-primary focus:ring-primary cursor-pointer">
                                <span class="text-sm text-gray-700 dark:text-gray-300 whitespace-nowrap">Remote</span>
                            </label>
                        </div>
                    </div>

                    <!-- Experience -->
                    <div class="mb-4">
                        <h3 class="text-sm font-semibold text-[#111318] dark:text-white mb-2">Experience</h3>
                        <div class="flex flex-wrap gap-2">
                            <label class="flex items-center gap-2 cursor-pointer shrink-0 hover:opacity-80 transition-opacity">
                                <input type="checkbox" name="experience_mobile" value="fresher" class="rounded border-gray-300 text-primary focus:ring-primary cursor-pointer">
                                <span class="text-sm text-gray-700 dark:text-gray-300 whitespace-nowrap">Fresher (0-1 yrs)</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer shrink-0 hover:opacity-80 transition-opacity">
                                <input type="checkbox" name="experience_mobile" value="junior" class="rounded border-gray-300 text-primary focus:ring-primary cursor-pointer">
                                <span class="text-sm text-gray-700 dark:text-gray-300 whitespace-nowrap">Junior (2-4 yrs)</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer shrink-0 hover:opacity-80 transition-opacity">
                                <input type="checkbox" name="experience_mobile" value="senior" class="rounded border-gray-300 text-primary focus:ring-primary cursor-pointer">
                                <span class="text-sm text-gray-700 dark:text-gray-300 whitespace-nowrap">Senior (5+ yrs)</span>
                            </label>
                        </div>
                    </div>

                    <!-- Salary Range -->
                    <div class="mb-4">
                        <h3 class="text-sm font-semibold text-[#111318] dark:text-white mb-2">Salary Range</h3>
                        <input 
                            type="range" 
                            id="salaryRangeMobile" 
                            min="0" 
                            max="200000" 
                            step="5000" 
                            value="0" 
                            class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer dark:bg-gray-700"
                        />
                        <div id="salaryDisplayMobile" class="text-sm font-medium text-primary mt-2 text-center">$0 - $200K+</div>
                    </div>

                    <!-- Date Posted -->
                    <div class="mb-4">
                        <h3 class="text-sm font-semibold text-[#111318] dark:text-white mb-2">Date Posted</h3>
                        <div class="flex flex-wrap gap-2">
                            <label class="flex items-center gap-2 cursor-pointer shrink-0 hover:opacity-80 transition-opacity">
                                <input type="checkbox" name="date_posted_mobile" value="24h" class="rounded border-gray-300 text-primary focus:ring-primary cursor-pointer">
                                <span class="text-sm text-gray-700 dark:text-gray-300 whitespace-nowrap">Last 24 hours</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer shrink-0 hover:opacity-80 transition-opacity">
                                <input type="checkbox" name="date_posted_mobile" value="3d" class="rounded border-gray-300 text-primary focus:ring-primary cursor-pointer">
                                <span class="text-sm text-gray-700 dark:text-gray-300 whitespace-nowrap">Last 3 days</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer shrink-0 hover:opacity-80 transition-opacity">
                                <input type="checkbox" name="date_posted_mobile" value="7d" class="rounded border-gray-300 text-primary focus:ring-primary cursor-pointer">
                                <span class="text-sm text-gray-700 dark:text-gray-300 whitespace-nowrap">Last week</span>
                            </label>
                        </div>
                    </div>

                    <!-- Clear Filters -->
                    <button id="clearFiltersMobile" class="w-full py-2 px-4 rounded-lg border border-gray-300 dark:border-gray-700 text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors cursor-pointer">
                        Clear All Filters
                    </button>
                </div>
            </div>
            <div id="filterOverlay" class="hidden fixed inset-0 bg-black/50 z-40"></div>

            <!-- Job Listings (Right) -->
            <section class="flex-1 p-4 md:p-6">
                <!-- Sort and View Controls -->
                <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
                    <!-- Mobile Filter Button (Left on mobile only) -->
                    <button id="mobileFilterToggle" class="md:hidden flex items-center justify-center gap-2 px-4 py-2 rounded-lg bg-primary text-white text-sm font-medium hover:bg-primary/90 transition-colors cursor-pointer">
                        <span class="material-symbols-outlined text-lg">tune</span>
                        <span>Filters</span>
                    </button>
                    <!-- Sort Section (Right aligned) -->
                    <div class="flex items-center gap-2 flex-wrap ml-auto">
                        <span class="text-sm text-gray-600 dark:text-gray-400">Sort:</span>
                        <select id="sortBy" class="rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-sm px-3 py-2 focus:outline-0 focus:ring-2 focus:ring-primary/50 cursor-pointer">
                            <option value="relevant">Relevant</option>
                            <option value="newest">Newest</option>
                            <option value="salary_high">Salary High</option>
                            <option value="popular">Popular</option>
                        </select>
                    </div>
                </div>

                <!-- Results Count -->
                <div class="mb-4">
                    <p id="resultsCount" class="text-sm text-gray-600 dark:text-gray-400">Loading jobs...</p>
                </div>

                <!-- Job Listings Container -->
                <div id="jobListings" class="grid grid-cols-1 gap-4 md:gap-6">
                    <!-- Skeleton loaders -->
                    <div class="skeleton-loader">
                        <div class="skeleton-header"></div>
                        <div class="skeleton-content"></div>
                        <div class="skeleton-footer"></div>
                    </div>
                </div>

                <!-- Empty States -->
                <div id="emptyState" class="hidden text-center py-12">
                    <span class="material-symbols-outlined text-6xl text-gray-400 dark:text-gray-600 mb-4">work_off</span>
                    <h3 class="text-xl font-semibold text-gray-700 dark:text-gray-300 mb-2">No jobs found</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-500">Try adjusting your filters or search terms</p>
                </div>

                <div id="errorState" class="hidden text-center py-12">
                    <span class="material-symbols-outlined text-6xl text-red-400 mb-4">error</span>
                    <h3 class="text-xl font-semibold text-red-600 dark:text-red-400 mb-2">Error loading jobs</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-500 mb-4">Please try again later</p>
                    <button id="retryBtn" class="px-4 py-2 rounded-lg bg-primary text-white hover:bg-primary/90 transition-colors">
                        Retry
                    </button>
                </div>

                <!-- Load More Button -->
                <div id="loadMoreContainer" class="hidden text-center mt-8">
                    <button id="loadMoreBtn" class="px-6 py-3 rounded-lg bg-primary text-white hover:bg-primary/90 transition-colors">
                        Load More Jobs
                    </button>
                </div>
            </section>
        </main>
        
        <?= view('partials/footer') ?>
    </div>

    <script>
        const baseUrl = '<?= base_url() ?>';
        const apiUrl = '<?= base_url('api/jobs.php') ?>';
    </script>
    <script src="<?= base_url('js/jobs.js') ?>"></script>
</body>
</html>

