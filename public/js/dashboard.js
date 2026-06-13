document.addEventListener("DOMContentLoaded", function () {
    var tables = document.querySelectorAll(".table-wrap table");
    tables.forEach(function (table) {
        table.classList.add("js-ready");
    });
});
