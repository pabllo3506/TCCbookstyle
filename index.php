<?php
// A sessão DEVE ser iniciada em todas as páginas
session_start();

include 'includes/config.php';

// Verificando se o usuário está logado
$logado = isset($_SESSION['usuario_id']);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bookstyle Salão de Beleza - Início</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>
<style>
    /* Seu CSS aqui... */
    .carousel-container { 
        width: 90%; 
        max-width: 600px; 
        margin-left: auto; 
        margin-right: auto; 
        margin-bottom: 20px;
        /* ADICIONADO: Necessário para os botões de posição absoluta */
        position: relative; 
    }
    .carousel-viewport { overflow: hidden; border-radius: 15px; }
    .carousel-track { display: flex; transition: transform 0.5s ease-in-out; }
    .carousel-slide { flex: 0 0 100%; box-sizing: border-box; /* Removido padding para a imagem preencher */ }
    .carousel-slide img { width: 100%; height: auto; border-radius: 10px; display: block; }
    .carousel-slide .description { margin-top: 15px; font-size: 1.1em; color: #ffffffff; text-align: center; }
    
    .carousel-button { 
        position: absolute; 
        top: 50%; 
        transform: translateY(-50%); 
        background-color: rgba(34, 27, 27, 0.7); 
        color: white; /* ADICIONADO: Cor para o ícone da seta */
        border: none; 
        border-radius: 50%; 
        width: 40px; 
        height: 40px; 
        font-size: 24px; 
        cursor: pointer; 
        z-index: 10; 
        display: flex; 
        justify-content: center; 
        align-items: center; 
        box-shadow: 0 2px 5px rgba(0,0,0,0.2); 
        transition: background-color 0.3s, color 0.3s; 
    }
    .carousel-button:hover { 
        background-color: white; 
        color: #333; /* ADICIONADO: Cor do ícone no hover */
    }
    
    .prev-button { left: 10px; }
    .next-button { right: 10px; }

    /* Estilos para os pontos de navegação (dots) */
    .carousel-nav {
        text-align: center;
        padding-top: 10px;
    }
    .carousel-dot {
        border: none;
        border-radius: 50%;
        width: 12px;
        height: 12px;
        background-color: #c4c4c4;
        margin: 0 5px;
        cursor: pointer;
        transition: background-color 0.3s;
    }
    .carousel-dot.active {
        background-color: #45725d; /* Cor primária */
    }

    /* Outros estilos */
    .main-button { background-color: #8A2BE2; color: white; border: none; padding: 15px 30px; margin: 10px 0; border-radius: 8px; font-size: 1.2em; cursor: pointer; transition: background-color 0.3s ease; display: block; width: fit-content; margin-left: auto; margin-right: auto; text-align: center; }
    .main-button:hover { background-color: #9932CC; }
    .buttons-container { display: flex; flex-direction: column; align-items: center; width: 100%; }
    
    .welcome-message {
        color: #45725d; 
        font-weight: bold;
        margin-right: 15px; 
    }
</style>

    <header>
        <div class="logo">
            <img src="pages/img/bslogotipo.png" alt="" style="width: 70px; height:70px;">
        </div>
        <nav class="nav-links">
            <a href="pages/feminino.php">Feminino</a>
            <a href="pages/masculino.php">Masculino</a>
            <a href="pages/kids.php">Kids</a>

            <?php if ($logado): ?>
                <span class="welcome-message"> Olá, <?php echo htmlspecialchars($_SESSION['usuario_nome']); ?>!</span>
                <a href="pages/logout.php" class="link-sair-simples">
                    &rarr; Sair
                </a>
            <?php else: ?>
                <a href="login.php">Login</a>
            <?php endif; ?>
        </nav>
    </header>

    <div class="main-content" style="padding-top: 110px; max-width: 800px; margin: 0 auto; text-align: center;">
        <div class="brand-section" style="padding: 30px 0; border-bottom: 2px solid var(--cor-linha); margin-bottom: 20px;">
            <h2 style="font-family: 'Times New Roman', serif; font-size: 48px; margin: 0; color: var(--cor-texto-claro); letter-spacing: 5px; border-bottom: none; padding-bottom: 0;">BOOKSTYLE</h2>
            <p style="font-size: 16px; color: #070707ff; margin: 10px 0 0;">Onde o Luxo Encontra a Conveniência.</p>
        </div>

        <div class="welcome-section" style="padding: 5px 0;">
            <h3 style="font-family: 'Times New Roman', serif; font-size: 30px; color: var(--cor-titulo); margin-bottom: 40px;">Bem-Vindos Ao Seu Novo Estilo!</h3>
            
            <section class="about-section-simple">
                <div class="carousel-container">
                    <div class="carousel-viewport">
                        <div class="carousel-track">
                            <div class="carousel-slide"> <img src="pages/img/imagem.png.png" alt="Corte e Estilo"> </div>
                            <div class="carousel-slide"> <img src="pages/img/imageem.png" alt="Barbearia"> </div>
                            <div class="carousel-slide"> <img src="pages/img/imagwm.png" alt="Penteados"> </div>
                            <div class="carousel-slide"> <img src="pages/img/imagerm.png" alt="Ambiente do Salão"> </div>
                            <div class="carousel-slide"> <img src="pages/img/imgg.png" alt="Nova imagem 1"> </div>
                            <div class="carousel-slide"> <img src="pages/img/img.png" alt="Nova imagem 2"> </div>
                        </div>
                    </div>
                    <button class="carousel-button prev-button">&#10094;</button>
                    <button class="carousel-button next-button">&#10095;</button>
                    <div class="carousel-nav"></div>
                </div>
            </section> </div>

        <div class="container-agendamento">
            <a href="pages/agendar.php" class="btn-agende-agora" style="padding: 15px 40px; width: auto;">
                Agende Agora
            </a>
            <a href="pages/meus_agendamentos.php" class="link-meus-agendamentos">Meus Agendamentos</a>
        </div>

        <section class="secao-sobre">
            <div class="container">
                <h2 class="title-decorated">Sobre Nós</h2>
                <p>
                    A BOOKSTYLE nasceu da visão de que a experiência de beleza deve ser tão impecável quanto o resultado final. Somos mais do que um salão: somos um Spa Urbano sofisticado, criado para clientes que valorizam a excelência e não abrem mão do tempo. Nosso ambiente é um refúgio de elegância, onde cada detalhe foi pensado para proporcionar conforto e bem-estar, desde o momento em que você agenda até a finalização do seu serviço.
                </p>
            </div>
        </section>
    </div>

    <footer class="site-footer">
        <div class="footer-content">
            <div class="footer-logo">
                Bookstyle
            </div>
            <div class="footer-links">
                <a href="/bookstyle_salao/index.php">Início</a>
                <a href="/bookstyle_salao/pages/agendar.php">Agendar</a>
                <a href="/bookstyle_salao/pages/meus_agendamentos.php">Meus Agendamentos</a>
            </div>
            <div class="footer-social">
                <a href="#" title="Instagram">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path d="M224.1 141c-63.6 0-114.9 51.3-114.9 114.9s51.3 114.9 114.9 114.9S339 319.5 339 255.9 287.7 141 224.1 141zm0 189.6c-41.1 0-74.7-33.5-74.7-74.7s33.5-74.7 74.7-74.7 74.7 33.5 74.7 74.7-33.6 74.7-74.7 74.7zm146.4-194.3c0 14.9-12 26.8-26.8 26.8-14.9 0-26.8-12-26.8-26.8s12-26.8 26.8-26.8 26.8 12 26.8 26.8zm76.1 27.2c-1.7-35.9-9.9-67.7-36.2-93.9-26.2-26.2-58-34.4-93.9-36.2-37-2.1-147.9-2.1-184.9 0-35.8 1.7-67.6 9.9-93.9 36.1s-34.4 58-36.2 93.9c-2.1 37-2.1 147.9 0 184.9 1.7 35.9 9.9 67.7 36.2 93.9 26.3 26.2 58 34.4 93.9 36.2 37 2.1 147.9 2.1 184.9 0 35.9-1.7 67.7-9.9 93.9-36.2 26.2-26.2 34.4-58 36.2-93.9 2.1-37 2.1-147.8 0-184.8zM398.8 388c-7.8 19.6-22.9 34.7-42.6 42.6-29.5 11.7-99.5 9-132.1 9s-102.7 2.6-132.1-9c-19.6-7.8-34.7-22.9-42.6-42.6-11.7-29.5-9-99.5-9-132.1s-2.6-102.7 9-132.1c7.8-19.6 22.9-34.7 42.6-42.6 29.5-11.7 99.5-9 132.1 9s102.7-2.6 132.1 9c19.6 7.8 34.7 22.9 42.6 42.6 11.7 29.5 9 99.5 9 132.1s2.7 102.7-9 132.1z"/></svg>
                </a>
                <a href="#" title="Facebook">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M504 256C504 119 393 8 256 8S8 119 8 256c0 123.78 90.69 226.38 209.25 245V327.69h-63V256h63v-54.64c0-62.15 37-96.48 93.67-96.48 27.14 0 55.52 4.84 55.52 4.84v61h-31.28c-30.8 0-40.41 19.12-40.41 38.73V256h68.78l-11 71.69h-57.78V501C413.31 482.38 504 379.78 504 256z"/></svg>
                </a>
                <a href="#" title="WhatsApp">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512">
                        <path d="M380.9 97.1C339 55.1 283.2 32 223.9 32c-122.4 0-222 99.6-222 222 0 39.1 10.2 77.3 29.6 111L0 480l117.7-30.9c32.4 17.7 68.9 27 106.1 27h.1c122.3 0 224.1-99.6 224.1-222 0-59.3-25.2-115-67.1-157zm-157 .9c49.4 0 95.6 19.2 130.4 54.1 34.8 34.9 56.2 81.2 56.1 130.5 0 108.7-88.2 197-196.9 197h-.1c-32.9 0-65.7-8.4-94.3-24.6l-6.7-4-69.8 18.3L72 359.2l-4.4-7c-18.5-29.4-28.2-63.3-28.2-98.2 0-108.7 88.2-197 196.9-197zm108.2 131.2c-5.5-2.8-32.8-16.2-37.9-18-5.1-1.9-8.8-2.8-12.5 2.8-3.7 5.6-14.3 18-17.6 21.8-3.2 3.7-6.5 4.2-12 1.4-32.6-16.3-54-29.1-75.5-66-5.7-9.8 5.7-9.1 16.3-30.3 1.8-3.7.9-6.9-.5-9.7-1.4-2.8-12.5-30.1-17.1-41.2-4.5-10.8-9.1-9.3-12.5-9.5-3.2-.2-6.9-.2-10.6-.2-3.7 0-9.7 1.4-14.8 6.9-5.1 5.6-19.4 19-19.4 46.3 0 27.3 19.9 53.7 22.6 57.4 2.8 3.7 39.1 59.7 94.8 83.8 35.2 15.2 49 16.5 66.6 13.9 10.7-1.6 32.8-13.4 37.4-26.4 4.6-13 4.6-24.1 3.2-26.4-1.3-2.5-5-3.9-10.5-6.6z"/>
                    </svg>
                </a>
            </div>
            <div class="footer-copyright">
                &copy; <?php echo date("Y"); ?> Bookstyle. Todos os direitos reservados.
            </div>
        </div>
    </footer>

    <script src="script.js"></script>
</body>
</html>