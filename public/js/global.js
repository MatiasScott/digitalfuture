document.addEventListener("DOMContentLoaded", function () {
	var flashes = document.querySelectorAll(".flash");
	if (!flashes.length) {
		return;
	}

	setTimeout(function () {
		flashes.forEach(function (item) {
			item.style.opacity = "0";
			item.style.transition = "opacity 0.4s ease";
		});
	}, 5000);
});
