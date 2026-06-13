<?php if (!empty($flashMessages)): ?>
    <div class="flash-wrap">
        <?php foreach ($flashMessages as $flash): ?>
            <div class="flash flash-<?= e($flash['type'] ?? 'info') ?>">
                <?= e($flash['message'] ?? '') ?>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>
