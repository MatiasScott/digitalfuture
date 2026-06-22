const primerNombreInput = document.getElementById('primer_nombre');
const segundoNombreInput = document.getElementById('segundo_nombre');
const primerApellidoInput = document.getElementById('primer_apellido');
const segundoApellidoInput = document.getElementById('segundo_apellido');
const correoInput = document.getElementById('correo');
const cedulaInput = document.getElementById('cedula');
const telefonoInput = document.getElementById('telefono');
const institucionInput = document.getElementById('institucion');
const ciudadInput = document.getElementById('ciudad');
const paisInput = document.getElementById('pais');
const amountInput = document.getElementById('amount-input');
const ticketTypeInput = document.getElementById('ticket-type-input');

function datosBasicosValidos() {
    return (
        primerNombreInput.value.trim() !== '' &&
        primerApellidoInput.value.trim() !== '' &&
        correoInput.value.trim() !== '' &&
        cedulaInput.value.trim() !== '' &&
        telefonoInput.value.trim() !== '' &&
        institucionInput.value.trim() !== '' &&
        ciudadInput.value.trim() !== '' &&
        paisInput.value.trim() !== '' &&
        ticketTypeInput.value.trim() !== ''
    );
}

// 🧾 TRANSFERENCIA  — el modal ya muestra el comprobante; aquí solo configuramos el form y lo enviamos
function mostrarTransferencia(event) {
    if (!datosBasicosValidos()) {
        if (event) event.preventDefault();
        alert('Completa todos los datos obligatorios del formulario.');
        return;
    }

    const form = document.getElementById('registration-form');
    form.action = BASE_URL + "/home/procesarPago";
    form.method = "POST";

    const comprobanteInput = document.getElementById('comprobante');
    // Si el archivo aún no está seleccionado, pedirlo y detener
    if (!comprobanteInput.files || comprobanteInput.files.length === 0) {
        if (event) event.preventDefault();
        alert('Por favor adjunta el comprobante de pago antes de enviar.');
        return;
    }
    // Todo ok → el submit natural enviará el formulario
}


// 💳 PAYPHONE
function pagarPayphone() {
    if (!datosBasicosValidos()) {
        alert('Completa los datos y selecciona una entrada.');
        return;
    }

    document.getElementById('comprobante-section').style.display = 'none';
    document.getElementById('comprobante').required = false;

    const referencia =
        ticketTypeInput.value + ' | ' +
        primerNombreInput.value + ' ' + primerApellidoInput.value +
        ' | ' + correoInput.value;

    const url =
        BASE_URL + "/home/payphone" + // <--- Cambia PATH por URL
        "?primer_nombre=" + encodeURIComponent(primerNombreInput.value) +
        "&segundo_nombre=" + encodeURIComponent(segundoNombreInput.value) +
        "&primer_apellido=" + encodeURIComponent(primerApellidoInput.value) +
        "&segundo_apellido=" + encodeURIComponent(segundoApellidoInput.value) +
        "&correo=" + encodeURIComponent(correoInput.value) +
        "&cedula=" + encodeURIComponent(cedulaInput.value) +
        "&telefono=" + encodeURIComponent(telefonoInput.value) +
        "&institucion=" + encodeURIComponent(institucionInput.value) +
        "&ciudad=" + encodeURIComponent(ciudadInput.value) +
        "&pais=" + encodeURIComponent(paisInput.value) +
        "&tipo_entrada=" + encodeURIComponent(ticketTypeInput.value) +
        "&referencia=" + encodeURIComponent(referencia);

    window.location.href = url;
}


function ticketType() {
    return document.getElementById('ticket-type-input').value;
}

document.addEventListener('DOMContentLoaded', () => {

    const layout = document.querySelector('.registration-layout');
    const summaryType = document.getElementById('summary-type');
    const summaryAmount = document.getElementById('summary-amount');
    const triggerArea = document.getElementById('modal-trigger-area');
    const modalTipoPreview = document.getElementById('modal-tipo-preview');
    const modalPrecioPreview = document.getElementById('modal-precio-preview');

    const ticketTypeInput = document.getElementById('ticket-type-input');
    const amountInput = document.getElementById('amount-input');

    if (!ticketTypeInput || !amountInput) {
        return;
    }

    document.querySelectorAll('.select-ticket').forEach(button => {
        button.addEventListener('click', () => {

            const tipo = button.dataset.type;
            const precio = button.dataset.price;

            // Setear valores
            ticketTypeInput.value = tipo;
            amountInput.value = precio;

            if (summaryType) {
                summaryType.textContent = tipo;
            }
            if (summaryAmount) {
                summaryAmount.textContent = `$${precio} USD`;
            }

            if (modalTipoPreview) {
                modalTipoPreview.textContent = tipo;
            }
            if (modalPrecioPreview) {
                modalPrecioPreview.textContent = `$${precio} USD`;
            }

            // Mostrar flujo actual del modal; fallback al layout anterior si existe.
            if (triggerArea) {
                triggerArea.style.display = 'block';
                triggerArea.scrollIntoView({ behavior: 'smooth', block: 'center' });
            } else if (layout) {
                layout.style.display = 'flex';
                layout.scrollIntoView({ behavior: 'smooth' });
            }
        });
    });

    if (triggerArea) {
        triggerArea.style.display = 'none';
    }
    if (layout) {
        layout.style.display = 'none';
    }

});
