</main>

<footer class="border-t border-stone-300 bg-white">
    <div class="mx-auto flex max-w-5xl flex-col gap-2 px-4 py-5 text-sm text-stone-600 sm:flex-row sm:items-center sm:justify-between sm:px-6 lg:px-8">
        <span>Anti-Illegal Gambling Enforcement and Compliance &middot; <?= date('Y') ?></span>
        <a href="admin.php" class="inline-flex items-center gap-2 hover:text-stone-900"><?= icon('lock', 'h-4 w-4') ?>Admin</a>
    </div>
</footer>

<script>
    // Mobile menu toggle
    const btn = document.getElementById('menu-btn');
    const menu = document.getElementById('mobile-menu');
    const iconOpen = document.getElementById('icon-open');
    const iconClose = document.getElementById('icon-close');
    btn.addEventListener('click', () => {
        const open = menu.classList.toggle('hidden') === false;
        btn.setAttribute('aria-expanded', String(open));
        iconOpen.classList.toggle('hidden', open);
        iconClose.classList.toggle('hidden', !open);
    });
</script>
</body>
</html>
