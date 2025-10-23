<?php
// O include já inicia a sessão para nós
include '../conexao.php'; // Usa $conn (mysqli)

// Apenas usuários logados podem processar um agendamento
if (!isset($_SESSION['usuario_id'])) {
    // Redireciona para login se não estiver logado
    header("Location: ../login.php?erro=login_necessario");
    exit('Acesso negado. Faça login para continuar.'); 
}

// Verifica se os dados vieram do formulário via método POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // 1. Coleta e valida os dados do formulário
    $id_cliente = filter_var($_SESSION['usuario_id'], FILTER_VALIDATE_INT); 
    $id_servico = filter_input(INPUT_POST, 'id_servico', FILTER_VALIDATE_INT);
    $id_profissional = filter_input(INPUT_POST, 'id_profissional', FILTER_VALIDATE_INT);
    $data = $_POST['data'] ?? null; 
    $hora = $_POST['hora'] ?? null; 

    // Verifica se todos os dados essenciais foram recebidos e são válidos
    if (!$id_cliente || !$id_servico || !$id_profissional || !$data || !$hora) {
         header("Location: agendar.php?erro=dados_invalidos"); 
         exit('Erro: Dados do formulário inválidos ou incompletos.');
    }
    
    // Combina data e hora para o formato DATETIME do banco (YYYY-MM-DD HH:MM:SS)
    $data_hora_str = $data . ' ' . $hora . ':00';
    try {
        $data_hora_obj = new DateTime($data_hora_str); 
    } catch (Exception $e) {
        header("Location: agendar.php?erro=data_invalida"); 
        exit('Erro: Data ou hora inválida.');
    }

    // 2. Validação: Verifica se a data não é no passado
    $agora = new DateTime(); 
    if ($data_hora_obj < $agora) {
        $servico_id_get = $_POST['id_servico'] ?? 0; 
        header("Location: agendar.php?servico_id=$servico_id_get&erro=data_passada"); 
        exit("Erro: Não é possível agendar em uma data ou hora que já passou.");
    }
    
    // 3. VERIFICAÇÃO DE CONFLITO DE HORÁRIO 
    // (Esta parte está correta no seu código)
    $duracao_servico = 0; 
    $stmt_duracao = $conn->prepare("SELECT duracao FROM servicos WHERE id = ?");
    if (!$stmt_duracao) { die("Erro ao preparar consulta de duração: " . $conn->error); }
    $stmt_duracao->bind_param("i", $id_servico);
    $stmt_duracao->execute();
    $resultado_duracao = $stmt_duracao->get_result();
    if ($resultado_duracao->num_rows > 0) {
        $duracao_servico = (int) $resultado_duracao->fetch_assoc()['duracao'];
    }
    $stmt_duracao->close();

    if ($duracao_servico <= 0) {
        header("Location: agendar.php?servico_id=$id_servico&erro=duracao_invalida");
        exit("Erro: Serviço com duração inválida.");
    }

    $horario_inicio_novo = $data_hora_obj;
    $horario_fim_novo = (clone $horario_inicio_novo)->add(new DateInterval('PT' . $duracao_servico . 'M'));

    $stmt_conflito = $conn->prepare("
        SELECT a.data_hora, s.duracao 
        FROM agendamentos a
        JOIN servicos s ON a.id_servico = s.id
        WHERE a.id_profissional = ? AND DATE(a.data_hora) = ? AND a.status = 'agendado'
    ");
    if (!$stmt_conflito) { die("Erro ao preparar consulta de conflito: " . $conn->error); }
    $stmt_conflito->bind_param("is", $id_profissional, $data);
    $stmt_conflito->execute();
    $agendamentos_existentes = $stmt_conflito->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt_conflito->close();

    $conflito_encontrado = false;
    foreach ($agendamentos_existentes as $ag) {
        $horario_inicio_existente = new DateTime($ag['data_hora']);
        $duracao_existente = (int) $ag['duracao'];
        if ($duracao_existente <= 0) continue; 
        
        $horario_fim_existente = (clone $horario_inicio_existente)->add(new DateInterval('PT' . $duracao_existente . 'M'));
        
        if ($horario_inicio_novo < $horario_fim_existente && $horario_fim_novo > $horario_inicio_existente) {
            $conflito_encontrado = true;
            break; 
        }
    }

    if ($conflito_encontrado) {
        header("Location: agendar.php?erro=horario_indisponivel"); 
        exit();
    }

    // **** VERIFICAÇÃO EXPLÍCITA DO CLIENTE (ADICIONADA) ****
    $stmt_check_cliente = $conn->prepare("SELECT id FROM clientes WHERE id = ?");
    if (!$stmt_check_cliente) { die("Erro ao preparar verificação de cliente: " . $conn->error); }
    $stmt_check_cliente->bind_param("i", $id_cliente);
    $stmt_check_cliente->execute();
    $result_check = $stmt_check_cliente->get_result();
    
    if ($result_check->num_rows === 0) {
         // Se o cliente não existe, NÃO insere e mostra erro CLARO
         $stmt_check_cliente->close();
         die("Erro CRÍTICO: O ID do cliente ($id_cliente) obtido da sessão NÃO foi encontrado na tabela 'clientes'. Verifique seu script de LOGIN se ele está salvando o ID correto na `\$_SESSION['usuario_id']`.");
    }
    $stmt_check_cliente->close();
    // **** FIM DA VERIFICAÇÃO ****


    // 4. Se não há conflitos E o cliente existe, TENTA inserir o novo agendamento
    
    $stmt_insert = $conn->prepare("INSERT INTO agendamentos (id_cliente, id_servico, id_profissional, data_hora, status) VALUES (?, ?, ?, ?, 'agendado')");
    if (!$stmt_insert) { die("Erro ao preparar inserção: " . $conn->error); }
    
    $data_hora_formatada = $horario_inicio_novo->format('Y-m-d H:i:s'); 
    
    $stmt_insert->bind_param("iiis", $id_cliente, $id_servico, $id_profissional, $data_hora_formatada);

    // Tenta executar a inserção
    if ($stmt_insert->execute()) {
        // <<<< SUCESSO! O AGENDAMENTO FOI SALVO >>>>
        $_SESSION['sucesso_agendamento'] = "Seu horário foi agendado com sucesso!";
        // <<<< REDIRECIONA PARA MEUS AGENDAMENTOS >>>>
        header("Location: meus_agendamentos.php"); 
        exit(); // Termina o script aqui para garantir o redirecionamento
    } else {
        // <<<< FALHA AO SALVAR (Pode ser outro erro de Foreign Key, etc) >>>>
        // Mostra o erro específico do MySQL.
        die("Ocorreu um erro ao salvar seu agendamento: " . $stmt_insert->error); 
    }

    $stmt_insert->close();
    $conn->close();

} else {
    // Acesso direto sem POST
    header("Location: ../index.php"); 
    exit();
}
?>