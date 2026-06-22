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

// 🧾 TRANSFERENCIA
function mostrarTransferencia(event) {
    if (!datosBasicosValidos()) {
        if (event) {
            event.preventDefault();
        }
        alert('Completa todos los datos obligatorios del formulario.');
        return;
    }

    const seccionComprobante = document.getElementById('comprobante-section');
    const form = document.getElementById('registration-form');

    form.action = BASE_URL + "/home/procesarPago";
    form.method = "POST";

    // Si la secci�n no es visible, la mostramos y detenemos el env�o
    if (seccionComprobante.style.display === 'none' || seccionComprobante.style.display === '') {
        if (event) {
            event.preventDefault();
        }

        seccionComprobante.style.display = 'block';
        document.getElementById('comprobante').required = true;

        alert('Ahora selecciona el comprobante y vuelve a presionar el bot�n para finalizar.');

        return;
    }

    // Si ya es visible, el submit natural del boton enviara el formulario.
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

    const ticketTypeInput = document.getElementById('ticket-type-input');
    const amountInput = document.getElementById('amount-input');

    document.querySelectorAll('.select-ticket').forEach(button => {
        button.addEventListener('click', () => {

            const tipo = button.dataset.type;
            const precio = button.dataset.price;

            // Setear valores
            ticketTypeInput.value = tipo;
            amountInput.value = precio;

            summaryType.textContent = tipo;
            summaryAmount.textContent = `$${precio} USD`;

            // 🔥 MOSTRAR FORMULARIO
            layout.style.display = 'flex';

            // Scroll suave al formulario
            layout.scrollIntoView({ behavior: 'smooth' });
        });
    });

});
