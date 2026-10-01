<?php
$pageTitle = 'Home | Anti-Illegal Gambling Program';
require __DIR__ . '/includes/header.php';

$cards = [
    ['enforcement.php', 'shield', 'Enforcement and compliance', 'The framework for detecting, suppressing, and eliminating unauthorized gambling operations.'],
    ['report.php', 'flag', 'Report a site', 'Send us the link to a suspected illegal gambling site so it can be reviewed.'],
    ['help.php', 'heart', 'Help resources', 'Free, confidential hotlines for people affected by gambling and their families.'],
];
?>
<section class="max-w-2xl">
    <h1 class="text-3xl font-bold leading-tight sm:text-4xl lg:text-5xl">Anti-Illegal Gambling Enforcement and Compliance</h1>
    <p class="mt-5 text-base leading-relaxed text-stone-700 sm:text-lg">
        A program for people addicted to online gambling, covering both minors and adults. Choose a section below to read the framework or find support.
    </p>
</section>

<section class="mt-10 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
    <?php foreach ($cards as [$href, $ico, $title, $text]): ?>
        <a href="<?= e($href) ?>" class="group flex flex-col rounded-2xl border border-stone-300 bg-white p-6 hover:border-stone-900 focus:outline-none focus-visible:ring-2 focus-visible:ring-stone-900">
            <span class="inline-flex h-11 w-11 items-center justify-center rounded-xl bg-stone-900 text-white"><?= icon($ico, 'h-5 w-5') ?></span>
            <h2 class="mt-4 text-lg font-semibold"><?= e($title) ?></h2>
            <p class="mt-2 flex-1 text-stone-700"><?= e($text) ?></p>
            <span class="mt-4 inline-flex items-center gap-1 text-sm font-medium">Open section <?= icon('chevron', 'h-4 w-4') ?></span>
        </a>
    <?php endforeach; ?>
</section>
<?php require __DIR__ . '/includes/footer.php'; ?>
