// Job Search & Listing JavaScript

class JobSearch {
    constructor() {
        this.baseUrl = window.baseUrl || '';
        // Remove trailing slash if present, then add api path
        this.baseUrl = this.baseUrl.replace(/\/$/, '');
        this.apiUrl = `${this.baseUrl}/api/jobs.php`;
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
            company: '',
            skills: []
        };
        this.sortBy = 'relevant';
        this.viewMode = 'list'; // 'list' or 'grid'
        this.userLocation = null;
        this.savedJobs = new Set(JSON.parse(localStorage.getItem('savedJobs') || '[]'));

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
        // Search input
        const searchInput = document.getElementById('searchInput');
        let searchTimeout;
        searchInput.addEventListener('input', (e) => {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                this.filters.q = e.target.value;
                this.resetAndLoad();
            }, 500);
        });

        // Filter toggle
        document.getElementById('filterToggle').addEventListener('click', () => {
            this.toggleMobileFilters();
        });

        document.getElementById('closeFilters').addEventListener('click', () => {
            this.closeMobileFilters();
        });

        document.getElementById('closeMobileFilters').addEventListener('click', () => {
            this.closeMobileFilters();
        });

        document.getElementById('filterOverlay').addEventListener('click', () => {
            this.closeMobileFilters();
        });

        // Job type filters (desktop and mobile)
        document.querySelectorAll('input[name="job_type"], input[name="job_type_mobile"]').forEach(checkbox => {
            checkbox.addEventListener('change', (e) => {
                this.updateArrayFilter('job_type', e.target.value, e.target.checked);
                this.syncCheckboxes('job_type', e.target.value, e.target.checked);
            });
        });

        // Experience filters (desktop and mobile)
        document.querySelectorAll('input[name="experience"], input[name="experience_mobile"]').forEach(checkbox => {
            checkbox.addEventListener('change', (e) => {
                this.updateArrayFilter('experience', e.target.value, e.target.checked);
                this.syncCheckboxes('experience', e.target.value, e.target.checked);
            });
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
                        salaryRangeMobile.value = e.target.value;
                    } else {
                        salaryRange.value = e.target.value;
                    }
                    this.updateSalaryDisplay();
                    this.debounce(() => this.resetAndLoad(), 300)();
                });
            }
        });

        // Date posted filters (desktop and mobile)
        document.querySelectorAll('input[name="date_posted"], input[name="date_posted_mobile"]').forEach(checkbox => {
            checkbox.addEventListener('change', (e) => {
                this.updateArrayFilter('date_posted', e.target.value, e.target.checked);
                this.syncCheckboxes('date_posted', e.target.value, e.target.checked);
            });
        });

        // Company filter (desktop and mobile)
        const companyFilter = document.getElementById('companyFilter');
        const companyFilterMobile = document.getElementById('companyFilterMobile');
        let companyTimeout;
        
        [companyFilter, companyFilterMobile].forEach(input => {
            if (input) {
                input.addEventListener('input', (e) => {
                    clearTimeout(companyTimeout);
                    this.filters.company = e.target.value;
                    // Sync both inputs
                    if (e.target.id === 'companyFilter') {
                        companyFilterMobile.value = e.target.value;
                    } else {
                        companyFilter.value = e.target.value;
                    }
                    companyTimeout = setTimeout(() => {
                        this.resetAndLoad();
                    }, 500);
                });
            }
        });

        // Skills filter (desktop and mobile)
        const skillsFilter = document.getElementById('skillsFilter');
        const skillsFilterMobile = document.getElementById('skillsFilterMobile');
        
        [skillsFilter, skillsFilterMobile].forEach(input => {
            if (input) {
                input.addEventListener('keypress', (e) => {
                    if (e.key === 'Enter' && e.target.value.trim()) {
                        e.preventDefault();
                        this.addSkill(e.target.value.trim());
                        e.target.value = '';
                        // Clear the other input too
                        if (e.target.id === 'skillsFilter') {
                            skillsFilterMobile.value = '';
                        } else {
                            skillsFilter.value = '';
                        }
                    }
                });
            }
        });

        // Clear filters (desktop and mobile)
        document.getElementById('clearFilters').addEventListener('click', () => {
            this.clearAllFilters();
        });
        
        const clearFiltersMobile = document.getElementById('clearFiltersMobile');
        if (clearFiltersMobile) {
            clearFiltersMobile.addEventListener('click', () => {
                this.clearAllFilters();
                this.closeMobileFilters();
            });
        }

        // Sort
        document.getElementById('sortBy').addEventListener('change', (e) => {
            this.sortBy = e.target.value;
            this.resetAndLoad();
        });

        // View mode
        document.getElementById('viewGrid').addEventListener('click', () => {
            this.setViewMode('grid');
        });

        document.getElementById('viewList').addEventListener('click', () => {
            this.setViewMode('list');
        });

        // Back button
        document.getElementById('backBtn').addEventListener('click', () => {
            window.history.back();
        });

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
            displayText = '₹0 - ₹50L+';
        } else if (value >= 5000000) {
            displayText = '₹50L+';
        } else {
            const lakhs = (value / 100000).toFixed(0);
            displayText = `₹${lakhs}L - ₹50L+`;
        }
        
        displays.forEach(display => {
            if (display) display.textContent = displayText;
        });
    }

    addSkill(skill) {
        if (!this.filters.skills.includes(skill)) {
            this.filters.skills.push(skill);
            this.renderSkillsTags();
            this.resetAndLoad();
        }
    }

    removeSkill(skill) {
        this.filters.skills = this.filters.skills.filter(s => s !== skill);
        this.renderSkillsTags();
        this.resetAndLoad();
    }

    renderSkillsTags() {
        const containers = [
            document.getElementById('skillsTags'),
            document.getElementById('skillsTagsMobile')
        ];
        
        const tagsHtml = this.filters.skills.map(skill => `
            <span class="skill-tag">
                ${skill}
                <span class="skill-tag-remove" onclick="jobSearch.removeSkill('${skill}')">×</span>
            </span>
        `).join('');
        
        containers.forEach(container => {
            if (container) container.innerHTML = tagsHtml;
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
            company: '',
            skills: []
        };

        // Reset UI (desktop and mobile)
        document.getElementById('searchInput').value = '';
        const companyFilters = [document.getElementById('companyFilter'), document.getElementById('companyFilterMobile')];
        companyFilters.forEach(el => { if (el) el.value = ''; });
        
        const skillsFilters = [document.getElementById('skillsFilter'), document.getElementById('skillsFilterMobile')];
        skillsFilters.forEach(el => { if (el) el.value = ''; });
        
        const salaryRanges = [document.getElementById('salaryRange'), document.getElementById('salaryRangeMobile')];
        salaryRanges.forEach(el => { if (el) el.value = 0; });
        
        document.querySelectorAll('input[type="checkbox"]').forEach(cb => cb.checked = false);
        this.updateSalaryDisplay();
        this.renderSkillsTags();
        this.resetAndLoad();
    }
    
    syncCheckboxes(filterName, value, checked) {
        // Sync desktop and mobile checkboxes
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
        sheet.classList.add('show');
        overlay.classList.add('show');
        document.body.style.overflow = 'hidden';
    }

    closeMobileFilters() {
        const sheet = document.getElementById('mobileFilterSheet');
        const overlay = document.getElementById('filterOverlay');
        sheet.classList.remove('show');
        overlay.classList.remove('show');
        document.body.style.overflow = '';
    }

    setViewMode(mode) {
        this.viewMode = mode;
        const listings = document.getElementById('jobListings');
        if (mode === 'grid') {
            listings.classList.add('grid-view-container');
            listings.classList.remove('list-view-container');
            document.getElementById('viewGrid').classList.add('bg-primary/10', 'text-primary');
            document.getElementById('viewList').classList.remove('bg-primary/10', 'text-primary');
        } else {
            listings.classList.add('list-view-container');
            listings.classList.remove('grid-view-container');
            document.getElementById('viewList').classList.add('bg-primary/10', 'text-primary');
            document.getElementById('viewGrid').classList.remove('bg-primary/10', 'text-primary');
        }
        this.renderJobs();
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
            if (this.filters.company) {
                params.append('company', this.filters.company);
            }
            if (this.filters.skills.length > 0) {
                params.append('skills', this.filters.skills.join(','));
            }

            if (this.userLocation) {
                params.append('lat', this.userLocation.lat);
                params.append('lng', this.userLocation.lng);
            }

            const response = await fetch(`${this.apiUrl}?${params.toString()}`);
            const data = await response.json();

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
        const salary = job.salary ? `₹${this.formatSalary(job.salary)}` : 'Not disclosed';
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
        // Add click handler for job title (quick preview)
        document.querySelectorAll('.job-card-title').forEach(title => {
            title.addEventListener('click', (e) => {
                const jobCard = e.target.closest('.job-card');
                const jobId = jobCard.dataset.jobId;
                this.showJobPreview(jobId);
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
        if (amount >= 10000000) {
            return `${(amount / 10000000).toFixed(1)}Cr`;
        } else if (amount >= 100000) {
            return `${(amount / 100000).toFixed(0)}L`;
        } else {
            return amount.toLocaleString('en-IN');
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
        const diffMins = Math.floor(diffMs / 60000);
        const diffHours = Math.floor(diffMs / 3600000);
        const diffDays = Math.floor(diffMs / 86400000);

        if (diffMins < 60) return `${diffMins} min ago`;
        if (diffHours < 24) return `${diffHours} hour${diffHours > 1 ? 's' : ''} ago`;
        if (diffDays < 7) return `${diffDays} day${diffDays > 1 ? 's' : ''} ago`;
        return `${Math.floor(diffDays / 7)} week${Math.floor(diffDays / 7) > 1 ? 's' : ''} ago`;
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

