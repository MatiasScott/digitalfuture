document.addEventListener("DOMContentLoaded", function () {
    var hero = document.querySelector(".hero");
    if (!hero) {
        return;
    }

    hero.animate(
        [
            { opacity: 0, transform: "translateY(10px)" },
            { opacity: 1, transform: "translateY(0)" }
        ],
        { duration: 550, easing: "ease-out", fill: "forwards" }
    );
});
