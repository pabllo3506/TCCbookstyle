<?php
// O include já inicia a sessão
include '../conexao.php';

// Define a categoria e o título para esta página específica
$categoria_atual = 'Kids'; 
$titulo_pagina = 'Espaço Kids';

// Verifica se o usuário está logado
$logado = isset($_SESSION['usuario_id']);

// Prepara e executa a consulta ao banco de dados de forma segura
$stmt = $conn->prepare("SELECT id, nome, preco, duracao FROM servicos WHERE categoria = ? ORDER BY nome ASC");
$stmt->bind_param("s", $categoria_atual);
$stmt->execute();
$servicos = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($titulo_pagina); ?> | Bookstyle</title>
    <link rel="stylesheet" href="../style.css">
    
    <style>
        .carousel-section {
            width: 100%;
            max-width: 1100px;
            margin: 100px auto 20px;
            padding: 0 30px;
            box-sizing: border-box;
        }
        .carousel-container { position: relative; border-radius: 8px; overflow: hidden; box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1); }
        .carousel-track { display: flex; transition: transform 0.5s ease-in-out; }
        .carousel-slide { flex: 0 0 100%; box-sizing: border-box; }
        .carousel-slide img { width: 100%; height: auto; display: block; aspect-ratio: 16 / 7; object-fit: cover; }
        .carousel-button {
            position: absolute; top: 50%; transform: translateY(-50%); background-color: rgba(255, 255, 255, 0.7);
            border: none; border-radius: 50%; width: 40px; height: 40px; color: var(--cor-primaria);
            font-size: 20px; font-weight: bold; cursor: pointer; z-index: 10;
            display: flex; justify-content: center; align-items: center; transition: background-color 0.3s, transform 0.2s;
        }
        .carousel-button:hover { background-color: white; transform: translateY(-50%) scale(1.1); }
        .prev-button { left: 15px; }
        .next-button { right: 15px; }
        .carousel-nav { position: absolute; bottom: 15px; left: 50%; transform: translateX(-50%); display: flex; gap: 10px; z-index: 10; }
        .carousel-dot { width: 10px; height: 10px; border-radius: 50%; background-color: rgba(255, 255, 255, 0.6); border: 1px solid rgba(0, 0, 0, 0.2); cursor: pointer; transition: background-color 0.3s, transform 0.2s; }
        .carousel-dot.active { background-color: white; }
    </style>
</head>
<body>

    <header>
        <div class="logo">
            <a href="../index.php">
                <img src="/bookstyle_salao/pages/img/bslogotipo.png" alt="" style="width: 70px; height:70px;">
            </a>
        </div>
        <nav class="nav-links">
            <a href="../index.php" title="Início" class="home-icon-nav">
                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor" viewBox="0 0 16 16"><path d="M8.707 1.5a1 1 0 0 0-1.414 0L.646 8.146a.5.5 0 0 0 .708.708L2 8.207V13.5A1.5 1.5 0 0 0 3.5 15h9a1.5 1.5 0 0 0 1.5-1.5V8.207l.646.647a.5.5 0 0 0 .708-.708L8.707 1.5Z"/></svg>
            </a>
            <a href="feminino.php">Feminino</a>
            <a href="masculino.php">Masculino</a>
            <a href="kids.php">Kids</a>

            <?php if ($logado): ?>
                <a href="meus_agendamentos.php">Meus Agendamentos</a>
                <span class="welcome-message">Olá, <?php echo htmlspecialchars($_SESSION['usuario_nome']); ?>!</span>
                <a href="../logout.php" class="nav-link-sair">Sair</a>
            <?php else: ?>
                <a href="../login.php">Login</a>
            <?php endif; ?>
        </nav>
    </header>

    <div class="carousel-section">
        <div class="carousel-container">
            <div class="carousel-track">
                <div class="carousel-slide">
                    <img src="/bookstyle_salao/pages/img/imagemcrianca.png" alt="Imagem de corte de cabelo infantil">
                </div>
                <div class="carousel-slide">
                    <img src="/bookstyle_salao/pages/img/imgcorteinfantil.png" alt="Imagem de penteado infantil para festas">
                </div>
                <div class="carousel-slide">
                    <img src="/bookstyle_salao/pages/img/espaco.png" alt="Imagem do ambiente infantil do salão">
                </div>
            </div>
            <button class="carousel-button prev-button">&#10094;</button>
            <button class="carousel-button next-button">&#10095;</button>
            <div class="carousel-nav"></div>
        </div>
    </div>

    <main class="container services-page" style="margin-top: 0;">
        <h2 class="title-decorated"><?php echo htmlspecialchars($titulo_pagina); ?></h2>
        
        <div class="services-grid">
            
            <?php if ($servicos->num_rows > 0): ?>
                <?php while ($servico = $servicos->fetch_assoc()): ?>
                
                <div class="service-card">
                    <div class="info-servico">
                        <h3><?php echo htmlspecialchars($servico['nome']); ?></h3>
                        <p class="service-details">Duração: <strong><?php echo htmlspecialchars($servico['duracao']); ?> minutos</strong></p>
                    </div>
                    <div class="details-footer">
                        <span class="service-price">R$ <?php echo number_format($servico['preco'], 2, ',', '.'); ?></span>
                        <a href="agendar.php?servico_id=<?php echo $servico['id']; ?>" class="btn-card-agendar">
                            Agendar
                        </a>
                    </div>
                </div>

                <?php endwhile; ?>
            <?php else: ?>
                <p style="text-align: center; grid-column: 1 / -1;">Nenhum serviço encontrado para a categoria Kids.</p>
            <?php endif; ?>

        </div>
    </main>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const track = document.querySelector('.carousel-track');
            const slides = Array.from(track.children);
            const nextButton = document.querySelector('.next-button');
            const prevButton = document.querySelector('.prev-button');
            const nav = document.querySelector('.carousel-nav');

            if (!track || slides.length === 0) return;

            const slideWidth = slides[0].getBoundingClientRect().width;

            slides.forEach((slide, index) => {
                const dot = document.createElement('button');
                dot.classList.add('carousel-dot');
                if (index === 0) dot.classList.add('active');
                nav.appendChild(dot);
                slide.style.left = slideWidth * index + 'px';
            });

            const dots = Array.from(nav.children);
            let currentIndex = 0;

            const updateDots = (currentDot, targetDot) => {
                currentDot.classList.remove('active');
                targetDot.classList.add('active');
            };

            const moveToSlide = (targetIndex) => {
                const currentDot = dots[currentIndex];
                const targetDot = dots[targetIndex];
                const targetSlide = slides[targetIndex];
                track.style.transform = `translateX(-${targetSlide.style.left})`;
                updateDots(currentDot, targetDot);
                currentIndex = targetIndex;
            };

            const nextSlide = () => {
                const nextIndex = (currentIndex + 1) % slides.length;
                moveToSlide(nextIndex);
            };

            const prevSlide = () => {
                const prevIndex = (currentIndex - 1 + slides.length) % slides.length;
                moveToSlide(prevIndex);
            };

            nextButton.addEventListener('click', nextSlide);
            prevButton.addEventListener('click', prevSlide);

            nav.addEventListener('click', e => {
                const targetDot = e.target.closest('button');
                if (!targetDot) return;
                const targetIndex = dots.findIndex(dot => dot === targetDot);
                moveToSlide(targetIndex);
            });
            
            setInterval(nextSlide, 5000); // Auto-play
        });
       
    </script>

</body>
</html>
<?php
// Fecha o statement e a conexão com o banco para liberar recursos
$stmt->close();
$conn->close();
?>