// Job Search & Listing JavaScript

class JobSearch {
    constructor() {
        this.baseUrl = window.baseUrl || '';
        // Remove trailing slash if present
        this.baseUrl = this.baseUrl.replace(/\/$/, '');
        // Use apiUrl from window if available, otherwise construct it
        if (window.apiUrl) {
            this.apiUrl = window.apiUrl;
        } else if (this.baseUrl) {
            this.apiUrl = `${this.baseUrl}/api/jobs.php`;
        } else {
            this.apiUrl = '/api/jobs.php';
        }
        this.currentPage = 1;
        this.perPage = 20;
        this.hasMore = true;
        this.loading = false;
        this.filters = {
            q: '',
            loc: '',
            job_type: [],
            experience: [],
            salary_min: 0,
            date_posted: [],
            category: [],
        };
        this.sortBy = 'relevant';
        this.viewMode = 'list'; // 'list' or 'grid'
        this.userLocation = null;
        this.savedJobs = new Set(JSON.parse(localStorage.getItem('savedJobs') || '[]'));
        this.locationDebounceTimer = null;
        this.salaryDebounceTimer = null;

        this.init();
    }

    init() {
        this.setupEventListeners();
        this.setupMobileFeatures();
        this.loadInitialJobs();
        this.setupInfiniteScroll();
        this.setupPullToRefresh();
    }

    setupEventListeners() {
        // Search is now handled by header search, get query from URL params
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.get('q')) {
            this.filters.q = urlParams.get('q');
        }

        // Filter toggle (desktop - inside filter panel)
        const filterToggle = document.getElementById('filterToggle');
        if (filterToggle) {
            filterToggle.addEventListener('click', () => {
                this.toggleMobileFilters();
            });
        }

        // Mobile filter toggle button (visible on mobile)
        const mobileFilterToggle = document.getElementById('mobileFilterToggle');
        if (mobileFilterToggle) {
            mobileFilterToggle.addEventListener('click', () => {
                this.toggleMobileFilters();
            });
        }

        const closeFilters = document.getElementById('closeFilters');
        if (closeFilters) {
            closeFilters.addEventListener('click', () => {
                this.closeMobileFilters();
            });
        }

        document.getElementById('closeMobileFilters').addEventListener('click', () => {
            this.closeMobileFilters();
        });

        document.getElementById('filterOverlay').addEventListener('click', () => {
            this.closeMobileFilters();
        });

        // Filter pill buttons (new UI) - Toggle functionality using event delegation
        // Use event delegation to handle clicks even if buttons are re-rendered
        // Store reference to 'this' for use in event handler
        const self = this;
        document.addEventListener('click', function(e) {
            const btn = e.target.closest('.filter-pill-btn');
            if (!btn) return;
            
            e.preventDefault();
            e.stopPropagation();
            
            const filterType = btn.dataset.filter;
            const filterValue = btn.dataset.value;
            
            if (!filterType || !filterValue) return;
            
            const isActive = btn.classList.contains('active');
            
            // Special handling for location pills - set text input value
            if (filterType === 'location') {
                const locationInput = document.getElementById('locationFilter');
                const locationInputMobile = document.getElementById('locationFilterMobile');
                if (isActive) {
                    // Remove filter - clear location
                    if (locationInput) locationInput.value = '';
                    if (locationInputMobile) locationInputMobile.value = '';
                    self.filters.loc = '';
                    btn.classList.remove('active');
                    btn.classList.add('bg-white', 'dark:bg-gray-700', 'text-gray-700', 'dark:text-gray-300');
                } else {
                    // Add filter - set location
                    if (locationInput) locationInput.value = filterValue;
                    if (locationInputMobile) locationInputMobile.value = filterValue;
                    self.filters.loc = filterValue;
                    btn.classList.add('active');
                    btn.classList.remove('bg-white', 'dark:bg-gray-700', 'text-gray-700', 'dark:text-gray-300');
                }
                // Sync location pills
                self.syncFilterPills(filterType, filterValue, !isActive);
                // Auto-apply location filter
                self.resetAndLoad();
            } else {
                // For other filter types (job_type, experience, category, date_posted)
                if (isActive) {
                    // Remove filter - deselect button
                    btn.classList.remove('active');
                    btn.classList.add('bg-white', 'dark:bg-gray-700', 'text-gray-700', 'dark:text-gray-300');
                    self.updateArrayFilter(filterType, filterValue, false);
                } else {
                    // Add filter - select button
                    btn.classList.add('active');
                    btn.classList.remove('bg-white', 'dark:bg-gray-700', 'text-gray-700', 'dark:text-gray-300');
                    self.updateArrayFilter(filterType, filterValue, true);
                }
                
                // Sync all buttons with same filter and value (desktop and mobile)
                self.syncFilterPills(filterType, filterValue, !isActive);
            }
        });

