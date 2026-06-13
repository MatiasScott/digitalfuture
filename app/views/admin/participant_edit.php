<?php $participant = isset($participant) && is_array($participant) ? $participant : []; ?>
<?php $ticketTypes = isset($ticketTypes) && is_array($ticketTypes) ? $ticketTypes : []; ?>

<header class="admin-header">
    <h1>Editar Participante #<?= e((string) $participant['id']) ?></h1>
</header>

<form class="form-grid" method="post" action="<?= e(url('/admin/participantes/' . $participant['id'] . '/editar')) ?>">
    <input type="hidden" name="_csrf" value="<?= e(Security::csrfToken()) ?>">

    <label>Primer nombre
        <input type="text" name="primer_nombre" required value="<?= e($participant['primer_nombre']) ?>">
    </label>
    <label>Segundo nombre
        <input type="text" name="segundo_nombre" value="<?= e($participant['segundo_nombre']) ?>">
    </label>
    <label>Primer apellido
        <input type="text" name="primer_apellido" required value="<?= e($participant['primer_apellido']) ?>">
    </label>
    <label>Segundo apellido
        <input type="text" name="segundo_apellido" value="<?= e($participant['segundo_apellido']) ?>">
    </label>
    <label>Correo
        <input type="email" name="correo" required value="<?= e($participant['correo']) ?>">
    </label>
    <label>Cedula
        <input type="text" name="cedula" required value="<?= e($participant['cedula']) ?>">
    </label>
    <label>Telefono
        <input type="text" name="telefono" required value="<?= e($participant['telefono']) ?>">
    </label>
    <label>Institucion
        <input type="text" name="institucion" required value="<?= e($participant['institucion']) ?>">
    </label>
    <label>Ciudad
        <input type="text" name="ciudad" required value="<?= e($participant['ciudad']) ?>">
    </label>
    <label>Pais
        <input type="text" name="pais" required value="<?= e($participant['pais']) ?>">
    </label>

    <label>Tipo de entrada
        <select name="tipo_entrada_id" required>
            <?php foreach ($ticketTypes as $ticket): ?>
                <option value="<?= e((string) $ticket['id']) ?>" <?= (int) $participant['tipo_entrada_id'] === (int) $ticket['id'] ? 'selected' : '' ?>>
                    <?= e($ticket['nombre']) ?> - $<?= e(number_format((float) $ticket['precio'], 2)) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </label>

    <button class="btn-primary" type="submit">Guardar cambios</button>
</form>
