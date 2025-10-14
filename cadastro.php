<?php
include 'includes/config.php';

$mensagem_status = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = sanitize($conn, $_POST['nome']);
    $email = sanitize($conn, $_POST['email']);
    $senha_digitada = $_POST['senha'];
    $confirma_senha = $_POST['confirma_senha'];
    $tipo = 'cliente';
    
    // 1. Validação de Senhas
    if ($senha_digitada !== $confirma_senha) {
        $mensagem_status = "Erro: As senhas digitadas não coincidem.";
    } else {
        // 2. Hash de Senha Seguro
        $senha_hash = password_hash($senha_digitada, PASSWORD_DEFAULT); 
        
        $stmt = $conn->prepare("INSERT INTO usuarios (nome, email, senha, tipo) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $nome, $email, $senha_hash, $tipo);
        
        if ($stmt->execute()) {
            // 3. Redirecionamento Imediato após sucesso
            header("Location: login.php?status=cadastro_sucesso");
            exit(); 
        } else {
            $mensagem_status = "Erro no cadastro. O e-mail pode já estar em uso.";
        }
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Cadastro | Bookstyle Salão</title>
    <link rel="stylesheet" href="style.css"> 
</head>
<body>

<div class="auth-container">
    <h2>Criar Conta Bookstyle</h2>

    <?php if ($mensagem_status): ?>
        <p class="status-message <?php echo (strpos($mensagem_status, 'Erro') !== false) ? 'status-error' : 'status-success'; ?>">
            <?php echo $mensagem_status; ?>
        </p>
    <?php endif; ?>

    <form action="cadastro.php" method="POST">
        <div class="input-group">
            <label for="nome">Nome Completo</label>
            <input type="text" id="nome" name="nome" required value="<?php echo isset($_POST['nome']) ? htmlspecialchars($_POST['nome']) : ''; ?>">
        </div>
        <div class="input-group">
            <label for="email">E-mail</label>
            <input type="email" id="email" name="email" required value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
        </div>
        <div class="input-group">
            <label for="senha">Senha</label>
            <input type="password" id="senha" name="senha" required>
        </div>
        <div class="input-group">
            <label for="confirma_senha">Confirme a Senha</label>
            <input type="password" id="confirma_senha" name="confirma_senha" required>
        </div>
        <button type="submit" class="btn-primary">Cadastrar</button>
    </form>
    <a href="login.php" style="margin-top: 15px; display: block; text-align: center;">Já tem conta? Faça Login</a>
</div>

</body>
</html>