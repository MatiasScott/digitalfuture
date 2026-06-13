<?php $ticketTypes = isset($ticketTypes) && is_array($ticketTypes) ? $ticketTypes : []; ?>

<section class="hero">
    <h1>Congreso Digital Future 2026</h1>
    <p>Una experiencia de alto impacto para profesionales, estudiantes e innovadores.</p>
    <a class="btn-primary" href="<?= e(url('/registro')) ?>">Inscribete ahora</a>
</section>

<section class="grid-two">
    <article>
        <h2>Informacion del congreso</h2>
        <p>Evento enfocado en tendencias de transformacion digital, IA aplicada y liderazgo tecnologico.</p>
    </article>
    <article>
        <h2>Agenda</h2>
        <ul>
            <li>Dia 1: Apertura, keynote y paneles estrategicos.</li>
            <li>Dia 2: Talleres y sesiones tecnicas.</li>
            <li>Dia 3: Networking, cierre y roadmap 2027.</li>
        </ul>
    </article>
</section>

<section>
    <h2>Speakers</h2>
    <p>Referentes nacionales e internacionales de producto, ingenieria y negocios.</p>
</section>

<section>
    <h2>Sponsors</h2>
    <p>Aliados estrategicos en educacion, tecnologia y emprendimiento.</p>
</section>

<section>
    <h2>Preguntas frecuentes</h2>
    <p>Consulta modalidades de participacion, certificacion y requisitos de ingreso.</p>
</section>

<section>
    <h2>Entradas</h2>
    <div class="tickets-grid">
        <?php foreach ($ticketTypes as $ticket): ?>
            <article class="ticket-card">
                <h3><?= e($ticket['nombre']) ?></h3>
                <p><?= e($ticket['descripcion']) ?></p>
                <strong>$<?= e(number_format((float) $ticket['precio'], 2)) ?></strong>
            </article>
        <?php endforeach; ?>
    </div>
</section>

<section>
    <h2>Contacto</h2>
    <p>Email: info@digitalfuturecongreso.com</p>
</section>
