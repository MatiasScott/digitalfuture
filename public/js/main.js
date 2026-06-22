document.addEventListener('DOMContentLoaded', function () {

    // --- Lógica de Selección de Tickets ---
    const ticketButtons = document.querySelectorAll('.select-ticket');
    const typeInput = document.getElementById('ticket-type-input');
    const amountInput = document.getElementById('amount-input');
    const summaryType = document.getElementById('summary-type');
    const summaryAmount = document.getElementById('summary-amount');
    const registrationLayout = document.querySelector('.registration-layout');

    ticketButtons.forEach(button => {
        button.addEventListener('click', function () {
            const type = this.getAttribute('data-type');
            const price = this.getAttribute('data-price');

            typeInput.value = type;
            amountInput.value = price;

            summaryType.textContent = type.charAt(0).toUpperCase() + type.slice(1);
            summaryAmount.textContent = `$${price} USD`;

            document.querySelectorAll('.ticket-card').forEach(card => card.classList.remove('selected'));
            this.closest('.ticket-card').classList.add('selected');

            registrationLayout.style.display = 'flex';
            registrationLayout.scrollIntoView({ behavior: 'smooth' });

        });
    });

    if (registrationLayout) {
        registrationLayout.style.display = 'none';
    }

    // --- Scroll suave ---
    document.querySelectorAll('.main-nav a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();

            document.querySelector(this.getAttribute('href')).scrollIntoView({
                behavior: 'smooth'
            });

            // Cerrar menú en móvil al hacer click
            document.querySelector('.main-nav').classList.remove('active');
        });
    });

    // --- Tabs Agenda ---
    const tabButtons = document.querySelectorAll('.agenda-tabs .tab-btn');
    const agendaContents = document.querySelectorAll('.agenda-content');

    tabButtons.forEach(button => {
        button.addEventListener('click', function () {
            tabButtons.forEach(btn => btn.classList.remove('active'));
            agendaContents.forEach(content => content.classList.remove('active'));

            this.classList.add('active');
            document.getElementById(this.getAttribute('data-day')).classList.add('active');
        });
    });

    // --- Countdown ---
    function startCountdown() {
        const countdownDate = new Date("July 23, 2026 00:00:00").getTime();

        const interval = setInterval(function () {
            const now = new Date().getTime();
            const distance = countdownDate - now;

            const days = Math.floor(distance / (1000 * 60 * 60 * 24));
            const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((distance % (1000 * 60)) / 1000);

            const countdownElement = document.querySelector('.countdown');
            if (!countdownElement) return;

            const items = countdownElement.querySelectorAll('.countdown-item h2');
            const formatTime = value => value.toString().padStart(2, '0');

            if (distance < 0) {
                clearInterval(interval);
                countdownElement.innerHTML = "¡El congreso ha comenzado!";
            } else {
                items[0].textContent = days;
                items[1].textContent = formatTime(hours);
                items[2].textContent = formatTime(minutes);
                items[3].textContent = formatTime(seconds);
            }
        }, 1000);
    }

    startCountdown();

    // --- MENÚ HAMBURGUESA (AQUÍ ES DONDE DEBE IR) ---
    const menuToggle = document.querySelector('.menu-toggle');
    const mainNav = document.querySelector('.main-nav');

    if (menuToggle && mainNav) {
        menuToggle.addEventListener('click', function () {
            mainNav.classList.toggle('active');
        });
    }

});
