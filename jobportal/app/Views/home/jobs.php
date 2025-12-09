<?= view('partials/head', ['title' => 'Job Search & Listings - TopTopJobs', 'css_file' => 'css/jobs.css']) ?>
<body class="font-display bg-background-light dark:bg-background-dark text-[#111318] dark:text-gray-200">
    <div class="relative flex min-h-screen w-full flex-col">
        <?= view('partials/header') ?>

        <main class="flex-grow flex flex-col md:flex-row">
            <!-- Filter Panel (Left) -->
            <aside id="filterPanel" class="hidden md:block sticky top-14 md:top-16 w-full md:w-80 lg:w-96 bg-white dark:bg-gray-800/50 border-r border-gray-200 dark:border-gray-700 p-6 h-[calc(100vh-4rem)] overflow-y-auto">
                <div class="mb-6">
                    <h2 class="text-xl font-bold text-[#111318] dark:text-white">All Filters</h2>
                </div>

                <!-- Job Type -->
                <div class="mb-6 filter-section">
                    <button class="filter-section-header w-full flex items-center justify-between py-3 cursor-pointer" onclick="toggleFilterSection(this)">
                        <div class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-lg text-gray-600 dark:text-gray-400">work</span>
                            <h3 class="text-sm font-semibold text-[#111318] dark:text-white">Job Type</h3>
                        </div>
                        <span class="material-symbols-outlined text-lg text-gray-600 dark:text-gray-400 filter-caret transition-transform">expand_less</span>
                    </button>
                    <div class="filter-section-content flex flex-wrap gap-2 mt-2">
                        <button type="button" class="filter-pill-btn px-4 py-2 rounded-full text-sm font-medium transition-colors border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-600" data-filter="job_type" data-value="full-time">
                            Full-time
                        </button>
                        <button type="button" class="filter-pill-btn px-4 py-2 rounded-full text-sm font-medium transition-colors border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-600" data-filter="job_type" data-value="part-time">
                            Part-time
                        </button>
                        <button type="button" class="filter-pill-btn px-4 py-2 rounded-full text-sm font-medium transition-colors border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-600" data-filter="job_type" data-value="internship">
                            Internship
                        </button>
                        <button type="button" class="filter-pill-btn px-4 py-2 rounded-full text-sm font-medium transition-colors border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-600" data-filter="job_type" data-value="remote">
                            Remote
                        </button>
                        <button type="button" class="filter-pill-btn px-4 py-2 rounded-full text-sm font-medium transition-colors border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-600" data-filter="job_type" data-value="contract">
                            Contract
                        </button>
                    </div>
                </div>

                <!-- Experience -->
                <div class="mb-6 filter-section">
                    <button class="filter-section-header w-full flex items-center justify-between py-3 cursor-pointer" onclick="toggleFilterSection(this)">
                        <div class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-lg text-gray-600 dark:text-gray-400">trending_up</span>
                            <h3 class="text-sm font-semibold text-[#111318] dark:text-white">Experience</h3>
                        </div>
                        <span class="material-symbols-outlined text-lg text-gray-600 dark:text-gray-400 filter-caret transition-transform">expand_less</span>
                    </button>
                    <div class="filter-section-content flex flex-wrap gap-2 mt-2">
                        <button type="button" class="filter-pill-btn px-4 py-2 rounded-full text-sm font-medium transition-colors border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-600" data-filter="experience" data-value="fresher">
                            Fresher (0-1 yrs)
                        </button>
                        <button type="button" class="filter-pill-btn px-4 py-2 rounded-full text-sm font-medium transition-colors border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-600" data-filter="experience" data-value="junior">
                            Junior (2-4 yrs)
                        </button>
                        <button type="button" class="filter-pill-btn px-4 py-2 rounded-full text-sm font-medium transition-colors border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-600" data-filter="experience" data-value="senior">
                            Senior (5+ yrs)
                        </button>
                    </div>
                </div>

                <!-- Salary Range -->
                <div class="mb-6 filter-section">
                    <button class="filter-section-header w-full flex items-center justify-between py-3 cursor-pointer" onclick="toggleFilterSection(this)">
                        <div class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-lg text-gray-600 dark:text-gray-400">attach_money</span>
                            <h3 class="text-sm font-semibold text-[#111318] dark:text-white">Salary Range</h3>
                        </div>
                        <span class="material-symbols-outlined text-lg text-gray-600 dark:text-gray-400 filter-caret transition-transform">expand_less</span>
                    </button>
                    <div class="filter-section-content mt-2">
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
                </div>

                <!-- Location -->
                <div class="mb-6 filter-section">
                    <button class="filter-section-header w-full flex items-center justify-between py-3 cursor-pointer" onclick="toggleFilterSection(this)">
                        <div class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-lg text-gray-600 dark:text-gray-400">location_on</span>
                            <h3 class="text-sm font-semibold text-[#111318] dark:text-white">Location</h3>
                        </div>
                        <span class="material-symbols-outlined text-lg text-gray-600 dark:text-gray-400 filter-caret transition-transform">expand_less</span>
                    </button>
                    <div class="filter-section-content mt-2">
                        <input 
                            type="text" 
                            id="locationFilter" 
                            placeholder="Enter city, state, or country"
                            class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-[#111318] dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-primary/50 focus:border-primary transition-colors"
                        />
                        <div class="flex flex-wrap gap-2 mt-3">
                            <button type="button" class="filter-pill-btn px-4 py-2 rounded-full text-sm font-medium transition-colors border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-600" data-filter="location" data-value="Remote">
                                Remote
                            </button>
                            <button type="button" class="filter-pill-btn px-4 py-2 rounded-full text-sm font-medium transition-colors border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-600" data-filter="location" data-value="New York">
                                New York
                            </button>
                            <button type="button" class="filter-pill-btn px-4 py-2 rounded-full text-sm font-medium transition-colors border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-600" data-filter="location" data-value="Los Angeles">
                                Los Angeles
                            </button>
                            <button type="button" class="filter-pill-btn px-4 py-2 rounded-full text-sm font-medium transition-colors border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-600" data-filter="location" data-value="San Francisco">
                                San Francisco
                            </button>
                            <button type="button" class="filter-pill-btn px-4 py-2 rounded-full text-sm font-medium transition-colors border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-600" data-filter="location" data-value="Chicago">
                                Chicago
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Job Category -->
                <div class="mb-6 filter-section">
                    <button class="filter-section-header w-full flex items-center justify-between py-3 cursor-pointer" onclick="toggleFilterSection(this)">
                        <div class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-lg text-gray-600 dark:text-gray-400">category</span>
                            <h3 class="text-sm font-semibold text-[#111318] dark:text-white">Job Category</h3>
                        </div>
                        <span class="material-symbols-outlined text-lg text-gray-600 dark:text-gray-400 filter-caret transition-transform">expand_less</span>
                    </button>
                    <div class="filter-section-content flex flex-wrap gap-2 mt-2">
                        <button type="button" class="filter-pill-btn px-4 py-2 rounded-full text-sm font-medium transition-colors border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-600" data-filter="category" data-value="Cashier">
                            Cashier
                        </button>
                        <button type="button" class="filter-pill-btn px-4 py-2 rounded-full text-sm font-medium transition-colors border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-600" data-filter="category" data-value="Data Entry">
                            Data Entry
                        </button>
                        <button type="button" class="filter-pill-btn px-4 py-2 rounded-full text-sm font-medium transition-colors border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-600" data-filter="category" data-value="IT/Software">
                            IT/Software
                        </button>
                        <button type="button" class="filter-pill-btn px-4 py-2 rounded-full text-sm font-medium transition-colors border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-600" data-filter="category" data-value="Marketing">
                            Marketing
                        </button>
                        <button type="button" class="filter-pill-btn px-4 py-2 rounded-full text-sm font-medium transition-colors border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-600" data-filter="category" data-value="Sales">
                            Sales
                        </button>
                        <button type="button" class="filter-pill-btn px-4 py-2 rounded-full text-sm font-medium transition-colors border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-600" data-filter="category" data-value="Customer Service">
                            Customer Service
                        </button>
                        <button type="button" class="filter-pill-btn px-4 py-2 rounded-full text-sm font-medium transition-colors border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-600" data-filter="category" data-value="Design">
                            Design
                        </button>
                        <button type="button" class="filter-pill-btn px-4 py-2 rounded-full text-sm font-medium transition-colors border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-600" data-filter="category" data-value="Engineering">
                            Engineering
                        </button>
                        <button type="button" class="filter-pill-btn px-4 py-2 rounded-full text-sm font-medium transition-colors border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-600" data-filter="category" data-value="Finance">
                            Finance
                        </button>
                        <button type="button" class="filter-pill-btn px-4 py-2 rounded-full text-sm font-medium transition-colors border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-600" data-filter="category" data-value="Healthcare">
                            Healthcare
                        </button>
                        <button type="button" class="filter-pill-btn px-4 py-2 rounded-full text-sm font-medium transition-colors border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-600" data-filter="category" data-value="Education">
                            Education
                        </button>
                        <button type="button" class="filter-pill-btn px-4 py-2 rounded-full text-sm font-medium transition-colors border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-600" data-filter="category" data-value="Other">
                            Other
                        </button>
                    </div>
                </div>

                <!-- Date Posted -->
                <div class="mb-6 filter-section">
                    <button class="filter-section-header w-full flex items-center justify-between py-3 cursor-pointer" onclick="toggleFilterSection(this)">
                        <div class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-lg text-gray-600 dark:text-gray-400">schedule</span>
                            <h3 class="text-sm font-semibold text-[#111318] dark:text-white">Date Posted</h3>
                        </div>
                        <span class="material-symbols-outlined text-lg text-gray-600 dark:text-gray-400 filter-caret transition-transform">expand_less</span>
                    </button>
                    <div class="filter-section-content flex flex-wrap gap-2 mt-2">
                        <button type="button" class="filter-pill-btn px-4 py-2 rounded-full text-sm font-medium transition-colors border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-600" data-filter="date_posted" data-value="24h">
                            Last 24 hours
                        </button>
                        <button type="button" class="filter-pill-btn px-4 py-2 rounded-full text-sm font-medium transition-colors border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-600" data-filter="date_posted" data-value="3d">
                            Last 3 days
                        </button>
                        <button type="button" class="filter-pill-btn px-4 py-2 rounded-full text-sm font-medium transition-colors border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-600" data-filter="date_posted" data-value="7d">
                            Last week
                        </button>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex gap-3 mt-auto pt-4 border-t border-gray-200 dark:border-gray-700">
                    <button id="clearFilters" class="w-full py-2.5 px-4 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors cursor-pointer">
                        Clear All Filters
                    </button>
                </div>
            </aside>

            <!-- Mobile Filter Sheet -->
            <div id="mobileFilterSheet" class="fixed inset-x-0 bottom-0 bg-white dark:bg-gray-800 rounded-t-3xl shadow-2xl transform translate-y-full transition-transform duration-300 z-50 max-h-[80vh] overflow-y-auto">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-xl font-bold text-[#111318] dark:text-white">All Filters</h2>
                        <button id="closeMobileFilters" class="flex items-center justify-center size-8 rounded-full hover:bg-gray-100 dark:hover:bg-gray-700 cursor-pointer transition-colors">
                            <span class="material-symbols-outlined text-lg">close</span>
                        </button>
                    </div>
                    
                    <!-- Job Type -->
                    <div class="mb-6 filter-section">
                        <button class="filter-section-header w-full flex items-center justify-between py-3 cursor-pointer" onclick="toggleFilterSection(this)">
                            <div class="flex items-center gap-2">
                                <span class="material-symbols-outlined text-lg text-gray-600 dark:text-gray-400">work</span>
                                <h3 class="text-sm font-semibold text-[#111318] dark:text-white">Job Type</h3>
                            </div>
                            <span class="material-symbols-outlined text-lg text-gray-600 dark:text-gray-400 filter-caret transition-transform">expand_less</span>
                        </button>
                        <div class="filter-section-content flex flex-wrap gap-2 mt-2">
                            <button type="button" class="filter-pill-btn px-4 py-2 rounded-full text-sm font-medium transition-colors border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-600" data-filter="job_type" data-value="full-time">
                                Full-time
                            </button>
                            <button type="button" class="filter-pill-btn px-4 py-2 rounded-full text-sm font-medium transition-colors border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-600" data-filter="job_type" data-value="part-time">
                                Part-time
                            </button>
                            <button type="button" class="filter-pill-btn px-4 py-2 rounded-full text-sm font-medium transition-colors border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-600" data-filter="job_type" data-value="internship">
                                Internship
                            </button>
                            <button type="button" class="filter-pill-btn px-4 py-2 rounded-full text-sm font-medium transition-colors border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-600" data-filter="job_type" data-value="remote">
                                Remote
                            </button>
                            <button type="button" class="filter-pill-btn px-4 py-2 rounded-full text-sm font-medium transition-colors border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-600" data-filter="job_type" data-value="contract">
                                Contract
                            </button>
                        </div>
                    </div>

                    <!-- Experience -->
                    <div class="mb-6 filter-section">
                        <button class="filter-section-header w-full flex items-center justify-between py-3 cursor-pointer" onclick="toggleFilterSection(this)">
                            <div class="flex items-center gap-2">
                                <span class="material-symbols-outlined text-lg text-gray-600 dark:text-gray-400">trending_up</span>
                                <h3 class="text-sm font-semibold text-[#111318] dark:text-white">Experience</h3>
                            </div>
                            <span class="material-symbols-outlined text-lg text-gray-600 dark:text-gray-400 filter-caret transition-transform">expand_less</span>
                        </button>
                        <div class="filter-section-content flex flex-wrap gap-2 mt-2">
                            <button type="button" class="filter-pill-btn px-4 py-2 rounded-full text-sm font-medium transition-colors border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-600" data-filter="experience" data-value="fresher">
                                Fresher (0-1 yrs)
                            </button>
                            <button type="button" class="filter-pill-btn px-4 py-2 rounded-full text-sm font-medium transition-colors border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-600" data-filter="experience" data-value="junior">
                                Junior (2-4 yrs)
                            </button>
                            <button type="button" class="filter-pill-btn px-4 py-2 rounded-full text-sm font-medium transition-colors border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-600" data-filter="experience" data-value="senior">
                                Senior (5+ yrs)
                            </button>
                        </div>
                    </div>

                    <!-- Salary Range -->
                    <div class="mb-6 filter-section">
                        <button class="filter-section-header w-full flex items-center justify-between py-3 cursor-pointer" onclick="toggleFilterSection(this)">
                            <div class="flex items-center gap-2">
                                <span class="material-symbols-outlined text-lg text-gray-600 dark:text-gray-400">attach_money</span>
                                <h3 class="text-sm font-semibold text-[#111318] dark:text-white">Salary Range</h3>
                            </div>
                            <span class="material-symbols-outlined text-lg text-gray-600 dark:text-gray-400 filter-caret transition-transform">expand_less</span>
                        </button>
                        <div class="filter-section-content mt-2">
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
                    </div>

                    <!-- Location -->
                    <div class="mb-6 filter-section">
                        <button class="filter-section-header w-full flex items-center justify-between py-3 cursor-pointer" onclick="toggleFilterSection(this)">
                            <div class="flex items-center gap-2">
                                <span class="material-symbols-outlined text-lg text-gray-600 dark:text-gray-400">location_on</span>
                                <h3 class="text-sm font-semibold text-[#111318] dark:text-white">Location</h3>
                            </div>
                            <span class="material-symbols-outlined text-lg text-gray-600 dark:text-gray-400 filter-caret transition-transform">expand_less</span>
                        </button>
                        <div class="filter-section-content mt-2">
                            <input 
                                type="text" 
                                id="locationFilterMobile" 
                                placeholder="Enter city, state, or country"
                                class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-[#111318] dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-primary/50 focus:border-primary transition-colors"
                            />
                            <div class="flex flex-wrap gap-2 mt-3">
                                <button type="button" class="filter-pill-btn px-4 py-2 rounded-full text-sm font-medium transition-colors border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-600" data-filter="location" data-value="Remote">
                                    Remote
                                </button>
                                <button type="button" class="filter-pill-btn px-4 py-2 rounded-full text-sm font-medium transition-colors border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-600" data-filter="location" data-value="New York">
                                    New York
                                </button>
                                <button type="button" class="filter-pill-btn px-4 py-2 rounded-full text-sm font-medium transition-colors border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-600" data-filter="location" data-value="Los Angeles">
                                    Los Angeles
                                </button>
                                <button type="button" class="filter-pill-btn px-4 py-2 rounded-full text-sm font-medium transition-colors border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-600" data-filter="location" data-value="San Francisco">
                                    San Francisco
                                </button>
                                <button type="button" class="filter-pill-btn px-4 py-2 rounded-full text-sm font-medium transition-colors border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-600" data-filter="location" data-value="Chicago">
                                    Chicago
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Job Category -->
                    <div class="mb-6 filter-section">
                        <button class="filter-section-header w-full flex items-center justify-between py-3 cursor-pointer" onclick="toggleFilterSection(this)">
                            <div class="flex items-center gap-2">
                                <span class="material-symbols-outlined text-lg text-gray-600 dark:text-gray-400">category</span>
                                <h3 class="text-sm font-semibold text-[#111318] dark:text-white">Job Category</h3>
                            </div>
                            <span class="material-symbols-outlined text-lg text-gray-600 dark:text-gray-400 filter-caret transition-transform">expand_less</span>
                        </button>
                        <div class="filter-section-content flex flex-wrap gap-2 mt-2">
                            <button type="button" class="filter-pill-btn px-4 py-2 rounded-full text-sm font-medium transition-colors border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-600" data-filter="category" data-value="Cashier">
                                Cashier
                            </button>
                            <button type="button" class="filter-pill-btn px-4 py-2 rounded-full text-sm font-medium transition-colors border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-600" data-filter="category" data-value="Data Entry">
                                Data Entry
                            </button>
                            <button type="button" class="filter-pill-btn px-4 py-2 rounded-full text-sm font-medium transition-colors border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-600" data-filter="category" data-value="IT/Software">
                                IT/Software
                            </button>
                            <button type="button" class="filter-pill-btn px-4 py-2 rounded-full text-sm font-medium transition-colors border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-600" data-filter="category" data-value="Marketing">
                                Marketing
                            </button>
                            <button type="button" class="filter-pill-btn px-4 py-2 rounded-full text-sm font-medium transition-colors border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-600" data-filter="category" data-value="Sales">
                                Sales
                            </button>
                            <button type="button" class="filter-pill-btn px-4 py-2 rounded-full text-sm font-medium transition-colors border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-600" data-filter="category" data-value="Customer Service">
                                Customer Service
                            </button>
                            <button type="button" class="filter-pill-btn px-4 py-2 rounded-full text-sm font-medium transition-colors border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-600" data-filter="category" data-value="Design">
                                Design
                            </button>
                            <button type="button" class="filter-pill-btn px-4 py-2 rounded-full text-sm font-medium transition-colors border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-600" data-filter="category" data-value="Engineering">
                                Engineering
                            </button>
                            <button type="button" class="filter-pill-btn px-4 py-2 rounded-full text-sm font-medium transition-colors border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-600" data-filter="category" data-value="Finance">
                                Finance
                            </button>
                            <button type="button" class="filter-pill-btn px-4 py-2 rounded-full text-sm font-medium transition-colors border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-600" data-filter="category" data-value="Healthcare">
                                Healthcare
                            </button>
                            <button type="button" class="filter-pill-btn px-4 py-2 rounded-full text-sm font-medium transition-colors border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-600" data-filter="category" data-value="Education">
                                Education
                            </button>
                            <button type="button" class="filter-pill-btn px-4 py-2 rounded-full text-sm font-medium transition-colors border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-600" data-filter="category" data-value="Other">
                                Other
                            </button>
                        </div>
                    </div>

                    <!-- Date Posted -->
                    <div class="mb-6 filter-section">
                        <button class="filter-section-header w-full flex items-center justify-between py-3 cursor-pointer" onclick="toggleFilterSection(this)">
                            <div class="flex items-center gap-2">
                                <span class="material-symbols-outlined text-lg text-gray-600 dark:text-gray-400">schedule</span>
                                <h3 class="text-sm font-semibold text-[#111318] dark:text-white">Date Posted</h3>
                            </div>
                            <span class="material-symbols-outlined text-lg text-gray-600 dark:text-gray-400 filter-caret transition-transform">expand_less</span>
                        </button>
                        <div class="filter-section-content flex flex-wrap gap-2 mt-2">
                            <button type="button" class="filter-pill-btn px-4 py-2 rounded-full text-sm font-medium transition-colors border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-600" data-filter="date_posted" data-value="24h">
                                Last 24 hours
                            </button>
                            <button type="button" class="filter-pill-btn px-4 py-2 rounded-full text-sm font-medium transition-colors border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-600" data-filter="date_posted" data-value="3d">
                                Last 3 days
                            </button>
                            <button type="button" class="filter-pill-btn px-4 py-2 rounded-full text-sm font-medium transition-colors border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-600" data-filter="date_posted" data-value="7d">
                                Last week
                            </button>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex gap-3 mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                        <button id="clearFiltersMobile" class="w-full py-2.5 px-4 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors cursor-pointer">
                            Clear All Filters
                        </button>
                    </div>
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
                <div id="jobListings" class="w-full">
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
        
        // Toggle filter section expand/collapse
        function toggleFilterSection(button) {
            const section = button.closest('.filter-section');
            const content = section.querySelector('.filter-section-content');
            const caret = button.querySelector('.filter-caret');
            
            if (content.style.display === 'none') {
                content.style.display = '';
                caret.style.transform = 'rotate(0deg)';
                caret.textContent = 'expand_less';
            } else {
                content.style.display = 'none';
                caret.style.transform = 'rotate(180deg)';
                caret.textContent = 'expand_more';
            }
        }
        
        // Initialize filter sections as expanded
        document.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('.filter-section-content').forEach(content => {
                content.style.display = '';
            });
        });
        
        // Handle filter pill button clicks
        document.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('.filter-pill-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    // Toggle active state
                    const isActive = this.classList.contains('active');
                    
                    if (isActive) {
                        // Deselect
                        this.classList.remove('active');
                        this.classList.remove('bg-gray-200', 'dark:bg-gray-600');
                        this.classList.add('bg-white', 'dark:bg-gray-700');
                    } else {
                        // Select
                        this.classList.add('active');
                        this.classList.remove('bg-white', 'dark:bg-gray-700');
                        this.classList.add('bg-gray-200', 'dark:bg-gray-600');
                    }
                });
            });
        });
    </script>
    <script src="<?= base_url('js/jobs.js') ?>"></script>
</body>
</html>

