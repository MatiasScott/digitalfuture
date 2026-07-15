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

            if (!typeInput || !amountInput) {
                return;
            }

            typeInput.value = type;
            amountInput.value = price;

            if (summaryType) {
                summaryType.textContent = type.charAt(0).toUpperCase() + type.slice(1);
            }
            if (summaryAmount) {
                summaryAmount.textContent = `$${price} USD`;
            }

            document.querySelectorAll('.ticket-card').forEach(card => card.classList.remove('selected'));
            this.closest('.ticket-card').classList.add('selected');

            // Actualizar previews del modal trigger
            const tipoPreview   = document.getElementById('modal-tipo-preview');
            const precioPreview = document.getElementById('modal-precio-preview');
            if (tipoPreview)   tipoPreview.textContent   = type;
            if (precioPreview) precioPreview.textContent = '$' + price + ' USD';

            // Mostrar área de botones del modal
            const triggerArea = document.getElementById('modal-trigger-area');
            if (triggerArea) {
                triggerArea.style.display = 'block';
                triggerArea.scrollIntoView({ behavior: 'smooth', block: 'center' });
            } else if (registrationLayout) {
                registrationLayout.style.display = 'flex';
                registrationLayout.scrollIntoView({ behavior: 'smooth' });
            }

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

    // --- PARTÍCULAS HERO ---
    (function initParticles() {
        const hero = document.querySelector('.hero-section');
        if (!hero) return;
        const canvas = document.createElement('canvas');
        canvas.id = 'particles-canvas';
        hero.prepend(canvas);
        const ctx = canvas.getContext('2d');
        let w, h, particles = [];
        const COUNT = 80;
        const COLORS = ['rgba(1,101,217,.7)', 'rgba(0,181,244,.6)', 'rgba(94,54,201,.5)'];

        function resize() {
            w = canvas.width  = hero.offsetWidth;
            h = canvas.height = hero.offsetHeight;
        }
        window.addEventListener('resize', resize);
        resize();

        function randomParticle() {
            return {
                x: Math.random() * w,
                y: Math.random() * h,
                r: Math.random() * 2 + .5,
                vx: (Math.random() - .5) * .4,
                vy: (Math.random() - .5) * .4,
                color: COLORS[Math.floor(Math.random() * COLORS.length)]
            };
        }
        for (let i = 0; i < COUNT; i++) particles.push(randomParticle());

        function draw() {
            ctx.clearRect(0, 0, w, h);
            particles.forEach(p => {
                ctx.beginPath();
                ctx.arc(p.x, p.y, p.r, 0, Math.PI * 2);
                ctx.fillStyle = p.color;
                ctx.fill();
            });
            for (let i = 0; i < particles.length; i++) {
                for (let j = i + 1; j < particles.length; j++) {
                    const dx = particles[i].x - particles[j].x;
                    const dy = particles[i].y - particles[j].y;
                    const dist = Math.sqrt(dx * dx + dy * dy);
                    if (dist < 100) {
                        ctx.beginPath();
                        ctx.moveTo(particles[i].x, particles[i].y);
                        ctx.lineTo(particles[j].x, particles[j].y);
                        ctx.strokeStyle = `rgba(0,181,244,${(.18 * (1 - dist / 100)).toFixed(3)})`;
                        ctx.lineWidth = .6;
                        ctx.stroke();
                    }
                }
            }
            particles.forEach(p => {
                p.x += p.vx; p.y += p.vy;
                if (p.x < 0 || p.x > w) p.vx *= -1;
                if (p.y < 0 || p.y > h) p.vy *= -1;
            });
            requestAnimationFrame(draw);
        }
        draw();
    })();

    // --- INTERSECTION OBSERVER (scroll reveal) ---
    (function initReveal() {
        const targets = document.querySelectorAll('.reveal, .reveal-left, .reveal-right');
        if (!targets.length) return;
        const io = new IntersectionObserver(entries => {
            entries.forEach(e => { if (e.isIntersecting) { e.target.classList.add('visible'); io.unobserve(e.target); } });
        }, { threshold: 0.12 });
        targets.forEach(t => io.observe(t));
    })();

    // --- MENÚ HAMBURGUESA (AQUÍ ES DONDE DEBE IR) ---
    const menuToggle = document.querySelector('.menu-toggle');
    const mainNav = document.querySelector('.main-nav');

    if (menuToggle && mainNav) {
        menuToggle.addEventListener('click', function () {
            mainNav.classList.toggle('active');
        });
    }

    // --- CARRUSEL DE SPEAKERS ---
    (function initSpeakersCarousel() {
        const carousel = document.querySelector('[data-carousel="speakers"]');
        if (!carousel) return;

        const wrapper = carousel.querySelector('.speaker-track-wrapper');
        const track = carousel.querySelector('.speaker-track');
        const slides = Array.from(carousel.querySelectorAll('.speaker-slide'));
        const prevBtn = carousel.querySelector('.speaker-control-prev');
        const nextBtn = carousel.querySelector('.speaker-control-next');
        const dotsContainer = document.querySelector('.speaker-dots');

        if (!wrapper || !track || !slides.length || !prevBtn || !nextBtn || !dotsContainer) {
            return;
        }

        let currentPage = 0;
        let slidesPerView = 2;
        let autoplayTimer = null;
        const AUTOPLAY_MS = 3500;

        function getSlidesPerView() {
            if (window.innerWidth <= 768) return 1;
            return 2;
        }

        function getTotalPages() {
            return Math.max(1, Math.ceil(slides.length / slidesPerView));
        }

        function renderDots(totalPages) {
            dotsContainer.innerHTML = '';
            for (let i = 0; i < totalPages; i++) {
                const dot = document.createElement('button');
                dot.className = 'speaker-dot';
                dot.type = 'button';
                dot.setAttribute('aria-label', `Ir al grupo ${i + 1}`);
                dot.addEventListener('click', function () {
                    currentPage = i;
                    updateCarousel();
                });
                dotsContainer.appendChild(dot);
            }
        }

        function getStartIndex() {
            return Math.min(currentPage * slidesPerView, slides.length - 1);
        }

        function updateCarousel() {
            slidesPerView = getSlidesPerView();
            const totalPages = getTotalPages();
            currentPage = Math.min(currentPage, totalPages - 1);

            const startIndex = getStartIndex();
            const offset = slides[startIndex].offsetLeft;

            track.style.transform = `translateX(-${offset}px)`;

            prevBtn.disabled = currentPage === 0;
            nextBtn.disabled = currentPage >= totalPages - 1;

            const dots = dotsContainer.querySelectorAll('.speaker-dot');
            if (dots.length !== totalPages) {
                renderDots(totalPages);
            }

            dotsContainer.querySelectorAll('.speaker-dot').forEach((dot, index) => {
                dot.classList.toggle('active', index === currentPage);
            });

            if (totalPages <= 1) {
                stopAutoplay();
            }
        }

        function nextPage() {
            const totalPages = getTotalPages();
            currentPage = currentPage < totalPages - 1 ? currentPage + 1 : 0;
            updateCarousel();
        }

        function startAutoplay() {
            if (autoplayTimer) return;
            if (getTotalPages() <= 1) return;
            autoplayTimer = setInterval(nextPage, AUTOPLAY_MS);
        }

        function stopAutoplay() {
            if (!autoplayTimer) return;
            clearInterval(autoplayTimer);
            autoplayTimer = null;
        }

        prevBtn.addEventListener('click', function () {
            const totalPages = getTotalPages();
            currentPage = currentPage > 0 ? currentPage - 1 : totalPages - 1;
            updateCarousel();
            stopAutoplay();
            startAutoplay();
        });

        nextBtn.addEventListener('click', function () {
            nextPage();
            stopAutoplay();
            startAutoplay();
        });

        window.addEventListener('resize', updateCarousel);
        wrapper.addEventListener('mouseenter', stopAutoplay);
        wrapper.addEventListener('mouseleave', startAutoplay);
        wrapper.addEventListener('focusin', stopAutoplay);
        wrapper.addEventListener('focusout', startAutoplay);
        document.addEventListener('visibilitychange', function () {
            if (document.hidden) {
                stopAutoplay();
            } else {
                startAutoplay();
            }
        });

        renderDots(getTotalPages());
        updateCarousel();
        startAutoplay();
    })();

});
