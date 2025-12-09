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
                            <div class="bg-white dark:bg-gray-800/50 rounded-lg p-6 border border-gray-200 dark:border-gray-700">
                                <div class="flex items-start gap-4 mb-4">
                                    <div id="companyLogo" class="size-16 bg-center bg-no-repeat aspect-square bg-cover rounded-2xl border border-gray-300 dark:border-gray-600 flex-shrink-0" style="background-color: #f0f0f0;"></div>
                                    <div class="flex-grow">
                                        <h1 id="jobTitle" class="text-2xl sm:text-3xl font-bold leading-tight tracking-[-0.015em] text-[#111318] dark:text-white mb-3"></h1>
                                        <div class="flex items-center flex-wrap gap-x-3 gap-y-1 text-primary/80 dark:text-primary/60">
                                            <p id="companyName" class="text-base font-normal leading-normal"></p>
                                            <span class="text-primary/60 dark:text-primary/40">·</span>
                                            <p id="jobLocation" class="text-base font-normal leading-normal"></p>
                                            <span class="text-primary/60 dark:text-primary/40">·</span>
                                            <p id="postedDate" class="text-base font-normal leading-normal"></p>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Action Buttons -->
                                <div class="flex gap-3 flex-wrap mt-4">
                                    <button id="saveJobBtn" class="flex items-center justify-center gap-2 rounded-full h-10 px-5 text-[#111318] dark:text-gray-200 text-sm font-bold leading-normal tracking-[0.015em] transition-colors" style="background-color: rgba(43, 238, 121, 0.5);" onmouseover="this.style.backgroundColor='rgba(43, 238, 121, 0.6)'" onmouseout="this.style.backgroundColor='rgba(43, 238, 121, 0.5)'">
                                        <span class="material-symbols-outlined text-base">bookmark</span>
                                        <span>Save Job</span>
                                    </button>
                                    <button id="shareJobBtn" class="flex items-center justify-center gap-2 rounded-full h-10 px-5 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 text-[#111318] dark:text-gray-200 text-sm font-bold leading-normal tracking-[0.015em] hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                        <span class="material-symbols-outlined text-base">share</span>
                                        <span>Share</span>
                                    </button>
                                </div>
                            </div>

                            <!-- Job Details Sections -->
                            <div class="border border-primary/20 dark:border-primary/10 rounded-lg p-6 bg-white dark:bg-gray-800/50">
                                <!-- Job Description -->
                                <div class="mb-6">
                                    <h3 class="text-[#111318] dark:text-white text-lg font-bold leading-normal mb-4">Job Description</h3>
                                    <p id="jobDescription" class="text-gray-600 dark:text-gray-400 text-sm font-normal leading-relaxed whitespace-pre-line"></p>
                                </div>

                                <!-- Responsibilities -->
                                <div class="mb-6 hidden" id="responsibilitiesSection">
                                    <h3 class="text-[#111318] dark:text-white text-lg font-bold leading-normal mb-4">Responsibilities</h3>
                                    <ul id="responsibilitiesList" class="text-[#4c9a6b] dark:text-gray-400 text-sm font-normal leading-relaxed list-disc pl-5 space-y-2"></ul>
                                </div>

                                <!-- Requirements -->
                                <div class="mb-6 hidden" id="requirementsSection">
                                    <h3 class="text-[#111318] dark:text-white text-lg font-bold leading-normal mb-4">Requirements</h3>
                                    <ul id="requirementsList" class="text-[#4c9a6b] dark:text-gray-400 text-sm font-normal leading-relaxed list-disc pl-5 space-y-2"></ul>
                                </div>

                                <!-- Required Skills -->
                                <div class="hidden" id="skillsSection">
                                    <h3 class="text-[#111318] dark:text-white text-lg font-bold leading-normal mb-4">Required Skills</h3>
                                    <div id="skillsContainer" class="flex flex-wrap gap-2"></div>
                                </div>
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
                        </div>

                        <!-- Right Column (Sticky Sidebar) -->
                        <div class="lg:col-span-1">
                            <div class="sticky top-28 flex flex-col gap-6">
                                <!-- Apply Card -->
                                <div class="border border-primary/20 dark:border-primary/10 rounded-lg p-6 flex flex-col gap-4 bg-white dark:bg-gray-800/50">
                                    <button id="applyNowBtn" class="w-full flex min-w-[84px] max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-full h-12 px-6 text-base font-bold leading-normal tracking-[0.015em] transition-colors" style="background-color: #2bee79; color: #0e2016;" onmouseover="this.style.backgroundColor='#25d46a'" onmouseout="this.style.backgroundColor='#2bee79'">
                                        <span class="truncate">Apply Now</span>
                                    </button>
                                    <div class="space-y-3 pt-2">
                                        <div class="flex justify-between items-center">
                                            <span class="text-sm font-medium text-[#111318] dark:text-gray-300">Salary Range</span>
                                            <span id="salaryRange" class="text-sm font-semibold text-[#111318] dark:text-gray-200">Not disclosed</span>
                                        </div>
                                        <div class="flex justify-between items-center">
                                            <span class="text-sm font-medium text-[#111318] dark:text-gray-300">Job Type</span>
                                            <span id="jobType" class="text-sm font-semibold text-[#111318] dark:text-gray-200"></span>
                                        </div>
                                        <div class="flex justify-between items-center">
                                            <span class="text-sm font-medium text-[#111318] dark:text-gray-300">Experience</span>
                                            <span id="experienceLevel" class="text-sm font-semibold text-[#111318] dark:text-gray-200"></span>
                                        </div>
                                        <div class="flex justify-between items-center">
                                            <span class="text-sm font-medium text-[#111318] dark:text-gray-300">Location</span>
                                            <span id="locationType" class="text-sm font-semibold text-[#111318] dark:text-gray-200"></span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Company Card -->
                                <div class="border border-primary/20 dark:border-primary/10 rounded-lg p-6 flex flex-col items-center text-center bg-white dark:bg-gray-800/50 relative">
                                    <a id="companyWebsiteLink" href="#" target="_blank" class="absolute top-4 right-4 text-[#111318] dark:text-gray-300 hover:text-primary transition-colors hidden">
                                        <span class="material-symbols-outlined text-xl">arrow_outward</span>
                                    </a>
                                    <div id="companyLogoCard" class="size-16 bg-center bg-no-repeat aspect-square bg-cover rounded-2xl mb-4 border border-gray-300 dark:border-gray-600" style="background-color: #f0f0f0;"></div>
                                    <h4 id="companyNameCard" class="font-bold text-lg text-[#111318] dark:text-white"></h4>
                                    <p id="companyDescriptionCard" class="text-sm text-[#111318] dark:text-gray-300 mt-1"></p>
                                </div>

                                <!-- Related Jobs Section -->
                                <div id="relatedJobsSection" class="border border-primary/20 dark:border-primary/10 rounded-lg p-6 bg-white dark:bg-gray-800/50">
                                    <h3 class="text-lg font-bold mb-4 text-[#111318] dark:text-white">Related Jobs</h3>
                                    <div id="relatedJobsContainer" class="flex flex-col gap-4">
                                        <!-- Related jobs will be loaded here -->
                                    </div>
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

            // Update page title for SEO
            document.title = `${job.title} at ${job.company_name} - JobFind`;

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
            document.getElementById('postedDate').textContent = `Posted ${getTimeAgo(job.posted_at)}`;

            // Description
            document.getElementById('jobDescription').textContent = job.description || 'No description available.';

            // Responsibilities
            if (job.responsibilities && (Array.isArray(job.responsibilities) ? job.responsibilities.length > 0 : job.responsibilities.trim())) {
                const responsibilities = Array.isArray(job.responsibilities) 
                    ? job.responsibilities 
                    : job.responsibilities.split('\n').filter(r => r.trim());
                const responsibilitiesList = document.getElementById('responsibilitiesList');
                responsibilitiesList.innerHTML = responsibilities.map(r => `<li>${r.trim()}</li>`).join('');
                document.getElementById('responsibilitiesSection').classList.remove('hidden');
            }

            // Requirements
            if (job.requirements && (Array.isArray(job.requirements) ? job.requirements.length > 0 : job.requirements.trim())) {
                const requirements = Array.isArray(job.requirements) 
                    ? job.requirements 
                    : job.requirements.split('\n').filter(r => r.trim());
                const requirementsList = document.getElementById('requirementsList');
                requirementsList.innerHTML = requirements.map(r => `<li>${r.trim()}</li>`).join('');
                document.getElementById('requirementsSection').classList.remove('hidden');
            }

            // Skills
            if (job.skills && job.skills.length > 0) {
                const skillsContainer = document.getElementById('skillsContainer');
                skillsContainer.innerHTML = job.skills.map(skill => 
                    `<span class="inline-flex items-center rounded-full bg-primary/20 dark:bg-primary/10 px-3 py-1 text-xs font-medium text-primary/80 dark:text-primary/60">${skill}</span>`
                ).join('');
                document.getElementById('skillsSection').classList.remove('hidden');
            }

            // Company description (only for sidebar card)
            if (job.company_description && job.company_description.trim()) {
                document.getElementById('companyDescriptionCard').textContent = job.company_description;
            }

            // Company website link
            const companyWebsiteLink = document.getElementById('companyWebsiteLink');
            if (job.company_website && job.company_website.trim()) {
                let websiteUrl = job.company_website.trim();
                // Add https:// if no protocol is specified
                if (!websiteUrl.match(/^https?:\/\//i)) {
                    websiteUrl = 'https://' + websiteUrl;
                }
                companyWebsiteLink.href = websiteUrl;
                companyWebsiteLink.classList.remove('hidden');
            } else {
                companyWebsiteLink.classList.add('hidden');
            }

            // Generate and inject Google JobPosting structured data
            generateJobPostingStructuredData(job);

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
                saveBtn.style.backgroundColor = '#2bee79';
                saveBtn.style.color = '#0e2016';
                saveBtn.onmouseover = function() { this.style.backgroundColor = '#25d46a'; };
                saveBtn.onmouseout = function() { this.style.backgroundColor = '#2bee79'; };
            }
            saveBtn.addEventListener('click', () => toggleSaveJob(job.id, saveBtn));

            // Share button
            document.getElementById('shareJobBtn').addEventListener('click', () => shareJob(job));

            // Apply button
            document.getElementById('applyNowBtn').addEventListener('click', () => {
                // Check if application URL or email is available
                if (job.application_url) {
                    // Open application URL in new tab
                    window.open(job.application_url, '_blank');
                } else if (job.application_email) {
                    // Open email client with pre-filled email
                    const subject = encodeURIComponent(`Application for ${job.title} at ${job.company_name}`);
                    const body = encodeURIComponent(`Dear Hiring Manager,\n\nI am writing to express my interest in the ${job.title} position at ${job.company_name}.\n\n[Your message here]\n\nBest regards`);
                    window.location.href = `mailto:${job.application_email}?subject=${subject}&body=${body}`;
                } else {
                    // Fallback to original behavior if no application method is available
                    <?php if (session()->get('is_logged_in')): ?>
                        // Redirect to application page or show modal
                        window.location.href = `<?= base_url('apply') ?>?job_id=${job.id}`;
                    <?php else: ?>
                        // Redirect to login
                        window.location.href = `<?= base_url('login') ?>?redirect=apply&job_id=${job.id}`;
                    <?php endif; ?>
                }
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
                btn.style.backgroundColor = 'rgba(43, 238, 121, 0.5)';
                btn.style.color = '#111318';
                btn.onmouseover = function() { this.style.backgroundColor = 'rgba(43, 238, 121, 0.6)'; };
                btn.onmouseout = function() { this.style.backgroundColor = 'rgba(43, 238, 121, 0.5)'; };
            } else {
                localStorage.setItem(`saved_job_${jobId}`, 'true');
                btn.innerHTML = '<span class="material-symbols-outlined text-base">bookmark</span><span class="truncate">Saved</span>';
                btn.style.backgroundColor = '#2bee79';
                btn.style.color = '#0e2016';
                btn.onmouseover = function() { this.style.backgroundColor = '#25d46a'; };
                btn.onmouseout = function() { this.style.backgroundColor = '#2bee79'; };
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
                // Fetch jobs from the same company first
                const response = await fetch(`${apiUrl}?company=${encodeURIComponent(currentJob.company_name)}&per_page=10`);
                const data = await response.json();
                
                let relatedJobs = [];
                
                if (data.success && data.jobs) {
                    // Filter out current job and get up to 3 jobs from same company
                    relatedJobs = data.jobs
                        .filter(job => job.id !== currentJob.id)
                        .slice(0, 3);
                }
                
                // If we don't have 3 jobs yet, fetch by job type
                if (relatedJobs.length < 3) {
                    const typeResponse = await fetch(`${apiUrl}?job_type=${encodeURIComponent(currentJob.job_type)}&per_page=10`);
                    const typeData = await typeResponse.json();
                    
                    if (typeData.success && typeData.jobs) {
                        const additionalJobs = typeData.jobs
                            .filter(job => 
                                job.id !== currentJob.id && 
                                !relatedJobs.some(rj => rj.id === job.id)
                            )
                            .slice(0, 3 - relatedJobs.length);
                        
                        relatedJobs = [...relatedJobs, ...additionalJobs];
                    }
                }
                
                // Display related jobs
                displayRelatedJobs(relatedJobs.slice(0, 3));
            } catch (error) {
                console.error('Error loading related jobs:', error);
                // Hide related jobs section if there's an error
                document.getElementById('relatedJobsSection').classList.add('hidden');
            }
        }
        
        function displayRelatedJobs(jobs) {
            const container = document.getElementById('relatedJobsContainer');
            
            if (!jobs || jobs.length === 0) {
                container.innerHTML = '<p class="text-sm text-gray-500 dark:text-gray-400 text-center">No related jobs found.</p>';
                return;
            }
            
            container.innerHTML = jobs.map(job => {
                const jobSlug = job.slug || `${job.company_name.toLowerCase().replace(/[^a-z0-9]+/g, '-')}-${job.title.toLowerCase().replace(/[^a-z0-9]+/g, '-')}-${job.id}`;
                const jobUrl = `${baseUrl}job/${jobSlug}/`;
                
                return `
                    <a href="${jobUrl}" class="block border border-gray-200 dark:border-gray-700 rounded-lg p-4 hover:border-primary/50 dark:hover:border-primary/50 hover:shadow-md transition-all duration-300">
                        <div class="flex items-start gap-3">
                            <img class="h-12 w-12 rounded-lg object-cover flex-shrink-0" 
                                 alt="${job.company_name} logo" 
                                 src="${job.company_logo || 'https://via.placeholder.com/48'}" 
                                 onerror="this.src='https://via.placeholder.com/48'"/>
                            <div class="flex-grow min-w-0">
                                <h4 class="text-sm font-bold text-[#111318] dark:text-white mb-1 line-clamp-2">${job.title}</h4>
                                <p class="text-xs text-gray-600 dark:text-gray-400 mb-2">${job.company_name}</p>
                                <div class="flex items-center gap-2 text-xs text-gray-500 dark:text-gray-500">
                                    <span>${job.location}</span>
                                    <span>·</span>
                                    <span>${getTimeAgo(job.posted_at)}</span>
                                </div>
                            </div>
                        </div>
                    </a>
                `;
            }).join('');
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

        function showError() {
            document.getElementById('loadingState').classList.add('hidden');
            document.getElementById('errorState').classList.remove('hidden');
        }

        function generateJobPostingStructuredData(job) {
            // Remove existing structured data if any
            const existingScript = document.getElementById('job-posting-structured-data');
            if (existingScript) {
                existingScript.remove();
            }

            // Format description in HTML
            let description = (job.description || '').replace(/\n/g, '<br>');
            
            // Add responsibilities
            if (job.responsibilities) {
                const responsibilities = Array.isArray(job.responsibilities) 
                    ? job.responsibilities 
                    : job.responsibilities.split('\n').filter(r => r.trim());
                if (responsibilities.length > 0) {
                    description += '<p><strong>Responsibilities:</strong></p><ul>';
                    responsibilities.forEach(resp => {
                        description += `<li>${resp.trim()}</li>`;
                    });
                    description += '</ul>';
                }
            }
            
            // Add requirements
            if (job.requirements) {
                const requirements = Array.isArray(job.requirements) 
                    ? job.requirements 
                    : job.requirements.split('\n').filter(r => r.trim());
                if (requirements.length > 0) {
                    description += '<p><strong>Requirements:</strong></p><ul>';
                    requirements.forEach(req => {
                        description += `<li>${req.trim()}</li>`;
                    });
                    description += '</ul>';
                }
            }
            
            // Add skills
            if (job.skills && job.skills.length > 0) {
                description += `<p><strong>Required Skills:</strong> ${job.skills.join(', ')}</p>`;
            }
            
            // Ensure description is not empty
            if (!description || description.trim() === '') {
                description = job.title || 'Job posting';
            }

            // Format datePosted
            const postedDate = new Date(job.posted_at);
            const datePosted = postedDate.toISOString().split('T')[0];

            // Build hiring organization
            const hiringOrganization = {
                "@type": "Organization",
                "name": job.company_name || "Unknown Company"
            };

            if (job.company_website) {
                let websiteUrl = job.company_website.trim();
                if (!websiteUrl.match(/^https?:\/\//i)) {
                    websiteUrl = 'https://' + websiteUrl;
                }
                hiringOrganization.sameAs = websiteUrl;
            }

            if (job.company_logo) {
                hiringOrganization.logo = job.company_logo;
            }

            // Build job location
            let jobLocation = null;
            if (!job.is_remote && job.location) {
                // Parse location (assuming format like "City, State" or "City, Country")
                const locationParts = job.location.split(',').map(s => s.trim());
                const address = {
                    "@type": "PostalAddress",
                    "addressLocality": locationParts[0] || job.location,
                    "addressCountry": locationParts.length > 1 ? locationParts[locationParts.length - 1] : "US"
                };
                if (locationParts.length > 2) {
                    address.addressRegion = locationParts[1];
                }

                jobLocation = {
                    "@type": "Place",
                    "address": address
                };
            }

            // Build structured data object
            const structuredData = {
                "@context": "https://schema.org/",
                "@type": "JobPosting",
                "title": job.title,
                "description": description,
                "datePosted": datePosted,
                "hiringOrganization": hiringOrganization,
                "identifier": {
                    "@type": "PropertyValue",
                    "name": job.company_name || "Unknown",
                    "value": job.id.toString()
                }
            };

            // Add job location or remote job properties
            if (job.is_remote) {
                structuredData.jobLocationType = "TELECOMMUTE";
                structuredData.applicantLocationRequirements = {
                    "@type": "Country",
                    "name": "USA"
                };
            } else if (jobLocation) {
                structuredData.jobLocation = jobLocation;
            }

            // Add employment type
            const employmentTypeMap = {
                'full-time': 'FULL_TIME',
                'part-time': 'PART_TIME',
                'contract': 'CONTRACTOR',
                'internship': 'INTERN',
                'remote': 'FULL_TIME',
                'freelance': 'CONTRACTOR',
                'temporary': 'TEMPORARY'
            };
            if (job.job_type && employmentTypeMap[job.job_type.toLowerCase()]) {
                structuredData.employmentType = employmentTypeMap[job.job_type.toLowerCase()];
            }

            // Add base salary if available
            if (job.salary_min && job.salary_max) {
                structuredData.baseSalary = {
                    "@type": "MonetaryAmount",
                    "currency": "USD",
                    "value": {
                        "@type": "QuantitativeValue",
                        "minValue": job.salary_min,
                        "maxValue": job.salary_max,
                        "unitText": "MONTH"
                    }
                };
            } else if (job.salary) {
                structuredData.baseSalary = {
                    "@type": "MonetaryAmount",
                    "currency": "USD",
                    "value": {
                        "@type": "QuantitativeValue",
                        "value": job.salary,
                        "unitText": "MONTH"
                    }
                };
            }

            // Add validThrough if available
            if (job.expires_at) {
                const validThroughDate = new Date(job.expires_at);
                structuredData.validThrough = validThroughDate.toISOString();
            }

            // Add directApply if application URL or email exists
            if (job.application_url || job.application_email) {
                structuredData.directApply = true;
            }

            // Create and inject script tag
            const script = document.createElement('script');
            script.id = 'job-posting-structured-data';
            script.type = 'application/ld+json';
            script.textContent = JSON.stringify(structuredData, null, 2);
            document.head.appendChild(script);
        }

        // Load job details on page load
        loadJobDetails();
    </script>
</body>
</html>

