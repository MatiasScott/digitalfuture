<?php
// Asegúrate de que $data existe si el controlador le pasa datos
$data = $data ?? [];

// 1. Incluir el Head (Metadata, CSS)
require_once __DIR__ . '/partials/head.php';
?>

<body>
    <?php require_once __DIR__ . '/partials/header.php'; ?>

    <main>
        <?php
        // 3a. Sección Principal (Hero)
        require_once __DIR__ . '/partials/section_hero.php';

        // 3b. Acerca del Evento / Puntos Clave
        require_once __DIR__ . '/partials/section_about.php';
        
        require_once __DIR__ . '/partials/section_thematic_axis.php';

        // 3c. Ponentes (Datos vendrían de $data['speakers'] o similar)
        // Aquí es donde se hace un bucle para mostrar los ponentes que obtuvimos del Modelo
        require_once __DIR__ . '/partials/section_speakers.php';

        // 3d. Agenda / Horario
        require_once __DIR__ . '/partials/section_agenda.php';

        // 3e. Compra de Entradas / Registro
        require_once __DIR__ . '/partials/section_tickets.php';

        // 3f. Contacto / Ubicación
        require_once __DIR__ . '/partials/section_submissions.php';

        // 3g. Contacto / Ubicación
        require_once __DIR__ . '/partials/section_contact.php';
        ?>
    </main>

    <?php require_once __DIR__ . '/partials/footer.php'; ?>
</body>

</html>