<?php
require_once __DIR__ . '/includes/db.php';

$errors = [];
$old = ['site_url' => '', 'description' => '', 'contact' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $old['site_url']    = trim($_POST['site_url'] ?? '');
    $old['description'] = trim($_POST['description'] ?? '');
    $old['contact']     = trim($_POST['contact'] ?? '');

    if (!csrf_check($_POST['csrf'] ?? null)) {
        $errors[] = 'Your session expired. Reload the page and try again.';
    }
    // Hidden field that real users never fill in (bot trap).
    if (!empty($_POST['nickname'])) {
        exit;
    }
    if ($old['site_url'] === '' || !preg_match('#^https?://#i', $old['site_url']) || !filter_var($old['site_url'], FILTER_VALIDATE_URL)) {
        $errors[] = 'Enter a full link that starts with http:// or https://.';
    } elseif (mb_strlen($old['site_url']) > 500) {
        $errors[] = 'The link is too long (500 characters maximum).';
    }
    $len = mb_strlen($old['description']);
    if ($len < 10 || $len > 2000) {
        $errors[] = 'Describe what you saw in 10 to 2000 characters.';
    }
    if (mb_strlen($old['contact']) > 150) {
        $errors[] = 'Contact details are too long (150 characters maximum).';
    }

    if (!$errors) {
        $stmt = db()->prepare('INSERT INTO reports (site_url, description, contact) VALUES (?, ?, ?)');
        $stmt->execute([$old['site_url'], $old['description'], $old['contact'] !== '' ? $old['contact'] : null]);
        header('Location: report.php?sent=1');
        exit;
    }
}

$sent = isset($_GET['sent']);
$pageTitle = 'Report a site | Anti-Illegal Gambling Program';
require __DIR__ . '/includes/header.php';

$field = 'mt-2 block w-full rounded-lg border border-stone-300 bg-white px-4 py-3 text-base focus:border-stone-900 focus:outline-none focus:ring-2 focus:ring-stone-900';
?>
<section class="max-w-2xl">
    <h1 class="text-3xl font-bold leading-tight sm:text-4xl">Report a site</h1>
    <p class="mt-4 text-base leading-relaxed text-stone-700 sm:text-lg">
        Send the link to a suspected illegal gambling site. Reports are saved for review.
    </p>
</section>

<?php if ($sent): ?>
    <div class="mt-6 flex max-w-2xl items-start gap-3 rounded-2xl border border-stone-900 bg-white p-4 sm:p-5" role="status">
        <span class="mt-0.5"><?= icon('check') ?></span>
        <p class="text-sm sm:text-base">Report received. Thank you for sending it.</p>
    </div>
<?php endif; ?>

<?php if ($errors): ?>
    <div class="mt-6 max-w-2xl rounded-2xl border border-stone-900 bg-white p-4 sm:p-5" role="alert">
        <p class="flex items-center gap-2 text-sm font-semibold sm:text-base"><?= icon('alert', 'h-4 w-4') ?>Fix the following:</p>
        <ul class="mt-2 list-disc space-y-1 pl-9 text-sm sm:text-base">
            <?php foreach ($errors as $err): ?><li><?= e($err) ?></li><?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<form method="post" action="report.php" class="mt-6 max-w-2xl space-y-6 rounded-2xl border border-stone-300 bg-white p-6 sm:p-8" novalidate>
    <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">
    <div class="hidden" aria-hidden="true">
        <label>Nickname <input type="text" name="nickname" tabindex="-1" autocomplete="off"></label>
    </div>

    <div>
        <label for="site_url" class="text-sm font-semibold sm:text-base">Site link</label>
        <input id="site_url" name="site_url" type="url" required maxlength="500" placeholder="https://example.com"
               value="<?= e($old['site_url']) ?>" class="<?= $field ?>">
    </div>

    <div>
        <label for="description" class="text-sm font-semibold sm:text-base">What did you see?</label>
        <textarea id="description" name="description" rows="5" required minlength="10" maxlength="2000"
                  class="<?= $field ?>"><?= e($old['description']) ?></textarea>
    </div>

    <div>
        <label for="contact" class="text-sm font-semibold sm:text-base">Your contact (optional)</label>
        <input id="contact" name="contact" type="text" maxlength="150" placeholder="Email or phone number"
               value="<?= e($old['contact']) ?>" class="<?= $field ?>">
    </div>

    <button type="submit" class="inline-flex w-full items-center justify-center gap-2 rounded-lg bg-stone-900 px-5 py-3 text-sm font-medium text-white hover:bg-stone-700 focus:outline-none focus-visible:ring-2 focus-visible:ring-stone-900 focus-visible:ring-offset-2 sm:w-auto">
        <?= icon('flag', 'h-4 w-4') ?>Submit report
    </button>
</form>
<?php require __DIR__ . '/includes/footer.php'; ?>
