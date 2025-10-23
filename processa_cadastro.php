<?php
include 'conexao.php'; // Inclui a conexão (que já inicia a sessão)

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = $_POST['nome_completo'];
    $email = $_POST['email'];
    $senha = $_POST['senha'];
    $confirmar_senha = $_POST['confirmar_senha'];

    if ($senha !== $confirmar_senha) {
        $_SESSION['erro_cadastro'] = "As senhas não coincidem!";
        header("Location: cadastro.php");
        exit();
    }

    // **** CORREÇÃO 1: Verificar na tabela 'clientes' ****
    $sql_check = "SELECT id FROM clientes WHERE email = ?";
    $stmt_check = $conn->prepare($sql_check);
    
    if (!$stmt_check) {
         // Adiciona um log de erro caso a preparação falhe
         error_log("Erro ao preparar check de email: " . $conn->error);
         $_SESSION['erro_cadastro'] = "Erro interno no servidor (check).";
         header("Location: cadastro.php");
         exit();
    }
    
    $stmt_check->bind_param("s", $email);
    $stmt_check->execute();
    if ($stmt_check->get_result()->num_rows > 0) {
        $_SESSION['erro_cadastro'] = "Este e-mail já está cadastrado!";
        $stmt_check->close(); // Fecha o statement
        header("Location: cadastro.php");
        exit();
    }
    $stmt_check->close(); // Fecha o statement se não encontrou
    
    // O hash está PERFEITO!
    $senha_hash = password_hash($senha, PASSWORD_DEFAULT);

    // **** CORREÇÃO 2: Inserir na tabela 'clientes' ****
    $sql_insert = "INSERT INTO clientes (nome, email, senha_hash) VALUES (?, ?, ?)";
    $stmt_insert = $conn->prepare($sql_insert);

    if (!$stmt_insert) {
         // Adiciona um log de erro caso a preparação falhe
         error_log("Erro ao preparar insert de cliente: " . $conn->error);
         $_SESSION['erro_cadastro'] = "Erro interno no servidor (insert).";
         header("Location: cadastro.php");
         exit();
    }

    $stmt_insert->bind_param("sss", $nome, $email, $senha_hash);

    if ($stmt_insert->execute()) {
        $_SESSION['sucesso_cadastro'] = "Cadastro realizado com sucesso! Faça o login.";
        $stmt_insert->close(); // Fecha o statement
        $conn->close(); // Fecha a conexão
        header("Location: login.php");
        exit();
    } else {
        $_SESSION['erro_cadastro'] = "Ocorreu um erro ao cadastrar. Tente novamente.";
        $stmt_insert->close(); // Fecha o statement
        $conn->close(); // Fecha a conexão
        header("Location: cadastro.php");
        exit();
    }
}
?>
