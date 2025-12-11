<?= view('partials/head', ['title' => 'Post a Job - TopTopJobs']) ?>
<body class="font-display bg-background-light dark:bg-background-dark text-[#111318] dark:text-gray-200">
    <div class="relative flex min-h-screen w-full flex-col">
        <?= view('partials/header') ?>

        <main class="flex-grow container mx-auto px-4 py-6 md:py-8 max-w-4xl">
            <!-- Page Header -->
            <div class="mb-6 md:mb-8">
                <h1 class="text-2xl md:text-3xl font-bold text-[#111318] dark:text-white mb-2">Post a New Job</h1>
                <p class="text-sm md:text-base text-gray-600 dark:text-gray-400">Fill out the form below to post your job listing</p>
            </div>

            <!-- Login Notice for Non-Logged Users -->
            <?php if (!session()->get('is_logged_in')): ?>
                <div class="mb-6 p-4 rounded-lg border border-yellow-200 dark:border-yellow-800 bg-yellow-50 dark:bg-yellow-900/20">
                    <div class="flex items-start gap-3">
                        <span class="material-symbols-outlined text-yellow-600 dark:text-yellow-400">info</span>
                        <div>
                            <p class="text-sm font-medium text-yellow-800 dark:text-yellow-300">You need to be logged in to post a job.</p>
                            <p class="text-xs text-yellow-700 dark:text-yellow-400 mt-1">Please <a href="<?= base_url('login?redirect=post-job') ?>" class="underline font-semibold">log in</a> or <a href="<?= base_url('signup') ?>" class="underline font-semibold">sign up</a> to continue.</p>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

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

            <!-- Job Posting Form -->
            <form action="<?= base_url('post-job') ?>" method="POST" enctype="multipart/form-data" id="postJobForm" class="space-y-6 md:space-y-8">
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
                                value="<?= old('job_title') ?>"
                                class="w-full px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-[#111318] dark:text-white focus:outline-none focus:ring-2 focus:ring-primary/50 focus:border-primary transition-colors"
                                placeholder="e.g., Senior Software Engineer"
                            />
                            <?php if (session()->getFlashdata('errors')['job_title'] ?? false): ?>
                                <p class="mt-1 text-xs text-red-600 dark:text-red-400"><?= esc(session()->getFlashdata('errors')['job_title']) ?></p>
                            <?php endif; ?>
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
                                    value="<?= old('application_email') ?>"
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
                                    value="<?= old('application_url') ?>"
                                    class="w-full px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-[#111318] dark:text-white focus:outline-none focus:ring-2 focus:ring-primary/50 focus:border-primary transition-colors"
                                    placeholder="https://company.com/apply"
                                />
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
                                <option value="full-time" <?= old('job_type') === 'full-time' ? 'selected' : '' ?>>Full-time</option>
                                <option value="part-time" <?= old('job_type') === 'part-time' ? 'selected' : '' ?>>Part-time</option>
                                <option value="internship" <?= old('job_type') === 'internship' ? 'selected' : '' ?>>Internship</option>
                                <option value="contract" <?= old('job_type') === 'contract' ? 'selected' : '' ?>>Contract</option>
                                <option value="remote" <?= old('job_type') === 'remote' ? 'selected' : '' ?>>Remote</option>
                            </select>
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
                                value="<?= old('location') ?>"
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
                                <span id="countryAutoDetectMsg">Auto-detected: <strong><?= esc($selectedCountry) ?></strong></span>
                            </p>
                            <?php if (session()->getFlashdata('errors')['country_code'] ?? false): ?>
                                <p class="mt-1 text-xs text-red-600 dark:text-red-400"><?= esc(session()->getFlashdata('errors')['country_code']) ?></p>
                            <?php endif; ?>
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
                                value="<?= old('application_phone') ?>"
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
                                            value="<?= old('salary_min') ?>"
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
                                            value="<?= old('salary_max') ?>"
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
                                <option value="Cashier" <?= old('job_category') === 'Cashier' ? 'selected' : '' ?>>Cashier</option>
                                <option value="Data Entry" <?= old('job_category') === 'Data Entry' ? 'selected' : '' ?>>Data Entry</option>
                                <option value="IT/Software" <?= old('job_category') === 'IT/Software' ? 'selected' : '' ?>>IT/Software</option>
                                <option value="Marketing" <?= old('job_category') === 'Marketing' ? 'selected' : '' ?>>Marketing</option>
                                <option value="Sales" <?= old('job_category') === 'Sales' ? 'selected' : '' ?>>Sales</option>
                                <option value="Customer Service" <?= old('job_category') === 'Customer Service' ? 'selected' : '' ?>>Customer Service</option>
                                <option value="Design" <?= old('job_category') === 'Design' ? 'selected' : '' ?>>Design</option>
                                <option value="Engineering" <?= old('job_category') === 'Engineering' ? 'selected' : '' ?>>Engineering</option>
                                <option value="Finance" <?= old('job_category') === 'Finance' ? 'selected' : '' ?>>Finance</option>
                                <option value="Healthcare" <?= old('job_category') === 'Healthcare' ? 'selected' : '' ?>>Healthcare</option>
                                <option value="Education" <?= old('job_category') === 'Education' ? 'selected' : '' ?>>Education</option>
                                <option value="Other" <?= old('job_category') === 'Other' ? 'selected' : '' ?>>Other</option>
                            </select>
                        </div>

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
                                <?php foreach ($collections as $collection): ?>
                                    <option value="<?= esc($collection['id']) ?>" <?= old('collection_id') == $collection['id'] ? 'selected' : '' ?>>
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
                                value="<?= old('min_experience', '0') ?>"
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
                            ><?= old('description') ?></textarea>
                        </div>

                        <!-- Job Image (Optional) -->
                        <div>
                            <label for="job_image" class="block text-sm font-medium text-[#111318] dark:text-gray-300 mb-2">
                                Job Image (Optional)
                            </label>
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
                            ><?= old('responsibilities') ?></textarea>
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
                            ><?= old('requirements') ?></textarea>
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
                                value="<?= old('skills') ?>"
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
                                value="<?= old('valid_through') ?>"
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
                        <!-- Company Name with Autocomplete -->
                        <div class="relative">
                            <label for="company_name" class="block text-sm font-medium text-[#111318] dark:text-gray-300 mb-2">
                                Company Name <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <input 
                                    type="text" 
                                    id="company_name" 
                                    name="company_name" 
                                    required
                                    autocomplete="off"
                                    value="<?= old('company_name') ?>" 
                                    class="w-full px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-[#111318] dark:text-white focus:outline-none focus:ring-2 focus:ring-primary/50 focus:border-primary transition-colors"
                                    placeholder="Type to search existing companies or enter a new company name"
                                />
                                <input type="hidden" id="company_id" name="company_id" value="">
                                <div id="companyAutocomplete" class="hidden absolute z-50 w-full mt-1 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg shadow-lg max-h-60 overflow-y-auto">
                                    <!-- Suggestions will be inserted here -->
                                </div>
                            </div>
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                <span id="companyStatus">Start typing to search for existing companies</span>
                            </p>
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
                            ><?= old('company_description') ?></textarea>
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
                                value="<?= old('company_website') ?>"
                                class="w-full px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-[#111318] dark:text-white focus:outline-none focus:ring-2 focus:ring-primary/50 focus:border-primary transition-colors"
                                placeholder="https://company.com"
                            />
                        </div>


                        <!-- Company Logo -->
                        <div>
                            <label for="company_logo" class="block text-sm font-medium text-[#111318] dark:text-gray-300 mb-2">
                                Company Logo
                            </label>
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
                    <p class="text-xs text-gray-500 dark:text-gray-400">
                        <span class="text-red-500">*</span> Required fields
                    </p>
                    <button 
                        type="submit" 
                        id="submitJobBtn"
                        <?php if (!session()->get('is_logged_in')): ?>disabled<?php endif; ?>
                        class="w-full sm:w-auto flex items-center justify-center gap-2 px-6 md:px-8 py-3 md:py-3.5 rounded-full text-base font-bold leading-normal tracking-[0.015em] transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                        style="background-color: <?= session()->get('is_logged_in') ? '#2bee79' : '#9ca3af' ?>; color: #0e2016;"
                        onmouseover="<?= session()->get('is_logged_in') ? "this.style.backgroundColor='#25d46a'" : '' ?>"
                        onmouseout="<?= session()->get('is_logged_in') ? "this.style.backgroundColor='#2bee79'" : '' ?>"
                    >
                        <span class="material-symbols-outlined text-xl">check_circle</span>
                        <span>Submit Job</span>
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
            // Validate file size (2MB max)
            if (file.size > 2 * 1024 * 1024) {
                alert('File size must be less than 2MB');
                if (input) input.value = '';
                return;
            }
            
            // Validate file type
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
        
        // Remove logo
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
            // Validate file size (5MB max)
            if (file.size > 5 * 1024 * 1024) {
                alert('File size must be less than 5MB');
                if (input) input.value = '';
                return;
            }
            
            // Validate file type
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
        
        // Remove job image
        function removeJobImage() {
            document.getElementById('job_image').value = '';
            document.getElementById('jobImagePreview').classList.add('hidden');
            document.getElementById('jobImagePreviewImg').src = '';
            document.getElementById('jobImageFileName').textContent = '';
        }
        
        // Drag and drop functionality
        (function() {
            const uploadArea = document.getElementById('logoUploadArea');
            const fileInput = document.getElementById('company_logo');
            
            ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
                uploadArea.addEventListener(eventName, preventDefaults, false);
            });
            
            function preventDefaults(e) {
                e.preventDefault();
                e.stopPropagation();
            }
            
            ['dragenter', 'dragover'].forEach(eventName => {
                uploadArea.addEventListener(eventName, highlight, false);
            });
            
            ['dragleave', 'drop'].forEach(eventName => {
                uploadArea.addEventListener(eventName, unhighlight, false);
            });
            
            function highlight(e) {
                uploadArea.classList.add('border-primary', 'bg-primary/5');
            }
            
            function unhighlight(e) {
                uploadArea.classList.remove('border-primary', 'bg-primary/5');
            }
            
            uploadArea.addEventListener('drop', handleDrop, false);
            
            function handleDrop(e) {
                const dt = e.dataTransfer;
                const files = dt.files;
                
                if (files.length > 0) {
                    fileInput.files = files;
                    processLogoFile(files[0], fileInput);
                }
            }
        })();

        // Drag and drop functionality for job image
        (function() {
            const jobImageUploadArea = document.getElementById('jobImageUploadArea');
            const jobImageInput = document.getElementById('job_image');
            
            if (jobImageUploadArea && jobImageInput) {
                ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
                    jobImageUploadArea.addEventListener(eventName, preventDefaults, false);
                });
                
                function preventDefaults(e) {
                    e.preventDefault();
                    e.stopPropagation();
                }
                
                ['dragenter', 'dragover'].forEach(eventName => {
                    jobImageUploadArea.addEventListener(eventName, highlight, false);
                });
                
                ['dragleave', 'drop'].forEach(eventName => {
                    jobImageUploadArea.addEventListener(eventName, unhighlight, false);
                });
                
                function highlight(e) {
                    jobImageUploadArea.classList.add('border-primary', 'bg-primary/5');
                }
                
                function unhighlight(e) {
                    jobImageUploadArea.classList.remove('border-primary', 'bg-primary/5');
                }
                
                jobImageUploadArea.addEventListener('drop', handleDrop, false);
                
                function handleDrop(e) {
                    const dt = e.dataTransfer;
                    const files = dt.files;
                    
                    if (files.length > 0) {
                        jobImageInput.files = files;
                        processJobImageFile(files[0], jobImageInput);
                    }
                }
            }
        })();
        
        // Handle form submission for non-logged users
        document.getElementById('postJobForm').addEventListener('submit', function(e) {
            const isLoggedIn = <?= session()->get('is_logged_in') ? 'true' : 'false' ?>;
            
            if (!isLoggedIn) {
                e.preventDefault();
                window.location.href = '<?= base_url('login?redirect=post-job') ?>';
                return false;
            }
        });

        // Validate that either email or URL is provided
        document.getElementById('postJobForm').addEventListener('submit', function(e) {
            const email = document.getElementById('application_email').value.trim();
            const url = document.getElementById('application_url').value.trim();
            
            if (!email && !url) {
                e.preventDefault();
                alert('Please provide either an Application Email or Application URL.');
                return false;
            }
        });

        // Salary Range Validation
        (function() {
            const salaryMinInput = document.getElementById('salary_min');
            const salaryMaxInput = document.getElementById('salary_max');
            const salaryError = document.getElementById('salaryError');
            
            function validateSalaryRange() {
                const min = parseFloat(salaryMinInput.value);
                const max = parseFloat(salaryMaxInput.value);
                
                // Only validate if both values are provided
                if (salaryMinInput.value.trim() && salaryMaxInput.value.trim()) {
                    if (min >= max) {
                        // Show error
                        salaryError.classList.remove('hidden');
                        salaryMinInput.classList.add('border-red-500', 'dark:border-red-500');
                        salaryMinInput.classList.remove('border-gray-300', 'dark:border-gray-600');
                        salaryMaxInput.classList.add('border-red-500', 'dark:border-red-500');
                        salaryMaxInput.classList.remove('border-gray-300', 'dark:border-gray-600');
                        return false;
                    } else {
                        // Hide error
                        salaryError.classList.add('hidden');
                        salaryMinInput.classList.remove('border-red-500', 'dark:border-red-500');
                        salaryMinInput.classList.add('border-gray-300', 'dark:border-gray-600');
                        salaryMaxInput.classList.remove('border-red-500', 'dark:border-red-500');
                        salaryMaxInput.classList.add('border-gray-300', 'dark:border-gray-600');
                        return true;
                    }
                } else {
                    // Hide error if one or both fields are empty
                    salaryError.classList.add('hidden');
                    salaryMinInput.classList.remove('border-red-500', 'dark:border-red-500');
                    salaryMinInput.classList.add('border-gray-300', 'dark:border-gray-600');
                    salaryMaxInput.classList.remove('border-red-500', 'dark:border-red-500');
                    salaryMaxInput.classList.add('border-gray-300', 'dark:border-gray-600');
                    return true;
                }
            }
            
            // Validate on input change
            if (salaryMinInput) {
                salaryMinInput.addEventListener('input', validateSalaryRange);
                salaryMinInput.addEventListener('blur', validateSalaryRange);
            }
            
            if (salaryMaxInput) {
                salaryMaxInput.addEventListener('input', validateSalaryRange);
                salaryMaxInput.addEventListener('blur', validateSalaryRange);
            }
            
            // Validate on form submission
            document.getElementById('postJobForm').addEventListener('submit', function(e) {
                if (!validateSalaryRange()) {
                    e.preventDefault();
                    salaryMinInput.focus();
                    return false;
                }
            });
        })();
        
        // Company Autocomplete Functionality
        (function() {
            const companyInput = document.getElementById('company_name');
            const companyIdInput = document.getElementById('company_id');
            const autocompleteDiv = document.getElementById('companyAutocomplete');
            const companyStatus = document.getElementById('companyStatus');
            const companyDescription = document.getElementById('company_description');
            const companyWebsite = document.getElementById('company_website');
            
            let searchTimeout = null;
            let selectedCompany = null;
            let companies = [];
            
            // Get API URL
            const baseUrl = window.location.origin;
            const apiUrl = baseUrl + '/api/companies.php';
            
            // Handle input changes
            companyInput.addEventListener('input', function(e) {
                const query = e.target.value.trim();
                
                // Clear selected company if user is typing
                if (selectedCompany && selectedCompany.name !== query) {
                    selectedCompany = null;
                    companyIdInput.value = '';
                    companyStatus.textContent = 'Start typing to search for existing companies';
                }
                
                // Clear timeout
                if (searchTimeout) {
                    clearTimeout(searchTimeout);
                }
                
                // If query is less than 2 characters, hide autocomplete
                if (query.length < 2) {
                    autocompleteDiv.classList.add('hidden');
                    companyStatus.textContent = 'Start typing to search for existing companies';
                    return;
                }
                
                // Debounce search
                searchTimeout = setTimeout(() => {
                    searchCompanies(query);
                }, 300);
            });
            
            // Search companies
            async function searchCompanies(query) {
                try {
                    companyStatus.textContent = 'Searching...';
                    const response = await fetch(`${apiUrl}?q=${encodeURIComponent(query)}&limit=10`);
                    const data = await response.json();
                    
                    if (data.success && data.companies.length > 0) {
                        companies = data.companies;
                        displaySuggestions(data.companies, query);
                        companyStatus.textContent = `Found ${data.count} company${data.count !== 1 ? 'ies' : ''}. Click to select or continue typing to create a new company.`;
                    } else {
                        companies = [];
                        displayNewCompanyOption(query);
                        companyStatus.textContent = 'No matching companies found. This will create a new company.';
                    }
                } catch (error) {
                    console.error('Error searching companies:', error);
                    companyStatus.textContent = 'Error searching companies. You can still enter a new company name.';
                    autocompleteDiv.classList.add('hidden');
                }
            }
            
            // Display suggestions
            function displaySuggestions(companies, query) {
                autocompleteDiv.innerHTML = '';
                
                // Add existing companies
                companies.forEach(company => {
                    const item = document.createElement('div');
                    item.className = 'px-4 py-3 hover:bg-gray-100 dark:hover:bg-gray-700 cursor-pointer border-b border-gray-200 dark:border-gray-700 last:border-b-0';
                    item.innerHTML = `
                        <div class="flex items-center gap-3">
                            ${company.logo ? `<img src="${company.logo}" alt="${company.name}" class="w-10 h-10 object-contain rounded">` : '<div class="w-10 h-10 bg-gray-200 dark:bg-gray-700 rounded flex items-center justify-center"><span class="material-symbols-outlined text-gray-400">business</span></div>'}
                            <div class="flex-1 min-w-0">
                                <div class="font-medium text-[#111318] dark:text-white truncate">${escapeHtml(company.name)}</div>
                                ${company.industry ? `<div class="text-xs text-gray-500 dark:text-gray-400">${escapeHtml(company.industry)}</div>` : ''}
                            </div>
                            <span class="text-xs text-primary">Select</span>
                        </div>
                    `;
                    item.addEventListener('click', () => selectCompany(company));
                    autocompleteDiv.appendChild(item);
                });
                
                // Add option to create new company
                const newCompanyItem = document.createElement('div');
                newCompanyItem.className = 'px-4 py-3 hover:bg-gray-100 dark:hover:bg-gray-700 cursor-pointer border-t-2 border-primary bg-primary/5';
                newCompanyItem.innerHTML = `
                    <div class="flex items-center gap-3">
                        <span class="material-symbols-outlined text-primary">add_circle</span>
                        <div class="flex-1">
                            <div class="font-medium text-primary">Create new company: "${escapeHtml(query)}"</div>
                            <div class="text-xs text-gray-500 dark:text-gray-400">This company doesn't exist yet</div>
                        </div>
                    </div>
                `;
                newCompanyItem.addEventListener('click', () => createNewCompany(query));
                autocompleteDiv.appendChild(newCompanyItem);
                
                autocompleteDiv.classList.remove('hidden');
            }
            
            // Display new company option only
            function displayNewCompanyOption(query) {
                autocompleteDiv.innerHTML = '';
                
                const newCompanyItem = document.createElement('div');
                newCompanyItem.className = 'px-4 py-3 hover:bg-gray-100 dark:hover:bg-gray-700 cursor-pointer';
                newCompanyItem.innerHTML = `
                    <div class="flex items-center gap-3">
                        <span class="material-symbols-outlined text-primary">add_circle</span>
                        <div class="flex-1">
                            <div class="font-medium text-primary">Create new company: "${escapeHtml(query)}"</div>
                            <div class="text-xs text-gray-500 dark:text-gray-400">This company will be created when you submit the form</div>
                        </div>
                    </div>
                `;
                newCompanyItem.addEventListener('click', () => createNewCompany(query));
                autocompleteDiv.appendChild(newCompanyItem);
                
                autocompleteDiv.classList.remove('hidden');
            }
            
            // Select existing company
            function selectCompany(company) {
                selectedCompany = company;
                companyInput.value = company.name;
                companyIdInput.value = company.id;
                
                // Pre-fill company details if available
                if (company.description && !companyDescription.value) {
                    companyDescription.value = company.description;
                }
                if (company.website && !companyWebsite.value) {
                    companyWebsite.value = company.website;
                }
                
                autocompleteDiv.classList.add('hidden');
                companyStatus.textContent = `Selected: ${company.name}${company.industry ? ' (' + company.industry + ')' : ''}`;
            }
            
            // Create new company
            function createNewCompany(name) {
                selectedCompany = null;
                companyInput.value = name;
                companyIdInput.value = '';
                autocompleteDiv.classList.add('hidden');
                companyStatus.textContent = 'New company will be created: ' + name;
            }
            
            // Hide autocomplete when clicking outside
            document.addEventListener('click', function(e) {
                if (!companyInput.contains(e.target) && !autocompleteDiv.contains(e.target)) {
                    autocompleteDiv.classList.add('hidden');
                }
            });
            
            // Handle keyboard navigation
            companyInput.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    autocompleteDiv.classList.add('hidden');
                } else if (e.key === 'ArrowDown' || e.key === 'ArrowUp') {
                    e.preventDefault();
                    // Simple keyboard navigation - could be enhanced
                    const items = autocompleteDiv.querySelectorAll('div[class*="cursor-pointer"]');
                    if (items.length > 0) {
                        items[0].click();
                    }
                }
            });
            
            // Escape HTML helper
            function escapeHtml(text) {
                const div = document.createElement('div');
                div.textContent = text;
                return div.innerHTML;
            }
        })();

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
                    // Hide location field when Remote is selected
                    locationContainer.style.display = 'none';
                    locationInput.removeAttribute('required');
                    if (locationRequiredSpan) {
                        locationRequiredSpan.style.display = 'none';
                    }
                    // Clear location value
                    locationInput.value = '';
                } else {
                    // Show location field for other job types
                    locationContainer.style.display = 'block';
                    locationInput.setAttribute('required', 'required');
                    if (locationRequiredSpan) {
                        locationRequiredSpan.style.display = 'inline';
                    }
                }
            }
            
            // Initialize on page load
            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', toggleLocationField);
            } else {
                toggleLocationField();
            }
            
            // Listen for changes
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
    </script>
</body>
</html>

