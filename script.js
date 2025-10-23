document.addEventListener('DOMContentLoaded', () => {
    // --- INÍCIO DO CÓDIGO DO CARROSSEL ---
    // Este código controla APENAS o carrossel da página inicial

    const carouselContainer = document.querySelector('.carousel-container');
    if (!carouselContainer) {
        // Se não houver carrossel nesta página, não faz nada
        return;
    }

    const track = carouselContainer.querySelector('.carousel-track');
    const slides = Array.from(track.children);
    const nextButton = carouselContainer.querySelector('.next-button');
    const prevButton = carouselContainer.querySelector('.prev-button');
    const navDotsContainer = carouselContainer.querySelector('.carousel-nav');

    if (!track || slides.length === 0) return;

    let slideWidth = slides[0].getBoundingClientRect().width;
    let currentIndex = 0;
    let autoplayInterval = null;

    const moveToSlide = (targetIndex) => {
        // Lógica para loop (ir do último para o primeiro, etc)
        if (targetIndex >= slides.length) {
            targetIndex = 0;
        }
        if (targetIndex < 0) {
            targetIndex = slides.length - 1;
        }
        
        // Move o track
        track.style.transform = 'translateX(-' + slideWidth * targetIndex + 'px)';
        currentIndex = targetIndex;
        updateNavDots();
    };

    // Cria os pontos de navegação (bolinhas)
    if (navDotsContainer) {
        navDotsContainer.innerHTML = ''; // Limpa pontos antigos
        slides.forEach((_, index) => {
            const dot = document.createElement('button');
            dot.classList.add('carousel-dot');
            dot.setAttribute('aria-label', `Ir para o slide ${index + 1}`);
            dot.addEventListener('click', () => {
                moveToSlide(index);
                resetAutoplay();
            });
            navDotsContainer.appendChild(dot);
        });
    }
    
    const navDots = navDotsContainer ? Array.from(navDotsContainer.children) : [];

    // Atualiza qual bolinha está ativa
    const updateNavDots = () => {
        if (navDots.length > 0) {
            navDots.forEach(dot => dot.classList.remove('active'));
            navDots[currentIndex].classList.add('active');
        }
    };

    // Botão de Próximo
    if (nextButton) {
        nextButton.addEventListener('click', () => {
            moveToSlide(currentIndex + 1);
            resetAutoplay();
        });
    }

    // Botão de Anterior
    if (prevButton) {
        prevButton.addEventListener('click', () => {
            moveToSlide(currentIndex - 1);
            resetAutoplay();
        });
    }

    // --- Autoplay (passar sozinho) ---
    const startAutoplay = () => {
        stopAutoplay(); // Garante que não haja dois rodando
        autoplayInterval = setInterval(() => {
            moveToSlide(currentIndex + 1);
        }, 5000); // Passa a cada 5 segundos
    };

    const stopAutoplay = () => {
        clearInterval(autoplayInterval);
    };

    const resetAutoplay = () => {
        stopAutoplay();
        startAutoplay();
    };

    // Para o autoplay quando o mouse está em cima
    carouselContainer.addEventListener('mouseenter', stopAutoplay);
    carouselContainer.addEventListener('mouseleave', startAutoplay);
    
    // --- Ajuste de Responsividade ---
    // Recalcula o tamanho do slide se a janela mudar
    window.addEventListener('resize', () => {
        slideWidth = slides[0].getBoundingClientRect().width;
        track.style.transition = 'none'; // Desliga a animação para o reajuste
        moveToSlide(currentIndex);
        
        // Liga a animação de volta
        setTimeout(() => {
            track.style.transition = 'transform 0.5s ease-in-out';
        }, 50);
    });

    // Inicia o carrossel no primeiro slide
    moveToSlide(0);
    startAutoplay();

    // --- FIM DO CÓDIGO DO CARROSSEL ---
});