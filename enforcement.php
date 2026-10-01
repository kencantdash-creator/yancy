<?php
require_once __DIR__ . '/includes/db.php';
$pageTitle = 'Enforcement | Anti-Illegal Gambling Program';

$sections = db()->query('SELECT * FROM sections ORDER BY section_no')->fetchAll();
$scopeStmt = db()->prepare('SELECT label, icon FROM scope_items WHERE section_id = ? ORDER BY sort_order');

require __DIR__ . '/includes/header.php';
?>
<div class="space-y-6">
<?php foreach ($sections as $section):
    $scopeStmt->execute([$section['id']]);
    $scope = $scopeStmt->fetchAll();
?>
<article class="mx-auto w-full max-w-3xl rounded-2xl border border-stone-300 bg-white p-6 sm:p-10">
    <h1 class="text-2xl font-bold leading-tight sm:text-3xl lg:text-4xl">
        <?= (int) $section['section_no'] ?>. <?= e($section['title']) ?>
    </h1>

    <div class="mt-8 space-y-8">
        <section>
            <p class="flex items-start gap-3 text-base sm:text-lg">
                <span class="mt-0.5 text-stone-500"><?= icon('target') ?></span>
                <span><strong class="font-semibold">Prepared for:</strong> <?= e($section['prepared_for']) ?><?= chip() ?></span>
            </p>
        </section>

        <section>
            <h2 class="flex items-center gap-3 text-base font-semibold sm:text-lg">
                <span class="text-stone-500"><?= icon('list') ?></span>Scope:
            </h2>
            <ul class="mt-4 space-y-3 sm:ml-8">
                <?php foreach ($scope as $i => $item): ?>
                    <li class="flex items-center gap-3 text-base sm:text-lg">
                        <span class="inline-flex h-9 w-9 items-center justify-center rounded-lg bg-stone-100 text-stone-700"><?= icon($item['icon']) ?></span>
                        <span><?= e($item['label']) ?><?= $i === count($scope) - 1 ? chip() : '' ?></span>
                    </li>
                <?php endforeach; ?>
            </ul>
        </section>

        <section>
            <p class="flex items-start gap-3 text-base leading-relaxed sm:text-lg">
                <span class="mt-0.5 text-stone-500"><?= icon('file') ?></span>
                <span><strong class="font-semibold">Summary:</strong> <?= e($section['summary']) ?><?= chip() ?></span>
            </p>
        </section>
    </div>

    <div class="mt-10 flex flex-wrap gap-3 border-t border-stone-200 pt-6">
        <a href="report.php" class="inline-flex items-center gap-2 rounded-lg bg-stone-900 px-4 py-2.5 text-sm font-medium text-white hover:bg-stone-700 focus:outline-none focus-visible:ring-2 focus-visible:ring-stone-900 focus-visible:ring-offset-2">
            <?= icon('flag', 'h-4 w-4') ?>Report a site
        </a>
        <a href="help.php" class="inline-flex items-center gap-2 rounded-lg border border-stone-300 px-4 py-2.5 text-sm font-medium hover:border-stone-900 focus:outline-none focus-visible:ring-2 focus-visible:ring-stone-900 focus-visible:ring-offset-2">
            Find help resources <?= icon('chevron', 'h-4 w-4') ?>
        </a>
    </div>
</article>
<?php endforeach; ?>
</div>
<?php require __DIR__ . '/includes/footer.php'; ?>
