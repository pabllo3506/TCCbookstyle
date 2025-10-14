<?php
include '../includes/config.php';
$categoria_atual = 'masculino'; 
$titulo_pagina = 'Espaço Masculino (Barbearia)';
$servicos = $conn->query("SELECT id, nome, preco, duracao FROM servicos WHERE categoria = '$categoria_atual' ORDER BY preco");
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title><?php echo $titulo_pagina; ?> | Bookstyle</title>
    
    <style>
        /* Variáveis de Cores Globais */
        :root {
            --fundo-principal: #000000;
            --fundo-secundario: #111111;
            --cor-destaque: #8A2BE2; /* Roxo principal */
            --cor-titulo: #DDA0DD; /* Roxo claro */
            --cor-linha: #8A2BE2;
            --cor-texto-claro: #FFFFFF;
            --cor-texto-suave: #CCCCCC;
        }

        /* Base */
        body {
            font-family: 'Montserrat', Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: var(--fundo-principal); 
            color: var(--cor-texto-claro);
            min-height: 100vh;
        }

        a {
            color: var(--cor-destaque);
            text-decoration: none;
            transition: color 0.3s;
        }

        a:hover {
            color: var(--cor-titulo);
        }

        /* HEADER */
        header {
            width: 100%;
            background-color: var(--fundo-principal);
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 40px;
            box-sizing: border-box;
            border-bottom: 2px solid var(--cor-linha);
            position: fixed; 
            top: 0;
            z-index: 100;
        }

        header h1 {
            font-size: 24px;
            margin: 0;
            color: var(--cor-destaque);
        }

        nav a {
            color: var(--cor-texto-claro);
            text-decoration: none;
            margin-left: 20px;
            font-size: 14px;
            font-weight: 500;
        }

        /* Container Principal */
        .container {
            width: 90%;
            max-width: 1200px;
            margin: 100px auto 40px; 
            background-color: var(--fundo-secundario);
            padding: 30px 40px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.5);
        }

        h2 {
            color: var(--cor-titulo);
            border-bottom: 2px solid var(--cor-linha);
            padding-bottom: 10px;
            margin-bottom: 30px;
            text-align: center;
        }

        /* Services Grid (Layout dos Cartões) */
        .services-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 25px;
            padding: 20px 0;
        }

        /* Card Individual do Serviço */
        .service-card {
            background-color: var(--fundo-principal);
            padding: 25px;
            border-radius: 8px;
            border: 1px solid var(--fundo-secundario);
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.4);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            transition: transform 0.3s, border-color 0.3s;
        }

        .service-card:hover {
            transform: translateY(-5px);
            border-color: var(--cor-destaque);
        }

        .service-card h3 {
            color: var(--cor-texto-claro);
            font-size: 1.4em;
            margin-top: 0;
            border-bottom: 1px solid var(--fundo-secundario);
            padding-bottom: 10px;
            margin-bottom: 10px;
        }

        .details {
            margin-top: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .service-price {
            color: var(--cor-destaque);
            font-size: 1.6em;
            font-weight: bold;
        }

        /* Botão Agendar no Card */
        .btn-card-agendar {
            background-color: var(--cor-destaque);
            color: var(--cor-texto-claro);
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1em;
            font-weight: bold;
            text-align: center;
            transition: background-color 0.3s;
        }

        .btn-card-agendar:hover {
            background-color: var(--cor-titulo);
        }

        /* Botão principal no rodapé da página */
        .btn-primary {
            background-color: var(--cor-destaque);
            color: var(--cor-texto-claro);
            padding: 12px 25px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 17px;
            font-weight: bold;
            text-decoration: none;
            transition: background-color 0.3s;
            display: inline-block;
        }

        .btn-primary:hover {
            background-color: var(--cor-titulo);
        }
    </style>
</head>
<body>

    <header>
        <div class="logo">
            <h1 style="color: var(--cor-destaque);">BS</h1> 
        </div>
        <nav class="nav-links">
            <a href="feminino.php">Feminino</a>
            <a href="masculino.php">Masculino</a>
            <a href="kids.php">Kids</a>
            <a href="../index.php">Home</a>
        </nav>
    </header>

    <div class="container">
        <h2>Cortes e Cuidados Exclusivos</h2>
        
        <div class="services-grid">
            <?php while ($servico = $servicos->fetch_assoc()): ?>
            <div class="service-card">
                <div>
                    <h3><?php echo $servico['nome']; ?></h3>
                    <p style="color: var(--cor-texto-suave);">Duração: **<?php echo $servico['duracao']; ?> minutos**. Detalhes sobre o serviço aqui.</p>
                </div>
                <div class="details">
                    <div>
                        <span class="service-price">R$ <?php echo number_format($servico['preco'], 2, ',', '.'); ?></span>
                    </div>
                    <a href="agendar.php?servico_id=<?php echo $servico['id']; ?>" class="btn-card-agendar">
                        Agendar
                    </a>
                </div>
            </div>
            <?php endwhile; ?>
        </div>

        <div style="text-align: center; margin-top: 50px;">
            <a href="agendar.php" class="btn-primary" style="width: auto; padding: 15px 30px; background-color: var(--cor-titulo);">Ver todas as opções de agendamento</a>
        </div>
    </div>

</body>
</html>