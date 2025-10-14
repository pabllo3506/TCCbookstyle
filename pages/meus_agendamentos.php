<?php
include '../includes/config.php';

// 1. VERIFICAÇÃO DE LOGIN
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$id_cliente = $_SESSION['user_id'];
$titulo_pagina = 'Meus Agendamentos';

// 2. BUSCA DE AGENDAMENTOS
// SQL para buscar os agendamentos do cliente, juntando com o nome do serviço e do profissional
$stmt = $conn->prepare("
    SELECT 
        a.data_hora, 
        a.status, 
        s.nome AS nome_servico, 
        s.duracao,
        p.nome AS nome_profissional
    FROM 
        agendamentos a
    JOIN 
        servicos s ON a.id_servico = s.id
    JOIN 
        profissionais p ON a.id_profissional = p.id
    WHERE 
        a.id_cliente = ? 
    ORDER BY 
        a.data_hora DESC
");
$stmt->bind_param("i", $id_cliente);
$stmt->execute();
$result = $stmt->get_result();
$agendamentos = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

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
            max-width: 1000px; 
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
        
        /* ------------------ Lista de Agendamentos ------------------ */
        .appointments-list {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .appointment-card {
            background-color: var(--fundo-principal);
            padding: 20px;
            border-radius: 8px;
            border-left: 5px solid var(--cor-destaque);
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .appointment-info {
            flex-grow: 1;
        }

        .appointment-info h3 {
            color: var(--cor-texto-claro);
            font-size: 1.3em;
            margin: 0 0 5px 0;
        }

        .appointment-info p {
            color: var(--cor-texto-suave);
            margin: 0 0 5px 0;
            font-size: 0.95em;
        }
        
        .appointment-info span {
            font-weight: bold;
            color: var(--cor-destaque);
        }

        .appointment-status {
            padding: 8px 15px;
            border-radius: 20px;
            font-weight: bold;
            font-size: 0.85em;
            text-transform: uppercase;
        }

        /* Cores de Status */
        .status-agendado {
            background-color: rgba(40, 167, 69, 0.2);
            color: #28a745; 
        }

        .status-concluido {
            background-color: rgba(0, 123, 255, 0.2);
            color: #007bff;
        }

        .status-cancelado {
            background-color: rgba(220, 53, 69, 0.2);
            color: #dc3545; 
        }

        /* Mensagem de Sem Agendamentos */
        .no-appointments {
            text-align: center;
            padding: 40px;
            border: 1px dashed var(--cor-linha);
            border-radius: 8px;
            color: var(--cor-texto-suave);
        }
        
    </style>
</head>
<body>

    <header>
        <div class="logo">
            <h1 style="color: var(--cor-destaque);">BS</h1> 
        </div>
        <nav class="nav-links">
            <a href="agendar.php">Agenda</a>
            <a href="feminino.php">Feminino</a>
            <a href="masculino.php">Masculino</a>
            <a href="kids.php">Kids</a>
            <a href="../index.php">Home</a>
        </nav>
    </header>

    <div class="container">
        <h2><?php echo $titulo_pagina; ?></h2>

        <?php if (!empty($agendamentos)): ?>
            <div class="appointments-list">
                <?php foreach ($agendamentos as $agendamento): 
                    // Formata data e hora
                    $data_formatada = date('d/m/Y', strtotime($agendamento['data_hora']));
                    $hora_formatada = date('H:i', strtotime($agendamento['data_hora']));
                    
                    // Normaliza o status para exibição (ex: 'agendado' -> 'Agendado')
                    $status_display = ucfirst($agendamento['status']); 
                    // Cria a classe CSS baseada no status (ex: 'status-agendado')
                    $status_class = 'status-' . strtolower($agendamento['status']); 
                ?>
                <div class="appointment-card">
                    <div class="appointment-info">
                        <h3><?php echo htmlspecialchars($agendamento['nome_servico']); ?></h3>
                        <p>Com: <span><?php echo htmlspecialchars($agendamento['nome_profissional']); ?></span></p>
                        <p>Dia: <span><?php echo $data_formatada; ?></span> às <span><?php echo $hora_formatada; ?></span> | Duração: <?php echo $agendamento['duracao']; ?> min.</p>
                    </div>
                    <div class="appointment-status <?php echo $status_class; ?>">
                        <?php echo $status_display; ?>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="no-appointments">
                <p>Você ainda não possui nenhum agendamento registrado.</p>
                <a href="agendar.php">Clique aqui para agendar seu primeiro serviço!</a>
            </div>
        <?php endif; ?>
    </div>

</body>
</html>