<!-- Top Navigation Bar -->
<header class="sticky top-0 z-50 w-full bg-white/80 dark:bg-background-dark/80 backdrop-blur-sm border-b border-gray-200 dark:border-gray-800">
    <div class="container mx-auto px-4 md:px-6">
        <div class="flex h-14 md:h-16 items-center justify-between">
            <div class="flex items-center gap-4">
                <a href="<?= base_url('/') ?>" class="flex items-center gap-4">
                    <div class="size-6 text-primary">
                        <svg fill="none" viewbox="0 0 48 48" xmlns="http://www.w3.org/2000/svg">
                            <path clip-rule="evenodd" d="M24 4H42V17.3333V30.6667H24V44H6V30.6667V17.3333H24V4Z" fill="currentColor" fill-rule="evenodd"></path>
                        </svg>
                    </div>
                    <h2 class="text-lg md:text-xl font-bold leading-tight tracking-[-0.015em] text-[#111318] dark:text-white">JobFind</h2>
                </a>
            </div>
            
            <!-- Desktop Menu -->
            <div class="hidden md:flex items-center gap-3">
                <?php if (session()->get('is_logged_in')): ?>
                    <!-- Guest menu (hidden when logged in) -->
                <?php else: ?>
                    <!-- Guest menu -->
                    <div class="flex items-center gap-3">
                        <a href="<?= base_url('login') ?>" class="px-4 py-2 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors text-sm font-medium">
                            Login
                        </a>
                        <a href="<?= base_url('signup') ?>" class="flex min-w-[84px] cursor-pointer items-center justify-center overflow-hidden rounded-full h-10 px-5 bg-primary text-white text-sm font-bold leading-normal tracking-[0.015em] hover:bg-primary/90 transition-colors">
                            <span class="truncate">Sign Up</span>
                        </a>
                    </div>
                <?php endif; ?>
                
                <!-- Post a Job Button -->
                <a href="/post-job" class="flex min-w-[84px] cursor-pointer items-center justify-center overflow-hidden rounded-full h-10 px-5 bg-primary text-white text-sm font-bold leading-normal tracking-[0.015em] hover:bg-primary/90 transition-colors">
                    <span class="truncate">Post a Job</span>
                </a>
                
                <?php if (session()->get('is_logged_in')): ?>
                    <!-- Logged in user menu with profile dropdown (Desktop only) -->
                    <div class="relative">
                        <!-- Profile Icon Button -->
                        <button 
                            id="profileMenuBtn"
                            type="button"
                            class="flex items-center justify-center size-10 rounded-full bg-primary text-white hover:bg-primary/90 transition-colors focus:outline-none focus:ring-2 focus:ring-primary/50 cursor-pointer z-50 relative"
                            aria-label="User menu"
                            aria-expanded="false"
                            style="pointer-events: auto;"
                        >
                            <span class="material-symbols-outlined text-xl pointer-events-none">person</span>
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
                <a href="/post-job" class="flex items-center justify-center size-10 rounded-full bg-primary text-white hover:bg-primary/90 transition-colors">
                    <span class="material-symbols-outlined text-xl">add</span>
                </a>
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
                    <a 
                        href="<?= base_url('login') ?>" 
                        class="flex items-center gap-3 px-4 py-3 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors"
                        onclick="closeMobileMenu()"
                    >
                        <span class="material-symbols-outlined text-xl">login</span>
                        <span>Login</span>
                    </a>
                    <a 
                        href="<?= base_url('signup') ?>" 
                        class="flex items-center gap-3 px-4 py-3 rounded-lg bg-primary text-white hover:bg-primary/90 transition-colors"
                        onclick="closeMobileMenu()"
                    >
                        <span class="material-symbols-outlined text-xl">person_add</span>
                        <span>Sign Up</span>
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
        
        // Initialize when DOM is ready
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', function() {
                initProfileDropdown();
                initMobileMenu();
            });
        } else {
            initProfileDropdown();
            initMobileMenu();
        }
    })();
</script>

