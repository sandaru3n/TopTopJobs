<?php
    $siteSettingsModel = new \App\Models\SiteSettingsModel();
    $contactContent = $siteSettingsModel->getSetting('contact_content');
    if (!$contactContent) {
        $contactContent = $siteSettingsModel->getSetting('contact_description', '');
    }
?>
<?= view('partials/head') ?>
<body class="font-display bg-background-light dark:bg-background-dark text-[#111318] dark:text-gray-200">
    <div class="relative flex min-h-screen w-full flex-col">
        <?= view('partials/header') ?>

        <main class="flex-grow container mx-auto px-4 py-8 md:py-12 max-w-4xl">
            <h1 class="text-3xl font-bold text-[#111318] dark:text-white mb-4">Contact Us</h1>
            <?php if (!empty($contactContent)): ?>
                <div class="prose dark:prose-invert max-w-none text-gray-700 dark:text-gray-300 leading-relaxed">
                    <?= nl2br(esc($contactContent)) ?>
                </div>
            <?php else: ?>
                <p class="text-gray-600 dark:text-gray-400 leading-relaxed mb-6">
                    Have questions or feedback? Reach out to us at <a href="mailto:support@toptopjobs.com" class="text-primary font-semibold">support@toptopjobs.com</a>.
                </p>
            <?php endif; ?>
        </main>

        <?= view('partials/footer') ?>
    </div>
</body>
</html>

