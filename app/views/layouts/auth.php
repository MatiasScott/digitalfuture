<!doctype html>
<html lang="es">
<head>
    <?php $contentViewSafe = isset($contentView) ? $contentView : ''; ?>
    <?php $stylesSafe = isset($styles) && is_array($styles) ? $styles : []; ?>
    <?php $scriptsSafe = isset($scripts) && is_array($scripts) ? $scripts : []; ?>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($title ?? 'Login') ?></title>
    <link rel="stylesheet" href="<?= e(url('/css/global.css')) ?>">
    <?php foreach ($stylesSafe as $style): ?>
        <link rel="stylesheet" href="<?= e(url('/css/' . $style)) ?>">
    <?php endforeach; ?>
</head>
<body class="auth-body">
    <main class="auth-container">
        <?php require __DIR__ . '/../partials/flash.php'; ?>
        <?php require $contentViewSafe; ?>
    </main>

    <script src="<?= e(url('/js/global.js')) ?>"></script>
    <?php foreach ($scriptsSafe as $script): ?>
        <script src="<?= e(url('/js/' . $script)) ?>"></script>
    <?php endforeach; ?>
</body>
</html>
