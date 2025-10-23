<?php
// **** GARANTE QUE A SESSÃO SEJA INICIADA ****
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// *** MUDE AQUI se o nome do seu arquivo de conexão for diferente ***
include 'conexao.php'; // Usa $conn (mysqli)

// Limpa qualquer erro de login anterior para evitar confusão
unset($_SESSION['erro_login']);

// Verifica se a requisição é POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Pega os dados do formulário
    $email = $_POST['email'] ?? null; 
    $senha_digitada = $_POST['senha'] ?? null;

    // Validação básica dos inputs
    if (empty($email) || empty($senha_digitada)) {
        $_SESSION['erro_login'] = "E-mail e senha são obrigatórios.";
        header("Location: login.php");
        exit();
    }

    // **** BUSCAR NA TABELA 'clientes' ****
    $sql = "SELECT id, nome, senha_hash FROM clientes WHERE email = ?"; 
      
    $stmt = $conn->prepare($sql);
    
    if (!$stmt) {
        // Erro de preparação (SQL inválido, problema no DB)
        error_log("Erro ao preparar consulta de login: " . $conn->error); // Loga o erro real
        $_SESSION['erro_login'] = "Erro interno no servidor. Tente novamente mais tarde."; 
        header("Location: login.php");
        exit();
    }

    // Associa o parâmetro (email) e executa
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    // Verifica se encontrou exatamente um cliente
    if ($result->num_rows === 1) {
        
        $cliente = $result->fetch_assoc(); // Pega os dados do cliente
        
        // Verifica se a senha está correta
        // (isset($cliente['senha_hash']) é uma boa verificação caso a coluna possa ser nula)
        if (isset($cliente['senha_hash']) && password_verify($senha_digitada, $cliente['senha_hash'])) {
            
            // *** LOGIN BEM SUCEDIDO! ***
            session_regenerate_id(true); // Boa prática de segurança

            // Guarda os dados corretos na sessão
            $_SESSION['usuario_id'] = $cliente['id']; 
            $_SESSION['usuario_nome'] = $cliente['nome'];
            
            // Fecha tudo ANTES de redirecionar
            $stmt->close(); 
            $conn->close();

            // Redireciona para a página principal
            header("Location: index.php"); 
            exit(); // Termina o script aqui

        } else {
            // Se entrou aqui, a SENHA estava errada
            $_SESSION['erro_login'] = "E-mail ou senha inválidos.";
        }

    } else {
        // Se entrou aqui, o EMAIL não foi encontrado (ou há duplicatas)
        $_SESSION['erro_login'] = "E-mail ou senha inválidos.";
    }

    // Se o script chegou até aqui, o login FALHOU (email ou senha errados)
    // Fecha as conexões antes de redirecionar de volta
    $stmt->close();
    $conn->close();
    header("Location: login.php");
    exit();

} else {
    // Se alguém tentar acessar o script diretamente (sem ser via POST), redireciona
    header("Location: login.php");
    exit();
}
?>