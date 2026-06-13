<?php $ticketTypes = isset($ticketTypes) && is_array($ticketTypes) ? $ticketTypes : []; ?>

<section class="page-head">
    <h1>Registro de Participantes</h1>
    <p>Completa el formulario para reservar tu cupo en el congreso.</p>
</section>

<form class="form-grid" method="post" action="<?= e(url('/registro')) ?>">
    <input type="hidden" name="_csrf" value="<?= e(Security::csrfToken()) ?>">

    <label>Primer nombre *
        <input type="text" name="primer_nombre" required value="<?= e(old('primer_nombre')) ?>">
    </label>
    <label>Segundo nombre
        <input type="text" name="segundo_nombre" value="<?= e(old('segundo_nombre')) ?>">
    </label>
    <label>Primer apellido *
        <input type="text" name="primer_apellido" required value="<?= e(old('primer_apellido')) ?>">
    </label>
    <label>Segundo apellido
        <input type="text" name="segundo_apellido" value="<?= e(old('segundo_apellido')) ?>">
    </label>
    <label>Correo electronico *
        <input type="email" name="correo" required value="<?= e(old('correo')) ?>">
    </label>
    <label>Cedula *
        <input type="text" name="cedula" required value="<?= e(old('cedula')) ?>">
    </label>
    <label>Telefono *
        <input type="text" name="telefono" required value="<?= e(old('telefono')) ?>">
    </label>
    <label>Institucion *
        <input type="text" name="institucion" required value="<?= e(old('institucion')) ?>">
    </label>
    <label>Ciudad *
        <input type="text" name="ciudad" required value="<?= e(old('ciudad')) ?>">
    </label>
    <label>Pais *
        <input type="text" name="pais" required value="<?= e(old('pais')) ?>">
    </label>

    <label>Tipo de entrada *
        <select name="tipo_entrada_id" required>
            <option value="">Selecciona</option>
            <?php foreach ($ticketTypes as $type): ?>
                <option value="<?= e((string) $type['id']) ?>" <?= old('tipo_entrada_id') === (string) $type['id'] ? 'selected' : '' ?>>
                    <?= e($type['nombre']) ?> - $<?= e(number_format((float) $type['precio'], 2)) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </label>

    <button type="submit" class="btn-primary">Registrar participante</button>
</form>
