<?= view('partials/head', ['title' => 'Profile - TopTopJobs']) ?>
<body class="font-display bg-background-light dark:bg-background-dark text-[#111318] dark:text-gray-200">
    <div class="relative flex min-h-screen w-full flex-col">
        <?= view('partials/header') ?>

        <main class="flex-grow container mx-auto px-4 md:px-6 py-8 md:py-12">
            <div class="max-w-4xl mx-auto">
                <h1 class="text-3xl font-bold text-[#111318] dark:text-white mb-8">Profile Settings</h1>
                
                <!-- Manage Jobs Link -->
                <div class="mb-6">
                    <a 
                        href="<?= base_url('manage-jobs') ?>" 
                        class="inline-flex items-center gap-2 px-6 py-2.5 rounded-full font-bold transition-colors"
                        style="background-color: #2bee79; color: #0e2016;"
                        onmouseover="this.style.backgroundColor='#25d46a'"
                        onmouseout="this.style.backgroundColor='#2bee79'"
                    >
                        <span class="material-symbols-outlined">work</span>
                        <span>Manage Jobs</span>
                    </a>
                </div>

                <!-- Success/Error Messages -->
                <?php if (session()->getFlashdata('success')): ?>
                    <div class="mb-6 p-4 bg-green-100 dark:bg-green-900/50 border border-green-400 dark:border-green-700 text-green-700 dark:text-green-300 rounded-lg">
                        <?= esc(session()->getFlashdata('success')) ?>
                    </div>
                <?php endif; ?>

                <?php if (session()->getFlashdata('error')): ?>
                    <div class="mb-6 p-4 bg-red-100 dark:bg-red-900/50 border border-red-400 dark:border-red-700 text-red-700 dark:text-red-300 rounded-lg">
                        <?= esc(session()->getFlashdata('error')) ?>
                    </div>
                <?php endif; ?>

                <?php if (session()->getFlashdata('errors')): ?>
                    <div class="mb-6 p-4 bg-red-100 dark:bg-red-900/50 border border-red-400 dark:border-red-700 text-red-700 dark:text-red-300 rounded-lg">
                        <ul class="list-disc list-inside">
                            <?php foreach (session()->getFlashdata('errors') as $error): ?>
                                <li><?= esc($error) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <!-- Left Column - Profile Picture -->
                    <div class="lg:col-span-1">
                        <div class="bg-white dark:bg-gray-800/50 rounded-lg p-6 border border-gray-200 dark:border-gray-700/50">
                            <h2 class="text-lg font-bold text-[#111318] dark:text-white mb-4">Profile Picture</h2>
                            <div class="flex flex-col items-center">
                                <div id="profilePicturePreview" class="relative mb-4">
                                    <img 
                                        id="profilePictureImg" 
                                        src="<?= esc($user['profile_picture'] ?? 'https://via.placeholder.com/150') ?>" 
                                        alt="Profile Picture" 
                                        class="w-32 h-32 rounded-full object-cover border-4 border-gray-200 dark:border-gray-700"
                                        onerror="this.src='https://via.placeholder.com/150'"
                                    />
                                </div>
                                <form action="<?= base_url('profile/update') ?>" method="POST" enctype="multipart/form-data" id="profilePictureForm">
                                    <input type="file" name="profile_picture" id="profilePictureInput" accept="image/png,image/jpg,image/jpeg,image/gif" class="hidden" />
                                    <label for="profilePictureInput" class="cursor-pointer inline-flex items-center justify-center gap-2 px-4 py-2 rounded-full text-sm font-bold transition-colors" style="background-color: #2bee79; color: #0e2016;" onmouseover="this.style.backgroundColor='#25d46a'" onmouseout="this.style.backgroundColor='#2bee79'">
                                        <span class="material-symbols-outlined text-lg">photo_camera</span>
                                        <span>Change Picture</span>
                                    </label>
                                </form>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-2 text-center">JPG, PNG or GIF. Max size 2MB</p>
                            </div>
                        </div>
                    </div>

                    <!-- Right Column - Profile Information -->
                    <div class="lg:col-span-2 space-y-6">
                        <!-- Personal Information -->
                        <div class="bg-white dark:bg-gray-800/50 rounded-lg p-6 border border-gray-200 dark:border-gray-700/50">
                            <h2 class="text-lg font-bold text-[#111318] dark:text-white mb-6">Personal Information</h2>
                            <form action="<?= base_url('profile/update') ?>" method="POST" enctype="multipart/form-data">
                                <div class="space-y-4">
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <label for="first_name" class="block text-sm font-medium text-[#111318] dark:text-gray-300 mb-2">First Name</label>
                                            <input 
                                                type="text" 
                                                id="first_name" 
                                                name="first_name" 
                                                value="<?= esc(old('first_name', $user['first_name'] ?? '')) ?>"
                                                class="w-full px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-[#111318] dark:text-white focus:outline-none focus:ring-2 focus:ring-primary/50 focus:border-primary transition-colors"
                                                placeholder="Enter your first name"
                                            />
                                        </div>
                                        <div>
                                            <label for="last_name" class="block text-sm font-medium text-[#111318] dark:text-gray-300 mb-2">Last Name</label>
                                            <input 
                                                type="text" 
                                                id="last_name" 
                                                name="last_name" 
                                                value="<?= esc(old('last_name', $user['last_name'] ?? '')) ?>"
                                                class="w-full px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-[#111318] dark:text-white focus:outline-none focus:ring-2 focus:ring-primary/50 focus:border-primary transition-colors"
                                                placeholder="Enter your last name"
                                            />
                                        </div>
                                    </div>
                                    <div>
                                        <label for="email" class="block text-sm font-medium text-[#111318] dark:text-gray-300 mb-2">Email</label>
                                        <input 
                                            type="email" 
                                            id="email" 
                                            name="email" 
                                            value="<?= esc($user['email'] ?? '') ?>"
                                            class="w-full px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-gray-100 dark:bg-gray-800 text-gray-500 dark:text-gray-400 cursor-not-allowed"
                                            disabled
                                            readonly
                                        />
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Email cannot be changed</p>
                                    </div>
                                    <div class="pt-4">
                                        <button 
                                            type="submit" 
                                            class="w-full md:w-auto px-6 py-2.5 rounded-full font-bold transition-colors"
                                            style="background-color: #2bee79; color: #0e2016;"
                                            onmouseover="this.style.backgroundColor='#25d46a'"
                                            onmouseout="this.style.backgroundColor='#2bee79'"
                                        >
                                            Save Changes
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <!-- Change Password -->
                        <div class="bg-white dark:bg-gray-800/50 rounded-lg p-6 border border-gray-200 dark:border-gray-700/50">
                            <h2 class="text-lg font-bold text-[#111318] dark:text-white mb-6">Change Password</h2>
                            <form action="<?= base_url('profile/password') ?>" method="POST" id="passwordForm">
                                <div class="space-y-4">
                                    <div>
                                        <label for="current_password" class="block text-sm font-medium text-[#111318] dark:text-gray-300 mb-2">Current Password</label>
                                        <input 
                                            type="password" 
                                            id="current_password" 
                                            name="current_password" 
                                            required
                                            class="w-full px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-[#111318] dark:text-white focus:outline-none focus:ring-2 focus:ring-primary/50 focus:border-primary transition-colors"
                                            placeholder="Enter your current password"
                                        />
                                    </div>
                                    <div>
                                        <label for="new_password" class="block text-sm font-medium text-[#111318] dark:text-gray-300 mb-2">New Password</label>
                                        <input 
                                            type="password" 
                                            id="new_password" 
                                            name="new_password" 
                                            required
                                            minlength="6"
                                            class="w-full px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-[#111318] dark:text-white focus:outline-none focus:ring-2 focus:ring-primary/50 focus:border-primary transition-colors"
                                            placeholder="Enter your new password"
                                        />
                                    </div>
                                    <div>
                                        <label for="confirm_password" class="block text-sm font-medium text-[#111318] dark:text-gray-300 mb-2">Confirm New Password</label>
                                        <input 
                                            type="password" 
                                            id="confirm_password" 
                                            name="confirm_password" 
                                            required
                                            minlength="6"
                                            class="w-full px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-[#111318] dark:text-white focus:outline-none focus:ring-2 focus:ring-primary/50 focus:border-primary transition-colors"
                                            placeholder="Confirm your new password"
                                        />
                                    </div>
                                    <div class="pt-4">
                                        <button 
                                            type="submit" 
                                            class="w-full md:w-auto px-6 py-2.5 rounded-full font-bold transition-colors"
                                            style="background-color: #2bee79; color: #0e2016;"
                                            onmouseover="this.style.backgroundColor='#25d46a'"
                                            onmouseout="this.style.backgroundColor='#2bee79'"
                                        >
                                            Change Password
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </main>

        <?= view('partials/footer') ?>
    </div>

    <script>
        // Profile picture preview and auto-submit
        const profilePictureInput = document.getElementById('profilePictureInput');
        const profilePictureImg = document.getElementById('profilePictureImg');
        const profilePictureForm = document.getElementById('profilePictureForm');

        if (profilePictureInput) {
            profilePictureInput.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    // Validate file size (2MB)
                    if (file.size > 2 * 1024 * 1024) {
                        alert('File size must be less than 2MB');
                        return;
                    }

                    // Validate file type
                    const validTypes = ['image/png', 'image/jpg', 'image/jpeg', 'image/gif'];
                    if (!validTypes.includes(file.type)) {
                        alert('Please select a valid image file (PNG, JPG, JPEG, or GIF)');
                        return;
                    }

                    // Preview image
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        profilePictureImg.src = e.target.result;
                    };
                    reader.readAsDataURL(file);

                    // Auto-submit form
                    profilePictureForm.submit();
                }
            });
        }

        // Password confirmation validation
        const passwordForm = document.getElementById('passwordForm');
        const newPasswordInput = document.getElementById('new_password');
        const confirmPasswordInput = document.getElementById('confirm_password');

        if (passwordForm) {
            passwordForm.addEventListener('submit', function(e) {
                const newPassword = newPasswordInput.value;
                const confirmPassword = confirmPasswordInput.value;

                if (newPassword !== confirmPassword) {
                    e.preventDefault();
                    alert('New password and confirm password do not match');
                    return false;
                }

                if (newPassword.length < 6) {
                    e.preventDefault();
                    alert('Password must be at least 6 characters long');
                    return false;
                }
            });
        }
    </script>
</body>
</html>

