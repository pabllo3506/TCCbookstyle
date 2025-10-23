<?php
// O include já inicia a sessão
include '../conexao.php';

// GARANTE QUE APENAS USUÁRIOS LOGADOS ACESSEM
if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../login.php?erro=login_necessario");
    exit();
}

$id_cliente = $_SESSION['usuario_id'];
$titulo_pagina = 'Meus Agendamentos';
$logado = true;

// BUSCA DE AGENDAMENTOS
$stmt = $conn->prepare("
    SELECT
        a.id, a.data_hora, a.status,
        s.nome AS nome_servico, s.duracao,
        p.nome AS nome_profissional
    FROM agendamentos a
    JOIN servicos s ON a.id_servico = s.id
    JOIN profissionais p ON a.id_profissional = p.id
    WHERE a.id_cliente = ?
    ORDER BY a.data_hora DESC
");
$stmt->bind_param("i", $id_cliente);
$stmt->execute();
$agendamentos = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $titulo_pagina; ?> | Bookstyle</title>
    <link rel="stylesheet" href="../style.css">
    
    <style>
        .appointments-list { display: flex; flex-direction: column; gap: 20px; }
        .appointment-card {
            background-color: var(--cor-fundo-secundaria);
            padding: 25px; border-radius: 8px;
            border-left: 5px solid; /* Cor definida pelo status */
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
            display: flex; justify-content: space-between; align-items: center;
            flex-wrap: wrap; gap: 15px;
            transition: transform 0.3s, box-shadow 0.3s;
        }
        .appointment-card:hover { transform: translateY(-5px); box-shadow: 0 8px 25px rgba(0, 0, 0, 0.12); }
        .appointment-info { flex-grow: 1; }
        .appointment-info h3 { color: var(--cor-titulo); font-size: 1.4em; margin: 0 0 8px 0; }
        .appointment-info p { color: #555; margin: 4px 0; font-size: 1em; }
        .appointment-info span { font-weight: bold; color: var(--cor-primaria); }

        .appointment-actions { display: flex; flex-direction: column; align-items: flex-end; gap: 10px; }
        .status-badge { padding: 8px 15px; border-radius: 20px; font-weight: bold; font-size: 0.9em; text-transform: uppercase; }

        /* CORES E BORDAS DOS STATUS */
        .status-agendado { background-color: rgba(0, 123, 255, 0.1); color: #007bff; }
        .appointment-card.agendado { border-left-color: #007bff; }
        .status-concluido { background-color: rgba(40, 167, 69, 0.1); color: #28a745; }
        .appointment-card.concluido { border-left-color: #28a745; }
        .status-cancelado { background-color: rgba(220, 53, 69, 0.1); color: #dc3545; }
        .appointment-card.cancelado { border-left-color: #dc3545; }

        /* BOTÃO DE CANCELAR */
        .btn-cancel {
            padding: 8px 15px; background-color: #dc3545; color: white;
            border: none; border-radius: 5px; cursor: pointer; text-decoration: none;
            font-size: 0.9em; font-weight: bold; transition: background-color 0.3s;
        }
        .btn-cancel:hover { background-color: #c82333; }

        .no-appointments { text-align: center; padding: 50px; border: 2px dashed #e0e0e0; border-radius: 8px; color: var(--cor-texto-claro); }
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
            <a href="../index.php">Início</a>
            <a href="agendar.php">Agendar</a>
            <a href="meus_agendamentos.php">Meus Agendamentos</a>

            <?php if ($logado): ?>
                <span class="welcome-message">Olá, <?php echo htmlspecialchars($_SESSION['usuario_nome']); ?>!</span>
                <a href="../logout.php">Sair</a>
            <?php else: ?>
                <a href="../login.php">Login</a>
            <?php endif; ?>
        </nav>
    </header>

    <div class="container">
        <h2><?php echo $titulo_pagina; ?></h2>
        
        <?php if (!empty($agendamentos)): ?>
            <div class="appointments-list">
                <?php foreach ($agendamentos as $ag):
                    $data_obj = new DateTime($ag['data_hora']);
                    $status_normalizado = strtolower($ag['status']);
                    $isFuture = $data_obj > new DateTime(); // Verifica se a data é no futuro
                ?>
                <div class="appointment-card <?php echo $status_normalizado; ?>">
                    <div class="appointment-info">
                        <h3><?php echo htmlspecialchars($ag['nome_servico']); ?></h3>
                        <p>Com: <span><?php echo htmlspecialchars($ag['nome_profissional']); ?></span></p>
                        <p>Dia: <span><?php echo $data_obj->format('d/m/Y'); ?></span> às <span><?php echo $data_obj->format('H:i'); ?></span></p>
                    </div>
                    
                    <div class="appointment-actions">
                        <span class="status-badge status-<?php echo $status_normalizado; ?>">
                            <?php echo htmlspecialchars(ucfirst($status_normalizado)); ?>
                        </span>
                        
                        <?php if ($status_normalizado === 'agendado' && $isFuture): ?>
                            <a href="cancelar_agendamento.php?id=<?php echo $ag['id']; ?>" class="btn-cancel" onclick="return confirm('Tem certeza que deseja cancelar este agendamento?');">
                                Cancelar
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="no-appointments">
                <p>Você ainda não possui nenhum agendamento registrado.</p>
                <a href="agendar.php" class="main-button">Agendar Meu Horário</a>
            </div>
        <?php endif; ?>
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
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path d="M224.1 141c-63.6 0-114.9 51.3-114.9 114.9s51.3 114.9 114.9 114.9S339 319.5 339 255.9 287.7 141 224.1 141zm0 189.6c-41.1 0-74.7-33.5-74.7-74.7s33.5-74.7 74.7-74.7 74.7 33.5 74.7 74.7-33.6 74.7-74.7 74.7zm146.4-194.3c0 14.9-12 26.8-26.8 26.8-14.9 0-26.8-12-26.8-26.8s12-26.8 26.8-26.8 26.8 12 26.8 26.8zm76.1 27.2c-1.7-35.9-9.9-67.7-36.2-93.9-26.2-26.2-58-34.4-93.9-36.2-37-2.1-147.9-2.1-184.9 0-35.8 1.7-67.6 9.9-93.9 36.1s-34.4 58-36.2 93.9c-2.1 37-2.1 147.9 0 184.9 1.7 35.9 9.9 67.7 36.2 93.9 26.3 26.2 58 34.4 93.9 36.2 37 2.1 147.9 2.1 184.9 0 35.9-1.7 67.7-9.9 93.9-36.2 26.2-26.2 34.4-58 36.2-93.9 2.1-37 2.1-147.8 0-184.8zM398.8 388c-7.8 19.6-22.9 34.7-42.6 42.6-29.5 11.7-99.5 9-132.1 9s-102.7 2.6-132.1-9c-19.6-7.8-34.7-22.9-42.6-42.6-11.7-29.5-9-99.5-9-132.1s-2.6-102.7 9-132.1c7.8-19.6 22.9-34.7 42.6-42.6 29.5-11.7 99.5-9 132.1-9s102.7-2.6 132.1 9c19.6 7.8 34.7 22.9 42.6 42.6 11.7 29.5 9 99.5 9 132.1s2.7 102.7-9 132.1z"/></svg>
            </a>
            <a href="#" title="Facebook">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M504 256C504 119 393 8 256 8S8 119 8 256c0 123.78 90.69 226.38 209.25 245V327.69h-63V256h63v-54.64c0-62.15 37-96.48 93.67-96.48 27.14 0 55.52 4.84 55.52 4.84v61h-31.28c-30.8 0-40.41 19.12-40.41 38.73V256h68.78l-11 71.69h-57.78V501C413.31 482.38 504 379.78 504 256z"/></svg>
            </a>
            <a href="#" title="WhatsApp">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path d="M380.9 97.1C339 55.1 283.2 32 223.9 32c-122.4 0-222 99.6-222 222 0 39.1 10.2 77.3 29.6 111L0 480l117.7-30.9c32.4 17.7 68.9 27 106.1 27h.1c122.3 0 224.1-99.6 224.1-222 0-59.3-25.2-115-67.1-157zm-157 .9c49.4 0 95.6 19.2 130.4 54.1 34.8 34.9 56.2 81.2 56.1 130.5 0 108.7-88.2 197-196.9 197h-.1c-32.9 0-65.7-8.4-94.3-24.6l-6.7-4-69.8 18.3L72 359.2l-4.4-7c-18.5-29.4-28.2-63.3-28.2-98.2 0-108.7 88.2-197 196.9-197zm108.2 131.2c-5.5-2.8-32.8-16.2-37.9-18-5.1-1.9-8.8-2.8-12.5 2.8-3.7 5.6-14.3 18-17.6 21.8-3.2 3.7-6.5 4.2-12 1.4-32.6-16.3-54-29.1-75.5-66-5.7-9.8 5.7-9.1 16.3-30.3 1.8-3.7.9-6.9-.5-9.7-1.4-2.8-12.5-30.1-17.1-41.2-4.5-10.8-9.1-9.3-12.5-9.5-3.2-.2-6.9-.2-10.6-.2-3.7 0-9.7 1.4-14.8 6.9-5.1 5.6-19.4 19-19.4 46.3 0 27.3 19.9 53.7 22.6 57.4 2.8 3.7 39.1 59.7 94.8 83.8 35.2 15.2 49 16.5 66.6 13.9 10.7-1.6 32.8-13.4 37.4-26.4 4.6-13 4.6-24.1 3.2-26.4-1.3-2.5-5-3.9-10.5-6.6z"/></svg>
            </a>
        </div>
        <div class="footer-copyright">
            &copy; <?php echo date("Y"); ?> Bookstyle. Todos os direitos reservados.
        </div>
    </div>
</footer>
</body>
</html>