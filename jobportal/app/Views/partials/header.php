<!-- Top Navigation Bar -->
<header id="mainHeader" class="sticky top-0 z-50 w-full bg-white/80 dark:bg-background-dark/80 backdrop-blur-sm border-b border-gray-200 dark:border-gray-800 transition-transform duration-300 ease-in-out">
    <div class="container mx-auto px-4 md:px-6">
        <!-- Top Row: Logo, Search, Actions -->
        <div class="flex h-14 md:h-16 items-center justify-between gap-3">
            <div class="flex items-center gap-4 shrink-0">
                <a href="<?= base_url('/') ?>" class="flex items-center gap-4">
                    <div class="size-6 text-primary">
                        <svg fill="none" viewbox="0 0 48 48" xmlns="http://www.w3.org/2000/svg">
                            <path clip-rule="evenodd" d="M24 4H42V17.3333V30.6667H24V44H6V30.6667V17.3333H24V4Z" fill="currentColor" fill-rule="evenodd"></path>
                        </svg>
                    </div>
                    <h2 class="text-lg md:text-xl font-bold leading-tight tracking-[-0.015em] text-[#111318] dark:text-white">JobFind</h2>
                </a>
            </div>
            
            <!-- Search Section (Desktop) -->
            <div class="hidden md:flex items-center gap-2 flex-1 max-w-2xl mx-4">
                <label class="relative flex flex-col min-w-0 flex-1">
                    <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 dark:text-gray-500">search</span>
                    <input 
                        id="headerSearchJobInput"
                        class="form-input flex w-full min-w-0 flex-1 resize-none overflow-hidden rounded-full text-[#111318] dark:text-white focus:outline-0 focus:ring-2 focus:ring-primary/50 border-none bg-background-light dark:bg-gray-800 h-10 placeholder:text-[#616f89] dark:placeholder:text-gray-500 pl-12 pr-4 text-sm font-normal leading-normal" 
                        placeholder="Job title, skill, or company" 
                        value=""
                    />
                </label>
                <a 
                    href="/jobs" 
                    id="headerSearchBtn"
                    class="flex min-w-[100px] shrink-0 cursor-pointer items-center justify-center overflow-hidden rounded-full h-10 px-4 bg-primary text-white text-sm font-bold leading-normal tracking-[0.015em] hover:bg-primary/90 transition-colors"
                >
                    <span class="truncate">Search</span>
                </a>
            </div>
            
            <!-- Desktop Menu -->
            <div class="hidden md:flex items-center gap-3 shrink-0">
                <!-- Navigation Links -->
                <a href="<?= base_url('/') ?>" class="px-4 py-2 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors text-sm font-medium">
                    Home
                </a>
                <a href="<?= base_url('jobs') ?>" class="px-4 py-2 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors text-sm font-medium">
                    Find a job
                </a>
                
                <?php if (session()->get('is_logged_in')): ?>
                    <!-- Guest menu (hidden when logged in) -->
                <?php else: ?>
                    <!-- Guest menu -->
                    <div class="flex items-center gap-3">
                        <a href="<?= base_url('login') ?>" class="flex min-w-[84px] cursor-pointer items-center justify-center overflow-hidden rounded-full h-10 px-5 text-sm font-bold leading-normal tracking-[0.015em] transition-colors" style="background-color: white; color: #000000; border: 2px solid #000000;" onmouseover="this.style.backgroundColor='#f5f5f5'" onmouseout="this.style.backgroundColor='white'">
                            <span class="truncate">Log In</span>
                        </a>
                        <a href="<?= base_url('signup') ?>" class="flex min-w-[84px] cursor-pointer items-center justify-center overflow-hidden rounded-full h-10 px-5 text-sm font-bold leading-normal tracking-[0.015em] transition-colors" style="background-color: #000000; color: white;" onmouseover="this.style.backgroundColor='#333333'" onmouseout="this.style.backgroundColor='#000000'">
                            <span class="truncate">Sign Up</span>
                        </a>
                    </div>
                <?php endif; ?>
                
                <!-- Post a Job Button -->
                <a href="<?= base_url('post-job') ?>" class="flex min-w-[84px] cursor-pointer items-center justify-center overflow-hidden rounded-full h-10 px-5 text-sm font-bold leading-normal tracking-[0.015em] transition-colors" style="background-color: #2bee79; color: #0e2016;" onmouseover="this.style.backgroundColor='#25d46a'" onmouseout="this.style.backgroundColor='#2bee79'">
                    <span class="truncate">Post a Job</span>
                </a>
                
                <?php if (session()->get('is_logged_in')): ?>
                    <!-- Logged in user menu with profile dropdown (Desktop only) -->
                    <div class="relative">
                        <!-- Profile Icon Button -->
                        <button 
                            id="profileMenuBtn"
                            type="button"
                            class="flex items-center justify-center size-10 rounded-full bg-primary text-white hover:bg-primary/90 transition-colors focus:outline-none focus:ring-2 focus:ring-primary/50 cursor-pointer z-50 relative overflow-hidden"
                            aria-label="User menu"
                            aria-expanded="false"
                            style="pointer-events: auto;"
                        >
                            <?php if (session()->get('profile_picture')): ?>
                                <img 
                                    src="<?= esc(session()->get('profile_picture')) ?>" 
                                    alt="Profile" 
                                    class="w-full h-full object-cover pointer-events-none"
                                    onerror="this.style.display='none'; this.nextElementSibling.style.display='block';"
                                />
                                <span class="material-symbols-outlined text-xl pointer-events-none" style="display: none;">person</span>
                            <?php else: ?>
                                <span class="material-symbols-outlined text-xl pointer-events-none">person</span>
                            <?php endif; ?>
                        </button>
                        
                        <!-- Dropdown Menu -->
                        <div 
                            id="profileDropdown" 
                            class="hidden absolute right-0 mt-2 w-64 bg-white dark:bg-gray-800 shadow-2xl border border-gray-200 dark:border-gray-700 py-3 z-[100]"
                        >
                            <!-- User Info -->
                            <div class="px-4 py-3 border-b border-gray-200 dark:border-gray-700">
                                <p class="text-base font-semibold text-[#111318] dark:text-white">
                                    <?= esc(session()->get('first_name') ? session()->get('first_name') . ' ' . session()->get('last_name') : session()->get('email')) ?>
                                </p>
                                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1 truncate">
                                    <?= esc(session()->get('email')) ?>
                                </p>
                            </div>
                            
                            <!-- Profile -->
                            <div class="py-1">
                                <a 
                                    href="<?= base_url('profile') ?>" 
                                    class="flex items-center gap-3 px-4 py-2.5 text-sm text-[#111318] dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors"
                                >
                                    <span class="material-symbols-outlined text-lg">person</span>
                                    <span>Profile</span>
                                </a>
                            </div>
                            
                            <!-- Sign Out -->
                            <div class="py-1">
                                <a 
                                    href="<?= base_url('logout') ?>" 
                                    class="flex items-center gap-3 px-4 py-2.5 text-sm text-[#111318] dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors"
                                >
                                    <span class="material-symbols-outlined text-lg">logout</span>
                                    <span>Sign out</span>
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
            
            <!-- Mobile Menu Button -->
            <div class="flex md:hidden items-center gap-2">
                <?php if (!session()->get('is_logged_in')): ?>
                    <!-- Guest buttons on mobile -->
                    <a href="<?= base_url('login') ?>" class="flex min-w-[70px] cursor-pointer items-center justify-center overflow-hidden rounded-full h-10 px-3 text-xs font-bold leading-normal tracking-[0.015em] transition-colors" style="background-color: white; color: #000000; border: 2px solid #000000;" onmouseover="this.style.backgroundColor='#f5f5f5'" onmouseout="this.style.backgroundColor='white'">
                        <span class="truncate">Log In</span>
                    </a>
                    <a href="<?= base_url('signup') ?>" class="flex min-w-[70px] cursor-pointer items-center justify-center overflow-hidden rounded-full h-10 px-3 text-xs font-bold leading-normal tracking-[0.015em] transition-colors" style="background-color: #000000; color: white;" onmouseover="this.style.backgroundColor='#333333'" onmouseout="this.style.backgroundColor='#000000'">
                        <span class="truncate">Sign Up</span>
                </a>
                <?php endif; ?>
                <button 
                    id="mobileMenuBtn"
                    type="button"
                    class="flex items-center justify-center size-10 rounded-full text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors"
                    aria-label="Menu"
                    aria-expanded="false"
                >
                    <span class="material-symbols-outlined text-xl" id="menuIcon">menu</span>
                </button>
            </div>
        </div>
        
        <!-- Mobile Search Section -->
        <div class="md:hidden pb-2 pt-2">
            <div class="flex w-full flex-nowrap items-center gap-2">
                <label class="relative flex flex-col min-w-0 flex-1">
                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 dark:text-gray-500 text-lg">search</span>
                    <input 
                        id="headerSearchJobInputMobile"
                        class="form-input flex w-full min-w-0 flex-1 resize-none overflow-hidden rounded-full text-[#111318] dark:text-white focus:outline-0 focus:ring-2 focus:ring-primary/50 border-none bg-background-light dark:bg-gray-800 h-10 placeholder:text-[#616f89] dark:placeholder:text-gray-500 pl-10 pr-3 text-sm font-normal leading-normal" 
                        placeholder="Job title, skill, or company" 
                        value=""
                    />
                </label>
                <a 
                    href="/jobs" 
                    id="headerSearchBtnMobile"
                    class="flex min-w-[80px] shrink-0 cursor-pointer items-center justify-center overflow-hidden rounded-full h-10 px-4 bg-primary text-white text-sm font-bold leading-normal tracking-[0.015em] hover:bg-primary/90 transition-colors"
                >
                    <span class="truncate">Search</span>
                </a>
            </div>
        </div>
    </div>
