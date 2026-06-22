<section id="tickets" class="tickets-section">
    <div class="container">
        <h2>Elige tu Entrada</h2>

        <div class="pricing-grid">

            <div class="ticket-card student">
                <h3>Estudiantes y Academicos</h3>
                <p class="price">$25 USD</p>
                <ul>
                    <li>Acceso a todas las conferencias</li>
                    <li>Kit</li>
                    <li>Certificado</li>
                </ul>
                <button class="btn btn-primary select-ticket" data-type="Academico" data-price="25">Seleccionar</button>
            </div>

            <div class="ticket-card basic">
                <h3>Publico Externo</h3>
                <p class="price">$50 USD</p>
                <ul>
                    <li>Acceso a todas las conferencias</li>
                    <li>Kit</li>
                    <li>Credenciales</li>
                    <li>Certificado de Asistencia Digital</li>
                </ul>
                <button class="btn btn-primary select-ticket" data-type="Publico Externo" data-price="50">Seleccionar</button>
            </div>
        </div>

        <div class="payment-instructions-container">
            <h3>Instrucciones para Pago por Transferencia</h3>
            <p>Realiza el deposito o transferencia del monto total a la siguiente cuenta bancaria y luego adjunta el comprobante en el formulario de registro.</p>

            <div class="bank-details-card">
                <ul>
                    <li><strong>Razón Social:</strong> INSTITUTO DE INVESTIGACIÓN E INNOVACIÓN SUPERARSE</li>
                    <li><strong>Tipo de Cuenta:</strong> Cuenta Corriente</li>
                    <li><strong>Banco:</strong> Banco Pichincha</li>
                    <li><strong>Número de Cuenta:</strong> 2100122603</li>
                    <li><strong>RUC:</strong> 1792660432001</li>
                    <li><strong>Correo de Contacto:</strong> israel.proano@nexodigitalmark.com</li>
                </ul>
            </div>

            <p class="verification-note">La inscripcion sera validada una vez que el equipo organizador confirme la recepcion del comprobante de pago.</p>
        </div>

        <!-- BOTONES PARA ABRIR MODAL (se muestran al seleccionar ticket) -->
        <div id="modal-trigger-area" style="display:none;margin-top:32px;text-align:center;">
            <p class="modal-ticket-info" style="color:var(--color-accent-cyan);font-size:1.1em;font-weight:700;margin-bottom:20px">
                Entrada seleccionada: <span id="modal-tipo-preview">-</span> &mdash;
                <span id="modal-precio-preview">$0 USD</span>
            </p>
            <div style="display:flex;gap:16px;justify-content:center;flex-wrap:wrap;">
                <button class="btn btn-cta btn-large" onclick="abrirModal('transferencia')">
                    <i class="fas fa-file-invoice"></i> Pagar por Transferencia
                </button>
                <button class="btn btn-success btn-large" onclick="abrirModal('tarjeta')">
                    <i class="fas fa-credit-card"></i> Pagar con Tarjeta
                </button>
            </div>
        </div>

    </div><!-- /container -->
</section>

<!-- ============================================================
     MODAL DE INSCRIPCION
     ============================================================ -->
<div id="registro-modal" class="rmodal-overlay" role="dialog" aria-modal="true" aria-labelledby="rmodal-title" style="display:none">
    <div class="rmodal-box">

        <!-- Cabecera -->
        <div class="rmodal-header">
            <div>
                <h2 id="rmodal-title">Formulario de Inscripción</h2>
                <p class="rmodal-subtitle">Completa tus datos para continuar con el pago</p>
            </div>
            <button class="rmodal-close" onclick="cerrarModal()" aria-label="Cerrar">&times;</button>
        </div>

        <!-- Resumen del ticket -->
        <div class="rmodal-ticket-badge">
            <span id="rmodal-tipo-badge">Académico</span>
            <strong id="rmodal-precio-badge">$25 USD</strong>
        </div>

        <!-- Paso 1: video tutorial -->
        <div id="rmodal-video-step" class="video-container" style="margin:0 0 24px 0">
            <video id="rmodal-tutorial-video" controls>
                <source src="<?= BASE_PATH ?>/video/TutorialPagosDigitalFuture.mp4" type="video/mp4">
                Tu navegador no soporta videos.
            </video>
            <div style="margin-top:16px;text-align:center;">
                <button type="button" class="btn btn-primary btn-large" onclick="mostrarFormularioPaso()">
                    Continuar al Formulario
                </button>
            </div>
        </div>

        <!-- Formulario -->
        <form id="registration-form" enctype="multipart/form-data" class="rmodal-form" style="display:none;">

            <div class="rmodal-grid">
                <div class="rmodal-field">
                    <label>Primer nombre *</label>
                    <input type="text" id="primer_nombre" name="primer_nombre" placeholder="Juan" required>
                </div>
                <div class="rmodal-field">
                    <label>Segundo nombre</label>
                    <input type="text" id="segundo_nombre" name="segundo_nombre" placeholder="Carlos">
                </div>
                <div class="rmodal-field">
                    <label>Primer apellido *</label>
                    <input type="text" id="primer_apellido" name="primer_apellido" placeholder="Pérez" required>
                </div>
                <div class="rmodal-field">
                    <label>Segundo apellido</label>
                    <input type="text" id="segundo_apellido" name="segundo_apellido" placeholder="López">
                </div>
                <div class="rmodal-field rmodal-full">
                    <label>Correo electrónico *</label>
                    <input type="email" id="correo" name="correo" placeholder="correo@ejemplo.com" required>
                </div>
                <div class="rmodal-field">
                    <label>Cédula / Pasaporte *</label>
                    <input type="text" id="cedula" name="cedula" placeholder="1234567890" required>
                </div>
                <div class="rmodal-field">
                    <label>Teléfono *</label>
                    <input type="text" id="telefono" name="telefono" placeholder="+593 99 000 0000" required>
                </div>
                <div class="rmodal-field rmodal-full">
                    <label>Institución *</label>
                    <input type="text" id="institucion" name="institucion" placeholder="Universidad / Empresa" required>
                </div>
                <div class="rmodal-field">
                    <label>Ciudad *</label>
                    <input type="text" id="ciudad" name="ciudad" placeholder="Quito" required>
                </div>
                <div class="rmodal-field">
                    <label>País *</label>
                    <input type="text" id="pais" name="pais" placeholder="Ecuador" required>
                </div>
            </div>

            <input type="hidden" id="ticket-type-input" name="tipo_entrada">
            <input type="hidden" id="amount-input" name="monto">

            <!-- Comprobante (solo visible en modo transferencia) -->
            <div id="comprobante-section" class="rmodal-comprobante" style="display:none">
                <label><i class="fas fa-paperclip"></i> Adjuntar comprobante de pago *</label>
                <input type="file" id="comprobante" name="comprobante" accept=".jpg,.jpeg,.png,.pdf">
                <p class="rmodal-hint">Formatos aceptados: JPG, PNG, PDF &mdash; Máx. 5MB</p>
            </div>

            <!-- Botones según modo -->
            <div id="rmodal-actions" class="rmodal-actions">
                <!-- se rellena por JS -->
            </div>

        </form>
    </div>
