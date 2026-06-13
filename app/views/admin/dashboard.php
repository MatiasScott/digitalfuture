<?php $stats = isset($stats) && is_array($stats) ? $stats : []; ?>

<header class="admin-header">
    <h1>Dashboard</h1>
    <p>Resumen general del congreso.</p>
</header>

<section class="kpi-grid">
    <article class="kpi-card"><h3>Inscritos</h3><strong><?= e((string) $stats['inscritos']) ?></strong></article>
    <article class="kpi-card"><h3>Pagos pendientes</h3><strong><?= e((string) $stats['pagos_pendientes']) ?></strong></article>
    <article class="kpi-card"><h3>Pagos aprobados</h3><strong><?= e((string) $stats['pagos_aprobados']) ?></strong></article>
    <article class="kpi-card"><h3>Asistentes confirmados</h3><strong><?= e((string) $stats['asistentes_confirmados']) ?></strong></article>
    <article class="kpi-card"><h3>Ingresos totales</h3><strong>$<?= e(number_format((float) $stats['ingresos_totales'], 2)) ?></strong></article>
</section>
