<?php
require_once __DIR__ . '/includes/db.php';
$pageTitle = 'Help resources | Anti-Illegal Gambling Program';

$hotlines = db()->query('SELECT * FROM hotlines ORDER BY sort_order')->fetchAll();
$numStmt = db()->prepare('SELECT network, display, tel FROM hotline_numbers WHERE hotline_id = ? ORDER BY id');

require __DIR__ . '/includes/header.php';
?>
<section class="max-w-2xl">
    <h1 class="text-3xl font-bold leading-tight sm:text-4xl">Help resources</h1>
    <p class="mt-4 text-base leading-relaxed text-stone-700 sm:text-lg">
        If gambling is hurting you or someone close to you, you can talk to a trained counselor. Support is confidential, and families can call too.
    </p>
</section>

<div class="mt-6 flex items-start gap-3 rounded-2xl border border-stone-900 bg-white p-4 sm:p-5">
    <span class="mt-0.5 text-stone-900"><?= icon('alert') ?></span>
    <p class="text-sm sm:text-base">If someone is in immediate danger, call <strong class="font-semibold">911</strong>.</p>
</div>

<section class="mt-8 grid gap-4 lg:grid-cols-2">
<?php foreach ($hotlines as $h):
    $numStmt->execute([$h['id']]);
    $numbers = $numStmt->fetchAll();
?>
    <article class="rounded-2xl border border-stone-300 bg-white p-6">
        <div class="flex items-center gap-3">
            <span class="inline-flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-stone-900 text-white"><?= icon($h['icon']) ?></span>
            <h2 class="text-lg font-semibold"><?= e($h['name']) ?></h2>
        </div>
        <p class="mt-4 text-stone-700"><?= e($h['description']) ?></p>
        <p class="mt-3 inline-flex items-center gap-2 text-sm font-medium text-stone-700"><?= icon('clock', 'h-4 w-4') ?><?= e($h['hours']) ?></p>

        <?php if ($numbers): ?>
            <ul class="mt-4 space-y-2">
                <?php foreach ($numbers as $n): ?>
                    <li>
                        <a href="tel:<?= e($n['tel']) ?>" class="flex items-center justify-between rounded-lg border border-stone-300 px-4 py-3 hover:border-stone-900 focus:outline-none focus-visible:ring-2 focus-visible:ring-stone-900">
                            <span class="text-sm text-stone-600"><?= e($n['network']) ?></span>
                            <span class="font-semibold"><?= e($n['display']) ?></span>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>

        <?php if ($h['note']): ?><p class="mt-4 text-stone-700"><?= e($h['note']) ?></p><?php endif; ?>

        <?php if ($h['website']): ?>
            <a href="<?= e($h['website']) ?>" target="_blank" rel="noopener noreferrer"
               class="mt-4 inline-flex items-center gap-2 rounded-lg bg-stone-900 px-4 py-2.5 text-sm font-medium text-white hover:bg-stone-700 focus:outline-none focus-visible:ring-2 focus-visible:ring-stone-900 focus-visible:ring-offset-2">
                <?= icon('globe', 'h-4 w-4') ?>Visit official website
            </a>
        <?php endif; ?>
    </article>
<?php endforeach; ?>
</section>
<?php require __DIR__ . '/includes/footer.php'; ?>
