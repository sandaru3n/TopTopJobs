<?= view('partials/head', ['title' => 'Edit Job - TopTopJobs']) ?>
<body class="font-display bg-background-light dark:bg-background-dark text-[#111318] dark:text-gray-200">
    <div class="relative flex min-h-screen w-full flex-col">
        <?= view('partials/header') ?>

        <main class="flex-grow container mx-auto px-4 py-6 md:py-8 max-w-4xl">
            <!-- Page Header -->
            <div class="mb-6 md:mb-8">
                <h1 class="text-2xl md:text-3xl font-bold text-[#111318] dark:text-white mb-2">Edit Job</h1>
                <p class="text-sm md:text-base text-gray-600 dark:text-gray-400">Update your job listing information</p>
            </div>

            <!-- Success/Error Messages -->
            <?php if (session()->getFlashdata('success')): ?>
                <div class="mb-6 p-4 rounded-lg bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800">
                    <p class="text-sm text-green-800 dark:text-green-300"><?= esc(session()->getFlashdata('success')) ?></p>
                </div>
            <?php endif; ?>

            <?php if (session()->getFlashdata('error')): ?>
                <div class="mb-6 p-4 rounded-lg bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800">
                    <p class="text-sm text-red-800 dark:text-red-300"><?= esc(session()->getFlashdata('error')) ?></p>
                </div>
            <?php endif; ?>

            <?php if (session()->getFlashdata('errors')): ?>
                <div class="mb-6 p-4 rounded-lg bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800">
                    <ul class="list-disc list-inside text-sm text-red-800 dark:text-red-300">
                        <?php foreach (session()->getFlashdata('errors') as $error): ?>
                            <li><?= esc($error) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <!-- Job Edit Form -->
            <form action="<?= base_url('update-job/' . $job['id']) ?>" method="POST" enctype="multipart/form-data" id="editJobForm" class="space-y-6 md:space-y-8">
                <?= csrf_field() ?>

                <!-- Job Information Section -->
                <div class="bg-white dark:bg-gray-800/50 rounded-lg p-4 md:p-6 border border-gray-200 dark:border-gray-700">
                    <h2 class="text-xl font-bold text-[#111318] dark:text-white mb-4 md:mb-6">Job Information</h2>
                    
                    <div class="space-y-4 md:space-y-5">
                        <!-- Job Title -->
                        <div>
                            <label for="job_title" class="block text-sm font-medium text-[#111318] dark:text-gray-300 mb-2">
                                Job Title <span class="text-red-500">*</span>
                            </label>
                            <input 
                                type="text" 
                                id="job_title" 
                                name="job_title" 
                                required
                                value="<?= esc(old('job_title', $job['title'])) ?>"
                                class="w-full px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-[#111318] dark:text-white focus:outline-none focus:ring-2 focus:ring-primary/50 focus:border-primary transition-colors"
                                placeholder="e.g., Senior Software Engineer"
                            />
                        </div>

                        <!-- Application Email/URL -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="application_email" class="block text-sm font-medium text-[#111318] dark:text-gray-300 mb-2">
                                    Application Email
                                </label>
                                <input 
                                    type="email" 
                                    id="application_email" 
                                    name="application_email" 
                                    value="<?= esc(old('application_email', $applicationEmail ?? '')) ?>"
                                    class="w-full px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-[#111318] dark:text-white focus:outline-none focus:ring-2 focus:ring-primary/50 focus:border-primary transition-colors"
                                    placeholder="jobs@company.com"
                                />
                            </div>
                            <div>
                                <label for="application_url" class="block text-sm font-medium text-[#111318] dark:text-gray-300 mb-2">
                                    Application URL
                                </label>
                                <input 
                                    type="url" 
                                    id="application_url" 
                                    name="application_url" 
                                    value="<?= esc(old('application_url', $applicationUrl ?? '')) ?>"
                                    class="w-full px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-[#111318] dark:text-white focus:outline-none focus:ring-2 focus:ring-primary/50 focus:border-primary transition-colors"
                                    placeholder="https://company.com/apply"
                                />
                            </div>
                        </div>

                        <!-- Location and Remote -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="location" class="block text-sm font-medium text-[#111318] dark:text-gray-300 mb-2">
                                    Location <span class="text-red-500">*</span>
                                </label>
                                <input 
                                    type="text" 
                                    id="location" 
                                    name="location" 
                                    required
                                    value="<?= esc(old('location', $job['location'])) ?>"
                                    class="w-full px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-[#111318] dark:text-white focus:outline-none focus:ring-2 focus:ring-primary/50 focus:border-primary transition-colors"
                                    placeholder="e.g., New York, NY"
                                />
                            </div>
                            <div class="flex items-end">
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input 
                                        type="checkbox" 
                                        id="is_remote" 
                                        name="is_remote" 
                                        value="1"
                                        <?= (old('is_remote') || $job['is_remote']) ? 'checked' : '' ?>
                                        class="w-5 h-5 rounded border-gray-300 dark:border-gray-600 text-primary focus:ring-primary/50 cursor-pointer"
                                    />
                                    <span class="text-sm font-medium text-[#111318] dark:text-gray-300">Remote Job</span>
                                </label>
                            </div>
                        </div>

                        <!-- Job Type -->
                        <div>
                            <label for="job_type" class="block text-sm font-medium text-[#111318] dark:text-gray-300 mb-2">
                                Job Type <span class="text-red-500">*</span>
                            </label>
                            <select 
                                id="job_type" 
                                name="job_type" 
                                required
                                class="w-full px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-[#111318] dark:text-white focus:outline-none focus:ring-2 focus:ring-primary/50 focus:border-primary transition-colors cursor-pointer"
                            >
                                <option value="">Select Job Type</option>
                                <option value="full-time" <?= (old('job_type', $job['job_type']) === 'full-time') ? 'selected' : '' ?>>Full-time</option>
                                <option value="part-time" <?= (old('job_type', $job['job_type']) === 'part-time') ? 'selected' : '' ?>>Part-time</option>
                                <option value="internship" <?= (old('job_type', $job['job_type']) === 'internship') ? 'selected' : '' ?>>Internship</option>
                                <option value="contract" <?= (old('job_type', $job['job_type']) === 'contract') ? 'selected' : '' ?>>Contract</option>
                                <option value="remote" <?= (old('job_type', $job['job_type']) === 'remote') ? 'selected' : '' ?>>Remote</option>
                            </select>
                        </div>

                        <!-- Application Phone Number -->
                        <div>
                            <label for="application_phone" class="block text-sm font-medium text-[#111318] dark:text-gray-300 mb-2">
                                Application Phone Number
                            </label>
                            <input 
                                type="tel" 
                                id="application_phone" 
                                name="application_phone" 
                                value="<?= esc(old('application_phone', $applicationPhone ?? '')) ?>"
                                class="w-full px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-[#111318] dark:text-white focus:outline-none focus:ring-2 focus:ring-primary/50 focus:border-primary transition-colors"
                                placeholder="+1 (555) 123-4567"
                            />
                        </div>

                        <!-- Monthly Salary -->
                        <div>
                            <label for="monthly_salary" class="block text-sm font-medium text-[#111318] dark:text-gray-300 mb-2">
                                Monthly Salary (Optional)
                            </label>
                            <div class="flex items-center gap-2">
                                <span class="text-gray-500 dark:text-gray-400">$</span>
                                <input 
                                    type="number" 
                                    id="monthly_salary" 
                                    name="monthly_salary" 
                                    min="0"
                                    step="100"
                                    value="<?= esc(old('monthly_salary', $monthlySalary ?? '')) ?>"
                                    class="flex-1 px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-[#111318] dark:text-white focus:outline-none focus:ring-2 focus:ring-primary/50 focus:border-primary transition-colors"
                                    placeholder="5000"
                                />
                            </div>
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Leave blank if salary is not disclosed</p>
                        </div>

                        <!-- Job Category -->
                        <div>
                            <label for="job_category" class="block text-sm font-medium text-[#111318] dark:text-gray-300 mb-2">
                                Job Category <span class="text-red-500">*</span>
                            </label>
                            <select 
                                id="job_category" 
                                name="job_category" 
                                required
                                class="w-full px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-[#111318] dark:text-white focus:outline-none focus:ring-2 focus:ring-primary/50 focus:border-primary transition-colors cursor-pointer"
                            >
                                <option value="">Select Category</option>
                                <?php 
                                $categories = ['Cashier', 'Data Entry', 'IT/Software', 'Marketing', 'Sales', 'Customer Service', 'Design', 'Engineering', 'Finance', 'Healthcare', 'Education', 'Other'];
                                $selectedCategory = old('job_category', $jobCategory ?? '');
                                foreach ($categories as $cat): 
                                ?>
                                    <option value="<?= esc($cat) ?>" <?= ($selectedCategory === $cat) ? 'selected' : '' ?>><?= esc($cat) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- Minimum Years of Experience -->
                        <div>
                            <label for="min_experience" class="block text-sm font-medium text-[#111318] dark:text-gray-300 mb-2">
                                Minimum Years of Experience
                            </label>
                            <input 
                                type="number" 
                                id="min_experience" 
                                name="min_experience" 
                                min="0"
                                max="50"
                                value="<?= esc(old('min_experience', $job['min_experience'] ?? 0)) ?>"
                                class="w-full px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-[#111318] dark:text-white focus:outline-none focus:ring-2 focus:ring-primary/50 focus:border-primary transition-colors"
                                placeholder="0"
                            />
                        </div>

                        <!-- Description -->
                        <div>
                            <label for="description" class="block text-sm font-medium text-[#111318] dark:text-gray-300 mb-2">
                                Job Description
                            </label>
                            <textarea 
                                id="description" 
                                name="description" 
                                rows="4"
                                class="w-full px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-[#111318] dark:text-white focus:outline-none focus:ring-2 focus:ring-primary/50 focus:border-primary transition-colors resize-y"
                                placeholder="Provide an overview of the job position..."
                            ><?= esc(old('description', $description ?? '')) ?></textarea>
                        </div>

                        <!-- Responsibilities -->
                        <div>
                            <label for="responsibilities" class="block text-sm font-medium text-[#111318] dark:text-gray-300 mb-2">
                                Responsibilities
                            </label>
                            <textarea 
                                id="responsibilities" 
                                name="responsibilities" 
                                rows="4"
                                class="w-full px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-[#111318] dark:text-white focus:outline-none focus:ring-2 focus:ring-primary/50 focus:border-primary transition-colors resize-y"
                                placeholder="Enter each responsibility on a new line..."
                            ><?= esc(old('responsibilities', is_array($job['responsibilities'] ?? null) ? implode("\n", $job['responsibilities']) : ($job['responsibilities'] ?? ''))) ?></textarea>
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Enter each responsibility on a separate line</p>
                        </div>

                        <!-- Requirements -->
                        <div>
                            <label for="requirements" class="block text-sm font-medium text-[#111318] dark:text-gray-300 mb-2">
                                Requirements
                            </label>
                            <textarea 
                                id="requirements" 
                                name="requirements" 
                                rows="4"
                                class="w-full px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-[#111318] dark:text-white focus:outline-none focus:ring-2 focus:ring-primary/50 focus:border-primary transition-colors resize-y"
                                placeholder="Enter each requirement on a new line..."
                            ><?= esc(old('requirements', is_array($job['requirements'] ?? null) ? implode("\n", $job['requirements']) : ($job['requirements'] ?? ''))) ?></textarea>
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Enter each requirement on a separate line</p>
                        </div>

                        <!-- Valid Through -->
                        <div>
                            <label for="valid_through" class="block text-sm font-medium text-[#111318] dark:text-gray-300 mb-2">
                                Valid Through (Expiration Date) <span class="text-red-500">*</span>
                            </label>
                            <input 
                                type="date" 
                                id="valid_through" 
                                name="valid_through" 
                                required
                                value="<?= esc(old('valid_through', $job['expires_at'] ? date('Y-m-d', strtotime($job['expires_at'])) : '')) ?>"
                                min="<?= date('Y-m-d') ?>"
                                class="w-full px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-[#111318] dark:text-white focus:outline-none focus:ring-2 focus:ring-primary/50 focus:border-primary transition-colors"
                            />
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Required. Set when the job expires.</p>
                        </div>
                    </div>
                </div>

                <!-- Company Information Section -->
                <div class="bg-white dark:bg-gray-800/50 rounded-lg p-4 md:p-6 border border-gray-200 dark:border-gray-700">
                    <h2 class="text-xl font-bold text-[#111318] dark:text-white mb-4 md:mb-6">Company Details</h2>
                    
                    <div class="space-y-4 md:space-y-5">
                        <!-- Company Name -->
                        <div>
                            <label for="company_name" class="block text-sm font-medium text-[#111318] dark:text-gray-300 mb-2">
                                Company Name <span class="text-red-500">*</span>
                            </label>
                            <input 
                                type="text" 
                                id="company_name" 
                                name="company_name" 
                                required
                                value="<?= esc(old('company_name', $company['name'] ?? '')) ?>"
                                class="w-full px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-[#111318] dark:text-white focus:outline-none focus:ring-2 focus:ring-primary/50 focus:border-primary transition-colors"
                                placeholder="e.g., TechCorp Inc."
                            />
                        </div>

                        <!-- Company Description -->
                        <div>
                            <label for="company_description" class="block text-sm font-medium text-[#111318] dark:text-gray-300 mb-2">
                                Company Description
                            </label>
                            <textarea 
                                id="company_description" 
                                name="company_description" 
                                rows="4"
                                class="w-full px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-[#111318] dark:text-white focus:outline-none focus:ring-2 focus:ring-primary/50 focus:border-primary transition-colors resize-y"
                                placeholder="Tell us about your company..."
                            ><?= esc(old('company_description', $company['description'] ?? '')) ?></textarea>
                        </div>

                        <!-- Company Website -->
                        <div>
                            <label for="company_website" class="block text-sm font-medium text-[#111318] dark:text-gray-300 mb-2">
                                Company Website
                            </label>
                            <input 
                                type="url" 
                                id="company_website" 
                                name="company_website" 
                                value="<?= esc(old('company_website', $company['website'] ?? '')) ?>"
                                class="w-full px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-[#111318] dark:text-white focus:outline-none focus:ring-2 focus:ring-primary/50 focus:border-primary transition-colors"
                                placeholder="https://company.com"
                            />
                        </div>

                        <!-- Company Logo -->
                        <div>
                            <label for="company_logo" class="block text-sm font-medium text-[#111318] dark:text-gray-300 mb-2">
                                Company Logo
                            </label>
                            <?php if (!empty($company['logo'])): ?>
                                <div class="mb-3">
                                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">Current Logo:</p>
                                    <img src="<?= esc($company['logo']) ?>" alt="Company Logo" class="h-20 w-20 object-contain rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 p-2">
                                </div>
                            <?php endif; ?>
                            <div class="flex items-center gap-4">
                                <label for="company_logo" id="logoUploadArea" class="flex flex-col items-center justify-center w-full h-32 border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                                    <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                        <span class="material-symbols-outlined text-3xl text-gray-400 dark:text-gray-500 mb-2">cloud_upload</span>
                                        <p class="mb-2 text-sm text-gray-500 dark:text-gray-400">
                                            <span class="font-semibold">Click to upload</span> or drag and drop
                                        </p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">PNG, JPG, GIF up to 2MB</p>
                                    </div>
                                    <input 
                                        type="file" 
                                        id="company_logo" 
                                        name="company_logo" 
                                        accept="image/png,image/jpeg,image/jpg,image/gif"
                                        class="hidden"
                                        onchange="handleLogoUpload(this)"
                                    />
                                </label>
                            </div>
                            <div id="logoPreview" class="mt-3 hidden">
                                <div class="flex items-center gap-3">
                                    <img id="logoPreviewImg" src="" alt="Logo preview" class="h-20 w-20 object-contain rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 p-2">
                                    <div>
                                        <p class="text-sm font-medium text-[#111318] dark:text-white" id="logoFileName"></p>
                                        <button type="button" onclick="removeLogo()" class="mt-1 text-xs text-red-600 dark:text-red-400 hover:underline">Remove logo</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="flex flex-col sm:flex-row gap-4 items-center justify-between pt-4">
                    <a 
                        href="<?= base_url('manage-jobs') ?>" 
                        class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200 underline"
                    >
                        ← Back to Manage Jobs
                    </a>
                    <button 
                        type="submit" 
                        class="w-full sm:w-auto flex items-center justify-center gap-2 px-6 md:px-8 py-3 md:py-3.5 rounded-full text-base font-bold leading-normal tracking-[0.015em] transition-colors"
                        style="background-color: #2bee79; color: #0e2016;"
                        onmouseover="this.style.backgroundColor='#25d46a'"
                        onmouseout="this.style.backgroundColor='#2bee79'"
                    >
                        <span class="material-symbols-outlined text-xl">save</span>
                        <span>Update Job</span>
                    </button>
                </div>
            </form>
        </main>

        <?= view('partials/footer') ?>
    </div>

    <script>
        // Handle logo upload preview (same as post-job.php)
        function handleLogoUpload(input) {
            if (input.files && input.files[0]) {
                processLogoFile(input.files[0], input);
            }
        }
        
        function processLogoFile(file, input) {
            if (file.size > 2 * 1024 * 1024) {
                alert('File size must be less than 2MB');
                if (input) input.value = '';
                return;
            }
            
            const validTypes = ['image/png', 'image/jpeg', 'image/jpg', 'image/gif'];
            if (!validTypes.includes(file.type)) {
                alert('Please upload a valid image file (PNG, JPG, GIF)');
                if (input) input.value = '';
                return;
            }
            
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('logoPreviewImg').src = e.target.result;
                document.getElementById('logoFileName').textContent = file.name;
                document.getElementById('logoPreview').classList.remove('hidden');
            };
            reader.readAsDataURL(file);
        }
        
        function removeLogo() {
            document.getElementById('company_logo').value = '';
            document.getElementById('logoPreview').classList.add('hidden');
            document.getElementById('logoPreviewImg').src = '';
            document.getElementById('logoFileName').textContent = '';
        }
    </script>
</body>
</html>

