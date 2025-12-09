<?= view('partials/head', ['title' => 'Job Details - JobFind']) ?>
<body class="font-display bg-background-light dark:bg-background-dark text-[#111318] dark:text-gray-200">
    <div class="relative flex h-auto min-h-screen w-full flex-col group/design-root overflow-x-hidden">
        <?= view('partials/header') ?>

        <main class="layout-container flex h-full grow flex-col">
            <div class="container mx-auto px-4 py-8">
                <!-- Breadcrumbs -->
                <div class="flex flex-wrap gap-2 mb-8">
                    <a class="text-primary/80 dark:text-primary/60 text-sm font-medium leading-normal hover:text-primary dark:hover:text-primary/80 transition-colors" href="<?= base_url('/') ?>">Home</a>
                    <span class="text-primary/50 text-sm font-medium leading-normal">/</span>
                    <a class="text-primary/80 dark:text-primary/60 text-sm font-medium leading-normal hover:text-primary dark:hover:text-primary/80 transition-colors" href="<?= base_url('jobs') ?>">Search Results</a>
                    <span class="text-primary/50 text-sm font-medium leading-normal">/</span>
                    <span class="text-[#111318] dark:text-gray-300 text-sm font-medium leading-normal" id="jobTitleBreadcrumb">Job Details</span>
                </div>

                <!-- Loading State -->
                <div id="loadingState" class="text-center py-12">
                    <div class="inline-block animate-spin rounded-full h-12 w-12 border-4 border-primary border-t-transparent mb-4"></div>
                    <p class="text-gray-600 dark:text-gray-400">Loading job details...</p>
                </div>

                <!-- Error State -->
                <div id="errorState" class="hidden text-center py-12">
                    <span class="material-symbols-outlined text-6xl text-red-400 mb-4">error</span>
                    <h3 class="text-xl font-semibold text-red-600 dark:text-red-400 mb-2">Job Not Found</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-500 mb-4">The job you're looking for doesn't exist or has been removed.</p>
                    <a href="<?= base_url('jobs') ?>" class="inline-block px-6 py-3 rounded-full bg-primary text-white hover:bg-primary/90 transition-colors">
                        Browse All Jobs
                    </a>
                </div>

                <!-- Job Details Content -->
                <div id="jobDetailsContent" class="hidden">
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
                        <!-- Left Column (Main Content) -->
                        <div class="lg:col-span-2 flex flex-col gap-8">
                            <!-- Job Title Card -->
                            <div class="flex flex-col sm:flex-row items-start gap-6 p-6 border border-primary/20 dark:border-primary/10 rounded-lg bg-white dark:bg-gray-800/50">
                                <div id="companyLogo" class="size-20 bg-center bg-no-repeat aspect-square bg-cover rounded-lg border border-primary/10 dark:border-primary/5" style="background-color: #f0f0f0;"></div>
                                <div class="flex-grow">
                                    <h1 id="jobTitle" class="text-2xl sm:text-3xl font-bold leading-tight tracking-[-0.015em] text-[#111318] dark:text-white mb-2"></h1>
                                    <div class="flex items-center flex-wrap gap-x-4 gap-y-1 text-primary/80 dark:text-primary/60">
                                        <p id="companyName" class="text-base font-normal leading-normal"></p>
                                        <span class="hidden sm:inline">·</span>
                                        <p id="jobLocation" class="text-base font-normal leading-normal"></p>
                                        <span class="hidden sm:inline">·</span>
                                        <p id="postedDate" class="text-base font-normal leading-normal"></p>
                                    </div>
                                </div>
                            </div>

                            <!-- ButtonGroup -->
                            <div class="flex flex-1 gap-3 flex-wrap">
                                <button id="saveJobBtn" class="flex min-w-[84px] max-w-[480px] cursor-pointer items-center justify-center gap-2 overflow-hidden rounded-full h-10 px-5 bg-primary/20 dark:bg-primary/10 text-[#111318] dark:text-gray-200 text-sm font-bold leading-normal tracking-[0.015em] hover:bg-primary/30 dark:hover:bg-primary/20 transition-colors">
                                    <span class="material-symbols-outlined text-base">bookmark</span>
                                    <span class="truncate">Save Job</span>
                                </button>
                                <button id="shareJobBtn" class="flex min-w-[84px] max-w-[480px] cursor-pointer items-center justify-center gap-2 overflow-hidden rounded-full h-10 px-5 bg-transparent border border-primary/20 dark:border-primary/10 text-[#111318] dark:text-gray-200 text-sm font-bold leading-normal tracking-[0.015em] hover:bg-primary/10 dark:hover:bg-primary/5 transition-colors">
                                    <span class="material-symbols-outlined text-base">share</span>
                                    <span class="truncate">Share</span>
                                </button>
                            </div>

                            <!-- Accordions for Job Details -->
                            <div class="flex flex-col border border-primary/20 dark:border-primary/10 rounded-lg overflow-hidden bg-white dark:bg-gray-800/50">
                                <!-- Job Description -->
                                <details class="flex flex-col border-b border-primary/20 dark:border-primary/10 group" open>
                                    <summary class="flex cursor-pointer items-center justify-between gap-6 p-4 bg-primary/10 dark:bg-primary/5 hover:bg-primary/20 dark:hover:bg-primary/10 transition-colors">
                                        <p class="text-[#111318] dark:text-gray-200 text-base font-bold leading-normal">Job Description</p>
                                        <span class="material-symbols-outlined text-[#111318] dark:text-gray-200 group-open:rotate-180 transition-transform">expand_more</span>
                                    </summary>
                                    <div class="p-4">
                                        <p id="jobDescription" class="text-[#4c9a6b] dark:text-gray-400 text-sm font-normal leading-relaxed whitespace-pre-line"></p>
                                    </div>
                                </details>

                                <!-- Responsibilities -->
                                <details class="flex flex-col border-b border-primary/20 dark:border-primary/10 group" id="responsibilitiesSection">
                                    <summary class="flex cursor-pointer items-center justify-between gap-6 p-4 bg-primary/10 dark:bg-primary/5 hover:bg-primary/20 dark:hover:bg-primary/10 transition-colors">
                                        <p class="text-[#111318] dark:text-gray-200 text-base font-bold leading-normal">Responsibilities</p>
                                        <span class="material-symbols-outlined text-[#111318] dark:text-gray-200 group-open:rotate-180 transition-transform">expand_more</span>
                                    </summary>
                                    <div class="p-4">
                                        <ul id="responsibilitiesList" class="text-[#4c9a6b] dark:text-gray-400 text-sm font-normal leading-relaxed list-disc pl-5 space-y-2"></ul>
                                    </div>
                                </details>

                                <!-- Requirements -->
                                <details class="flex flex-col border-b border-primary/20 dark:border-primary/10 group" id="requirementsSection">
                                    <summary class="flex cursor-pointer items-center justify-between gap-6 p-4 bg-primary/10 dark:bg-primary/5 hover:bg-primary/20 dark:hover:bg-primary/10 transition-colors">
                                        <p class="text-[#111318] dark:text-gray-200 text-base font-bold leading-normal">Requirements</p>
                                        <span class="material-symbols-outlined text-[#111318] dark:text-gray-200 group-open:rotate-180 transition-transform">expand_more</span>
                                    </summary>
                                    <div class="p-4">
                                        <ul id="requirementsList" class="text-[#4c9a6b] dark:text-gray-400 text-sm font-normal leading-relaxed list-disc pl-5 space-y-2"></ul>
                                    </div>
                                </details>

                                <!-- Skills -->
                                <details class="flex flex-col border-b border-primary/20 dark:border-primary/10 group" id="skillsSection">
                                    <summary class="flex cursor-pointer items-center justify-between gap-6 p-4 bg-primary/10 dark:bg-primary/5 hover:bg-primary/20 dark:hover:bg-primary/10 transition-colors">
                                        <p class="text-[#111318] dark:text-gray-200 text-base font-bold leading-normal">Required Skills</p>
                                        <span class="material-symbols-outlined text-[#111318] dark:text-gray-200 group-open:rotate-180 transition-transform">expand_more</span>
                                    </summary>
                                    <div class="p-4">
                                        <div id="skillsContainer" class="flex flex-wrap gap-2"></div>
                                    </div>
                                </details>

                                <!-- About the Company -->
                                <details class="flex flex-col group" id="companySection">
                                    <summary class="flex cursor-pointer items-center justify-between gap-6 p-4 bg-primary/10 dark:bg-primary/5 hover:bg-primary/20 dark:hover:bg-primary/10 transition-colors">
                                        <p class="text-[#111318] dark:text-gray-200 text-base font-bold leading-normal">About the Company</p>
                                        <span class="material-symbols-outlined text-[#111318] dark:text-gray-200 group-open:rotate-180 transition-transform">expand_more</span>
                                    </summary>
                                    <div class="p-4">
                                        <p id="companyDescription" class="text-[#4c9a6b] dark:text-gray-400 text-sm font-normal leading-relaxed"></p>
                                    </div>
                                </details>
                            </div>

                            <!-- Location Section -->
                            <div id="locationSection">
                                <h3 class="text-xl font-bold mb-4 text-[#111318] dark:text-white">Location</h3>
                                <div class="aspect-video w-full rounded-lg overflow-hidden border border-primary/20 dark:border-primary/10 bg-gray-100 dark:bg-gray-800">
                                    <div id="mapContainer" class="w-full h-full flex items-center justify-center text-gray-400">
                                        <div class="text-center">
                                            <span class="material-symbols-outlined text-4xl mb-2">location_on</span>
                                            <p id="locationText" class="text-sm"></p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Related Jobs Section -->
                            <div id="relatedJobsSection">
                                <h3 class="text-xl font-bold mb-4 text-[#111318] dark:text-white">Related Jobs</h3>
                                <div id="relatedJobsContainer" class="flex gap-4 overflow-x-auto pb-4 -mx-4 px-4">
                                    <!-- Related jobs will be loaded here -->
                                </div>
                            </div>
                        </div>

                        <!-- Right Column (Sticky Sidebar) -->
                        <div class="lg:col-span-1">
                            <div class="sticky top-28 flex flex-col gap-6">
                                <!-- Apply Card -->
                                <div class="border border-primary/20 dark:border-primary/10 rounded-lg p-6 flex flex-col gap-4 bg-white dark:bg-gray-800/50">
                                    <button id="applyNowBtn" class="w-full flex min-w-[84px] max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-full h-12 px-6 bg-primary text-[#0d1b13] text-base font-bold leading-normal tracking-[0.015em] hover:bg-primary/90 transition-colors">
                                        <span class="truncate">Apply Now</span>
                                    </button>
                                    <div class="space-y-3 pt-2">
                                        <div class="flex justify-between items-center">
                                            <span class="text-sm font-medium text-primary/80 dark:text-primary/60">Salary Range</span>
                                            <span id="salaryRange" class="text-sm font-semibold text-[#111318] dark:text-gray-200">Not disclosed</span>
                                        </div>
                                        <div class="flex justify-between items-center">
                                            <span class="text-sm font-medium text-primary/80 dark:text-primary/60">Job Type</span>
                                            <span id="jobType" class="text-sm font-semibold text-[#111318] dark:text-gray-200"></span>
                                        </div>
                                        <div class="flex justify-between items-center">
                                            <span class="text-sm font-medium text-primary/80 dark:text-primary/60">Experience</span>
                                            <span id="experienceLevel" class="text-sm font-semibold text-[#111318] dark:text-gray-200"></span>
                                        </div>
                                        <div class="flex justify-between items-center">
                                            <span class="text-sm font-medium text-primary/80 dark:text-primary/60">Location</span>
                                            <span id="locationType" class="text-sm font-semibold text-[#111318] dark:text-gray-200"></span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Company Card -->
                                <div class="border border-primary/20 dark:border-primary/10 rounded-lg p-6 flex flex-col items-center text-center bg-white dark:bg-gray-800/50">
                                    <div id="companyLogoCard" class="size-16 bg-center bg-no-repeat aspect-square bg-cover rounded-md mb-4 border border-primary/10 dark:border-primary/5" style="background-color: #f0f0f0;"></div>
                                    <h4 id="companyNameCard" class="font-bold text-lg text-[#111318] dark:text-white"></h4>
                                    <p id="companyDescriptionCard" class="text-sm text-primary/80 dark:text-primary/60 mt-1"></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>

        <?= view('partials/footer') ?>
    </div>

    <script>
        const baseUrl = '<?= base_url() ?>';
        const apiUrl = '<?= base_url('api/jobs.php') ?>';
        
        // Extract job ID from URL - support both query param and slug format
        function getJobIdFromUrl() {
            // Try query parameter first
            const queryId = new URLSearchParams(window.location.search).get('id');
            if (queryId) {
                return parseInt(queryId);
            }
            
            // Extract from slug format: /job/company-title-id or /job/company-title-id/
            const path = window.location.pathname;
            const slugMatch = path.match(/\/job\/([^\/]+)/);
            if (slugMatch) {
                const slug = slugMatch[1];
                // Extract ID from end of slug (e.g., "google-senior-product-designer-1" -> 1)
                const idMatch = slug.match(/-(\d+)$/);
                if (idMatch) {
                    return parseInt(idMatch[1]);
                }
            }
            
            return null;
        }
        
        const jobId = getJobIdFromUrl();
        
        // Load job details
        async function loadJobDetails() {
            if (!jobId) {
                showError();
                return;
            }

            try {
                const response = await fetch(`${apiUrl}?id=${jobId}`);
                const data = await response.json();

                if (data.success && data.job) {
                    displayJobDetails(data.job);
                } else {
                    showError();
                }
            } catch (error) {
                console.error('Error loading job details:', error);
                showError();
            }
        }

        function displayJobDetails(job) {
            // Hide loading, show content
            document.getElementById('loadingState').classList.add('hidden');
            document.getElementById('jobDetailsContent').classList.remove('hidden');

            // Update breadcrumb
            document.getElementById('jobTitleBreadcrumb').textContent = job.title;

            // Company logo
            const companyLogo = job.company_logo || 'https://via.placeholder.com/80';
            document.getElementById('companyLogo').style.backgroundImage = `url("${companyLogo}")`;
            document.getElementById('companyLogoCard').style.backgroundImage = `url("${companyLogo}")`;

            // Job title and company
            document.getElementById('jobTitle').textContent = job.title;
            document.getElementById('companyName').textContent = job.company_name;
            document.getElementById('companyNameCard').textContent = job.company_name;

            // Location and date
            document.getElementById('jobLocation').textContent = job.location;
            document.getElementById('locationText').textContent = job.location;
            const postedDate = new Date(job.posted_at);
            const daysAgo = Math.floor((Date.now() - postedDate.getTime()) / (1000 * 60 * 60 * 24));
            document.getElementById('postedDate').textContent = `Posted ${daysAgo === 0 ? 'today' : daysAgo === 1 ? '1 day ago' : `${daysAgo} days ago`}`;

            // Description
            document.getElementById('jobDescription').textContent = job.description || 'No description available.';

            // Responsibilities
            if (job.responsibilities) {
                const responsibilities = Array.isArray(job.responsibilities) 
                    ? job.responsibilities 
                    : job.responsibilities.split('\n').filter(r => r.trim());
                const responsibilitiesList = document.getElementById('responsibilitiesList');
                responsibilitiesList.innerHTML = responsibilities.map(r => `<li>${r.trim()}</li>`).join('');
            } else {
                document.getElementById('responsibilitiesSection').classList.add('hidden');
            }

            // Requirements
            if (job.requirements) {
                const requirements = Array.isArray(job.requirements) 
                    ? job.requirements 
                    : job.requirements.split('\n').filter(r => r.trim());
                const requirementsList = document.getElementById('requirementsList');
                requirementsList.innerHTML = requirements.map(r => `<li>${r.trim()}</li>`).join('');
            } else {
                document.getElementById('requirementsSection').classList.add('hidden');
            }

            // Skills
            if (job.skills && job.skills.length > 0) {
                const skillsContainer = document.getElementById('skillsContainer');
                skillsContainer.innerHTML = job.skills.map(skill => 
                    `<span class="inline-flex items-center rounded-full bg-primary/20 dark:bg-primary/10 px-3 py-1 text-xs font-medium text-primary/80 dark:text-primary/60">${skill}</span>`
                ).join('');
            } else {
                document.getElementById('skillsSection').classList.add('hidden');
            }

            // Company description
            if (job.company_description) {
                document.getElementById('companyDescription').textContent = job.company_description;
                document.getElementById('companyDescriptionCard').textContent = job.company_description;
            } else {
                document.getElementById('companySection').classList.add('hidden');
            }

            // Sidebar info
            if (job.salary_min && job.salary_max) {
                const salary = `$${(job.salary_min / 1000).toFixed(0)}k - $${(job.salary_max / 1000).toFixed(0)}k`;
                document.getElementById('salaryRange').textContent = salary;
            } else if (job.salary) {
                document.getElementById('salaryRange').textContent = `$${(job.salary / 1000).toFixed(0)}k`;
            }

            document.getElementById('jobType').textContent = formatJobType(job.job_type);
            document.getElementById('experienceLevel').textContent = formatExperience(job.experience_level || job.experience);
            document.getElementById('locationType').textContent = job.is_remote ? 'Remote' : 'On-site';

            // Save job button
            const saveBtn = document.getElementById('saveJobBtn');
            const isSaved = localStorage.getItem(`saved_job_${job.id}`) === 'true';
            if (isSaved) {
                saveBtn.innerHTML = '<span class="material-symbols-outlined text-base">bookmark</span><span class="truncate">Saved</span>';
                saveBtn.classList.add('bg-primary', 'text-white');
                saveBtn.classList.remove('bg-primary/20', 'text-[#111318]');
            }
            saveBtn.addEventListener('click', () => toggleSaveJob(job.id, saveBtn));

            // Share button
            document.getElementById('shareJobBtn').addEventListener('click', () => shareJob(job));

            // Apply button
            document.getElementById('applyNowBtn').addEventListener('click', () => {
                // Check if user is logged in
                <?php if (session()->get('is_logged_in')): ?>
                    // Redirect to application page or show modal
                    window.location.href = `<?= base_url('apply') ?>?job_id=${job.id}`;
                <?php else: ?>
                    // Redirect to login
                    window.location.href = `<?= base_url('login') ?>?redirect=apply&job_id=${job.id}`;
                <?php endif; ?>
            });

            // Load related jobs
            loadRelatedJobs(job);
        }

        function formatJobType(type) {
            const types = {
                'full-time': 'Full-time',
                'part-time': 'Part-time',
                'contract': 'Contract',
                'internship': 'Internship',
                'remote': 'Remote',
                'freelance': 'Freelance'
            };
            return types[type] || type;
        }

        function formatExperience(level) {
            const levels = {
                'fresher': '0-1 years',
                'junior': '2-4 years',
                'mid': '3-5 years',
                'senior': '5+ years',
                'lead': '8+ years'
            };
            return levels[level] || level || 'Not specified';
        }

        function toggleSaveJob(jobId, btn) {
            const isSaved = localStorage.getItem(`saved_job_${jobId}`) === 'true';
            if (isSaved) {
                localStorage.removeItem(`saved_job_${jobId}`);
                btn.innerHTML = '<span class="material-symbols-outlined text-base">bookmark</span><span class="truncate">Save Job</span>';
                btn.classList.remove('bg-primary', 'text-white');
                btn.classList.add('bg-primary/20', 'text-[#111318]');
            } else {
                localStorage.setItem(`saved_job_${jobId}`, 'true');
                btn.innerHTML = '<span class="material-symbols-outlined text-base">bookmark</span><span class="truncate">Saved</span>';
                btn.classList.add('bg-primary', 'text-white');
                btn.classList.remove('bg-primary/20', 'text-[#111318]');
            }
        }

        function shareJob(job) {
            // Generate slug URL
            const jobSlug = job.slug || (job.company_name.toLowerCase().replace(/[^a-z0-9]+/g, '-') + '-' + job.title.toLowerCase().replace(/[^a-z0-9]+/g, '-') + '-' + job.id);
            const shareUrl = `${baseUrl}/job/${jobSlug}/`;
            
            if (navigator.share) {
                navigator.share({
                    title: job.title,
                    text: `Check out this job: ${job.title} at ${job.company_name}`,
                    url: shareUrl
                });
            } else {
                // Fallback: copy to clipboard
                navigator.clipboard.writeText(shareUrl);
                alert('Link copied to clipboard!');
            }
        }

        async function loadRelatedJobs(currentJob) {
            try {
                const response = await fetch(`${apiUrl}?company=${encodeURIComponent(currentJob.company_name)}&per_page=3`);
                const data = await response.json();

                if (data.success && data.jobs) {
                    const relatedJobs = data.jobs.filter(j => j.id !== currentJob.id).slice(0, 3);
                    const container = document.getElementById('relatedJobsContainer');
                    
                    if (relatedJobs.length === 0) {
                        document.getElementById('relatedJobsSection').classList.add('hidden');
                        return;
                    }

                    container.innerHTML = relatedJobs.map(job => {
                        const jobSlug = job.slug || (job.company_name.toLowerCase().replace(/[^a-z0-9]+/g, '-') + '-' + job.title.toLowerCase().replace(/[^a-z0-9]+/g, '-') + '-' + job.id);
                        return `
                        <div class="flex-shrink-0 w-72 border border-primary/20 dark:border-primary/10 rounded-lg p-4 bg-white dark:bg-gray-800/50 hover:border-primary/50 dark:hover:border-primary/30 transition-colors cursor-pointer" onclick="window.location.href='<?= base_url('job') ?>/${jobSlug}/'">
                            <div class="flex flex-col items-stretch justify-start">
                                <div class="w-full bg-center bg-no-repeat aspect-video bg-cover rounded-md mb-4 border border-primary/10 dark:border-primary/5" style="background-image: url('${job.company_logo || 'https://via.placeholder.com/300'}'); background-size: contain; background-position: center;"></div>
                                <div class="flex w-full flex-col items-stretch justify-center gap-1">
                                    <p class="text-[#111318] dark:text-white text-md font-bold leading-tight">${job.title}</p>
                                    <p class="text-primary/80 dark:text-primary/60 text-sm font-normal leading-normal">${job.company_name} - ${job.location}</p>
                                    <p class="text-primary/60 dark:text-primary/40 text-xs font-normal leading-normal">${getTimeAgo(job.posted_at)}</p>
                                </div>
                                </div>
                            </div>
                    `;
                    }).join('');
                } else {
                    document.getElementById('relatedJobsSection').classList.add('hidden');
                }
            } catch (error) {
                console.error('Error loading related jobs:', error);
                document.getElementById('relatedJobsSection').classList.add('hidden');
            }
        }

        function getTimeAgo(dateString) {
            const date = new Date(dateString);
            const daysAgo = Math.floor((Date.now() - date.getTime()) / (1000 * 60 * 60 * 24));
            if (daysAgo === 0) return 'Posted today';
            if (daysAgo === 1) return 'Posted 1 day ago';
            if (daysAgo < 7) return `Posted ${daysAgo} days ago`;
            if (daysAgo < 30) return `Posted ${Math.floor(daysAgo / 7)} week${Math.floor(daysAgo / 7) > 1 ? 's' : ''} ago`;
            return `Posted ${Math.floor(daysAgo / 30)} month${Math.floor(daysAgo / 30) > 1 ? 's' : ''} ago`;
        }

        function showError() {
            document.getElementById('loadingState').classList.add('hidden');
            document.getElementById('errorState').classList.remove('hidden');
        }

        // Load job details on page load
        loadJobDetails();
    </script>
</body>
</html>

