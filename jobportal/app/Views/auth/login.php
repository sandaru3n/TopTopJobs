<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <meta name="description" content="Sign in to your TopTopJobs account to access your profile, manage job applications, and explore career opportunities. Login securely to continue your job search journey."/>
    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-017G3E1N5V"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());

      gtag('config', 'G-017G3E1N5V');
    </script>
    <title>Login - TopTopJobs</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    <style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
    </style>
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "primary": "#2b6cee",
                        "background-light": "#f6f6f8",
                        "background-dark": "#101622",
                    },
                    fontFamily: {
                        "display": ["Inter", "sans-serif"]
                    },
                    borderRadius: {"DEFAULT": "1rem", "lg": "2rem", "xl": "3rem", "full": "9999px"},
                },
            },
        }
    </script>
    <!-- Favicon -->
    <link rel="apple-touch-icon" sizes="180x180" href="<?= base_url('apple-touch-icon.png') ?>">
    <link rel="icon" type="image/png" sizes="32x32" href="<?= base_url('favicon-32x32.png') ?>">
    <link rel="icon" type="image/png" sizes="16x16" href="<?= base_url('favicon-16x16.png') ?>">
    <link rel="manifest" href="<?= base_url('site.webmanifest') ?>">
</head>
<body class="font-display bg-background-light dark:bg-background-dark">
    <!-- Back to Home Button (Top Left) -->
    <div class="absolute top-4 left-4 z-10">
        <a href="<?= base_url('/') ?>" class="inline-flex items-center justify-center w-10 h-10 bg-black dark:bg-gray-900 rounded-full hover:bg-gray-800 dark:hover:bg-gray-700 transition-colors shadow-lg" title="Back to Home">
            <span class="material-symbols-outlined text-white text-xl">arrow_back</span>
        </a>
    </div>
    
    <div class="min-h-screen flex items-center justify-center px-4 py-12">
        <div class="w-full max-w-md">
            <!-- Logo and Title -->
            <div class="text-center mb-8">
                <div class="flex items-center justify-center gap-4 mb-4">
                    <div class="size-8 text-primary">
                        <svg fill="none" viewbox="0 0 48 48" xmlns="http://www.w3.org/2000/svg">
                            <path clip-rule="evenodd" d="M24 4H42V17.3333V30.6667H24V44H6V30.6667V17.3333H24V4Z" fill="currentColor" fill-rule="evenodd"></path>
                        </svg>
                    </div>
                    <h1 class="text-2xl font-bold text-[#111318] dark:text-white">TopTopJobs</h1>
                </div>
                <h2 class="text-xl font-semibold text-[#111318] dark:text-white mb-2">Welcome Back</h2>
                <p class="text-sm text-gray-600 dark:text-gray-400">Sign in to your account to continue</p>
            </div>

            <!-- Login Form -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-8">
                <?php if (session()->getFlashdata('error')): ?>
                    <div class="mb-4 p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg">
                        <p class="text-sm text-red-600 dark:text-red-400"><?= session()->getFlashdata('error') ?></p>
                    </div>
                <?php endif; ?>

                <?php if (session()->getFlashdata('success')): ?>
                    <div class="mb-4 p-4 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg">
                        <p class="text-sm text-green-600 dark:text-green-400"><?= session()->getFlashdata('success') ?></p>
                    </div>
                <?php endif; ?>

                <?php if (session()->getFlashdata('errors')): ?>
                    <div class="mb-4 p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg">
                        <ul class="text-sm text-red-600 dark:text-red-400 list-disc list-inside">
                            <?php foreach (session()->getFlashdata('errors') as $error): ?>
                                <li><?= $error ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <form action="<?= base_url('auth/processLogin') ?>" method="POST" class="space-y-6">
                    <?= csrf_field() ?>
                    
                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Email Address
                        </label>
                        <div class="relative">
                            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">email</span>
                            <input 
                                type="email" 
                                id="email" 
                                name="email" 
                                value="<?= old('email') ?>"
                                class="w-full pl-10 pr-4 py-3 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-[#111318] dark:text-white focus:outline-none focus:ring-2 focus:ring-primary/50 focus:border-primary" 
                                placeholder="you@example.com"
                                required
                            />
                        </div>
                    </div>

                    <!-- Password -->
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Password
                        </label>
                        <div class="relative">
                            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">lock</span>
                            <input 
                                type="password" 
                                id="password" 
                                name="password" 
                                class="w-full pl-10 pr-4 py-3 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-[#111318] dark:text-white focus:outline-none focus:ring-2 focus:ring-primary/50 focus:border-primary" 
                                placeholder="••••••••"
                                required
                            />
                        </div>
                    </div>

                    <!-- Remember Me & Forgot Password -->
                    <div class="flex items-center justify-between">
                        <label class="flex items-center">
                            <input 
                                type="checkbox" 
                                name="remember" 
                                class="rounded border-gray-300 text-primary focus:ring-primary"
                            />
                            <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">Remember me</span>
                        </label>
                        <a href="/forgot-password" class="text-sm text-primary hover:underline">
                            Forgot password?
                        </a>
                    </div>

                    <!-- Submit Button -->
                    <button 
                        type="submit" 
                        class="w-full py-3 px-4 bg-primary text-white rounded-lg font-semibold hover:bg-primary/90 transition-colors focus:outline-none focus:ring-2 focus:ring-primary/50"
                    >
                        Sign In
                    </button>
                </form>

                <!-- Divider -->
                <div class="my-6 flex items-center">
                    <div class="flex-1 border-t border-gray-300 dark:border-gray-700"></div>
                    <span class="px-4 text-sm text-gray-500 dark:text-gray-400">OR</span>
                    <div class="flex-1 border-t border-gray-300 dark:border-gray-700"></div>
                </div>

                <!-- Google Sign-In Button -->
                <button 
                    type="button"
                    id="googleSignInBtn"
                    class="w-full py-3 px-4 bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg border border-gray-300 dark:border-gray-600 font-semibold hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors focus:outline-none focus:ring-2 focus:ring-primary/50 flex items-center justify-center gap-3"
                >
                    <svg class="w-5 h-5" viewBox="0 0 24 24">
                        <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                        <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                        <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                        <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                    </svg>
                    Continue with Google
                </button>
            </div>

            <!-- Sign Up Link (Outside the box) -->
            <div class="mt-6 text-center">
                <p class="text-sm text-gray-600 dark:text-gray-400">
                    Don't have an account? 
                    <a href="<?= base_url('signup') ?>" class="text-primary font-semibold hover:underline">
                        Sign up
                    </a>
                </p>
            </div>

            <!-- Back to Home -->
            <div class="mt-6 text-center">
                <a href="<?= base_url('/') ?>" class="text-sm text-gray-600 dark:text-gray-400 hover:text-primary transition-colors">
                    ← Back to Home
                </a>
            </div>
        </div>
    </div>

    <!-- Google Sign-In Script -->
    <script src="https://accounts.google.com/gsi/client" async defer></script>
    <script>
        // Handle Google Sign-In callback
        function handleGoogleSignIn(response) {
            // Send the credential to the server
            fetch('<?= base_url('auth/google') ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    '<?= csrf_header() ?>': '<?= csrf_hash() ?>'
                },
                body: JSON.stringify({
                    credential: response.credential
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Redirect on success
                    window.location.href = data.redirect || '<?= base_url('/manage-jobs') ?>';
                } else {
                    // Show error
                    alert(data.message || 'Google sign-in failed. Please try again.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred during Google sign-in. Please try again.');
            });
        }

        // Initialize Google Sign-In when page loads
        window.onload = function () {
            const googleClientId = '<?= env('GOOGLE_CLIENT_ID', '') ?>';
            
            if (googleClientId) {
                // Initialize Google Identity Services
                google.accounts.id.initialize({
                    client_id: googleClientId,
                    callback: handleGoogleSignIn
                });

                // Attach click handler to custom button
                const googleBtn = document.getElementById('googleSignInBtn');
                if (googleBtn) {
                    googleBtn.addEventListener('click', function(e) {
                        e.preventDefault();
                        // Use One Tap or show popup
                        google.accounts.oauth2.initTokenClient({
                            client_id: googleClientId,
                            scope: 'email profile',
                            callback: (response) => {
                                if (response.access_token) {
                                    // Fetch user info using access token
                                    fetch('https://www.googleapis.com/oauth2/v2/userinfo', {
                                        headers: {
                                            'Authorization': 'Bearer ' + response.access_token
                                        }
                                    })
                                    .then(res => res.json())
                                    .then(profile => {
                                        // Create credential-like object for backend
                                        handleGoogleSignIn({
                                            credential: JSON.stringify({
                                                sub: profile.id,
                                                email: profile.email,
                                                email_verified: profile.verified_email,
                                                given_name: profile.given_name,
                                                family_name: profile.family_name,
                                                picture: profile.picture
                                            })
                                        });
                                    })
                                    .catch(error => {
                                        console.error('Error fetching user info:', error);
                                        alert('Failed to get user information. Please try again.');
                                    });
                                }
                            }
                        }).requestAccessToken();
                    });
                }
            } else {
                // Hide Google button if client ID is not configured
                const googleBtn = document.getElementById('googleSignInBtn');
                if (googleBtn) {
                    googleBtn.style.display = 'none';
                }
            }
        };
    </script>
</body>
</html>

