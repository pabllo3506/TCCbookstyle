<?php
// É crucial iniciar a sessão no topo para poder ler as mensagens
session_start();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Criar Conta | Bookstyle</title>
    <style>
        /* Seu CSS ... */
        :root { --cor-fundo-principal: #f8f9fa; --cor-fundo-secundaria: #ffffff; --cor-primaria: #45725d; --cor-destaque: #45725d; --cor-destaque-hover: #3a614f; --cor-linha: #cccccc; --cor-texto-botao: #ffffff; --cor-texto: #45725d; }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Segoe UI', 'Roboto', sans-serif; background-color: var(--cor-fundo-principal); color: var(--cor-texto); display: flex; justify-content: center; align-items: center; min-height: 100vh; padding: 20px 0; }
        .auth-container { background-color: var(--cor-fundo-secundaria); padding: 40px 50px; border-radius: 8px; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1); width: 100%; max-width: 450px; text-align: center; border: 2px solid var(--cor-linha); transition: all 0.4s ease-in-out; }
        .auth-container:hover { border-color: var(--cor-destaque); transform: translateY(-5px); box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15); }
        .auth-title { color: var(--cor-primaria); font-family: 'Georgia', serif; font-size: 28px; font-weight: normal; margin-bottom: 15px; padding-bottom: 15px; border-bottom: 2px solid var(--cor-primaria); }
        .form-group { margin-bottom: 20px; display: flex; align-items: center; flex-wrap: wrap; }
        .form-group label { font-size: 14px; font-weight: 500; color: var(--cor-texto); flex-basis: 110px; text-align: right; margin-right: 15px; }
        .form-group input { flex: 1; padding: 10px; border: 1px solid var(--cor-linha); border-radius: 5px; font-size: 16px; transition: border-color 0.3s, box-shadow 0.3s; min-width: 150px; }
        .form-group input:focus { outline: none; border-color: var(--cor-destaque); box-shadow: 0 0 5px rgba(69, 114, 93, 0.3); }
        .btn-primary { background-color: var(--cor-destaque); color: var(--cor-texto-botao); padding: 12px 20px; border: none; border-radius: 50px; cursor: pointer; font-size: 16px; font-weight: bold; text-decoration: none; transition: background-color 0.3s, transform 0.2s; width: 100%; margin-top: 10px; }
        .btn-primary:hover { background-color: var(--cor-destaque-hover); transform: translateY(-2px); }
        .login-link { margin-top: 25px; font-size: 14px; }
        .login-link a { color: var(--cor-destaque); font-weight: bold; text-decoration: none; }
        .login-link a:hover { text-decoration: underline; }
        .home-link-login { position: fixed; top: 25px; left: 25px; color: #72978b; text-decoration: none; z-index: 100; transition: transform 0.2s ease-in-out; }
        .home-link-login:hover { transform: scale(1.1); }
        .home-link-login svg { display: block; }

        /* CSS PARA AS NOVAS MENSAGENS */
        .message {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
            font-size: 1em;
            text-align: center;
            font-weight: bold;
        }
        .error-message {
            background-color: #f8d7da; /* Vermelho claro */
            color: #721c24; /* Vermelho escuro */
            border: 1px solid #f5c6cb;
        }
        .success-message {
            background-color: #d4edda; /* Verde claro */
            color: #155724; /* Verde escuro */
            border: 1px solid #c3e6cb;
        }
    </style>
</head>
<body>

    <a href="index.php" class="home-link-login" title="Voltar ao Início">
        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" viewBox="0 0 16 16">
            <path d="M8.707 1.5a1 1 0 0 0-1.414 0L.646 8.146a.5.5 0 0 0 .708.708L8 2.207l6.646 6.647a.5.5 0 0 0 .708-.708L8.707 1.5z"/>
            <path d="m8 3.293 6 6V13.5a1.5 1.5 0 0 1-1.5 1.5h-9A1.5 1.5 0 0 1 2 13.5V9.293l6-6z"/>
        </svg>
    </a>

    <div class="auth-container">
        <h2 class="auth-title">Criar Conta Bookstyle</h2>
        
        <?php
            // LÓGICA PARA EXIBIR A MENSAGEM DE ERRO
            if (isset($_SESSION['erro_cadastro'])) {
                // Exibe a mensagem de erro dentro de uma div estilizada
                echo '<div class="message error-message">' . htmlspecialchars($_SESSION['erro_cadastro']) . '</div>';
                // Limpa a mensagem da sessão para não aparecer de novo
                unset($_SESSION['erro_cadastro']);
            }
        ?>

        <form action="processa_cadastro.php" method="POST">
            <div class="form-group">
                <label for="nome">Nome Completo</label>
                <input type="text" id="nome" name="nome_completo" required>
            </div>
            <div class="form-group">
                <label for="email">E-mail</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="senha">Senha</label>
                <input type="password" id="senha" name="senha" required>
            </div>
            <div class="form-group">
                <label for="confirmar_senha">Confirme</label>
                <input type="password" id="confirmar_senha" name="confirmar_senha" required>
            </div>
            
            <button type="submit" class="btn-primary">Cadastrar</button>
        </form>

        <p class="login-link">
            Já tem conta? <a href="login.php">Faça Login</a>
        </p>
    </div>

   
</body>
</html>