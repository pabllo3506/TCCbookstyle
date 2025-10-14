<?php
include '../includes/config.php';

// Redireciona se o usuário não estiver logado
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$mensagem_status = "";
$servico_selecionado = isset($_GET['servico_id']) ? (int)$_GET['servico_id'] : null;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_cliente = $_SESSION['user_id'];
    $id_servico = sanitize($conn, $_POST['servico']);
    $id_profissional = sanitize($conn, $_POST['profissional']);
    $data_agenda = sanitize($conn, $_POST['data_agenda']);
    $hora_agenda = sanitize($conn, $_POST['hora_agenda']);

    $data_hora_agendamento = $data_agenda . " " . $hora_agenda . ":00";
    
    // Verifica se já existe agendamento para este profissional e horário
    $stmt_check = $conn->prepare("SELECT id FROM agendamentos WHERE id_profissional = ? AND data_hora = ? AND status != 'cancelado'");
    $stmt_check->bind_param("is", $id_profissional, $data_hora_agendamento);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();

    if ($result_check->num_rows > 0) {
        $mensagem_status = "O horário selecionado já está ocupado. Escolha outro.";
    } else {
        // Insere o novo agendamento
        $stmt_insert = $conn->prepare("INSERT INTO agendamentos (id_cliente, id_profissional, id_servico, data_hora) VALUES (?, ?, ?, ?)");
        $stmt_insert->bind_param("iiis", $id_cliente, $id_profissional, $id_servico, $data_hora_agendamento);
        
        if ($stmt_insert->execute()) {
            $mensagem_status = "Agendamento realizado com sucesso para o dia " . date('d/m/Y H:i', strtotime($data_hora_agendamento)) . "!";
        } else {
            $mensagem_status = "Erro ao agendar.";
        }
        $stmt_insert->close();
    }
    $stmt_check->close();
}

$servicos = $conn->query("SELECT id, nome, categoria FROM servicos ORDER BY categoria, nome");
$profissionais = $conn->query("SELECT id, nome, funcao FROM profissionais ORDER BY nome");
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Marcar Horário | Bookstyle Salão</title>
    <style>
        /* Variáveis de Cores Globais */
        :root {
            --fundo-principal: #000000;
            --fundo-secundario: #111111;
            --cor-destaque: #8A2BE2;
            --cor-titulo: #DDA0DD;
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
            max-width: 800px;
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

        /* Formulário e Inputs */
        .input-group { 
            margin-bottom: 20px; 
            text-align: left; 
        }

        .input-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: var(--cor-texto-suave);
        }

        input[type="date"], 
        input[type="time"], 
        select {
            width: 100%; 
            box-sizing: border-box;
            padding: 12px;
            margin-top: 5px;
            border: 1px solid var(--cor-destaque);
            background-color: var(--fundo-principal);
            color: var(--cor-texto-claro);
            border-radius: 5px;
            font-size: 16px;
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
        }

        input:focus, select:focus {
            outline: 2px solid var(--cor-titulo);
            border-color: var(--cor-titulo);
        }
        
        /* CORREÇÃO VISUAL APLICADA AQUI */
        select option {
            background: var(--fundo-secundario);
            color: var(--cor-texto-claro);
            padding: 8px; /* Melhoria de visualização */
        }

        /* Botões */
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
            width: 100%; 
            display: block;
        }

        .btn-primary:hover {
            background-color: var(--cor-titulo);
        }

        /* Mensagens de Status */
        .status-message { 
            padding: 15px; 
            margin-bottom: 25px; 
            border-radius: 5px; 
            font-weight: bold;
            text-align: center;
        }
        .status-success { 
            background-color: rgba(40, 167, 69, 0.2);
            color: #28a745; 
            border: 1px solid #28a745; 
        }
        .status-error { 
            background-color: rgba(220, 53, 69, 0.2);
            color: #dc3545; 
            border: 1px solid #dc3545; 
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
        <h2>Seu Agendamento</h2>

        <?php if ($mensagem_status): ?>
            <div class="status-message <?php echo (strpos($mensagem_status, 'Erro') !== false || strpos($mensagem_status, 'ocupado') !== false) ? 'status-error' : 'status-success'; ?>">
                <?php echo $mensagem_status; ?>
            </div>
        <?php endif; ?>

        <form action="agendar.php" method="POST">
            <div class="input-group">
                <label for="servico">Serviço Desejado:</label>
                <select id="servico" name="servico" required>
                    <option value=""></option>
                    <option value="">Selecione o Serviço</option>
                    <option value="">Selecione o Serviço</option>
                    <option value="">Selecione o Serviço</option>
                    <option value="">Selecione o Serviço</option>
                    <option value="">Selecione o Serviço</option>
                    <option value="">Selecione o Serviço</option>
                    <option value="">Selecione o Serviço</option>
                    <option value="">Selecione o Serviço</option>
                    <?php 
                    $categoria_atual = "";
                    while ($row = $servicos->fetch_assoc()): 
                        if ($row['categoria'] != $categoria_atual) {
                            if ($categoria_atual != "") { echo '</optgroup>'; }
                            $categoria_atual = $row['categoria'];
                            echo '<optgroup label="' . ucfirst($categoria_atual) . '">';
                        }
                        $selected = ($servico_selecionado == $row['id']) ? 'selected' : '';
                    ?>
                        <option value="<?php echo $row['id']; ?>" <?php echo $selected; ?>>
                            <?php echo $row['nome']; ?>
                        </option>
                    <?php endwhile; if ($categoria_atual != "") { echo '</optgroup>'; } ?>
                </select>
            </div>

            <div class="input-group">
                <label for="profissional">Profissional:</label>
                <select id="profissional" name="profissional" required>
                    <option value="">Selecione o Profissional</option>
                    <option value="">Selecione o Profissional</option>
                    <option value="">Selecione o Profissional</option>
                    <?php while ($row = $profissionais->fetch_assoc()): ?>
                        <option value="<?php echo $row['id']; ?>"><?php echo $row['nome']; ?> (<?php echo $row['funcao']; ?>)</option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div class="input-group">
                <label for="data_agenda">Data:</label>
                <input type="date" id="data_agenda" name="data_agenda" required min="<?php echo date('Y-m-d'); ?>">
            </div>

            <div class="input-group">
                <label for="hora_agenda">Hora:</label>
                <input type="time" id="hora_agenda" name="hora_agenda" required min="09:00" max="18:00" step="1800">
            </div>

            <button type="submit" class="btn-primary" style="margin-top: 20px;">Confirmar Agendamento</button>
        </form>
    </div>

</body>
</html>