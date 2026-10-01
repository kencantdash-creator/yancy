<?php
require_once __DIR__ . '/functions.php';
$current = basename($_SERVER['SCRIPT_NAME']);
$pageTitle = $pageTitle ?? 'Anti-Illegal Gambling Program';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($pageTitle) ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: { extend: { fontFamily: { sans: ['Inter', 'ui-sans-serif', 'system-ui', 'sans-serif'] } } }
        };
    </script>
</head>
<body class="flex min-h-screen flex-col bg-stone-100 font-sans text-stone-900 antialiased">

<header class="border-b border-stone-300 bg-white">
    <div class="mx-auto flex max-w-5xl items-center justify-between px-4 py-3 sm:px-6 lg:px-8">
        <a href="index.php" class="flex items-center gap-2 font-semibold">
            <span class="inline-flex h-9 w-9 items-center justify-center rounded-lg bg-stone-900 text-white">
                <?= icon('shield', 'h-5 w-5') ?>
            </span>
            <span class="text-sm sm:text-base">Anti-Illegal Gambling</span>
        </a>

        <!-- Desktop menu -->
        <nav class="hidden items-center gap-1 lg:flex" aria-label="Main">
            <?php foreach (nav_items() as $file => [$label, $ico]): $active = $current === $file; ?>
                <a href="<?= e($file) ?>"
                   class="inline-flex items-center gap-2 rounded-lg px-3 py-2 text-sm font-medium focus:outline-none focus-visible:ring-2 focus-visible:ring-stone-900 <?= $active ? 'bg-stone-900 text-white' : 'text-stone-700 hover:bg-stone-200' ?>"
                   <?= $active ? 'aria-current="page"' : '' ?>>
                    <?= icon($ico, 'h-4 w-4') ?><?= e($label) ?>
                </a>
            <?php endforeach; ?>
        </nav>

        <!-- Mobile toggle -->
        <button id="menu-btn" type="button" aria-expanded="false" aria-controls="mobile-menu" aria-label="Toggle menu"
                class="inline-flex h-10 w-10 items-center justify-center rounded-lg border border-stone-300 text-stone-800 hover:bg-stone-100 focus:outline-none focus-visible:ring-2 focus-visible:ring-stone-900 lg:hidden">
            <span id="icon-open"><?= icon('menu') ?></span>
            <span id="icon-close" class="hidden"><?= icon('x') ?></span>
        </button>
    </div>

    <!-- Mobile menu -->
    <nav id="mobile-menu" class="hidden border-t border-stone-200 px-4 py-3 lg:hidden" aria-label="Mobile">
        <?php foreach (nav_items() as $file => [$label, $ico]): $active = $current === $file; ?>
            <a href="<?= e($file) ?>"
               class="mb-1 flex items-center gap-3 rounded-lg px-3 py-3 text-sm font-medium <?= $active ? 'bg-stone-900 text-white' : 'text-stone-700 hover:bg-stone-100' ?>"
               <?= $active ? 'aria-current="page"' : '' ?>>
                <?= icon($ico, 'h-4 w-4') ?><?= e($label) ?>
            </a>
        <?php endforeach; ?>
    </nav>
</header>

<main class="mx-auto w-full max-w-5xl flex-1 px-4 py-8 sm:px-6 sm:py-12 lg:px-8">
