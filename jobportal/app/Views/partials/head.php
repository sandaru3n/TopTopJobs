<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title><?= esc($title ?? 'Job Portal - TopTopJobs') ?></title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
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
    <style>
        /* Profile Dropdown Styles (Desktop only) */
        #profileDropdown {
            animation: fadeIn 0.2s ease-in-out;
        }
        
        #profileMenuBtn {
            pointer-events: auto !important;
            cursor: pointer !important;
            z-index: 50;
            position: relative;
        }
        
        /* Hide profile dropdown on mobile */
        @media (max-width: 768px) {
            #profileMenuBtn,
            #profileDropdown {
                display: none !important;
            }
        }
        
        /* Mobile Menu Styles */
        #mobileMenu {
            will-change: transform;
        }
        
        #mobileMenu:not(.translate-x-full) {
            transform: translateX(0);
        }
        
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        /* Header Scroll Hide/Show */
        #mainHeader {
            will-change: transform;
            transform: translateY(0);
        }
        
        /* Filter Pills Scroll Hide/Show */
        #filterPillsSection {
            will-change: transform;
            transform: translateY(0);
        }
        
        /* Filter Pills - Keep on one line */
        #filterPillsSection .flex {
            flex-wrap: nowrap !important;
        }
        
        /* Hide scrollbar for filter pills */
        #filterPillsSection .overflow-x-auto {
            -ms-overflow-style: none;  /* IE and Edge */
            scrollbar-width: none;  /* Firefox */
        }
        
        #filterPillsSection .overflow-x-auto::-webkit-scrollbar {
            display: none;  /* Chrome, Safari and Opera */
        }
    </style>
    <?php if (!empty($css_file)): ?>
        <link rel="stylesheet" href="<?= base_url($css_file) ?>">
    <?php endif; ?>
</head>