</header>

<!-- Mobile Menu Overlay -->
<div id="mobileMenuOverlay" class="hidden fixed inset-0 bg-black/50 z-40 md:hidden"></div>

<!-- Mobile Menu -->
<div 
    id="mobileMenu" 
    class="fixed top-0 right-0 h-full w-80 bg-white dark:bg-gray-800 shadow-2xl transform translate-x-full transition-transform duration-300 z-50 md:hidden overflow-y-auto"
>
    <div class="flex flex-col h-full">
        <!-- Mobile Menu Header -->
        <div class="flex items-center justify-between p-4 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-bold text-[#111318] dark:text-white">Menu</h3>
            <button 
                id="closeMobileMenuBtn"
                type="button"
                class="flex items-center justify-center size-8 rounded-full hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors"
                aria-label="Close menu"
            >
                <span class="material-symbols-outlined text-xl">close</span>
            </button>
        </div>
        
        <!-- Post a Job Button (Top of Menu) -->
        <div class="p-4 border-b border-gray-200 dark:border-gray-700">
            <a 
                href="<?= base_url('post-job') ?>" 
                class="flex items-center justify-center w-full rounded-full h-12 transition-colors font-bold text-sm"
                style="background-color: #2bee79; color: #0e2016;"
                onmouseover="this.style.backgroundColor='#25d46a'"
                onmouseout="this.style.backgroundColor='#2bee79'"
                onclick="closeMobileMenu()"
            >
                <span class="material-symbols-outlined text-xl mr-2">add</span>
                <span>Post a Job</span>
            </a>
        </div>
        
        <!-- Mobile Menu Content -->
        <div class="flex-1 p-4">
            <?php if (session()->get('is_logged_in')): ?>
                <!-- Logged in user menu -->
                <div class="mb-6 pb-6 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="flex items-center justify-center size-12 rounded-full bg-primary text-white">
                            <span class="material-symbols-outlined text-2xl">person</span>
                        </div>
                        <div>
                            <p class="text-base font-semibold text-[#111318] dark:text-white">
                                <?= esc(session()->get('first_name') ? session()->get('first_name') . ' ' . session()->get('last_name') : session()->get('email')) ?>
                            </p>
                            <p class="text-sm text-gray-500 dark:text-gray-400 truncate">
                                <?= esc(session()->get('email')) ?>
                            </p>
                        </div>
                    </div>
                </div>
                
                <!-- Menu Items -->
                <div class="space-y-2">
                    <?php if (session()->get('user_type') === 'admin'): ?>
                        <a 
                            href="<?= base_url('admin/dashboard') ?>" 
                            class="flex items-center gap-3 px-4 py-3 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors"
                            onclick="closeMobileMenu()"
                        >
                            <span class="material-symbols-outlined text-xl">dashboard</span>
                            <span>Dashboard</span>
                        </a>
                    <?php endif; ?>
                    <a 
                        href="<?= base_url('/') ?>" 
                        class="flex items-center gap-3 px-4 py-3 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors"
                        onclick="closeMobileMenu()"
                    >
                        <span class="material-symbols-outlined text-xl">home</span>
                        <span>Home</span>
                    </a>
                    <a 
                        href="<?= base_url('jobs') ?>" 
                        class="flex items-center gap-3 px-4 py-3 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors"
                        onclick="closeMobileMenu()"
                    >
                        <span class="material-symbols-outlined text-xl">search</span>
                        <span>Browse Jobs</span>
                    </a>
                    <a 
                        href="<?= base_url('profile') ?>" 
                        class="flex items-center gap-3 px-4 py-3 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors"
                        onclick="closeMobileMenu()"
                    >
                        <span class="material-symbols-outlined text-xl">account_circle</span>
                        <span>My Profile</span>
                    </a>
                    <a 
                        href="<?= base_url('saved-jobs') ?>" 
                        class="flex items-center gap-3 px-4 py-3 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors"
                        onclick="closeMobileMenu()"
                    >
                        <span class="material-symbols-outlined text-xl">bookmark</span>
                        <span>Saved Jobs</span>
                    </a>
                </div>
                
                <!-- Sign Out -->
                <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                    <a 
                        href="<?= base_url('logout') ?>" 
                        class="flex items-center gap-3 px-4 py-3 rounded-lg text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors"
                        onclick="closeMobileMenu()"
                    >
                        <span class="material-symbols-outlined text-xl">logout</span>
                        <span>Sign out</span>
                    </a>
                </div>
            <?php else: ?>
                <!-- Guest menu -->
                <div class="space-y-2">
                    <a 
                        href="<?= base_url('/') ?>" 
                        class="flex items-center gap-3 px-4 py-3 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors"
                        onclick="closeMobileMenu()"
                    >
                        <span class="material-symbols-outlined text-xl">home</span>
                        <span>Home</span>
                    </a>
                    <a 
                        href="<?= base_url('jobs') ?>" 
                        class="flex items-center gap-3 px-4 py-3 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors"
                        onclick="closeMobileMenu()"
                    >
                        <span class="material-symbols-outlined text-xl">search</span>
                        <span>Browse Jobs</span>
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
    // Profile dropdown toggle (Desktop only)
    (function() {
        function initProfileDropdown() {
            const profileBtn = document.getElementById('profileMenuBtn');
            const profileDropdown = document.getElementById('profileDropdown');
            
            if (!profileBtn || !profileDropdown) {
                return;
            }
            
            // Toggle dropdown on click (collapse/expand)
            profileBtn.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                const isHidden = profileDropdown.classList.contains('hidden');
                
                if (isHidden) {
                    // Open dropdown
                    profileDropdown.classList.remove('hidden');
                    profileBtn.setAttribute('aria-expanded', 'true');
                } else {
                    // Close dropdown (collapse)
                    profileDropdown.classList.add('hidden');
                    profileBtn.setAttribute('aria-expanded', 'false');
                }
            });
            
            // Close dropdown when clicking outside
            document.addEventListener('click', function(e) {
                if (!profileBtn.contains(e.target) && !profileDropdown.contains(e.target)) {
                    profileDropdown.classList.add('hidden');
                    profileBtn.setAttribute('aria-expanded', 'false');
                }
            });
            
            // Close dropdown on escape key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    profileDropdown.classList.add('hidden');
                    profileBtn.setAttribute('aria-expanded', 'false');
                }
            });
            
            // Close dropdown when clicking on menu items
            profileDropdown.querySelectorAll('a').forEach(link => {
                link.addEventListener('click', function() {
                    profileDropdown.classList.add('hidden');
                    profileBtn.setAttribute('aria-expanded', 'false');
                });
            });
        }
        
        // Mobile menu toggle
        function initMobileMenu() {
            const mobileMenuBtn = document.getElementById('mobileMenuBtn');
            const closeMobileMenuBtn = document.getElementById('closeMobileMenuBtn');
            const mobileMenu = document.getElementById('mobileMenu');
            const mobileMenuOverlay = document.getElementById('mobileMenuOverlay');
            const menuIcon = document.getElementById('menuIcon');
            
            if (!mobileMenuBtn || !mobileMenu) {
                return;
            }
            
            function openMobileMenu() {
                mobileMenu.classList.remove('translate-x-full');
                mobileMenuOverlay.classList.remove('hidden');
                mobileMenuBtn.setAttribute('aria-expanded', 'true');
                document.body.style.overflow = 'hidden';
            }
            
            function closeMobileMenu() {
                mobileMenu.classList.add('translate-x-full');
                mobileMenuOverlay.classList.add('hidden');
                mobileMenuBtn.setAttribute('aria-expanded', 'false');
                document.body.style.overflow = '';
            }
            
            // Open menu
            mobileMenuBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                openMobileMenu();
            });
            
            // Close menu buttons
            if (closeMobileMenuBtn) {
                closeMobileMenuBtn.addEventListener('click', closeMobileMenu);
            }
            
            mobileMenuOverlay.addEventListener('click', closeMobileMenu);
            
            // Close on escape key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && !mobileMenu.classList.contains('translate-x-full')) {
                    closeMobileMenu();
                }
            });
            
            // Make closeMobileMenu available globally
            window.closeMobileMenu = closeMobileMenu;
        }
        
        // Header scroll hide/show functionality
        function initHeaderScroll() {
            const header = document.getElementById('mainHeader');
            const filterPills = document.getElementById('filterPillsSection');
            
            if (!header) return;
            
            let lastScrollTop = 0;
            let scrollThreshold = 10; // Minimum scroll distance to trigger hide/show
            let ticking = false;
            
            function updateHeader() {
                const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
                
                // Only hide/show if scrolled more than threshold
                if (Math.abs(scrollTop - lastScrollTop) < scrollThreshold) {
                    ticking = false;
                    return;
                }
                
                if (scrollTop > lastScrollTop && scrollTop > 100) {
                    // Scrolling down - hide header and filter pills
                    header.style.transform = 'translateY(-100%)';
                    if (filterPills) {
                        filterPills.style.transform = 'translateY(-100%)';
                    }
                } else {
                    // Scrolling up - show header and filter pills
                    header.style.transform = 'translateY(0)';
                    if (filterPills) {
                        filterPills.style.transform = 'translateY(0)';
                    }
                }
                
                lastScrollTop = scrollTop <= 0 ? 0 : scrollTop;
                ticking = false;
            }
            
            function onScroll() {
                if (!ticking) {
                    window.requestAnimationFrame(updateHeader);
                    ticking = true;
                }
            }
            
            window.addEventListener('scroll', onScroll, { passive: true });
        }
        
        // Header search functionality
        function initHeaderSearch() {
            // Desktop search
            const desktopSearchInput = document.getElementById('headerSearchJobInput');
            const desktopSearchBtn = document.getElementById('headerSearchBtn');
            
            // Mobile search
            const mobileSearchInput = document.getElementById('headerSearchJobInputMobile');
            const mobileSearchBtn = document.getElementById('headerSearchBtnMobile');
            
            function handleSearch(searchInput, e) {
                if (e) {
                    e.preventDefault();
                }
                const job = searchInput.value.trim();
                if (job) {
                    window.location.href = `/jobs?q=${encodeURIComponent(job)}`;
                } else {
                    window.location.href = '/jobs';
                }
            }
            
            // Desktop search handlers
            if (desktopSearchBtn) {
                desktopSearchBtn.addEventListener('click', (e) => handleSearch(desktopSearchInput, e));
            }
            if (desktopSearchInput) {
                desktopSearchInput.addEventListener('keypress', (e) => {
                    if (e.key === 'Enter') {
                        handleSearch(desktopSearchInput, e);
                    }
                });
            }
            
            // Mobile search handlers
            if (mobileSearchBtn) {
                mobileSearchBtn.addEventListener('click', (e) => handleSearch(mobileSearchInput, e));
            }
            if (mobileSearchInput) {
                mobileSearchInput.addEventListener('keypress', (e) => {
                    if (e.key === 'Enter') {
                        handleSearch(mobileSearchInput, e);
                    }
                });
            }
        }
        
        // Initialize when DOM is ready
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', function() {
                initProfileDropdown();
                initMobileMenu();
                initHeaderSearch();
                initHeaderScroll();
            });
        } else {
            initProfileDropdown();
            initMobileMenu();
            initHeaderSearch();
            initHeaderScroll();
        }
    })();
</script>

