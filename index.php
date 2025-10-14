<?php
include 'includes/config.php';
$logado = isset($_SESSION['user_id']);
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
       .carousel-container {
    /* É OBRIGATÓRIO que o elemento tenha uma largura definida */
    width: 90%;
    max-width: 600px; /* Use a largura que fizer sentido para o seu design */

    /* A mágica da centralização horizontal acontece aqui */
    margin-left: auto;
    margin-right: auto;
    margin-bottom: 40px;

    /* Forma abreviada (mais comum):
       A linha abaixo faz a mesma coisa que as duas acima.
       Define 0 para margem superior/inferior e 'auto' para esquerda/direita. */
    /* margin: 0 auto; */
}

        .carousel-viewport {
            overflow: hidden;
            border-radius: 15px;
        }

        .carousel-track {
            display: flex;
            /* A transição é o que cria o efeito de "deslizar" */
            transition: transform 0.5s ease-in-out;
        }

        .carousel-slide {
            /* Garante que cada slide ocupe 100% da largura do viewport */
            flex: 0 0 100%;
            box-sizing: border-box;
            padding: 10px;
            text-align: center;
        }

        .carousel-slide img {
            width: 100%;
            height: auto;
            border-radius: 10px;
            display: block;
        }

       .carousel-slide .description {
    margin-top: 15px;
    font-size: 1.1em;
    color: #ffffffff; /* Cor da descrição para contrastar */
    text-align: center; /* Centraliza o texto da descrição */
}

        /* Estilo dos botões de navegação */
        .carousel-button {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            background-color: rgba(34, 27, 27, 0.7);
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
            transition: background-color 0.3s;
        }

        .main-button {
    background-color: #8A2BE2; /* Um roxo mais forte */
    color: white;
    border: none;
    padding: 15px 30px;
    margin: 10px 0; /* Margem para separar os botões */
    border-radius: 8px;
    font-size: 1.2em;
    cursor: pointer;
    transition: background-color 0.3s ease;
    display: block; /* Para que cada botão ocupe sua própria linha e margin auto funcione */
    width: fit-content; /* Largura se ajusta ao conteúdo */
    margin-left: auto; /* Centraliza o botão */
    margin-right: auto; /* Centraliza o botão */
    text-align: center; /* Garante que o texto dentro do botão esteja centralizado */
}

        .main-button:hover {
    background-color: #9932CC; /* Um roxo ligeiramente diferente ao passar o mouse */
}
        
        .carousel-button:hover {
            background-color: white;
        }

        .buttons-container { /* Adicione esta div em volta dos seus botões no HTML */
    display: flex;
    flex-direction: column; /* Para os botões ficarem um embaixo do outro */
    align-items: center; /* Centraliza os botões horizontalmente */
    width: 100%; /* Ocupa toda a largura disponível */
}

        .prev-button {
            left: 10px;
        }

        .next-button {
            right: 10px;
        }

