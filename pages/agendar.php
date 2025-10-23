<?php
// O include já contém o session_start()
include '../conexao.php'; // Usa $conn (mysqli)

// GUARDA DE SEGURANÇA: Garante que apenas usuários logados acessem
if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../login.php?erro=login_necessario");
    exit();
}

// --- CORREÇÃO DE SEGURANÇA E DA QUERY ---

// Busca todos os serviços e os agrupa por categoria
$servicos_por_categoria = [];
// *** AQUI ESTAVA O ERRO DA COLUNA 'status' (LINHA 16) ***
// Removido "WHERE status = 'ativo'"
$stmt_servicos = $conn->prepare("SELECT id, nome, categoria FROM servicos ORDER BY categoria, nome");
// Se a query falhar, mostra o erro
if (!$stmt_servicos) {
    die("Erro na preparação da consulta de serviços: " . $conn->error);
}
$stmt_servicos->execute();
$todos_servicos_raw = $stmt_servicos->get_result();
while ($servico = $todos_servicos_raw->fetch_assoc()) {
    $servicos_por_categoria[strtolower($servico['categoria'])][] = $servico;
}
$stmt_servicos->close();

// Busca a lista de profissionais
$lista_profissionais = [];
// *** Removido "WHERE status = 'ativo'" também da query de profissionais ***
// (Caso sua tabela 'profissionais' também não tenha a coluna 'status')
// Se ela tiver, pode adicionar de volta: WHERE status = 'ativo'
$stmt_prof = $conn->prepare("SELECT id, nome FROM profissionais ORDER BY nome");
// Se a query falhar, mostra o erro
if (!$stmt_prof) {
    die("Erro na preparação da consulta de profissionais: " . $conn->error);
}
$stmt_prof->execute();
$profissionais_raw = $stmt_prof->get_result();
while ($p = $profissionais_raw->fetch_assoc()) {
    $lista_profissionais[] = $p;
}
$stmt_prof->close();
// --- FIM DA CORREÇÃO ---

$titulo_pagina = 'Agendar Serviço';
$logado = isset($_SESSION['usuario_id']);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $titulo_pagina; ?> | Bookstyle</title>
    <link rel="stylesheet" href="../style.css">
    
  <style>
    /* Estilos herdados e sobreescritos para o tema claro */
    body {
        display: block; 
    }
    .container {
        width: 90%;
        max-width: 600px;
        margin: 120px auto 40px;
        background-color: var(--cor-fundo-secundaria);
        padding: 40px;
        border-radius: 12px;
        border: 1px solid #e0e0e0;
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
    }
    .container:hover {
        box-shadow: 0 12px 30px rgba(0, 0, 0, 0.15);
    }
    .container h2 {
        color: var(--cor-titulo); 
        border-bottom: 2px solid var(--cor-linha);
        padding-bottom: 15px;
        margin: 0 auto 35px;
        text-align: center;
        font-size: 2.2em;
        width: fit-content;
        transition: color 0.3s ease;
    }
    .form-group {
        margin-bottom: 25px;
    }
    .form-group label {
        display: block;
        margin-bottom: 10px;
        font-weight: bold;
        color: var(--cor-texto);
        font-size: 1.1em;
    }
    .form-group input, 
    .form-group select {
        width: 100%;
        padding: 12px;
        box-sizing: border-box;
        background-color: #fcfcfc;
        border: 2px solid #b0c4b6;
        border-radius: 8px;
        color: #333;
        font-size: 1em;
        font-family: 'Segoe UI', 'Roboto', sans-serif;
        transition: border-color 0.3s ease, box-shadow 0.3s ease, background-color 0.3s ease;
    }
    .form-group input:focus, 
    .form-group select:focus {
        border-color: var(--cor-destaque);
        box-shadow: 0 0 12px rgba(69, 114, 93, 0.4);
        background-color: #ffffff;
        outline: none;
    }
    .form-group select {
        appearance: none;
        background-image: url('data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%22292.4%22%20height%3D%22292.4%22%3E%3Cpath%20fill%3D%22%2345725d%22%20d%3D%22M287%2C114.7L159.2%2C242.5c-4.7%2C4.7-12.3%2C4.7-17%2C0L5.3%2C114.7c-4.7-4.7-4.7-12.3%2C0-17l19.9-19.9c4.7-4.7%2C12.3-4.7%2C17%2C0l110.4%2C110.4L250.2%2C77.8c4.7-4.7%2C12.3-4.7%2C17%2C0l19.9%2C19.9C291.7%2C102.4%2C291.7%2C110%2C287%2C114.7z%22%2F%3E%3C%2Fsvg%3E');
        background-repeat: no-repeat;
        background-position: right 15px center;
        background-size: 10px auto;
        padding-right: 40px;
    }
    .form-group select:disabled {
        background-color: #e9ecef;
        cursor: not-allowed;
        opacity: 0.7;
    }
    .btn-submit {
        display: block;
        width: 100%;
        padding: 15px;
        background-color: var(--cor-destaque);
        color: white;
        border: none;
        border-radius: 50px;
        font-size: 1.2em;
        font-weight: bold;
        cursor: pointer;
        box-shadow: 0 4px 15px rgba(69, 114, 93, 0.4);
        transition: background-color 0.3s ease, transform 0.2s ease, box-shadow 0.3s ease;
        text-transform: uppercase;
        letter-spacing: 1px;
    }
    .btn-submit:hover {
        background-color: var(--cor-secundaria-hover);
        transform: translateY(-3px) scale(1.01);
        box-shadow: 0 8px 25px rgba(69, 114, 93, 0.6);
    }
    .alert {
        padding: 15px;
        margin: 0 auto 20px auto;
        border-radius: 5px;
        text-align: center;
        font-weight: 500;
        width: 100%;
        max-width: 450px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }
    .alert-error {
        background-color: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb;
    }
    header .logo img {
        width: 60px;
        height: 60px;
    }
