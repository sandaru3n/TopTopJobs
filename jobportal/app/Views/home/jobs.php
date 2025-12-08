<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Job Search & Listings - JobFind</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
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
    <link rel="stylesheet" href="<?= base_url('css/jobs.css') ?>">
</head>
<body class="font-display bg-background-light dark:bg-background-dark text-[#111318] dark:text-gray-200">
    <div class="relative flex min-h-screen w-full flex-col">
        <!-- Search Header (Fixed on mobile) -->
        <header class="sticky top-0 z-50 w-full bg-white/80 dark:bg-background-dark/80 backdrop-blur-sm border-b border-gray-200 dark:border-gray-800">
            <div class="container mx-auto px-4 md:px-6">
                <div class="flex h-14 md:h-16 items-center justify-between gap-3">
                    <!-- Back button (mobile only) -->
                    <button id="backBtn" class="md:hidden flex items-center justify-center size-10 rounded-full hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
                        <span class="material-symbols-outlined text-xl">arrow_back</span>
                    </button>
                    
                    <!-- Search input -->
                    <div class="relative flex-1">
                        <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 dark:text-gray-500">search</span>
                        <input 
                            id="searchInput" 
                            type="text" 
                            class="w-full rounded-full text-[#111318] dark:text-white focus:outline-0 focus:ring-2 focus:ring-primary/50 border-none bg-background-light dark:bg-gray-800 h-10 md:h-14 placeholder:text-[#616f89] dark:placeholder:text-gray-500 pl-12 pr-4 text-sm md:text-base font-normal leading-normal" 
                            placeholder="Job title, skill, or company"
                        />
                    </div>
                    
                    <!-- Filter toggle button -->
                    <button id="filterToggle" class="flex items-center justify-center size-10 md:size-12 rounded-full bg-primary/10 dark:bg-primary/20 text-primary dark:text-white hover:bg-primary/20 dark:hover:bg-primary/30 transition-colors">
                        <span class="material-symbols-outlined text-xl">tune</span>
                    </button>
                </div>
            </div>
        </header>

        <main class="flex-grow flex flex-col md:flex-row">
            <!-- Filter Panel (Left) -->
            <aside id="filterPanel" class="hidden md:block w-full md:w-80 lg:w-96 bg-white dark:bg-gray-800/50 border-r border-gray-200 dark:border-gray-700 p-6 overflow-y-auto max-h-[calc(100vh-4rem)]">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-xl font-bold text-[#111318] dark:text-white">Filters</h2>
                    <button id="closeFilters" class="md:hidden flex items-center justify-center size-8 rounded-full hover:bg-gray-100 dark:hover:bg-gray-700">
                        <span class="material-symbols-outlined text-lg">close</span>
                    </button>
                </div>

                <!-- Job Type -->
                <div class="mb-6">
                    <h3 class="text-sm font-semibold text-[#111318] dark:text-white mb-3">Job Type</h3>
                    <div class="space-y-2">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="job_type" value="full-time" class="rounded border-gray-300 text-primary focus:ring-primary">
                            <span class="text-sm text-gray-700 dark:text-gray-300">Full-time</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="job_type" value="part-time" class="rounded border-gray-300 text-primary focus:ring-primary">
                            <span class="text-sm text-gray-700 dark:text-gray-300">Part-time</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="job_type" value="internship" class="rounded border-gray-300 text-primary focus:ring-primary">
                            <span class="text-sm text-gray-700 dark:text-gray-300">Internship</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="job_type" value="remote" class="rounded border-gray-300 text-primary focus:ring-primary">
                            <span class="text-sm text-gray-700 dark:text-gray-300">Remote</span>
                        </label>
                    </div>
                </div>

                <!-- Experience -->
                <div class="mb-6">
                    <h3 class="text-sm font-semibold text-[#111318] dark:text-white mb-3">Experience</h3>
                    <div class="space-y-2">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="experience" value="fresher" class="rounded border-gray-300 text-primary focus:ring-primary">
                            <span class="text-sm text-gray-700 dark:text-gray-300">Fresher (0-1 yrs)</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="experience" value="junior" class="rounded border-gray-300 text-primary focus:ring-primary">
                            <span class="text-sm text-gray-700 dark:text-gray-300">Junior (2-4 yrs)</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="experience" value="senior" class="rounded border-gray-300 text-primary focus:ring-primary">
                            <span class="text-sm text-gray-700 dark:text-gray-300">Senior (5+ yrs)</span>
                        </label>
                    </div>
                </div>

                <!-- Salary Range -->
                <div class="mb-6">
                    <h3 class="text-sm font-semibold text-[#111318] dark:text-white mb-3">Salary Range</h3>
                    <input 
                        type="range" 
                        id="salaryRange" 
                        min="0" 
                        max="5000000" 
                        step="100000" 
                        value="0" 
                        class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer dark:bg-gray-700"
                    />
                    <div class="flex justify-between mt-2">
                        <span id="salaryMin" class="text-xs text-gray-600 dark:text-gray-400">₹0</span>
                        <span id="salaryMax" class="text-xs text-gray-600 dark:text-gray-400">₹50L+</span>
                    </div>
                    <div id="salaryDisplay" class="text-sm font-medium text-primary mt-2 text-center">₹0 - ₹50L+</div>
                </div>

                <!-- Date Posted -->
                <div class="mb-6">
                    <h3 class="text-sm font-semibold text-[#111318] dark:text-white mb-3">Date Posted</h3>
                    <div class="space-y-2">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="date_posted" value="24h" class="rounded border-gray-300 text-primary focus:ring-primary">
                            <span class="text-sm text-gray-700 dark:text-gray-300">Last 24 hours</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="date_posted" value="3d" class="rounded border-gray-300 text-primary focus:ring-primary">
                            <span class="text-sm text-gray-700 dark:text-gray-300">Last 3 days</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="date_posted" value="7d" class="rounded border-gray-300 text-primary focus:ring-primary">
                            <span class="text-sm text-gray-700 dark:text-gray-300">Last week</span>
                        </label>
                    </div>
                </div>

                <!-- Company -->
                <div class="mb-6">
                    <h3 class="text-sm font-semibold text-[#111318] dark:text-white mb-3">Company</h3>
                    <input 
                        type="text" 
                        id="companyFilter" 
                        placeholder="Filter by company" 
                        class="w-full rounded-lg text-[#111318] dark:text-white focus:outline-0 focus:ring-2 focus:ring-primary/50 border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 h-10 px-4 text-sm"
                    />
                </div>

                <!-- Skills -->
                <div class="mb-6">
                    <h3 class="text-sm font-semibold text-[#111318] dark:text-white mb-3">Skills</h3>
                    <input 
                        type="text" 
                        id="skillsFilter" 
                        placeholder="Add skills" 
                        class="w-full rounded-lg text-[#111318] dark:text-white focus:outline-0 focus:ring-2 focus:ring-primary/50 border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 h-10 px-4 text-sm"
                    />
                    <div id="skillsTags" class="flex flex-wrap gap-2 mt-3"></div>
                </div>

                <!-- Clear Filters -->
                <button id="clearFilters" class="w-full py-2 px-4 rounded-lg border border-gray-300 dark:border-gray-700 text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                    Clear All Filters
                </button>
            </aside>

            <!-- Mobile Filter Sheet -->
            <div id="mobileFilterSheet" class="fixed inset-x-0 bottom-0 bg-white dark:bg-gray-800 rounded-t-3xl shadow-2xl transform translate-y-full transition-transform duration-300 z-50 max-h-[80vh] overflow-y-auto">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-xl font-bold text-[#111318] dark:text-white">Filters</h2>
                        <button id="closeMobileFilters" class="flex items-center justify-center size-8 rounded-full hover:bg-gray-100 dark:hover:bg-gray-700">
                            <span class="material-symbols-outlined text-lg">close</span>
                        </button>
                    </div>
                    
                    <!-- Job Type -->
                    <div class="mb-6">
                        <h3 class="text-sm font-semibold text-[#111318] dark:text-white mb-3">Job Type</h3>
                        <div class="space-y-2">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" name="job_type_mobile" value="full-time" class="rounded border-gray-300 text-primary focus:ring-primary">
                                <span class="text-sm text-gray-700 dark:text-gray-300">Full-time</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" name="job_type_mobile" value="part-time" class="rounded border-gray-300 text-primary focus:ring-primary">
                                <span class="text-sm text-gray-700 dark:text-gray-300">Part-time</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" name="job_type_mobile" value="internship" class="rounded border-gray-300 text-primary focus:ring-primary">
                                <span class="text-sm text-gray-700 dark:text-gray-300">Internship</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" name="job_type_mobile" value="remote" class="rounded border-gray-300 text-primary focus:ring-primary">
                                <span class="text-sm text-gray-700 dark:text-gray-300">Remote</span>
                            </label>
                        </div>
                    </div>

                    <!-- Experience -->
                    <div class="mb-6">
                        <h3 class="text-sm font-semibold text-[#111318] dark:text-white mb-3">Experience</h3>
                        <div class="space-y-2">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" name="experience_mobile" value="fresher" class="rounded border-gray-300 text-primary focus:ring-primary">
                                <span class="text-sm text-gray-700 dark:text-gray-300">Fresher (0-1 yrs)</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" name="experience_mobile" value="junior" class="rounded border-gray-300 text-primary focus:ring-primary">
                                <span class="text-sm text-gray-700 dark:text-gray-300">Junior (2-4 yrs)</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" name="experience_mobile" value="senior" class="rounded border-gray-300 text-primary focus:ring-primary">
                                <span class="text-sm text-gray-700 dark:text-gray-300">Senior (5+ yrs)</span>
                            </label>
                        </div>
                    </div>

                    <!-- Salary Range -->
                    <div class="mb-6">
                        <h3 class="text-sm font-semibold text-[#111318] dark:text-white mb-3">Salary Range</h3>
                        <input 
                            type="range" 
                            id="salaryRangeMobile" 
                            min="0" 
                            max="5000000" 
                            step="100000" 
                            value="0" 
                            class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer dark:bg-gray-700"
                        />
                        <div id="salaryDisplayMobile" class="text-sm font-medium text-primary mt-2 text-center">₹0 - ₹50L+</div>
                    </div>

                    <!-- Date Posted -->
                    <div class="mb-6">
                        <h3 class="text-sm font-semibold text-[#111318] dark:text-white mb-3">Date Posted</h3>
                        <div class="space-y-2">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" name="date_posted_mobile" value="24h" class="rounded border-gray-300 text-primary focus:ring-primary">
                                <span class="text-sm text-gray-700 dark:text-gray-300">Last 24 hours</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" name="date_posted_mobile" value="3d" class="rounded border-gray-300 text-primary focus:ring-primary">
                                <span class="text-sm text-gray-700 dark:text-gray-300">Last 3 days</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" name="date_posted_mobile" value="7d" class="rounded border-gray-300 text-primary focus:ring-primary">
                                <span class="text-sm text-gray-700 dark:text-gray-300">Last week</span>
                            </label>
                        </div>
                    </div>

                    <!-- Company -->
                    <div class="mb-6">
                        <h3 class="text-sm font-semibold text-[#111318] dark:text-white mb-3">Company</h3>
                        <input 
                            type="text" 
                            id="companyFilterMobile" 
                            placeholder="Filter by company" 
                            class="w-full rounded-lg text-[#111318] dark:text-white focus:outline-0 focus:ring-2 focus:ring-primary/50 border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 h-10 px-4 text-sm"
                        />
                    </div>

                    <!-- Skills -->
                    <div class="mb-6">
                        <h3 class="text-sm font-semibold text-[#111318] dark:text-white mb-3">Skills</h3>
                        <input 
                            type="text" 
                            id="skillsFilterMobile" 
                            placeholder="Add skills" 
                            class="w-full rounded-lg text-[#111318] dark:text-white focus:outline-0 focus:ring-2 focus:ring-primary/50 border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 h-10 px-4 text-sm"
                        />
                        <div id="skillsTagsMobile" class="flex flex-wrap gap-2 mt-3"></div>
                    </div>

                    <!-- Clear Filters -->
                    <button id="clearFiltersMobile" class="w-full py-2 px-4 rounded-lg border border-gray-300 dark:border-gray-700 text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                        Clear All Filters
                    </button>
                </div>
            </div>
            <div id="filterOverlay" class="hidden fixed inset-0 bg-black/50 z-40"></div>

            <!-- Job Listings (Right) -->
            <section class="flex-1 p-4 md:p-6">
                <!-- Sort and View Controls -->
                <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
                    <div class="flex items-center gap-2 flex-wrap">
                        <span class="text-sm text-gray-600 dark:text-gray-400">Sort:</span>
                        <select id="sortBy" class="rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-sm px-3 py-2 focus:outline-0 focus:ring-2 focus:ring-primary/50">
                            <option value="relevant">Relevant</option>
                            <option value="newest">Newest</option>
                            <option value="salary_high">Salary High</option>
                            <option value="popular">Popular</option>
                        </select>
                    </div>
                    <div class="flex items-center gap-2">
                        <button id="viewGrid" class="flex items-center justify-center size-9 rounded-lg border border-gray-300 dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                            <span class="material-symbols-outlined text-lg">grid_view</span>
                        </button>
                        <button id="viewList" class="flex items-center justify-center size-9 rounded-lg border border-gray-300 dark:border-gray-700 bg-primary/10 text-primary hover:bg-primary/20 transition-colors">
                            <span class="material-symbols-outlined text-lg">view_list</span>
                        </button>
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
    </div>

    <script>
        const baseUrl = '<?= base_url() ?>';
    </script>
    <script src="<?= base_url('js/jobs.js') ?>"></script>
</body>
</html>