        // Legacy checkbox support (if any remain)
        document.querySelectorAll('input[name="job_type"], input[name="job_type_mobile"]').forEach(checkbox => {
            checkbox.addEventListener('change', (e) => {
                this.updateArrayFilter('job_type', e.target.value, e.target.checked);
                this.syncCheckboxes('job_type', e.target.value, e.target.checked);
            });
        });

        // Legacy checkbox support (if any remain)
        document.querySelectorAll('input[name="experience"], input[name="experience_mobile"]').forEach(checkbox => {
            checkbox.addEventListener('change', (e) => {
                this.updateArrayFilter('experience', e.target.value, e.target.checked);
                this.syncCheckboxes('experience', e.target.value, e.target.checked);
            });
        });

        // Location input (desktop and mobile)
        const locationFilter = document.getElementById('locationFilter');
        const locationFilterMobile = document.getElementById('locationFilterMobile');
        
        [locationFilter, locationFilterMobile].forEach(input => {
            if (input) {
                input.addEventListener('input', (e) => {
                    this.filters.loc = e.target.value.trim();
                    // Sync both inputs
                    if (e.target.id === 'locationFilter') {
                        if (locationFilterMobile) locationFilterMobile.value = e.target.value;
                    } else {
                        if (locationFilter) locationFilter.value = e.target.value;
                    }
                    
                    // Clear location pill buttons if user types custom location
                    if (this.filters.loc) {
                        document.querySelectorAll('.filter-pill-btn[data-filter="location"]').forEach(btn => {
                            btn.classList.remove('active');
                            // Restore default styles
                            btn.classList.add('bg-white', 'dark:bg-gray-700', 'text-gray-700', 'dark:text-gray-300');
                        });
                    }
                    
                    // Auto-apply location filter with debounce
                    clearTimeout(this.locationDebounceTimer);
                    this.locationDebounceTimer = setTimeout(() => {
                        this.resetAndLoad();
                    }, 500); // Wait 500ms after user stops typing
                });
                
                input.addEventListener('keypress', (e) => {
                    if (e.key === 'Enter') {
                        clearTimeout(this.locationDebounceTimer);
                        this.resetAndLoad();
                    }
                });
            }
        });

        // Salary range (desktop and mobile)
        const salaryRange = document.getElementById('salaryRange');
        const salaryRangeMobile = document.getElementById('salaryRangeMobile');
        
        [salaryRange, salaryRangeMobile].forEach(range => {
            if (range) {
                range.addEventListener('input', (e) => {
                    this.filters.salary_min = parseInt(e.target.value);
                    // Sync both sliders
                    if (e.target.id === 'salaryRange') {
                        if (salaryRangeMobile) salaryRangeMobile.value = e.target.value;
                    } else {
                        if (salaryRange) salaryRange.value = e.target.value;
                    }
                    this.updateSalaryDisplay();
                });
            }
        });

        // Legacy checkbox support for date posted (if any remain)
        document.querySelectorAll('input[name="date_posted"], input[name="date_posted_mobile"]').forEach(checkbox => {
            checkbox.addEventListener('change', (e) => {
                this.updateArrayFilter('date_posted', e.target.value, e.target.checked);
                this.syncCheckboxes('date_posted', e.target.value, e.target.checked);
            });
        });

        // Clear filters (desktop and mobile)
        const clearFiltersBtn = document.getElementById('clearFilters');
        const clearFiltersMobileBtn = document.getElementById('clearFiltersMobile');
        if (clearFiltersBtn) {
            clearFiltersBtn.addEventListener('click', () => {
                this.clearAllFilters();
            });
        }
        if (clearFiltersMobileBtn) {
            clearFiltersMobileBtn.addEventListener('click', () => {
                this.clearAllFilters();
            });
        }


        // Sort
        document.getElementById('sortBy').addEventListener('change', (e) => {
            this.sortBy = e.target.value;
            this.resetAndLoad();
        });

        // Back button removed - search is now in header