</style>
</head>
<body>
    <header>
         <div class="logo">
            <img src="img/bslogotipo.png" alt="Bookstyle Logo" style="width: 70px; height:70px;">
         </div>
        <nav class="nav-links">
            <a href="feminino.php">Feminino</a>
            <a href="masculino.php">Masculino</a>
            <a href="kids.php">Kids</a>
            <a href="meus_agendamentos.php">Meus Agendamentos</a>

            <?php if ($logado): ?>
                <span class="welcome-message">Olá, <?php echo htmlspecialchars($_SESSION['usuario_nome']); ?>!</span>
                <a href="../logout.php">Sair</a>
            <?php else: ?>
                <a href="../login.php">Login</a>
            <?php endif; ?>
        </nav>
    </header>

    <div class="container agendamento-form">
        <h2><?php echo $titulo_pagina; ?></h2>

        <?php
            if (isset($_GET['erro'])) {
                echo '<div class="alert alert-error">Horário indisponível. Por favor, escolha outra data ou horário.</div>';
            }
        ?>

        <form action="processa_agendamento.php" method="POST">
                <input type="hidden" name="id_cliente" value="<?php echo $_SESSION['usuario_id']; ?>">

            <div class="form-group">
                <label for="categoria">Categoria:</label>
                <select name="categoria" id="categoria" required>
                    <option value="" disabled selected>Selecione a categoria</option>
                    <option value="feminino">Feminino</option>
                    <option value="masculino">Masculino</option>
                    <option value="kids">Kids</option>
                </select>
            </div>

            <div class="form-group">
                <label for="id_servico">Serviço:</label>
                <select name="id_servico" id="id_servico" required disabled>
                    <option value="" disabled selected>Selecione a categoria primeiro</option>
                </select>
            </div>

            <div class="form-group">
                <label for="id_profissional">Profissional:</label>
                <select name="id_profissional" id="id_profissional" required>
                    <option value="" disabled selected>Selecione um profissional</option>
                    <?php foreach($lista_profissionais as $p): ?>
                        <option value="<?php echo $p['id']; ?>"><?php echo htmlspecialchars($p['nome']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="data">Data:</label>
                <input type="date" name="data" id="data" required>
            </div>

            <div class="form-group">
                <label for="hora">Horário:</label>
                <select name="hora" id="hora" required>
                    <option value="" disabled selected>Selecione um horário</option>
                    <?php 
                        for ($h = 9; $h < 18; $h++) {
                            $hora_cheia = sprintf('%02d:00', $h); // Formata para 09:00
                            $hora_meia = sprintf('%02d:30', $h); // Formata para 09:30
                            echo "<option value='{$hora_cheia}'>{$hora_cheia}</option>";
                            echo "<option value='{$hora_meia}'>{$hora_meia}</option>";
                        }
                         echo "<option value='18:00'>18:00</option>";
                    ?>
                </select>
            </div>

            <button type="submit" class="main-button btn-submit">Confirmar Agendamento</button>
        </form>
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
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path d="M380.9 97.1C339 55.1 283.2 32 223.9 32c-122.4 0-222 99.6-222 222 0 39.1 10.2 77.3 29.6 111L0 480l117.7-30.9c32.4 17.7 68.9 27 106.1 27h.1c122.3 0 224.1-99.6 224.1-222 0-59.3-25.2-115-67.1-157zm-157 .9c49.4 0 95.6 19.2 130.4 54.1 34.8 34.9 56.2 81.2 56.1 130.5 0 108.7-88.2 197-196.9 197h-.1c-32.9 0-65.7-8.4-94.3-24.6l-6.7-4-69.8 18.3L72 359.2l-4.4-7c-18.5-29.4-28.2-63.3-28.2-98.2 0-108.7 88.2-197 196.9-197zm108.2 131.2c-5.5-2.8-32.8-16.2-37.9-18-5.1-1.9-8.8-2.8-12.5 2.8-3.7 5.6-14.3 18-17.6 21.8-3.2 3.7-6.5 4.2-12 1.4-32.6-16.3-54-29.1-75.5-66-5.7-9.8 5.7-9.1 16.3-30.3 1.8-3.7.9-6.9-.5-9.7-1.4-2.8-12.5-30.1-17.1-41.2-4.5-10.8-9.1-9.3-12.5-9.5-3.2-.2-6.9-.2-10.6-.2-3.7 0-9.7 1.4-14.8 6.9-5.1 5.6-19.4 19-19.4 46.3 0 27.3 19.9 53.7 22.6 57.4 2.8 3.7 39.1 59.7 94.8 83.8 35.2 15.2 49 16.5 66.6 13.9 10.7-1.6 32.8-13.4 37.4-26.4 4.6-13 4.6-24.1 3.2-26.4-1.3-2.5-5-3.9-10.5-6.6z"/></svg>
                </a>
            </div>
            <div class="footer-copyright">
                &copy; <?php echo date("Y"); ?> Bookstyle. Todos os direitos reservados.
            </div>
        </div>
    </footer>

    <script>
        // CORRIGIDO: O SCRIPT INTEIRO ESTÁ DENTRO DO 'DOMContentLoaded'
        document.addEventListener('DOMContentLoaded', () => {
            
            // Pega os dados dos serviços que o PHP buscou
            // Esta linha transforma o PHP em um objeto JavaScript
            const servicosPorCategoria = <?php echo json_encode($servicos_por_categoria); ?>;
            
            // Pega os elementos do formulário
            const categoriaSelect = document.getElementById('categoria');
            const servicoSelect = document.getElementById('id_servico');

            // Adiciona o "ouvinte" de mudança
            categoriaSelect.addEventListener('change', function() {
                const categoriaSelecionada = this.value; // ex: "feminino"
                
                // Limpa o select de serviço
                servicoSelect.innerHTML = '<option value="" disabled selected>Selecione o serviço</option>';

                // Verifica se a categoria existe e tem serviços
                // Adicionado console.log para depuração
                console.log("Categoria selecionada:", categoriaSelecionada);
                console.log("Serviços para esta categoria:", servicosPorCategoria[categoriaSelecionada]);

                if (categoriaSelecionada && servicosPorCategoria[categoriaSelecionada]) {
                    servicoSelect.disabled = false; // Habilita o campo
                    
                    // Adiciona cada serviço da categoria no <select>
                    servicosPorCategoria[categoriaSelecionada].forEach(function(servico) {
                        const option = document.createElement('option');
                        option.value = servico.id;
                        option.textContent = servico.nome;
                        servicoSelect.appendChild(option);
                    });
                } else {
                    servicoSelect.disabled = true; // Mantém desabilitado
                    servicoSelect.innerHTML = '<option value="" disabled selected>Selecione a categoria primeiro</option>';
                    // Adicionado log para quando não encontra serviços
                    console.log("Nenhum serviço encontrado para a categoria:", categoriaSelecionada);
                }
            });
            
        }); // Fim do 'DOMContentLoaded'
    </script>
</body>
</html>