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

                        <!-- Location -->
                        <div id="locationContainer">
                            <label for="location" class="block text-sm font-medium text-[#111318] dark:text-gray-300 mb-2">
                                Location <span class="text-red-500 location-required">*</span>
                            </label>
                            <input 
                                type="text" 
                                id="location" 
                                name="location" 
                                value="<?= esc(old('location', $job['location'])) ?>"
                                class="w-full px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-[#111318] dark:text-white focus:outline-none focus:ring-2 focus:ring-primary/50 focus:border-primary transition-colors"
                                placeholder="e.g., New York, NY"
                            />
                        </div>

                        <!-- Country -->
                        <div>
                            <label for="country_code" class="block text-sm font-medium text-[#111318] dark:text-gray-300 mb-2">
                                Country <span class="text-xs text-gray-500 dark:text-gray-400">(Auto-detected from your location)</span>
                            </label>
                            <select 
                                id="country_code" 
                                name="country_code" 
                                class="w-full px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-[#111318] dark:text-white focus:outline-none focus:ring-2 focus:ring-primary/50 focus:border-primary transition-colors cursor-pointer"
                            >
                                <?php 
                                $selectedCountryCode = old('country_code', $detectedCountry['country_code'] ?? 'LK');
                                $selectedCountry = old('country', $detectedCountry['country'] ?? 'Sri Lanka');
                                foreach ($countryList as $code => $name): 
                                    $selected = ($code === $selectedCountryCode) ? 'selected' : '';
                                ?>
                                    <option value="<?= esc($code) ?>" <?= $selected ?> data-country="<?= esc($name) ?>">
                                        <?= esc($name) ?> (<?= esc($code) ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <input type="hidden" id="country" name="country" value="<?= esc($selectedCountry) ?>">
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                <span id="countryAutoDetectMsg">Selected: <strong><?= esc($selectedCountry) ?></strong></span>
                            </p>
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

                        <!-- Monthly Salary Range -->
                        <div>
                            <label class="block text-sm font-medium text-[#111318] dark:text-gray-300 mb-2">
                                Monthly Salary Range (Optional)
                            </label>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="salary_min" class="block text-xs text-gray-500 dark:text-gray-400 mb-1">Minimum</label>
                                    <div class="flex items-center gap-2">
                                        <span class="text-gray-500 dark:text-gray-400">$</span>
                                        <input 
                                            type="number" 
                                            id="salary_min" 
                                            name="salary_min" 
                                            min="0"
                                            step="any"
                                            value="<?= esc(old('salary_min', $job['salary_min'] ?? '')) ?>"
                                            class="flex-1 px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-[#111318] dark:text-white focus:outline-none focus:ring-2 focus:ring-primary/50 focus:border-primary transition-colors"
                                            placeholder="3000"
                                        />
                                    </div>
                                </div>
                                <div>
                                    <label for="salary_max" class="block text-xs text-gray-500 dark:text-gray-400 mb-1">Maximum</label>
                                    <div class="flex items-center gap-2">
                                        <span class="text-gray-500 dark:text-gray-400">$</span>
                                        <input 
                                            type="number" 
                                            id="salary_max" 
                                            name="salary_max" 
                                            min="0"
                                            step="any"
                                            value="<?= esc(old('salary_max', $job['salary_max'] ?? '')) ?>"
                                            class="flex-1 px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-[#111318] dark:text-white focus:outline-none focus:ring-2 focus:ring-primary/50 focus:border-primary transition-colors"
                                            placeholder="6000"
                                        />
                                    </div>
                                </div>
                            </div>
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Leave blank if salary is not disclosed</p>
                            <p id="salaryError" class="mt-1 text-xs text-red-600 dark:text-red-400 hidden">Minimum salary must be less than maximum salary</p>
                        </div>

                        <!-- Job Category -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="category_id" class="block text-sm font-medium text-[#111318] dark:text-gray-300 mb-2">
                                    Category <span class="text-red-500">*</span>
                                </label>
                                <select 
                                    id="category_id" 
                                    name="category_id" 
                                    required
                                    class="w-full px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-[#111318] dark:text-white focus:outline-none focus:ring-2 focus:ring-primary/50 focus:border-primary transition-colors cursor-pointer"
                                >
                                    <option value="">Select Category</option>
                                    <?php if (!empty($categories)): ?>
                                        <?php 
                                        $selectedCategoryId = old('category_id', $job['category_id'] ?? '');
                                        foreach ($categories as $category): 
                                        ?>
                                            <option value="<?= esc($category['id']) ?>" <?= $selectedCategoryId == $category['id'] ? 'selected' : '' ?>>
                                                <?= esc($category['name']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </div>
                            <div>
                                <label for="subcategory_id" class="block text-sm font-medium text-[#111318] dark:text-gray-300 mb-2">
                                    Subcategory <span class="text-xs font-normal text-gray-500 dark:text-gray-400">(Optional)</span>
                                </label>
                                <select 
                                    id="subcategory_id" 
                                    name="subcategory_id" 
                                    class="w-full px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-[#111318] dark:text-white focus:outline-none focus:ring-2 focus:ring-primary/50 focus:border-primary transition-colors cursor-pointer disabled:opacity-50 disabled:cursor-not-allowed"
                                    disabled
                                >
                                    <option value="">Select Category First</option>
                                </select>
                                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                    <span id="subcategoryHint">Select a category above to see subcategories</span>
                                </p>
                            </div>
                        </div>
                        <!-- Legacy job_category field for backward compatibility (hidden) -->
                        <input type="hidden" id="job_category" name="job_category" value="">

                        <!-- Collection (Optional) -->
                        <?php if (!empty($collections) && count($collections) > 0): ?>
                        <div>
                            <label for="collection_id" class="block text-sm font-medium text-[#111318] dark:text-gray-300 mb-2">
                                Add to Collection (Optional)
                            </label>
                            <select 
                                id="collection_id" 
                                name="collection_id" 
                                class="w-full px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-[#111318] dark:text-white focus:outline-none focus:ring-2 focus:ring-primary/50 focus:border-primary transition-colors cursor-pointer"
                            >
                                <option value="">None - Don't add to any collection</option>
                                <?php 
                                $selectedCollectionId = old('collection_id', $currentCollectionId ?? '');
                                foreach ($collections as $collection): 
                                ?>
                                    <option value="<?= esc($collection['id']) ?>" <?= $selectedCollectionId == $collection['id'] ? 'selected' : '' ?>>
                                        <?= esc($collection['name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Optionally add this job to an existing collection page</p>
                        </div>
                        <?php endif; ?>

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

                        <!-- Job Image (Optional) -->
                        <div>
                            <label for="job_image" class="block text-sm font-medium text-[#111318] dark:text-gray-300 mb-2">
                                Job Image (Optional)
                            </label>
                            <?php if (!empty($jobImageUrl)): ?>
                                <div class="mb-3">
                                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">Current Image:</p>
                                    <img src="<?= esc($jobImageUrl) ?>" alt="Job Image" class="h-32 w-auto object-contain rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 p-2">
                                </div>
                            <?php endif; ?>
                            <div class="flex items-center gap-4">
                                <label for="job_image" id="jobImageUploadArea" class="flex flex-col items-center justify-center w-full h-32 border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                                    <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                        <span class="material-symbols-outlined text-3xl text-gray-400 dark:text-gray-500 mb-2">image</span>
                                        <p class="mb-2 text-sm text-gray-500 dark:text-gray-400">
                                            <span class="font-semibold">Click to upload</span> or drag and drop
                                        </p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">PNG, JPG, GIF up to 5MB</p>
                                    </div>
                                    <input 
                                        type="file" 
                                        id="job_image" 
                                        name="job_image" 
                                        accept="image/png,image/jpeg,image/jpg,image/gif"
                                        class="hidden"
                                        onchange="handleJobImageUpload(this)"
                                    />
                                </label>
                            </div>
                            <div id="jobImagePreview" class="mt-3 hidden">
                                <div class="flex items-center gap-3">
                                    <img id="jobImagePreviewImg" src="" alt="Job image preview" class="h-32 w-auto object-contain rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 p-2">
                                    <div>
                                        <p class="text-sm font-medium text-[#111318] dark:text-white" id="jobImageFileName"></p>
                                        <button type="button" onclick="removeJobImage()" class="mt-1 text-xs text-red-600 dark:text-red-400 hover:underline">Remove image</button>
                                    </div>
                                </div>
                            </div>
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

                        <!-- Required Skills -->
                        <div>
                            <label for="skills" class="block text-sm font-medium text-[#111318] dark:text-gray-300 mb-2">
                                Required Skills
                            </label>
                            <input 
                                type="text" 
                                id="skills" 
                                name="skills" 
                                value="<?= esc(old('skills', $job['skills_required'] ?? '')) ?>"
                                class="w-full px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-[#111318] dark:text-white focus:outline-none focus:ring-2 focus:ring-primary/50 focus:border-primary transition-colors"
                                placeholder="e.g., JavaScript, React, Node.js, Python (comma-separated)"
                            />
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Enter skills separated by commas</p>
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
        // Handle logo upload preview
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

        // Handle job image upload preview
        function handleJobImageUpload(input) {
            if (input.files && input.files[0]) {
                processJobImageFile(input.files[0], input);
            }
        }
        
        function processJobImageFile(file, input) {
            if (file.size > 5 * 1024 * 1024) {
                alert('File size must be less than 5MB');
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
                document.getElementById('jobImagePreviewImg').src = e.target.result;
                document.getElementById('jobImageFileName').textContent = file.name;
                document.getElementById('jobImagePreview').classList.remove('hidden');
            };
            reader.readAsDataURL(file);
        }
        
        function removeJobImage() {
            document.getElementById('job_image').value = '';
            document.getElementById('jobImagePreview').classList.add('hidden');
            document.getElementById('jobImagePreviewImg').src = '';
            document.getElementById('jobImageFileName').textContent = '';
        }

        // Handle Job Type and Location visibility
        (function() {
            const jobTypeSelect = document.getElementById('job_type');
            const locationContainer = document.getElementById('locationContainer');
            const locationInput = document.getElementById('location');
            const locationLabel = locationInput ? locationInput.previousElementSibling : null;
            const locationRequiredSpan = locationLabel ? locationLabel.querySelector('.location-required') : null;
            
            function toggleLocationField() {
                if (!jobTypeSelect || !locationContainer || !locationInput) return;
                
                const selectedJobType = jobTypeSelect.value;
                const isRemote = selectedJobType === 'remote';
                
                if (isRemote) {
                    locationContainer.style.display = 'none';
                    locationInput.removeAttribute('required');
                    if (locationRequiredSpan) {
                        locationRequiredSpan.style.display = 'none';
                    }
                    locationInput.value = '';
                } else {
                    locationContainer.style.display = 'block';
                    locationInput.setAttribute('required', 'required');
                    if (locationRequiredSpan) {
                        locationRequiredSpan.style.display = 'inline';
                    }
                }
            }
            
            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', toggleLocationField);
            } else {
                toggleLocationField();
            }
            
            if (jobTypeSelect) {
                jobTypeSelect.addEventListener('change', toggleLocationField);
            }
        })();

        // Update country name when country code changes
        (function() {
            const countryCodeSelect = document.getElementById('country_code');
            const countryInput = document.getElementById('country');
            const countryMsg = document.getElementById('countryAutoDetectMsg');
            
            if (countryCodeSelect && countryInput && countryMsg) {
                countryCodeSelect.addEventListener('change', function() {
                    const selectedOption = this.options[this.selectedIndex];
                    const countryName = selectedOption.getAttribute('data-country');
                    if (countryName) {
                        countryInput.value = countryName;
                        countryMsg.innerHTML = 'Selected: <strong>' + countryName + '</strong>';
                    }
                });
            }
        })();

        // Handle Category and Subcategory dynamic loading
        (function() {
            const categorySelect = document.getElementById('category_id');
            const subcategorySelect = document.getElementById('subcategory_id');
            const jobCategoryHidden = document.getElementById('job_category');
            
            if (!categorySelect || !subcategorySelect) return;
            
            const baseUrl = window.location.origin;
            const apiUrl = baseUrl + '/api/subcategories.php';
            
            const oldSubcategoryId = <?= old('subcategory_id') ? (int)old('subcategory_id') : ($job['subcategory_id'] ?? 'null') ?>;
            const currentCategoryId = <?= old('category_id') ? (int)old('category_id') : ($job['category_id'] ?? 'null') ?>;
            
            function loadSubcategories(categoryId, preselectedSubcategoryId = null) {
                const subcategoryHint = document.getElementById('subcategoryHint');
                
                subcategorySelect.innerHTML = '<option value="">Loading...</option>';
                subcategorySelect.disabled = true;
                
                if (subcategoryHint) {
                    subcategoryHint.textContent = 'Loading subcategories...';
                }
                
                if (jobCategoryHidden && categoryId) {
                    const selectedCategoryOption = categorySelect.options[categorySelect.selectedIndex];
                    if (selectedCategoryOption) {
                        jobCategoryHidden.value = selectedCategoryOption.text;
                    }
                }
                
                if (!categoryId) {
                    subcategorySelect.innerHTML = '<option value="">Select Category First</option>';
                    subcategorySelect.disabled = true;
                    if (subcategoryHint) {
                        subcategoryHint.textContent = 'Select a category above to see subcategories';
                    }
                    return;
                }
                
                fetch(`${apiUrl}?category_id=${encodeURIComponent(categoryId)}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.success && data.subcategories.length > 0) {
                            subcategorySelect.innerHTML = '<option value="">Select Subcategory (Optional)</option>';
                            
                            data.subcategories.forEach(subcategory => {
                                const option = document.createElement('option');
                                option.value = subcategory.id;
                                option.textContent = subcategory.name;
                                
                                if (preselectedSubcategoryId && subcategory.id == preselectedSubcategoryId) {
                                    option.selected = true;
                                }
                                
                                subcategorySelect.appendChild(option);
                            });
                            
                            subcategorySelect.disabled = false;
                            if (subcategoryHint) {
                                subcategoryHint.textContent = `${data.count} subcategories available (optional)`;
                            }
                        } else {
                            subcategorySelect.innerHTML = '<option value="">No subcategories available</option>';
                            subcategorySelect.disabled = false;
                            if (subcategoryHint) {
                                subcategoryHint.textContent = 'No subcategories available for this category';
                            }
                        }
                    })
                    .catch(error => {
                        console.error('Error fetching subcategories:', error);
                        subcategorySelect.innerHTML = '<option value="">Error loading subcategories</option>';
                        subcategorySelect.disabled = false;
                        if (subcategoryHint) {
                            subcategoryHint.textContent = 'Error loading subcategories. Please try again.';
                            subcategoryHint.classList.add('text-red-600', 'dark:text-red-400');
                        }
                    });
            }
            
            categorySelect.addEventListener('change', function() {
                loadSubcategories(this.value, oldSubcategoryId);
            });
            
            // Load subcategories on page load if category is already selected
            if (categorySelect.value || currentCategoryId) {
                const categoryIdToLoad = categorySelect.value || currentCategoryId;
                loadSubcategories(categoryIdToLoad, oldSubcategoryId);
            }
        })();

        // Salary Range Validation
        (function() {
            const salaryMinInput = document.getElementById('salary_min');
            const salaryMaxInput = document.getElementById('salary_max');
            const salaryError = document.getElementById('salaryError');
            
            function validateSalaryRange() {
                const min = parseFloat(salaryMinInput.value);
                const max = parseFloat(salaryMaxInput.value);
                
                if (salaryMinInput.value.trim() && salaryMaxInput.value.trim()) {
                    if (min >= max) {
                        salaryError.classList.remove('hidden');
                        salaryMinInput.classList.add('border-red-500', 'dark:border-red-500');
                        salaryMinInput.classList.remove('border-gray-300', 'dark:border-gray-600');
                        salaryMaxInput.classList.add('border-red-500', 'dark:border-red-500');
                        salaryMaxInput.classList.remove('border-gray-300', 'dark:border-gray-600');
                        return false;
                    } else {
                        salaryError.classList.add('hidden');
                        salaryMinInput.classList.remove('border-red-500', 'dark:border-red-500');
                        salaryMinInput.classList.add('border-gray-300', 'dark:border-gray-600');
                        salaryMaxInput.classList.remove('border-red-500', 'dark:border-red-500');
                        salaryMaxInput.classList.add('border-gray-300', 'dark:border-gray-600');
                        return true;
                    }
                } else {
                    salaryError.classList.add('hidden');
                    salaryMinInput.classList.remove('border-red-500', 'dark:border-red-500');
                    salaryMinInput.classList.add('border-gray-300', 'dark:border-gray-600');
                    salaryMaxInput.classList.remove('border-red-500', 'dark:border-red-500');
                    salaryMaxInput.classList.add('border-gray-300', 'dark:border-gray-600');
                    return true;
                }
            }
            
            if (salaryMinInput) {
                salaryMinInput.addEventListener('input', validateSalaryRange);
                salaryMinInput.addEventListener('blur', validateSalaryRange);
            }
            
            if (salaryMaxInput) {
                salaryMaxInput.addEventListener('input', validateSalaryRange);
                salaryMaxInput.addEventListener('blur', validateSalaryRange);
            }
            
            const editJobForm = document.getElementById('editJobForm');
            if (editJobForm) {
                editJobForm.addEventListener('submit', function(e) {
                    if (!validateSalaryRange()) {
                        e.preventDefault();
                        salaryMinInput.focus();
                        return false;
                    }
                });
            }
        })();
    </script>
</body>
</html>

