<?php
include 'includes/config.php';

$mensagem_erro = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = sanitize($conn, $_POST['email']);
    $senha_digitada = $_POST['senha'];

    $stmt = $conn->prepare("SELECT id, nome, senha, tipo FROM usuarios WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 1) {
        $usuario = $result->fetch_assoc();
        
        if (MD5($senha_digitada) == $usuario['senha']) { 
            $_SESSION['user_id'] = $usuario['id'];
            $_SESSION['user_nome'] = $usuario['nome'];
            $_SESSION['user_tipo'] = $usuario['tipo'];
            
            header("Location: index.php"); 
            exit();
        } else {
            $mensagem_erro = "Senha incorreta.";
        }
    } else {
        $mensagem_erro = "Usuário não encontrado.";
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Login | Bookstyle Salão</title>
    <link rel="stylesheet" href="style.css"> 
</head>
<body>

<div class="auth-container">
    <h2>Login Bookstyle</h2>
    <?php if ($mensagem_erro) : ?>
        <p class="status-message status-error"><?php echo $mensagem_erro; ?></p>
    <?php endif; ?>
    
    <form action="login.php" method="POST">
        <div class="input-group">
            <label for="email">E-mail</label>
            <input type="email" id="email" name="email" required>
        </div>
        <div class="input-group">
            <label for="senha">Senha</label>
            <input type="password" id="senha" name="senha" required>
        </div>
        <button type="submit" class="btn-primary">Entrar</button>
    </form>
    <a href="cadastro.php" style="margin-top: 15px; display: block; text-align: center;">Ainda não tem conta? Cadastre-se</a>
</div>

</body>
</html>
