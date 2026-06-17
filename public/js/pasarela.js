document.getElementById('btn-payphone').addEventListener('click', function () {

    const tipo = document.getElementById('ticket-type-input').value;
    const monto = document.getElementById('amount-input').value;
    const nombre = document.getElementById('name').value;
    const apellido = document.getElementById('lastname').value;
    const email = document.getElementById('email').value;

    if (!tipo || !monto || !nombre || !apellido || !email) {
        alert('Completa el formulario y selecciona una entrada antes de pagar.');
        return;
    }

    const referencia = `${tipo} | ${nombre} ${apellido} | ${email}`;

    const url =
        `/home/payphone` +
        `?monto=${encodeURIComponent(monto)}` +
        `&referencia=${encodeURIComponent(referencia)}`;

    window.location.href = url;
});