</div>

<script>
    window.BASE_URL = window.BASE_URL || "<?= BASE_URL ?>";

    // Texto visible arriba de los botones de modo
    const modalTipoPreview  = document.getElementById('modal-tipo-preview');
    const modalPrecioPreview = document.getElementById('modal-precio-preview');

    // Variables de estado del modal
    let _modoPago = 'transferencia';

    function abrirModal(modo) {
        _modoPago = modo;
        const tipo   = document.getElementById('ticket-type-input').value;
        const precio = document.getElementById('amount-input').value;

        document.getElementById('rmodal-tipo-badge').textContent   = tipo;
        document.getElementById('rmodal-precio-badge').textContent = '$' + precio + ' USD';
        document.getElementById('rmodal-title').textContent =
            modo === 'transferencia' ? 'Inscripción – Pago por Transferencia' : 'Inscripción – Pago con Tarjeta';

        const compSection = document.getElementById('comprobante-section');
        const compInput   = document.getElementById('comprobante');
        if (modo === 'transferencia') {
            compSection.style.display = 'block';
            compInput.required = true;
        } else {
            compSection.style.display = 'none';
            compInput.required = false;
        }

        const actions = document.getElementById('rmodal-actions');
        if (modo === 'transferencia') {
            actions.innerHTML = `
                <button type="submit" class="btn btn-cta btn-large rmodal-btn" onclick="mostrarTransferencia(event)">
                    <i class="fas fa-paper-plane"></i> Enviar Comprobante
                </button>`;
        } else {
            actions.innerHTML = `
                <button type="button" class="btn btn-success btn-large rmodal-btn" onclick="pagarPayphoneModal()">
                    <i class="fas fa-credit-card"></i> Continuar al pago con tarjeta
                </button>`;
        }

        // Flujo por pasos: primero video, luego formulario.
        const videoStep = document.getElementById('rmodal-video-step');
        const form = document.getElementById('registration-form');
        if (videoStep) {
            videoStep.style.display = 'block';
        }
        if (form) {
            form.style.display = 'none';
        }

        document.getElementById('registro-modal').style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }

    function mostrarFormularioPaso() {
        const videoStep = document.getElementById('rmodal-video-step');
        const form = document.getElementById('registration-form');

        if (videoStep) {
            videoStep.style.display = 'none';
        }
        if (form) {
            form.style.display = 'block';
        }
    }

    function cerrarModal() {
        const tutorialVideo = document.getElementById('rmodal-tutorial-video');
        if (tutorialVideo) {
            tutorialVideo.pause();
            tutorialVideo.currentTime = 0;
        }

        const videoStep = document.getElementById('rmodal-video-step');
        const form = document.getElementById('registration-form');
        if (videoStep) {
            videoStep.style.display = 'block';
        }
        if (form) {
            form.style.display = 'none';
        }

        document.getElementById('registro-modal').style.display = 'none';
        document.body.style.overflow = '';
    }

    // Cerrar al hacer click fuera del box
    document.getElementById('registro-modal').addEventListener('click', function(e) {
        if (e.target === this) cerrarModal();
    });

    // Wrapper para Payphone desde modal
    function pagarPayphoneModal() {
        pagarPayphone();
    }
</script>

<script src="<?= BASE_PATH ?>/js/formularioPago.js"></script>