        // Retry button
        document.getElementById('retryBtn').addEventListener('click', () => {
            this.loadInitialJobs();
        });

        // Load more
        document.getElementById('loadMoreBtn').addEventListener('click', () => {
            this.loadMoreJobs();
        });
    }

    setupMobileFeatures() {
        // Request location permission
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(
                (position) => {
                    this.userLocation = {
                        lat: position.coords.latitude,
                        lng: position.coords.longitude
                    };
                },
                (error) => {
                    console.log('Location access denied:', error);
                }
            );
        }

        // Setup swipe gestures for job cards
        this.setupSwipeGestures();
    }

    setupSwipeGestures() {
        let touchStartX = 0;
        let touchEndX = 0;

        document.addEventListener('touchstart', (e) => {
            const jobCard = e.target.closest('.job-card');
            if (jobCard) {
                touchStartX = e.changedTouches[0].screenX;
            }
        }, { passive: true });

        document.addEventListener('touchend', (e) => {
            const jobCard = e.target.closest('.job-card');
            if (jobCard) {
                touchEndX = e.changedTouches[0].screenX;
                this.handleSwipe(jobCard, touchStartX, touchEndX);
            }
        }, { passive: true });
    }

    handleSwipe(jobCard, startX, endX) {
        const swipeDistance = endX - startX;
        const minSwipeDistance = 50;

        if (Math.abs(swipeDistance) > minSwipeDistance) {
            if (swipeDistance > 0) {
                // Swipe right - Apply
                this.applyToJob(jobCard.dataset.jobId);
            } else {
                // Swipe left - Save
                this.toggleSaveJob(jobCard.dataset.jobId);
            }
        }
    }

    setupInfiniteScroll() {
        let ticking = false;

        window.addEventListener('scroll', () => {
            if (!ticking) {
                window.requestAnimationFrame(() => {
                    if (this.shouldLoadMore()) {
                        this.loadMoreJobs();
                    }
                    ticking = false;
                });
                ticking = true;
            }
        }, { passive: true });
    }

    shouldLoadMore() {
        if (this.loading || !this.hasMore) return false;

        const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
        const windowHeight = window.innerHeight;
        const documentHeight = document.documentElement.scrollHeight;

        return scrollTop + windowHeight >= documentHeight - 500;
    }

    setupPullToRefresh() {
        let touchStartY = 0;
        let touchEndY = 0;
        let isPulling = false;

        document.addEventListener('touchstart', (e) => {
            if (window.scrollY === 0) {
                touchStartY = e.touches[0].clientY;
                isPulling = true;
            }
        }, { passive: true });

        document.addEventListener('touchmove', (e) => {
            if (isPulling && window.scrollY === 0) {
                touchEndY = e.touches[0].clientY;
                const pullDistance = touchEndY - touchStartY;

                if (pullDistance > 50) {
                    // Show pull to refresh indicator
                }
            }
        }, { passive: true });

        document.addEventListener('touchend', () => {
            if (isPulling && touchEndY - touchStartY > 100) {
                this.resetAndLoad();
            }
            isPulling = false;
        }, { passive: true });
    }

    updateArrayFilter(filterName, value, checked) {
        // Ensure the filter array exists
        if (!Array.isArray(this.filters[filterName])) {
            this.filters[filterName] = [];
        }
        
        if (checked) {
            if (!this.filters[filterName].includes(value)) {
                this.filters[filterName].push(value);
            }
        } else {
            this.filters[filterName] = this.filters[filterName].filter(v => v !== value);
        }
        this.resetAndLoad();
    }

    updateSalaryDisplay() {
        const value = this.filters.salary_min;
        const displays = [
            document.getElementById('salaryDisplay'),
            document.getElementById('salaryDisplayMobile')
        ];
        
        let displayText;
        if (value === 0) {
            displayText = '$0 - $200K+';
        } else if (value >= 200000) {
            displayText = '$200K+';
        } else {
            const thousands = (value / 1000).toFixed(0);
            displayText = `$${thousands}K - $200K+`;
        }
        
        displays.forEach(display => {
            if (display) display.textContent = displayText;
        });
    }


    clearAllFilters() {
        this.filters = {
            q: '',
            loc: '',
            job_type: [],
            experience: [],
            salary_min: 0,
            date_posted: [],
            category: [],
        };

        // Reset UI (desktop and mobile)
        const salaryRanges = [document.getElementById('salaryRange'), document.getElementById('salaryRangeMobile')];
        salaryRanges.forEach(el => { if (el) el.value = 0; });
        
        // Reset location inputs
        const locationInputs = [document.getElementById('locationFilter'), document.getElementById('locationFilterMobile')];
        locationInputs.forEach(el => { if (el) el.value = ''; });
        
        // Reset pill buttons
        document.querySelectorAll('.filter-pill-btn').forEach(btn => {
            btn.classList.remove('active');
            // Restore default styles
            btn.classList.add('bg-white', 'dark:bg-gray-700', 'text-gray-700', 'dark:text-gray-300');
        });
        
        // Reset legacy checkboxes if any
        document.querySelectorAll('input[type="checkbox"]').forEach(cb => cb.checked = false);
        this.updateSalaryDisplay();
        this.resetAndLoad();
    }
    
    syncFilterPills(filterType, filterValue, isActive) {
        // Sync all pill buttons with same filter type and value (desktop and mobile)
        document.querySelectorAll(`.filter-pill-btn[data-filter="${filterType}"][data-value="${filterValue}"]`).forEach(btn => {
            if (isActive) {
                btn.classList.add('active');
                // Remove default styles, active class will handle styling via CSS
                btn.classList.remove('bg-white', 'dark:bg-gray-700', 'text-gray-700', 'dark:text-gray-300');
            } else {
                btn.classList.remove('active');
                // Restore default styles
                btn.classList.add('bg-white', 'dark:bg-gray-700', 'text-gray-700', 'dark:text-gray-300');
            }
        });
    }
    
    syncCheckboxes(filterName, value, checked) {
        // Sync desktop and mobile checkboxes (legacy support)
        const desktopName = filterName === 'date_posted' ? 'date_posted' : filterName;
        const mobileName = filterName === 'date_posted' ? 'date_posted_mobile' : 
                          filterName === 'job_type' ? 'job_type_mobile' : 
                          filterName === 'experience' ? 'experience_mobile' : filterName;
        
        document.querySelectorAll(`input[name="${desktopName}"], input[name="${mobileName}"]`).forEach(cb => {
            if (cb.value === value) {
                cb.checked = checked;
            }
        });
    }

    toggleMobileFilters() {
        const sheet = document.getElementById('mobileFilterSheet');
        const overlay = document.getElementById('filterOverlay');
        if (sheet && overlay) {
            // Sync current filter values to mobile filters
            this.syncFiltersToMobile();
            
            sheet.classList.add('show');
            overlay.classList.remove('hidden');
            overlay.classList.add('show');
            document.body.style.overflow = 'hidden';
        }
    }

    syncFiltersToMobile() {
        // Sync salary range
        const salaryRange = document.getElementById('salaryRange');
        const salaryRangeMobile = document.getElementById('salaryRangeMobile');
        if (salaryRange && salaryRangeMobile) {
            salaryRangeMobile.value = salaryRange.value;
        }

        // Sync checkboxes
        ['job_type', 'experience', 'date_posted'].forEach(filterName => {
            const desktopName = filterName === 'date_posted' ? 'date_posted' : filterName;
            const mobileName = filterName === 'date_posted' ? 'date_posted_mobile' : 
                              filterName === 'job_type' ? 'job_type_mobile' : 
                              filterName === 'experience' ? 'experience_mobile' : filterName;
            
            document.querySelectorAll(`input[name="${desktopName}"]`).forEach(desktopCb => {
                const mobileCb = document.querySelector(`input[name="${mobileName}"][value="${desktopCb.value}"]`);
                if (mobileCb) {
                    mobileCb.checked = desktopCb.checked;
                }
            });
        });
    }

    closeMobileFilters() {
        const sheet = document.getElementById('mobileFilterSheet');
        const overlay = document.getElementById('filterOverlay');
        if (sheet && overlay) {
            sheet.classList.remove('show');
            overlay.classList.remove('show');
            overlay.classList.add('hidden');
            document.body.style.overflow = '';
        }
    }

    async loadInitialJobs() {
        this.currentPage = 1;
        this.hasMore = true;
        await this.loadJobs(true);
    }

    async loadMoreJobs() {
        if (this.loading || !this.hasMore) return;
        this.currentPage++;
        await this.loadJobs(false);
    }

    resetAndLoad() {
        this.currentPage = 1;
        this.hasMore = true;
        this.loadJobs(true);
    }

    async loadJobs(reset = false) {
        if (this.loading) return;

        this.loading = true;
        const container = document.getElementById('jobListings');
        const emptyState = document.getElementById('emptyState');
        const errorState = document.getElementById('errorState');

        if (reset) {
            container.innerHTML = this.getSkeletonLoaders();
            emptyState.classList.add('hidden');
            errorState.classList.add('hidden');
        }

        try {
            const params = new URLSearchParams();
            params.append('q', this.filters.q);
            params.append('loc', this.filters.loc);
            params.append('page', this.currentPage);
            params.append('per_page', this.perPage);
            params.append('sort', this.sortBy);
            params.append('sal_min', this.filters.salary_min);

            if (this.filters.job_type.length > 0) {
                params.append('job_type', this.filters.job_type.join(','));
            }
            if (this.filters.experience.length > 0) {
                params.append('experience', this.filters.experience.join(','));
            }
            if (this.filters.date_posted.length > 0) {
                params.append('date_posted', this.filters.date_posted.join(','));
            }
            if (this.filters.category && this.filters.category.length > 0) {
                params.append('category', this.filters.category.join(','));
            }

            if (this.userLocation) {
                params.append('lat', this.userLocation.lat);
                params.append('lng', this.userLocation.lng);
            }

            const url = `${this.apiUrl}?${params.toString()}`;
            console.log('Fetching jobs from:', url);
            
            const response = await fetch(url);
            
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            
            const data = await response.json();
            console.log('Jobs data received:', data);

            if (data.success) {
                if (reset) {
                    this.jobs = data.jobs;
                } else {
                    this.jobs = [...(this.jobs || []), ...data.jobs];
                }

                this.hasMore = data.has_more;
                this.renderJobs();
                this.updateResultsCount(data.total, data.page, data.per_page);

                if (this.jobs.length === 0 && reset) {
                    emptyState.classList.remove('hidden');
                } else {
                    emptyState.classList.add('hidden');
                }

                errorState.classList.add('hidden');
            } else {
                throw new Error(data.message || 'Failed to load jobs');
            }
        } catch (error) {
            console.error('Error loading jobs:', error);
            console.error('Error details:', {
                message: error.message,
                stack: error.stack,
                apiUrl: this.apiUrl,
                baseUrl: this.baseUrl
            });
            if (reset) {
                container.innerHTML = '';
                errorState.classList.remove('hidden');
            }
        } finally {
            this.loading = false;
            this.updateLoadMoreButton();
        }
    }

    renderJobs() {
        const container = document.getElementById('jobListings');
        if (!this.jobs || this.jobs.length === 0) {
            container.innerHTML = '';
            return;
        }

        container.innerHTML = this.jobs.map(job => this.renderJobCard(job)).join('');
        this.setupJobCardActions();
    }

    renderJobCard(job) {
        const isSaved = this.savedJobs.has(job.id);
        const salary = job.salary ? `$${this.formatSalary(job.salary)}` : 'Not disclosed';
        const distance = job.distance ? `${job.distance} km away` : '';
        const skills = (job.skills || []).slice(0, 3);
        const postedTime = this.getTimeAgo(job.posted_at);

        return `
            <div class="job-card ${this.viewMode}-view" data-job-id="${job.id}">
                <div class="job-card-header">
                    <div class="job-card-company">
                        <img src="${job.company_logo || 'https://via.placeholder.com/48'}" 
                             alt="${job.company_name} logo" 
                             class="job-card-company-logo"
                             onerror="this.src='https://via.placeholder.com/48'">
                        <div>
                            <h3 class="job-card-title">${this.truncateText(job.title, 40)}</h3>
                            <p class="job-card-company-name">${job.company_name} ${job.company_rating ? `⭐ ${job.company_rating}` : ''}</p>
                        </div>
                    </div>
                    ${job.badge ? `<span class="job-card-badge ${job.badge_class || 'bg-green-100 text-green-800'}">${job.badge}</span>` : ''}
                </div>
                <p class="job-card-description">${job.description || ''}</p>
                <div class="job-card-skills">
                    ${skills.map(skill => `<span class="job-card-skill">${skill}</span>`).join('')}
                </div>
                <div class="job-card-footer">
                    <div class="job-card-meta">
                        <span>📍 ${job.location}</span>
                        ${distance ? `<span>${distance}</span>` : ''}
                        <span>💰 ${salary}</span>
                        <span>🕒 ${postedTime}</span>
                    </div>
                    <div class="job-card-actions">
                        <button class="job-card-action-btn ${isSaved ? 'saved' : ''}" 
                                onclick="jobSearch.toggleSaveJob(${job.id})"
                                aria-label="Save job">
                            <span class="material-symbols-outlined">${isSaved ? 'bookmark' : 'bookmark_border'}</span>
                        </button>
                        <button class="job-card-action-btn apply" 
                                onclick="jobSearch.applyToJob(${job.id})"
                                aria-label="Apply">
                            <span class="material-symbols-outlined">send</span>
                        </button>
                        <button class="job-card-action-btn" 
                                onclick="jobSearch.shareJob(${job.id})"
                                aria-label="Share">
                            <span class="material-symbols-outlined">share</span>
                        </button>
                    </div>
                </div>
            </div>
        `;
    }

    setupJobCardActions() {
        // Add click handler for job card to navigate to job details page
        document.querySelectorAll('.job-card').forEach(card => {
            card.style.cursor = 'pointer';
            card.addEventListener('click', (e) => {
                // Don't navigate if clicking on action buttons
                if (e.target.closest('.job-card-actions')) {
                    return;
                }
                const jobId = card.dataset.jobId;
                // Find the job to get its slug
                const job = this.jobs.find(j => j.id == jobId);
                if (job && job.slug) {
                    window.location.href = `${this.baseUrl}/job/${job.slug}/`;
                } else {
                    // Fallback: generate slug from company name, title, and ID
                    const companySlug = (job?.company_name || '').toLowerCase().replace(/[^a-z0-9]+/g, '-');
                    const titleSlug = (job?.title || '').toLowerCase().replace(/[^a-z0-9]+/g, '-');
                    window.location.href = `${this.baseUrl}/job/${companySlug}-${titleSlug}-${jobId}/`;
                }
            });
        });
    }

    getSkeletonLoaders() {
        return Array(3).fill(0).map(() => `
            <div class="skeleton-loader">
                <div class="skeleton-header"></div>
                <div class="skeleton-content"></div>
                <div class="skeleton-footer"></div>
            </div>
        `).join('');
    }

    updateResultsCount(total, page, perPage) {
        const countEl = document.getElementById('resultsCount');
        const start = (page - 1) * perPage + 1;
        const end = Math.min(page * perPage, total);
        countEl.textContent = `Showing ${start}-${end} of ${total} results`;
    }

    updateLoadMoreButton() {
        const container = document.getElementById('loadMoreContainer');
        if (this.hasMore && !this.loading) {
            container.classList.remove('hidden');
        } else {
            container.classList.add('hidden');
        }
    }

    toggleSaveJob(jobId) {
        if (this.savedJobs.has(jobId)) {
            this.savedJobs.delete(jobId);
        } else {
            this.savedJobs.add(jobId);
        }
        localStorage.setItem('savedJobs', JSON.stringify([...this.savedJobs]));
        this.renderJobs();
    }

    applyToJob(jobId) {
        // TODO: Implement apply functionality
        alert(`Applying to job #${jobId}`);
    }

    shareJob(jobId) {
        const job = this.jobs.find(j => j.id === jobId);
        if (!job) return;

        const text = `Check out this job: ${job.title} at ${job.company_name}`;
        const url = `${window.location.origin}/jobs?id=${jobId}`;
        const whatsappUrl = `https://wa.me/?text=${encodeURIComponent(text + ' ' + url)}`;

        if (navigator.share) {
            navigator.share({
                title: job.title,
                text: text,
                url: url
            }).catch(() => {
                window.open(whatsappUrl, '_blank');
            });
        } else {
            window.open(whatsappUrl, '_blank');
        }
    }

    showJobPreview(jobId) {
        // TODO: Implement quick preview modal
        console.log('Show preview for job:', jobId);
    }

    formatSalary(amount) {
        if (amount >= 1000000) {
            return `${(amount / 1000000).toFixed(1)}M`;
        } else if (amount >= 1000) {
            return `${(amount / 1000).toFixed(0)}K`;
        } else {
            return amount.toLocaleString('en-US');
        }
    }

    truncateText(text, maxLength) {
        if (text.length <= maxLength) return text;
        return text.substring(0, maxLength) + '...';
    }

    getTimeAgo(dateString) {
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

    debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }
}

// Initialize on page load
let jobSearch;
document.addEventListener('DOMContentLoaded', () => {
    jobSearch = new JobSearch();
});

