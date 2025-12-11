<?php
    $siteSettingsModel = new \App\Models\SiteSettingsModel();
    $termsContent = $siteSettingsModel->getSetting('terms_content');
    if (!$termsContent) {
        $termsContent = $siteSettingsModel->getSetting('terms_description', '');
    }
?>
<?= view('partials/head') ?>
<body class="font-display bg-background-light dark:bg-background-dark text-[#111318] dark:text-gray-200">
    <div class="relative flex min-h-screen w-full flex-col">
        <?= view('partials/header') ?>

        <main class="flex-grow container mx-auto px-4 py-8 md:py-12 max-w-4xl">
            <h1 class="text-3xl font-bold text-[#111318] dark:text-white mb-4">Terms &amp; Conditions</h1>
            <?php if (!empty($termsContent)): ?>
                <div class="prose dark:prose-invert max-w-none text-gray-700 dark:text-gray-300 leading-relaxed">
                    <?= nl2br(esc($termsContent)) ?>
                </div>
            <?php else: ?>
                <p class="text-gray-600 dark:text-gray-400 leading-relaxed">
                    These are placeholder terms. Please update this page with your actual terms and conditions.
                </p>
            <?php endif; ?>
        </main>

        <?= view('partials/footer') ?>
    </div>
</body>
</html>

