<!doctype html>
<html lang="es">
<head>
    <?php $contentViewSafe = isset($contentView) ? $contentView : ''; ?>
    <?php $stylesSafe = isset($styles) && is_array($styles) ? $styles : []; ?>
    <?php $scriptsSafe = isset($scripts) && is_array($scripts) ? $scripts : []; ?>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($title ?? 'Panel Admin') ?></title>
    <link rel="stylesheet" href="<?= e(url('/css/global.css')) ?>">
    <?php foreach ($stylesSafe as $style): ?>
        <link rel="stylesheet" href="<?= e(url('/css/' . $style)) ?>">
    <?php endforeach; ?>
</head>
<body class="admin-body">
    <div class="admin-shell">
        <?php require __DIR__ . '/../partials/admin_sidebar.php'; ?>

        <section class="admin-content">
            <?php require __DIR__ . '/../partials/flash.php'; ?>
            <?php require $contentViewSafe; ?>
        </section>
    </div>

    <script src="<?= e(url('/js/global.js')) ?>"></script>
    <?php foreach ($scriptsSafe as $script): ?>
        <script src="<?= e(url('/js/' . $script)) ?>"></script>
    <?php endforeach; ?>
</body>
</html>
