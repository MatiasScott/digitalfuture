<?php $participant = isset($participant) && is_array($participant) ? $participant : null; ?>

<section class="page-head">
    <h1>Pagos y Comprobantes</h1>
    <p>Registra tu pago o inicia la pasarela PayPhone.</p>
</section>

<?php if ($participant !== null): ?>
    <article class="participant-summary">
        <h2>Participante #<?= e((string) $participant['id']) ?></h2>
        <p><?= e($participant['primer_nombre'] . ' ' . $participant['primer_apellido']) ?> - <?= e($participant['correo']) ?></p>
        <p>Tipo entrada: <?= e($participant['tipo_entrada']) ?> | Monto: $<?= e(number_format((float) $participant['precio'], 2)) ?></p>
    </article>
<?php else: ?>
    <p>Ingresa desde el enlace de registro para cargar un pago asociado.</p>
<?php endif; ?>

<?php if ($participant !== null): ?>
<section class="pay-grid">
    <form method="post" action="<?= e(url('/pagos')) ?>" enctype="multipart/form-data">
        <input type="hidden" name="_csrf" value="<?= e(Security::csrfToken()) ?>">
        <input type="hidden" name="participante_id" value="<?= e((string) $participant['id']) ?>">

        <h3>Subir comprobante</h3>
        <label>Metodo de pago
            <select name="metodo_pago" required>
                <option value="transferencia">Transferencia</option>
                <option value="deposito">Deposito</option>
            </select>
        </label>
        <label>Transaction ID
            <input type="text" name="transaction_id" placeholder="Opcional">
        </label>
        <label>Referencia
            <input type="text" name="referencia" placeholder="Opcional">
        </label>
        <label>Comprobante (PDF/JPG/JPEG/PNG)
            <input type="file" name="comprobante" accept=".pdf,.jpg,.jpeg,.png" required>
        </label>

        <button type="submit" class="btn-primary">Registrar pago manual</button>
    </form>

    <form id="payphone-form" method="post" action="<?= e(url('/pagos/payphone')) ?>">
        <input type="hidden" name="_csrf" value="<?= e(Security::csrfToken()) ?>">
        <input type="hidden" name="participante_id" value="<?= e((string) $participant['id']) ?>">

        <h3>Pagar con PayPhone</h3>
        <p>Se iniciara el pago con el monto de tu tipo de entrada.</p>
        <button type="submit" class="btn-secondary">Iniciar PayPhone</button>
        <pre id="payphone-response" class="payphone-response"></pre>
    </form>
</section>
<?php endif; ?>
