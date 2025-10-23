<?php
session_start();
include '../includes/config.php';
$logado = isset($_SESSION['usuario_id']);

$mensagem_sucesso = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = strip_tags($_POST['nome']);
    $email = strip_tags($_POST['email']);
    $telefone = strip_tags($_POST['telefone']);
    $pacote_interesse = strip_tags($_POST['pacote_interesse']);
    $data_evento = strip_tags($_POST['data_evento']);
    $mensagem = strip_tags($_POST['mensagem']);
    
    $mensagem_sucesso = "Obrigado, " . htmlspecialchars($nome) . "! Sua solicitação para o " . htmlspecialchars($pacote_interesse) . " foi recebida. Entraremos em contato em breve!";
}

$pacote_selecionado = isset($_GET['pacote']) ? htmlspecialchars($_GET['pacote']) : 'Não especificado';
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orçamento Dia da Noiva | Bookstyle</title>
    <link rel="stylesheet" href="../style.css">

    <style>
        .form-container-agendamento {
            max-width: 500px;
            margin: 40px auto;
            padding: 40px;
            background-color: #fff;
            border-radius: 8px;
            border: 1px solid #eee;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        }
        
        .form-container-agendamento h2 {
            text-align: center;
            color: var(--cor-titulo);
            font-size: 2em;
            margin-top: 0;
            margin-bottom: 30px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            font-size: 0.9em;
            color: #555;
        }

        .form-group input,
        .form-group textarea {
            width: 100%;
            padding: 12px;
            /* MUDANÇA: Borda dos campos mais escura e visível */
            border: 1px solid #999;
            border-radius: 5px;
            font-size: 1em;
            box-sizing: border-box;
            height: 48px;
            transition: border-color 0.3s;
        }
        
        .form-group input:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: var(--cor-primaria); /* Borda fica verde ao clicar */
        }

        .form-group textarea {
            min-height: 120px;
            resize: vertical;
        }
        
        ::placeholder {
            color: #aaa;
        }

        .btn-principal {
            background-color: var(--cor-primaria);
            color: white;
            padding: 15px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1.1em;
            font-weight: bold;
            width: 100%;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: background-color 0.3s;
        }

        .btn-principal:hover {
            background-color: var(--cor-titulo);
        }
        
        .success-message {
            padding: 15px;
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
            border-radius: 5px;
            text-align: center;
        }
    </style>
</head>
<body>

    <header>
        <div class="logo">
            <a href="../index.php"><img src="/bookstyle_salao/pages/img/bslogotipo.png" alt="Bookstyle Logo" style="width: 70px; height:70px;"></a>
        </div>
        <nav class="nav-links">
            <a href="../index.php" title="Início" class="home-icon-nav"><svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor" viewBox="0 0 16 16"><path d="M8.707 1.5a1 1 0 0 0-1.414 0L.646 8.146a.5.5 0 0 0 .708.708L2 8.207V13.5A1.5 1.5 0 0 0 3.5 15h9a1.5 1.5 0 0 0 1.5-1.5V8.207l.646.647a.5.5 0 0 0 .708-.708L8.707 1.5Z"/></svg></a>
            <a href="feminino.php">Feminino</a>
            <a href="masculino.php">Masculino</a>
            <a href="kids.php">Kids</a>
            <?php if ($logado): ?>
                <span class="welcome-message">Olá, <?php echo htmlspecialchars($_SESSION['usuario_nome']); ?>!</span>
                <a href="../logout.php" class="nav-link-sair">Sair</a>
            <?php else: ?>
                <a href="../login.php">Login</a>
            <?php endif; ?>
        </nav>
    </header>

    <main class="container">
        <div class="form-container-agendamento">
            
            <?php if (!empty($mensagem_sucesso)): ?>
                <h2>Solicitação Enviada!</h2>
                <div class="success-message">
                    <?php echo $mensagem_sucesso; ?>
                </div>
                <div style="text-align: center; margin-top: 20px;">
                    <a href="feminino.php" class="btn-principal" style="display: inline-block; text-decoration: none; width: auto;">Voltar</a>
                </div>
            <?php else: ?>
                <h2>Orçamento - Dia da Noiva</h2>
                <form action="solicitar_orcamento.php" method="POST">
                    <div class="form-group">
                        <label for="pacote_interesse">Pacote de Interesse</label>
                        <input type="text" id="pacote_interesse" name="pacote_interesse" value="<?php echo $pacote_selecionado; ?>" readonly style="background-color: #f0f0f0; color: #555;">
                    </div>
                    <div class="form-group">
                        <label for="nome">Nome Completo (Noiva ou Contato)</label>
                        <input type="text" id="nome" name="nome" placeholder="Digite seu nome completo" required>
                    </div>
                    <div class="form-group">
                        <label for="email">E-mail</label>
                        <input type="email" id="email" name="email" placeholder="exemplo@email.com" required>
                    </div>
                    <div class="form-group">
                        <label for="telefone">Telefone / WhatsApp</label>
                        <input type="tel" id="telefone" name="telefone" placeholder="(XX) XXXXX-XXXX" required>
                    </div>
                    <div class="form-group">
                        <label for="data_evento">Data do Evento</label>
                        <input type="date" id="data_evento" name="data_evento" required>
                    </div>
                    <div class="form-group">
                        <label for="mensagem">Mensagem</label>
                        <textarea id="mensagem" name="mensagem" placeholder="Horário, local onde irá se arrumar, etc." required></textarea>
                    </div>
                    <button type="submit" class="btn-principal">Enviar Solicitação</button>
                </form>
            <?php endif; ?>

        </div>
    </main>

</body>
</html>