<?php
require_once __DIR__ . '/includes/db.php';

if (isset($_GET['logout'])) {
    $_SESSION = [];
    session_destroy();
    header('Location: admin.php');
    exit;
}

$loginError = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if (!csrf_check($_POST['csrf'] ?? null)) {
        $loginError = 'Your session expired. Try again.';
    } elseif ($action === 'login') {
        if (hash_equals(ADMIN_PASSWORD, (string) ($_POST['password'] ?? ''))) {
            session_regenerate_id(true);
            $_SESSION['admin'] = true;
            header('Location: admin.php');
            exit;
        }
        $loginError = 'Wrong password.';
    } elseif (!empty($_SESSION['admin']) && in_array($action, ['review', 'delete'], true)) {
        $id = (int) ($_POST['id'] ?? 0);
        if ($action === 'review') {
            db()->prepare("UPDATE reports SET status = 'reviewed' WHERE id = ?")->execute([$id]);
        } else {
            db()->prepare('DELETE FROM reports WHERE id = ?')->execute([$id]);
        }
        header('Location: admin.php');
        exit;
    }
}

$isAdmin = !empty($_SESSION['admin']);
$reports = $isAdmin ? db()->query('SELECT * FROM reports ORDER BY created_at DESC')->fetchAll() : [];
$newCount = count(array_filter($reports, fn($r) => $r['status'] === 'new'));

$pageTitle = 'Admin | Anti-Illegal Gambling Program';
require __DIR__ . '/includes/header.php';
$btn = 'inline-flex items-center gap-2 rounded-lg px-4 py-2 text-sm font-medium focus:outline-none focus-visible:ring-2 focus-visible:ring-stone-900 focus-visible:ring-offset-2';
?>

<?php if (!$isAdmin): ?>
    <section class="mx-auto max-w-md">
        <h1 class="text-3xl font-bold">Admin login</h1>
        <?php if ($loginError): ?>
            <p class="mt-4 flex items-center gap-2 rounded-lg border border-stone-900 bg-white p-3 text-sm" role="alert"><?= icon('alert', 'h-4 w-4') ?><?= e($loginError) ?></p>
        <?php endif; ?>
        <form method="post" action="admin.php" class="mt-6 space-y-5 rounded-2xl border border-stone-300 bg-white p-6">
            <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">
            <input type="hidden" name="action" value="login">
            <div>
                <label for="password" class="text-sm font-semibold">Password</label>
                <input id="password" name="password" type="password" required autofocus
                       class="mt-2 block w-full rounded-lg border border-stone-300 px-4 py-3 focus:border-stone-900 focus:outline-none focus:ring-2 focus:ring-stone-900">
            </div>
            <button type="submit" class="<?= $btn ?> w-full justify-center bg-stone-900 py-3 text-white hover:bg-stone-700"><?= icon('lock', 'h-4 w-4') ?>Log in</button>
        </form>
    </section>
<?php else: ?>
    <section class="flex flex-wrap items-end justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold sm:text-4xl">Submitted reports</h1>
            <p class="mt-2 text-stone-700"><?= count($reports) ?> total, <?= $newCount ?> new</p>
        </div>
        <a href="admin.php?logout=1" class="<?= $btn ?> border border-stone-300 hover:border-stone-900">Log out</a>
    </section>

    <?php if (!$reports): ?>
        <p class="mt-8 rounded-2xl border border-stone-300 bg-white p-6 text-stone-700">No reports yet. New submissions from the Report a site page will appear here.</p>
    <?php endif; ?>

    <div class="mt-8 space-y-4">
    <?php foreach ($reports as $r): $isNew = $r['status'] === 'new'; ?>
        <article class="rounded-2xl border border-stone-300 bg-white p-5 sm:p-6">
            <div class="flex flex-wrap items-center justify-between gap-2">
                <span class="inline-flex items-center gap-2 rounded-full px-3 py-1 text-xs font-semibold <?= $isNew ? 'bg-stone-900 text-white' : 'bg-stone-200 text-stone-700' ?>">
                    <?= $isNew ? 'New' : 'Reviewed' ?>
                </span>
                <time class="text-sm text-stone-600" datetime="<?= e($r['created_at']) ?>"><?= e(date('M j, Y g:i A', strtotime($r['created_at']))) ?></time>
            </div>
            <p class="mt-3 break-all font-semibold"><?= e($r['site_url']) ?></p>
            <p class="mt-2 whitespace-pre-line text-stone-700"><?= e($r['description']) ?></p>
            <?php if ($r['contact']): ?>
                <p class="mt-2 text-sm text-stone-600">Contact: <?= e($r['contact']) ?></p>
            <?php endif; ?>
            <div class="mt-4 flex flex-wrap gap-2">
                <?php if ($isNew): ?>
                    <form method="post" action="admin.php">
                        <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">
                        <input type="hidden" name="action" value="review">
                        <input type="hidden" name="id" value="<?= (int) $r['id'] ?>">
                        <button class="<?= $btn ?> bg-stone-900 text-white hover:bg-stone-700"><?= icon('check', 'h-4 w-4') ?>Mark reviewed</button>
                    </form>
                <?php endif; ?>
                <form method="post" action="admin.php" onsubmit="return confirm('Delete this report?');">
                    <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">
                    <input type="hidden" name="action" value="delete">
                    <input type="hidden" name="id" value="<?= (int) $r['id'] ?>">
                    <button class="<?= $btn ?> border border-stone-300 hover:border-stone-900">Delete</button>
                </form>
            </div>
        </article>
    <?php endforeach; ?>
    </div>
<?php endif; ?>
<?php require __DIR__ . '/includes/footer.php'; ?>
