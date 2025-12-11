<?= view('partials/head', ['title' => 'Job Details - TopTopJobs']) ?>
<style>
    /* Hide header on scroll down */
    #mainHeader.header-hidden {
        transform: translateY(-100%);
    }
</style>
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
                                    <img id="companyLogo" src="" alt="Company Logo" class="size-16 object-cover rounded-2xl border border-gray-300 dark:border-gray-600 flex-shrink-0" style="background-color: #f0f0f0;" onerror="this.onerror=null; this.src=this.dataset.placeholder || 'data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSI2NCIgaGVpZ2h0PSI2NCIgdmlld0JveD0iMCAwIDY0IDY0Ij48cmVjdCB3aWR0aD0iNjQiIGhlaWdodD0iNjQiIGZpbGw9IiNlNWU3ZWIiLz48dGV4dCB4PSI1MCUiIHk9IjUwJSIgZm9udC1mYW1pbHk9IkFyaWFsLCBzYW5zLXNlcmlmIiBmb250LXNpemU9IjIxIiBmaWxsPSIjOWNhM2FmIiB0ZXh0LWFuY2hvcj0ibWlkZGxlIiBkb21pbmFudC1iYXNlbGluZT0ibWlkZGxlIj5Mb2dvPC90ZXh0Pjwvc3ZnPg==';">
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

                                <!-- Job Image -->
                                <div class="mb-6 hidden" id="jobImageSection">
                                    <img id="jobImage" src="" alt="Job Image" class="w-full rounded-lg object-cover border border-gray-200 dark:border-gray-700">
                                </div>

                                <!-- Responsibilities -->
                                <div class="mb-6 hidden" id="responsibilitiesSection">
                                    <h3 class="text-[#111318] dark:text-white text-lg font-bold leading-normal mb-4">Responsibilities</h3>
                                    <ul id="responsibilitiesList" class="text-[#111318] dark:text-gray-300 text-sm font-normal leading-relaxed list-disc pl-5 space-y-2 list-item-black"></ul>
                                </div>

                                <!-- Requirements -->
                                <div class="mb-6 hidden" id="requirementsSection">
                                    <h3 class="text-[#111318] dark:text-white text-lg font-bold leading-normal mb-4">Requirements</h3>
                                    <ul id="requirementsList" class="text-[#111318] dark:text-gray-300 text-sm font-normal leading-relaxed list-disc pl-5 space-y-2 list-item-black"></ul>
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
                                <div class="aspect-video w-full rounded-lg overflow-hidden border border-primary/20 dark:border-primary/10 bg-gray-100 dark:bg-gray-800 relative">
                                    <!-- Google Maps Embed -->
                                    <iframe id="googleMapEmbed" class="hidden w-full h-full border-0" frameborder="0" style="border:0" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                                    
                                    <!-- Fallback placeholder when no map available -->
                                    <div id="mapContainer" class="w-full h-full flex items-center justify-center text-gray-400">
                                        <div class="text-center">
                                            <span class="material-symbols-outlined text-4xl mb-2">location_on</span>
                                            <p id="locationText" class="text-sm"></p>
                                        </div>
                                    </div>
                                    
                                    <!-- Google Maps Link Button -->
                                    <a id="googleMapsLink" href="#" target="_blank" rel="noopener noreferrer" class="hidden absolute bottom-4 right-4 bg-white dark:bg-gray-800 text-[#111318] dark:text-white px-4 py-2 rounded-lg shadow-lg hover:shadow-xl transition-shadow flex items-center gap-2 border border-gray-300 dark:border-gray-600 z-10">
                                        <span class="material-symbols-outlined text-lg">open_in_new</span>
                                        <span class="text-sm font-medium">Open in Google Maps</span>
                                    </a>
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
                                    <div class="absolute top-4 right-4 flex gap-2">
                                        <a id="companyWebsiteLink" href="#" target="_blank" class="text-[#111318] dark:text-gray-300 hover:text-primary transition-colors hidden" title="Company Website">
                                            <span class="material-symbols-outlined text-xl">arrow_outward</span>
                                        </a>
                                        <a id="companyMapsLink" href="#" target="_blank" rel="noopener noreferrer" class="text-[#111318] dark:text-gray-300 hover:text-primary transition-colors hidden" title="View on Google Maps">
                                            <span class="material-symbols-outlined text-xl">map</span>
                                        </a>
                                    </div>
                                    <img id="companyLogoCard" src="" alt="Company Logo" class="size-16 object-cover rounded-2xl mb-4 border border-gray-300 dark:border-gray-600" style="background-color: #f0f0f0;" onerror="this.onerror=null; this.src=this.dataset.placeholder || 'data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSI2NCIgaGVpZ2h0PSI2NCIgdmlld0JveD0iMCAwIDY0IDY0Ij48cmVjdCB3aWR0aD0iNjQiIGhlaWdodD0iNjQiIGZpbGw9IiNlNWU3ZWIiLz48dGV4dCB4PSI1MCUiIHk9IjUwJSIgZm9udC1mYW1pbHk9IkFyaWFsLCBzYW5zLXNlcmlmIiBmb250LXNpemU9IjIxIiBmaWxsPSIjOWNhM2FmIiB0ZXh0LWFuY2hvcj0ibWlkZGxlIiBkb21pbmFudC1iYXNlbGluZT0ibWlkZGxlIj5Mb2dvPC90ZXh0Pjwvc3ZnPg==';">
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
        // Use current origin to support both www and non-www versions
        // Remove /public/ from pathname if present to get clean base URL
        let basePath = window.location.pathname;
        // Remove /public/ from anywhere in the path (all occurrences)
        basePath = basePath.replace(/\/public\/?/g, '/').replace(/\/+/g, '/');
        // Get the base path (everything before /job/)
        const pathParts = basePath.split('/job/');
        let cleanBasePath = pathParts[0] || '/';
        
        // Remove /public/ from path for clean URLs (remove all occurrences)
        cleanBasePath = cleanBasePath.replace(/\/public\/?/g, '/').replace(/\/+/g, '/');
        
        // Ensure clean base path
        if (cleanBasePath === '/') {
            cleanBasePath = '';
        }
        
        const baseUrl = window.location.origin + (cleanBasePath ? cleanBasePath.replace(/\/$/, '') + '/' : '/');
        const apiUrl = baseUrl.replace(/\/+$/, '/') + 'api/jobs.php';
        
        console.log('Base URL:', baseUrl);
        console.log('API URL:', apiUrl);
        console.log('Current pathname:', window.location.pathname);
        
        // Extract job ID and slug from URL - support both query param and slug format
        function getJobInfoFromUrl() {
            // Try query parameter first
            const queryId = new URLSearchParams(window.location.search).get('id');
            if (queryId) {
                const id = parseInt(queryId);
                if (!isNaN(id) && id > 0) {
                    console.log('Found job ID from query param:', id);
                    return { id: id, slug: null };
                }
            }
            
            // Extract from slug format: /job/company-title-id or /job/company-title-id/
            // Also handle /public/job/company-title-id
            const path = window.location.pathname.replace(/^\/public/, '');
            const slugMatch = path.match(/\/job\/([^\/]+)/);
            if (slugMatch) {
                const slug = slugMatch[1];
                console.log('Found slug from path:', slug);
                
                // Extract ID from end of slug (e.g., "google-senior-product-designer-1" -> 1)
                const idMatch = slug.match(/-(\d+)$/);
                if (idMatch) {
                    const id = parseInt(idMatch[1]);
                    if (!isNaN(id) && id > 0) {
                        console.log('Extracted job ID from slug:', id);
                        return { id: id, slug: slug };
                    }
                }
                // If no ID found, return slug only
                console.log('No ID found in slug, using slug only');
                return { id: null, slug: slug };
            }
            
            console.warn('No job ID or slug found in URL');
            return { id: null, slug: null };
        }
        
        let jobInfo = getJobInfoFromUrl();
        let jobId = jobInfo.id;
        let jobSlug = jobInfo.slug;
        
        // Validate jobId if present
        if (jobId !== null && (isNaN(jobId) || jobId <= 0)) {
            console.error('Invalid job ID:', jobId);
            jobId = null;
        }
        
        console.log('=== Job Info Extracted ===');
        console.log('Job ID:', jobId, '(type:', typeof jobId, ')');
        console.log('Job Slug:', jobSlug);
        console.log('Is Valid ID:', jobId !== null && !isNaN(jobId) && jobId > 0);
        
        // Handle header hide/show on scroll (override header.php default behavior)
        let lastScrollY = window.scrollY;
        let ticking = false;
        const headerEl = document.getElementById('mainHeader');
        const SCROLL_THRESHOLD = 5; // Minimum scroll difference to trigger hide/show
        
        // Track scroll direction using wheel events (for better detection at bottom)
        let wheelDirection = 0;
        let wheelTimeout;
        
        window.addEventListener('wheel', (e) => {
            wheelDirection = e.deltaY > 0 ? 1 : -1; // 1 = down, -1 = up
            clearTimeout(wheelTimeout);
            wheelTimeout = setTimeout(() => {
                wheelDirection = 0;
            }, 200);
        }, { passive: true });

        function handleHeaderOnScroll() {
            if (!headerEl) return;
            
            if (!ticking) {
                window.requestAnimationFrame(() => {
                    const currentY = window.scrollY || window.pageYOffset;
                    const scrollDiff = currentY - lastScrollY;
                    
                    // Calculate if we're at the bottom
                    const scrollHeight = Math.max(
                        document.body.scrollHeight,
                        document.body.offsetHeight,
                        document.documentElement.clientHeight,
                        document.documentElement.scrollHeight,
                        document.documentElement.offsetHeight
                    );
                    const clientHeight = window.innerHeight || document.documentElement.clientHeight;
                    const scrollTop = currentY;
                    const distanceFromBottom = scrollHeight - (scrollTop + clientHeight);
                    const isAtBottom = distanceFromBottom <= 150; // Very generous threshold to catch bottom earlier
                    
                    // Always show header at the top of the page
                    if (currentY <= 50) {
                        headerEl.classList.remove('header-hidden');
                        headerEl.style.transform = 'translateY(0)';
                        headerEl.style.transition = 'transform 0.3s ease-in-out';
                        lastScrollY = currentY;
                        ticking = false;
                        return;
                    }
                    
                    // Always show header when at bottom of page OR when trying to scroll up at bottom
                    if (isAtBottom || (isAtBottom && wheelDirection < 0)) {
                        headerEl.classList.remove('header-hidden');
                        headerEl.style.transform = 'translateY(0)';
                        headerEl.style.transition = 'transform 0.3s ease-in-out';
                        lastScrollY = currentY;
                        ticking = false;
                        return;
                    }

                    // Hide on scroll down, show on scroll up
                    // Use a smaller threshold to be more responsive
                    if (Math.abs(scrollDiff) >= SCROLL_THRESHOLD) {
                        if (scrollDiff > 0) {
                            // Scrolling down
                            headerEl.classList.add('header-hidden');
                            headerEl.style.transform = 'translateY(-100%)';
                            headerEl.style.transition = 'transform 0.3s ease-in-out';
                        } else {
                            // Scrolling up - always show
                            headerEl.classList.remove('header-hidden');
                            headerEl.style.transform = 'translateY(0)';
                            headerEl.style.transition = 'transform 0.3s ease-in-out';
                        }
                        lastScrollY = currentY;
                    } else if (scrollDiff < 0 && isAtBottom) {
                        // Special case: trying to scroll up at bottom (even if position doesn't change much)
                        headerEl.classList.remove('header-hidden');
                        headerEl.style.transform = 'translateY(0)';
                        headerEl.style.transition = 'transform 0.3s ease-in-out';
                    }
                    
                    ticking = false;
                });
                ticking = true;
            }
        }
        
        // Add our custom scroll handler with higher priority
        // This will run after header.php handler and override its inline styles
        window.addEventListener('scroll', handleHeaderOnScroll, { passive: true });
        
        // Also check periodically to ensure header shows at bottom
        // This handles cases where content loads dynamically
        function checkBottomAndShowHeader() {
            if (!headerEl) return;
            
            const currentY = window.scrollY || window.pageYOffset;
            const scrollHeight = Math.max(
                document.body.scrollHeight,
                document.body.offsetHeight,
                document.documentElement.clientHeight,
                document.documentElement.scrollHeight,
                document.documentElement.offsetHeight
            );
            const clientHeight = window.innerHeight || document.documentElement.clientHeight;
            const distanceFromBottom = scrollHeight - (currentY + clientHeight);
            const isAtBottom = distanceFromBottom <= 150; // Very generous threshold to catch bottom earlier
            
            // Show header if at bottom OR if user is trying to scroll up (wheel direction)
            if (isAtBottom || (isAtBottom && wheelDirection < 0)) {
                headerEl.classList.remove('header-hidden');
                headerEl.style.transform = 'translateY(0)';
                headerEl.style.transition = 'transform 0.3s ease-in-out';
            }
        }
        
        // Enhanced scroll handler that also checks wheel direction
        function enhancedScrollHandler() {
            handleHeaderOnScroll();
            
            // If at bottom and trying to scroll up, show header immediately
            if (!headerEl) return;
            
            const currentY = window.scrollY || window.pageYOffset;
            const scrollHeight = Math.max(
                document.body.scrollHeight,
                document.documentElement.scrollHeight
            );
            const clientHeight = window.innerHeight || document.documentElement.clientHeight;
            const distanceFromBottom = scrollHeight - (currentY + clientHeight);
            const isAtBottom = distanceFromBottom <= 100;
            
            if (isAtBottom && wheelDirection < 0) {
                // User is trying to scroll up at bottom
                headerEl.classList.remove('header-hidden');
                headerEl.style.transform = 'translateY(0)';
                headerEl.style.transition = 'transform 0.3s ease-in-out';
            }
        }
        
        // Replace scroll handler with enhanced version
        window.removeEventListener('scroll', handleHeaderOnScroll);
        window.addEventListener('scroll', enhancedScrollHandler, { passive: true });
        
        // Check on load and periodically (for dynamic content)
        window.addEventListener('load', checkBottomAndShowHeader);
        setTimeout(checkBottomAndShowHeader, 500);
        setTimeout(checkBottomAndShowHeader, 1000);
        
        // Also check on scroll end (debounced)
        let scrollTimeout;
        window.addEventListener('scroll', () => {
            clearTimeout(scrollTimeout);
            scrollTimeout = setTimeout(checkBottomAndShowHeader, 150);
        }, { passive: true });

        // Load job details
        async function loadJobDetails() {
            console.log('=== Loading Job Details ===');
            console.log('Job ID:', jobId);
            console.log('Job Slug:', jobSlug);
            console.log('API URL:', apiUrl);
            console.log('Full API URL with ID:', jobId ? `${apiUrl}?id=${jobId}` : 'N/A');
            console.log('Full API URL with Slug:', jobSlug ? `${apiUrl}?slug=${encodeURIComponent(jobSlug)}` : 'N/A');
            
            // Validate inputs
            if (!jobId && !jobSlug) {
                console.error('❌ No job ID or slug found in URL');
                console.error('Current URL:', window.location.href);
                console.error('Pathname:', window.location.pathname);
                showError();
                return;
            }
            
            // Validate jobId if present
            if (jobId !== null && (isNaN(jobId) || jobId <= 0)) {
                console.error('❌ Invalid job ID:', jobId);
                if (jobSlug) {
                    console.log('Will try slug lookup instead');
                } else {
                    showError();
                    return;
                }
            }

            try {
                // Try with ID first if available and valid
                if (jobId && !isNaN(jobId) && jobId > 0) {
                    const idUrl = `${apiUrl}?id=${jobId}`;
                    console.log(`🔍 Fetching job with ID: ${jobId}`);
                    console.log(`📡 API Request URL: ${idUrl}`);
                    
                    const response = await fetch(idUrl);
                    
                    console.log('📥 Response status:', response.status, response.statusText);
                    console.log('📥 Response headers:', Object.fromEntries(response.headers.entries()));
                    
                    if (!response.ok) {
                        // Try to get error message from response
                        let errorMessage = `API error: ${response.status}`;
                        try {
                            const errorData = await response.json();
                            errorMessage = errorData.message || errorMessage;
                            console.error('❌ API Error Response:', errorData);
                        } catch (e) {
                            const errorText = await response.text();
                            console.error('❌ API Error Text:', errorText);
                        }
                        console.error('❌ API response not OK:', response.status, response.statusText);
                        
                        // If 404, try slug lookup as fallback
                        if (response.status === 404 && jobSlug) {
                            console.log('⚠️ ID lookup returned 404, trying slug lookup...');
                        } else {
                            throw new Error(errorMessage);
                        }
                    } else {
                        const data = await response.json();
                        console.log('✅ API response received:', data);

                        if (data.success && data.job) {
                            console.log('✅ Job found by ID:', data.job);
                            displayJobDetails(data.job);
                            return;
                        } else {
                            console.warn('⚠️ ID lookup returned no job:', data);
                            if (jobSlug) {
                                console.log('Will try slug lookup...');
                            }
                        }
                    }
                }
                
                // Fallback to slug lookup if ID failed or no ID available
                if (jobSlug) {
                    const slugUrl = `${apiUrl}?slug=${encodeURIComponent(jobSlug)}`;
                    console.log(`🔍 Fetching job with slug: ${jobSlug}`);
                    console.log(`📡 API Request URL: ${slugUrl}`);
                    
                    const slugResponse = await fetch(slugUrl);
                    
                    console.log('📥 Slug Response status:', slugResponse.status, slugResponse.statusText);
                    
                    if (!slugResponse.ok) {
                        let errorMessage = `Slug API error: ${slugResponse.status}`;
                        try {
                            const errorData = await slugResponse.json();
                            errorMessage = errorData.message || errorMessage;
                            console.error('❌ Slug API Error Response:', errorData);
                        } catch (e) {
                            const errorText = await slugResponse.text();
                            console.error('❌ Slug API Error Text:', errorText);
                        }
                        throw new Error(errorMessage);
                    }
                    
                    const slugData = await slugResponse.json();
                    console.log('✅ Slug API response received:', slugData);
                    
                    if (slugData.success && slugData.job) {
                        console.log('✅ Job found by slug:', slugData.job);
                        displayJobDetails(slugData.job);
                        return;
                    } else {
                        console.error('❌ Slug lookup also failed:', slugData);
                    }
                }
                
                // Both lookups failed
                console.error('❌ Job not found after all attempts');
                console.error('JobId:', jobId);
                console.error('Slug:', jobSlug);
                console.error('API URL:', apiUrl);
                showError();
            } catch (error) {
                console.error('❌ Error loading job details:', error);
                console.error('❌ Error name:', error.name);
                console.error('❌ Error message:', error.message);
                console.error('❌ Error stack:', error.stack);
                
                // Check if it's a network error
                if (error.message.includes('Failed to fetch') || error.message.includes('NetworkError')) {
                    console.error('🌐 Network error - API endpoint may be unreachable');
                    console.error('💡 Check if API URL is correct:', apiUrl);
                }
                
                showError();
            }
        }

        function displayJobDetails(job) {
            // Hide loading, show content
            document.getElementById('loadingState').classList.add('hidden');
            document.getElementById('jobDetailsContent').classList.remove('hidden');

            // Update page title for SEO
            document.title = `${job.title} at ${job.company_name} - TopTopJobs`;

            // Update breadcrumb
            document.getElementById('jobTitleBreadcrumb').textContent = job.title;

            // Company logo - handle broken images gracefully
            // Use data URI SVG as placeholder (no external requests needed)
            const getPlaceholderImage = (size) => {
                const svg = `<svg xmlns="http://www.w3.org/2000/svg" width="${size}" height="${size}" viewBox="0 0 ${size} ${size}"><rect width="${size}" height="${size}" fill="#e5e7eb"/><text x="50%" y="50%" font-family="Arial, sans-serif" font-size="${size/3}" fill="#9ca3af" text-anchor="middle" dominant-baseline="middle">Logo</text></svg>`;
                return 'data:image/svg+xml;base64,' + btoa(svg);
            };
            const companyLogo = job.company_logo || getPlaceholderImage(64);
            const placeholder = getPlaceholderImage(64);
            
            // Set logo images with fallback using <img> tags
            const logoElement = document.getElementById('companyLogo');
            const logoCardElement = document.getElementById('companyLogoCard');
            
            if (logoElement) {
                logoElement.src = companyLogo;
                logoElement.alt = `${job.company_name} Logo`;
                logoElement.dataset.placeholder = placeholder;
            }
            
            if (logoCardElement) {
                logoCardElement.src = companyLogo;
                logoCardElement.alt = `${job.company_name} Logo`;
                logoCardElement.dataset.placeholder = placeholder;
            }

            // Job title and company
            document.getElementById('jobTitle').textContent = job.title;
            document.getElementById('companyName').textContent = job.company_name;
            document.getElementById('companyNameCard').textContent = job.company_name;

            // Location and date
            document.getElementById('jobLocation').textContent = job.location;
            document.getElementById('locationText').textContent = job.location;
            
            // Calculate and display time ago
            const timeAgo = getTimeAgo(job.posted_at);
            document.getElementById('postedDate').textContent = `Posted ${timeAgo}`;

            // Description
            document.getElementById('jobDescription').textContent = job.description || 'No description available.';

            // Job Image
            const jobImageSection = document.getElementById('jobImageSection');
            const jobImage = document.getElementById('jobImage');
            if (job.image && job.image.trim()) {
                // Image URL should already be processed by the API
                let imageUrl = job.image.trim();
                // Ensure it starts with / if it's a relative path
                if (!imageUrl.startsWith('http://') && !imageUrl.startsWith('https://') && !imageUrl.startsWith('data:') && !imageUrl.startsWith('/')) {
                    imageUrl = '/' + imageUrl;
                }
                jobImage.src = imageUrl;
                jobImage.alt = `${job.title} - Job Image`;
                jobImage.onerror = function() {
                    // Hide image section if image fails to load
                    jobImageSection.classList.add('hidden');
                };
                jobImage.onload = function() {
                    // Show image section when image loads successfully
                    jobImageSection.classList.remove('hidden');
                };
                // Try to load the image (will trigger onload or onerror)
                jobImageSection.classList.remove('hidden');
            } else {
                jobImageSection.classList.add('hidden');
            }

            // Responsibilities
            if (job.responsibilities) {
                let responsibilities = [];
                if (Array.isArray(job.responsibilities)) {
                    responsibilities = job.responsibilities.filter(r => r != null);
                } else if (typeof job.responsibilities === 'string' && job.responsibilities.trim()) {
                    responsibilities = job.responsibilities.split('\n').filter(r => r && typeof r === 'string' && r.trim());
                }
                if (responsibilities.length > 0) {
                    const responsibilitiesList = document.getElementById('responsibilitiesList');
                    responsibilitiesList.innerHTML = responsibilities.map(r => {
                        const rText = typeof r === 'string' ? r.trim() : String(r || '').trim();
                        return rText ? `<li>${rText}</li>` : '';
                    }).filter(html => html).join('');
                    document.getElementById('responsibilitiesSection').classList.remove('hidden');
                }
            }

            // Requirements
            if (job.requirements) {
                let requirements = [];
                if (Array.isArray(job.requirements)) {
                    requirements = job.requirements.filter(r => r != null);
                } else if (typeof job.requirements === 'string' && job.requirements.trim()) {
                    requirements = job.requirements.split('\n').filter(r => r && typeof r === 'string' && r.trim());
                }
                if (requirements.length > 0) {
                    const requirementsList = document.getElementById('requirementsList');
                    requirementsList.innerHTML = requirements.map(r => {
                        const rText = typeof r === 'string' ? r.trim() : String(r || '').trim();
                        return rText ? `<li>${rText}</li>` : '';
                    }).filter(html => html).join('');
                    document.getElementById('requirementsSection').classList.remove('hidden');
                }
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

            // Company Google Maps link and embed
            const companyMapsLink = document.getElementById('companyMapsLink');
            const googleMapsLink = document.getElementById('googleMapsLink');
            const googleMapEmbed = document.getElementById('googleMapEmbed');
            const mapContainer = document.getElementById('mapContainer');
            const locationText = document.getElementById('locationText');
            
            // Function to extract coordinates or place from Google Maps URL
            async function resolveGoogleMapsUrl(mapsUrl) {
                if (!mapsUrl || !mapsUrl.trim()) return null;
                
                const url = mapsUrl.trim();
                let resolvedUrl = url;
                
                // Handle short URLs (maps.app.goo.gl) - need to resolve to get full URL
                if (url.includes('maps.app.goo.gl') || url.includes('goo.gl/maps')) {
                    try {
                        // Use a proxy approach - try to get the full URL
                        // Note: CORS may block this, so we'll use a fallback
                        const response = await fetch(url, { 
                            method: 'HEAD', 
                            redirect: 'follow',
                            mode: 'no-cors' // This won't give us the URL but won't error
                        });
                        // If we can't get the resolved URL, we'll extract from the short URL pattern
                        // or use the location text as fallback
                    } catch (e) {
                        console.log('Could not resolve short URL directly');
                    }
                }
                
                // Extract location info from URL
                return extractLocationFromUrl(resolvedUrl);
            }
            
            // Function to extract location info from Google Maps URL
            function extractLocationFromUrl(url, companyName, location) {
                // Priority 1: Extract coordinates (lat,lng format) - most precise
                // Format: @lat,lng or ?ll=lat,lng or /@lat,lng
                const coordPatterns = [
                    /@(-?\d+\.?\d*),(-?\d+\.?\d*)/,  // @lat,lng
                    /[?&]ll=(-?\d+\.?\d*),(-?\d+\.?\d*)/,  // ?ll=lat,lng
                    /[?&]center=(-?\d+\.?\d*),(-?\d+\.?\d*)/,  // ?center=lat,lng
                ];
                
                for (const pattern of coordPatterns) {
                    const match = url.match(pattern);
                    if (match) {
                        const lat = parseFloat(match[1]);
                        const lng = parseFloat(match[2]);
                        // Use coordinates with location name - this will show a red pin with label
                        // Format: q=Label+@lat,lng shows the label on the pin
                        const label = companyName ? (location ? `${companyName}, ${location}` : companyName) : (location || `${lat},${lng}`);
                        return {
                            type: 'coordinates',
                            lat: lat,
                            lng: lng,
                            embedUrl: `https://www.google.com/maps?q=${encodeURIComponent(label)}+@${lat},${lng}&ll=${lat},${lng}&z=16&output=embed`
                        };
                    }
                }
                
                // Priority 2: Extract place ID (very precise)
                const placeIdMatch = url.match(/[?&]place_id=([^&]+)/);
                if (placeIdMatch) {
                    const placeId = placeIdMatch[1];
                    // Use place ID with label - this will show a marker with name
                    const label = companyName || location || 'Location';
                    return {
                        type: 'place_id',
                        placeId: placeId,
                        embedUrl: `https://www.google.com/maps?q=place_id:${placeId}&output=embed&z=16`
                    };
                }
                
                // Priority 3: Extract place name from /place/ path
                const placeMatch = url.match(/\/place\/([^\/\?&]+)/);
                if (placeMatch) {
                    const placeName = decodeURIComponent(placeMatch[1].replace(/\+/g, ' '));
                    // Use place name - Google Maps will show a marker with the name
                    return {
                        type: 'place',
                        place: placeName,
                        embedUrl: `https://www.google.com/maps?q=${encodeURIComponent(placeName)}&output=embed&z=16`
                    };
                }
                
                // Priority 4: Extract search query
                const searchMatch = url.match(/\/search\/([^\/\?&]+)/);
                if (searchMatch) {
                    const searchTerm = decodeURIComponent(searchMatch[1].replace(/\+/g, ' '));
                    return {
                        type: 'search',
                        query: searchTerm,
                        embedUrl: `https://www.google.com/maps?q=${encodeURIComponent(searchTerm)}&output=embed&z=16`
                    };
                }
                
                // Priority 5: Extract q parameter
                const queryMatch = url.match(/[?&]q=([^&]+)/);
                if (queryMatch) {
                    const query = decodeURIComponent(queryMatch[1].replace(/\+/g, ' '));
                    return {
                        type: 'query',
                        query: query,
                        embedUrl: `https://www.google.com/maps?q=${encodeURIComponent(query)}&output=embed&z=16`
                    };
                }
                
                return null;
            }
            
            // Function to get Google Maps embed URL with marker
            function getGoogleMapsEmbedUrl(mapsUrl, location) {
                // If we have a maps URL, try to extract location info
                if (mapsUrl) {
                    const locationInfo = extractLocationFromUrl(mapsUrl);
                    if (locationInfo) {
                        return locationInfo.embedUrl;
                    }
                }
                
                // Fallback: Use location text (Google Maps will automatically show a marker)
                if (location && location.trim()) {
                    return `https://www.google.com/maps?q=${encodeURIComponent(location.trim())}&output=embed&z=16`;
                }
                
                return null;
            }
            
            // Setup Google Maps with location marker
            const jobLocation = job.location || '';
            let embedUrl = null;
            
            // Function to load map with marker and location name (default: use company name)
            async function loadGoogleMap() {
                let embedUrl = null;
                const companyName = job.company_name || '';
                const location = jobLocation || '';
                
                // Priority 1: If Google Maps URL is available, use it
                if (job.company_maps_url && job.company_maps_url.trim()) {
                    const mapsUrl = job.company_maps_url.trim();
                    
                    companyMapsLink.href = mapsUrl;
                    companyMapsLink.classList.remove('hidden');
                    googleMapsLink.href = mapsUrl;
                    googleMapsLink.classList.remove('hidden');
                    
                    // Try to extract location info from URL with company name and location
                    const locationInfo = extractLocationFromUrl(mapsUrl, companyName, location);
                    
                    if (locationInfo) {
                        // We have location info (coordinates, place ID, etc.)
                        embedUrl = locationInfo.embedUrl;
                        console.log('Using extracted location:', locationInfo.type, locationInfo);
                    } else if (location && location.trim()) {
                        // Fallback: Use location text with company name (will show marker with label)
                        const label = companyName ? `${companyName}, ${location}` : location;
                        embedUrl = `https://www.google.com/maps?q=${encodeURIComponent(label.trim())}&output=embed&z=16`;
                        console.log('Using location text with company name as fallback');
                    } else if (companyName) {
                        // Fallback: Use company name only
                        embedUrl = `https://www.google.com/maps?q=${encodeURIComponent(companyName.trim())}&output=embed&z=16`;
                        console.log('Using company name as fallback');
                    } else {
                        // Try to resolve short URL (may not work due to CORS)
                        try {
                            const resolved = await resolveGoogleMapsUrl(mapsUrl);
                            if (resolved) {
                                embedUrl = resolved.embedUrl;
                            }
                        } catch (e) {
                            console.log('Could not resolve URL:', e);
                        }
                    }
                } else {
                    // Priority 2: No Google Maps URL - use company name and location to create map
                    // This ensures every company shows a map by default
                    let searchQuery = '';
                    
                    if (companyName && location) {
                        // Use both company name and location
                        searchQuery = `${companyName}, ${location}`;
                    } else if (companyName) {
                        // Use company name only
                        searchQuery = companyName;
                    } else if (location) {
                        // Use location only
                        searchQuery = location;
                    }
                    
                    if (searchQuery) {
                        embedUrl = `https://www.google.com/maps?q=${encodeURIComponent(searchQuery.trim())}&output=embed&z=16`;
                        
                        // Create Google Maps search link
                        const mapsSearchUrl = `https://www.google.com/maps/search/?api=1&query=${encodeURIComponent(searchQuery.trim())}`;
                        googleMapsLink.href = mapsSearchUrl;
                        googleMapsLink.classList.remove('hidden');
                    }
                    
                    // Hide company maps link if no URL available
                    companyMapsLink.classList.add('hidden');
                }
                
                // Display map if we have an embed URL
                if (embedUrl) {
                    googleMapEmbed.src = embedUrl;
                    googleMapEmbed.classList.remove('hidden');
                    mapContainer.classList.add('hidden');
                    
                    // Update location text for accessibility
                    if (location) {
                        locationText.textContent = location;
                    } else if (companyName) {
                        locationText.textContent = companyName;
                    } else {
                        locationText.textContent = 'View location on map';
                    }
                } else {
                    // Could not create embed URL, show placeholder
                    googleMapEmbed.classList.add('hidden');
                    mapContainer.classList.remove('hidden');
                    companyMapsLink.classList.add('hidden');
                    googleMapsLink.classList.add('hidden');
                    
                    if (location) {
                        locationText.textContent = location;
                    } else if (companyName) {
                        locationText.textContent = companyName;
                    } else {
                        locationText.textContent = 'Location not specified';
                    }
                }
            }
            
            // Load the map
            loadGoogleMap();

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
            // Check if job is saved (server-side)
            checkJobSaved(job.id, saveBtn);
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

        // Check if job is saved (server-side)
        async function checkJobSaved(jobId, btn) {
            try {
                const response = await fetch(`${baseUrl.replace(/\/$/, '')}/api/check-saved-job/${jobId}`);
                const data = await response.json();
                
                if (data.success && data.saved) {
                    updateSaveButton(btn, true);
                } else {
                    updateSaveButton(btn, false);
                }
            } catch (error) {
                console.error('Error checking saved job:', error);
                updateSaveButton(btn, false);
            }
        }

        // Update save button appearance
        function updateSaveButton(btn, isSaved) {
            if (isSaved) {
                btn.innerHTML = '<span class="material-symbols-outlined text-base">bookmark</span><span class="truncate">Saved</span>';
                btn.style.backgroundColor = '#2bee79';
                btn.style.color = '#0e2016';
                btn.onmouseover = function() { this.style.backgroundColor = '#25d46a'; };
                btn.onmouseout = function() { this.style.backgroundColor = '#2bee79'; };
            } else {
                btn.innerHTML = '<span class="material-symbols-outlined text-base">bookmark</span><span class="truncate">Save Job</span>';
                btn.style.backgroundColor = 'rgba(43, 238, 121, 0.5)';
                btn.style.color = '#111318';
                btn.onmouseover = function() { this.style.backgroundColor = 'rgba(43, 238, 121, 0.6)'; };
                btn.onmouseout = function() { this.style.backgroundColor = 'rgba(43, 238, 121, 0.5)'; };
            }
        }

        // Toggle save job (server-side)
        async function toggleSaveJob(jobId, btn) {
            <?php if (!session()->get('is_logged_in')): ?>
                // Redirect to login if not logged in
                window.location.href = '<?= base_url('login?redirect=' . urlencode(current_url())) ?>';
                return;
            <?php endif; ?>

            try {
                const response = await fetch(`${baseUrl.replace(/\/$/, '')}/api/toggle-save-job`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({ job_id: jobId })
                });

                const data = await response.json();

                if (data.success) {
                    updateSaveButton(btn, data.saved);
                } else {
                    alert(data.message || 'An error occurred');
                }
            } catch (error) {
                console.error('Error toggling save job:', error);
                alert('An error occurred. Please try again.');
            }
        }

        function shareJob(job) {
            // Generate slug URL
            const jobSlug = job.slug || (job.company_name.toLowerCase().replace(/[^a-z0-9]+/g, '-') + '-' + job.title.toLowerCase().replace(/[^a-z0-9]+/g, '-') + '-' + job.id);
            // Remove /public/ from baseUrl if present for clean URLs (remove all occurrences)
            const cleanBaseUrl = baseUrl.replace(/\/public\/?/g, '/').replace(/\/+/g, '/').replace(/\/$/, '');
            const shareUrl = `${cleanBaseUrl}/job/${jobSlug}/`.replace(/\/+/g, '/');
            
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
            
            // Extract base path from current location to avoid double domain issue
            let currentPath = window.location.pathname;
            currentPath = currentPath.replace(/\/public\/?/g, '/').replace(/\/+/g, '/');
            const pathParts = currentPath.split('/job/');
            const basePath = (pathParts[0] || '').replace(/\/$/, '');
            
            container.innerHTML = jobs.map(job => {
                const jobSlug = job.slug || `${job.company_name.toLowerCase().replace(/[^a-z0-9]+/g, '-')}-${job.title.toLowerCase().replace(/[^a-z0-9]+/g, '-')}-${job.id}`;
                // Construct relative URL using base path
                const jobUrl = `${basePath}/job/${jobSlug}`.replace(/\/+/g, '/');
                
                return `
                    <a href="${jobUrl}" class="block border border-gray-200 dark:border-gray-700 rounded-lg p-4 hover:border-primary/50 dark:hover:border-primary/50 hover:shadow-md transition-all duration-300">
                        <div class="flex items-start gap-3">
                            <img class="h-12 w-12 rounded-lg object-cover flex-shrink-0" 
                                 alt="${job.company_name} logo" 
                                 src="${job.company_logo || 'data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSI0OCIgaGVpZ2h0PSI0OCIgdmlld0JveD0iMCAwIDQ4IDQ4Ij48cmVjdCB3aWR0aD0iNDgiIGhlaWdodD0iNDgiIGZpbGw9IiNlNWU3ZWIiLz48dGV4dCB4PSI1MCUiIHk9IjUwJSIgZm9udC1mYW1pbHk9IkFyaWFsLCBzYW5zLXNlcmlmIiBmb250LXNpemU9IjE2IiBmaWxsPSIjOWNhM2FmIiB0ZXh0LWFuY2hvcj0ibWlkZGxlIiBkb21pbmFudC1iYXNlbGluZT0ibWlkZGxlIj5Mb2dvPC90ZXh0Pjwvc3ZnPg=='}" 
                                 onerror="this.src='data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSI0OCIgaGVpZ2h0PSI0OCIgdmlld0JveD0iMCAwIDQ4IDQ4Ij48cmVjdCB3aWR0aD0iNDgiIGhlaWdodD0iNDgiIGZpbGw9IiNlNWU3ZWIiLz48dGV4dCB4PSI1MCUiIHk9IjUwJSIgZm9udC1mYW1pbHk9IkFyaWFsLCBzYW5zLXNlcmlmIiBmb250LXNpemU9IjE2IiBmaWxsPSIjOWNhM2FmIiB0ZXh0LWFuY2hvcj0ibWlkZGxlIiBkb21pbmFudC1iYXNlbGluZT0ibWlkZGxlIj5Mb2dvPC90ZXh0Pjwvc3ZnPg=='"/>
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
            if (!dateString) return 'recently';
            
            const now = new Date();
            let date;
            
            // Handle different date formats from database
            // MySQL datetime format: "2024-01-18 14:30:00"
            // If it's already a valid date string, use it directly
            // Otherwise, try to parse it
            if (typeof dateString === 'string') {
                // Replace space with 'T' for ISO format if needed, or parse as-is
                // MySQL datetime: "2024-01-18 14:30:00" -> "2024-01-18T14:30:00"
                const isoString = dateString.includes('T') ? dateString : dateString.replace(' ', 'T');
                date = new Date(isoString);
            } else {
                date = new Date(dateString);
            }
            
            // Check if date is valid
            if (isNaN(date.getTime())) {
                console.error('Invalid date:', dateString);
                return 'recently';
            }
            
            const diffMs = now - date;
            
            // Handle negative differences (future dates) - shouldn't happen but just in case
            if (diffMs < 0) {
                return 'just now';
            }
            
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
                let responsibilities = [];
                if (Array.isArray(job.responsibilities)) {
                    responsibilities = job.responsibilities;
                } else if (typeof job.responsibilities === 'string') {
                    responsibilities = job.responsibilities.split('\n').filter(r => r && typeof r === 'string' && r.trim());
                }
                if (responsibilities.length > 0) {
                    description += '<p><strong>Responsibilities:</strong></p><ul>';
                    responsibilities.forEach(resp => {
                        const respText = typeof resp === 'string' ? resp.trim() : String(resp || '').trim();
                        if (respText) {
                            description += `<li>${respText}</li>`;
                        }
                    });
                    description += '</ul>';
                }
            }
            
            // Add requirements
            if (job.requirements) {
                let requirements = [];
                if (Array.isArray(job.requirements)) {
                    requirements = job.requirements;
                } else if (typeof job.requirements === 'string') {
                    requirements = job.requirements.split('\n').filter(r => r && typeof r === 'string' && r.trim());
                }
                if (requirements.length > 0) {
                    description += '<p><strong>Requirements:</strong></p><ul>';
                    requirements.forEach(req => {
                        const reqText = typeof req === 'string' ? req.trim() : String(req || '').trim();
                        if (reqText) {
                            description += `<li>${reqText}</li>`;
                        }
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
                // Ensure logo URL is absolute
                let logoUrl = job.company_logo;
                if (!logoUrl.match(/^https?:\/\//i)) {
                    // Convert relative URL to absolute
                    logoUrl = window.location.origin + '/' + logoUrl.replace(/^\//, '');
                }
                // Replace local development URLs
                logoUrl = logoUrl.replace(/^https?:\/\/toptopjobs\.local/, window.location.origin);
                hiringOrganization.logo = logoUrl;
            }

            // Build job location
            let jobLocation = null;
            if (!job.is_remote && job.location) {
                // Use country_code from job if available, otherwise default to LK (Sri Lanka)
                const countryCode = job.country_code || 'LK';
                const countryName = job.country || 'Sri Lanka';
                
                // Parse location (assuming format like "City, State" or "City, Country")
                const locationParts = job.location.split(',').map(s => s.trim());
                const address = {
                    "@type": "PostalAddress",
                    "addressLocality": locationParts[0] || job.location,
                    "addressCountry": countryCode
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
                // Use country_code from job if available, otherwise default to LK (Sri Lanka)
                const countryCode = job.country_code || 'LK';
                const countryName = job.country || 'Sri Lanka';
                structuredData.applicantLocationRequirements = {
                    "@type": "Country",
                    "name": countryName
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
        // Add timeout to prevent infinite loading
        let loadTimeout;
        const startLoad = async () => {
            loadTimeout = setTimeout(() => {
                console.error('Job details loading timeout after 10 seconds');
                const loadingState = document.getElementById('loadingState');
                if (loadingState && !loadingState.classList.contains('hidden')) {
                    console.error('Still in loading state, showing error');
                    showError();
                }
            }, 10000);
            
            try {
                await loadJobDetails();
            } finally {
                if (loadTimeout) {
                    clearTimeout(loadTimeout);
                }
            }
        };
        
        startLoad();
    </script>
</body>
</html>