</style>
    <header>
        <div class="logo">
            <img src="/bookstyle_salao/pages/img/bslogotipo.png" alt="" style="width: 100px; height:100px;"> 
        </div>
        <nav class="nav-links">
            <a href="pages/feminino.php">Feminino</a>
            <a href="pages/masculino.php">Masculino</a>
            <a href="pages/kids.php">Kids</a>
            <?php if ($logado): ?>
                <a href="logout.php">Sair</a>
            <?php else: ?>
                <a href="login.php">Login</a>
                <span class="user-icon">&#128100;</span>
            <?php endif; ?>
        </nav>
    </header>

    <div class="main-content" style="padding-top: 100px; max-width: 800px; margin: 0 auto; text-align: center;">
        <div class="brand-section" style="padding: 30px 0; border-bottom: 2px solid var(--cor-linha); margin-bottom: 20px;">
            <h2 style="font-family: 'Times New Roman', serif; font-size: 48px; margin: 0; color: var(--cor-texto-claro); letter-spacing: 5px; border-bottom: none; padding-bottom: 0;">BOOKSTYLE</h2>
            <p style="font-size: 16px; color: #070707ff; margin: 10px 0 0;">Onde o Luxo Encontra a Conveniência.</p>
        </div>

        <div class="welcome-section" style="padding: 5px 0;">
            <h3 style="font-family: 'Times New Roman', serif; font-size: 30px; color: var(--cor-titulo); margin-bottom: 40px;">Bem-Vindos Ao Seu Novo Estilo!</h3>

             <div class="carousel-container">
        <div class="carousel-viewport">
            <div class="carousel-track">
                <div class="carousel-slide">
                    <img src="https://picsum.photos/600/350?random=1" alt="Paisagem 1">
                    <div class="description">Descrição da Paisagem 1</div>
                </div>
                <div class="carousel-slide">
                    <img src="https://picsum.photos/600/350?random=2" alt="Paisagem 2">
                    <div class="description">Descrição da Paisagem 2</div>
                </div>
                <div class="carousel-slide">
                    <img src="https://picsum.photos/600/350?random=3" alt="Paisagem 3">
                    <div class="description">Descrição da Paisagem 3</div>
                </div>
                <div class="carousel-slide">
                    <img src="https://picsum.photos/600/350?random=4" alt="Paisagem 4">
                    <div class="description">Descrição da Paisagem 4</div>
                </div>
            </div>
        </div>

        <button class="carousel-button prev-button">&#10094;</button>
        <button class="carousel-button next-button">&#10095;</button>
    </div>

    
           
            
            <a href="pages/agendar.php" class="btn-primary" style="padding: 15px 40px; width: auto;">
                Agende Agora
            </a>
            
            <a href="#" style="display: block; margin-top: 20px; color: #ccc; font-size: 14px;">
                
            </a>
            <a href="pages/meus_agendamentos.php">Meus Agendamentos</a>
        </div>
    </div>

    <script>
        // JavaScript (A Lógica)
        document.addEventListener('DOMContentLoaded', () => {
            const track = document.querySelector('.carousel-track');
            const slides = Array.from(track.children);
            const nextButton = document.querySelector('.next-button');
            const prevButton = document.querySelector('.prev-button');
            const slideWidth = slides[0].getBoundingClientRect().width;

            // 1. Clonar o primeiro e o último slide
            const firstClone = slides[0].cloneNode(true);
            const lastClone = slides[slides.length - 1].cloneNode(true);

            firstClone.id = 'first-clone';
            lastClone.id = 'last-clone';

            track.append(firstClone);
            track.prepend(lastClone);

            const allSlides = Array.from(track.children);
            
            // 2. Posição inicial
            // Começamos no primeiro slide REAL, que agora é o segundo item (índice 1)
            let currentIndex = 1;
            track.style.transform = `translateX(-${slideWidth * currentIndex}px)`;

            // Função para mover os slides
            const moveToSlide = (index) => {
                track.style.transition = 'transform 0.5s ease-in-out';
                currentIndex = index;
                track.style.transform = `translateX(-${slideWidth * currentIndex}px)`;
            };

            // 3. Lógica para os botões
            nextButton.addEventListener('click', () => {
                // Se estamos no último slide REAL, vamos para o clone do primeiro
                if (currentIndex >= allSlides.length - 2) return; // Evita cliques múltiplos
                moveToSlide(currentIndex + 1);
            });

            prevButton.addEventListener('click', () => {
                // Se estamos no primeiro slide REAL, vamos para o clone do último
                if (currentIndex <= 1) return; // Evita cliques múltiplos
                moveToSlide(currentIndex - 1);
            });

            // 4. A "mágica" do loop infinito
            track.addEventListener('transitionend', () => {
                // Se o slide atual é o clone do primeiro (no final da fila)
                if (allSlides[currentIndex].id === 'first-clone') {
                    // "Pula" para o primeiro slide real sem animação
                    track.style.transition = 'none';
                    currentIndex = 1;
                    track.style.transform = `translateX(-${slideWidth * currentIndex}px)`;
                }
                
                // Se o slide atual é o clone do último (no início da fila)
                if (allSlides[currentIndex].id === 'last-clone') {
                    // "Pula" para o último slide real sem animação
                    track.style.transition = 'none';
                    currentIndex = slides.length; // O índice do último slide real
                    track.style.transform = `translateX(-${slideWidth * currentIndex}px)`;
                }
            });
        });
    </script>

</body>
</html>