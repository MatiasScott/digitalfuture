document.addEventListener("DOMContentLoaded", function () {
    var form = document.getElementById("payphone-form");
    var responseBox = document.getElementById("payphone-response");

    if (!form || !responseBox) {
        return;
    }

    form.addEventListener("submit", function (event) {
        event.preventDefault();
        responseBox.textContent = "Inicializando pago con PayPhone...";

        var formData = new FormData(form);

        fetch(form.action, {
            method: "POST",
            body: formData,
            headers: {
                "X-Requested-With": "XMLHttpRequest"
            }
        })
            .then(function (res) {
                return res.json();
            })
            .then(function (data) {
                responseBox.textContent = JSON.stringify(data, null, 2);
            })
            .catch(function () {
                responseBox.textContent = "No fue posible conectar con PayPhone.";
            });
    });
});
