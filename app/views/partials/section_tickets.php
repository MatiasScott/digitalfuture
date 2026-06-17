<section id="tickets" class="tickets-section">
    <div class="container">
        <h2>Elige tu Entrada</h2>

        <div class="pricing-grid">

            <div class="ticket-card student">
                <h3>Entrada Estudiante</h3>
                <p class="price">$25 USD</p>
                <ul>
                    <li>Acceso a todas las conferencias</li>
                    <li>Kit</li>
                    <li>Certificado</li>
                </ul>
                <button class="btn btn-primary select-ticket" data-type="Estudiante" data-price="25">Seleccionar</button>
            </div>

            <div class="ticket-card basic">
                <h3>Entrada Profesional</h3>
                <p class="price">$60 USD</p>
                <ul>
                    <li>Acceso a todas las conferencias</li>
                    <li>Kit</li>
                    <li>Credenciales</li>
                    <li>Certificado de Asistencia Digital</li>
                </ul>
                <button class="btn btn-primary select-ticket" data-type="Profesional" data-price="60">Seleccionar</button>
            </div>

            <div class="ticket-card premium">
                <h3>Entrada VIP</h3>
                <p class="price">$120 USD</p>
                <ul>
                    <li>Acceso preferencial</li>
                    <li>Acceso a Expoferia</li>
                    <li>Credenciales</li>
                    <li>Certificado de Asistencia Digital</li>
                </ul>
                <button class="btn btn-primary select-ticket" data-type="VIP" data-price="120">Seleccionar</button>
            </div>
        </div>

        <div class="payment-instructions-container">
            <h3>Instrucciones para Pago por Transferencia</h3>
            <p>Por favor, realiza el depósito o transferencia del monto total a la siguiente cuenta bancaria y luego adjunta el comprobante en el formulario de registro a continuación.</p>

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

            <p class="verification-note">La inscripción será validada una vez que el equipo de Superarse confirme la recepción del comprobante de pago.</p>
        </div>

        <div class="registration-layout">

            <!-- Video tutorial (70%) -->
            <div class="video-container">
                <video controls autoplay>
                    <source src="video/TutorialPagosAgrovet.mp4" type="video/mp4">
                    Tu navegador no soporta videos.
                </video>
            </div>

            <!-- Formulario (30%) -->
            <div class="registration-form-container">
                <h3>Finalizar Registro</h3>

                <form id="registration-form" enctype="multipart/form-data">

                    <!-- Datos obligatorios SIEMPRE -->
                    <label>Primer nombre:</label>
                    <input type="text" id="primer_nombre" name="primer_nombre" required>

                    <label>Segundo nombre (opcional):</label>
                    <input type="text" id="segundo_nombre" name="segundo_nombre">

                    <label>Primer apellido:</label>
                    <input type="text" id="primer_apellido" name="primer_apellido" required>

                    <label>Segundo apellido (opcional):</label>
                    <input type="text" id="segundo_apellido" name="segundo_apellido">

                    <label>Correo electronico:</label>
                    <input type="email" id="correo" name="correo" required>

                    <label>Cedula:</label>
                    <input type="text" id="cedula" name="cedula" required>

                    <label>Telefono:</label>
                    <input type="text" id="telefono" name="telefono" required>

                    <label>Institucion:</label>
                    <input type="text" id="institucion" name="institucion" required>

                    <label>Ciudad:</label>
                    <input type="text" id="ciudad" name="ciudad" required>

                    <label>Pais:</label>
                    <input type="text" id="pais" name="pais" required>

                    <input type="hidden" id="ticket-type-input" name="tipo_entrada">
                    <input type="hidden" id="amount-input" name="monto">

                    <div id="comprobante-section" style="display:none">
                        <label>Comprobante de pago:</label>
                        <input type="file" id="comprobante" name="comprobante" accept=".jpg,.jpeg,.png,.pdf">
                    </div>

                    <p class="summary">
                        Entrada: <span id="summary-type">Ninguna</span>
                    </p>
                    <p class="summary">
                        Monto: <strong id="summary-amount">$0 USD</strong>
                    </p>

                    <!-- BOTONES -->
                    <button type="submit"
                        class="btn btn-cta btn-large"
                        onclick="mostrarTransferencia(event)">
                        Registrar y Enviar Comprobante
                    </button>

                    <button type="button"
                        class="btn btn-success btn-large"
                        onclick="pagarPayphone()">
                        Pago Con Tarjeta
                    </button>

                </form>
            </div>
        </div>
    </div>
</section>

<script>
    const BASE_URL = "<?= BASE_URL ?>";
</script>

<script src="/js/formularioPago.js"></script>