<?php
// **** ADIÇÃO 1: INICIA A SESSÃO ****
// Isso DEVE ser a primeira coisa no arquivo, antes de qualquer HTML.
// Permite que o PHP leia a variável $_SESSION['erro_login']
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Bookstyle</title>
    <style>
        /* ... (todo o resto do seu CSS permanece igual) ... */
        :root {
            --cor-fundo-principal: #f8f9fa;
            --cor-fundo-secundaria: #ffffff;
            --cor-primaria: #45725d;
            --cor-destaque: #45725d;
            --cor-destaque-hover: #3a614f;
            --cor-linha: #cccccc;
            --cor-texto-botao: #ffffff;
            --cor-texto: #45725d;
        }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Segoe UI', 'Roboto', sans-serif;
            background-color: var(--cor-fundo-principal);
            color: var(--cor-texto);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .login-container {
            background-color: var(--cor-fundo-secundaria);
            padding: 40px 50px;
            border-radius: 8px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
            text-align: center;
            border: 2px solid var(--cor-linha);
            transition: all 0.4s ease-in-out;
        }
        .login-container:hover {
            border-color: var(--cor-destaque);
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        }
        .login-title {
            color: var(--cor-primaria);
            font-family: 'Georgia', serif;
            font-size: 28px;
            font-weight: normal;
            margin-bottom: 15px;
            padding-bottom: 15px;
            border-bottom: 2px solid var(--cor-primaria);
        }
        .form-group {
            margin-bottom: 20px;
            text-align: left;
            display: flex;
            align-items: center;
            flex-wrap: wrap;
        }
        .form-group label {
            font-size: 14px;
            font-weight: 500;
            color: var(--cor-texto);
            width: 70px;
            margin-right: 10px;
        }
        .form-group input {
            flex: 1;
            padding: 10px;
            border: 1px solid var(--cor-linha);
            border-radius: 5px;
            font-size: 16px;
            transition: border-color 0.3s, box-shadow 0.3s;
        }
        .form-group input:focus {
            outline: none;
            border-color: var(--cor-destaque);
            box-shadow: 0 0 5px rgba(69, 114, 93, 0.3);
        }
        .btn-primary {
            background-color: var(--cor-destaque);
            color: var(--cor-texto-botao);
            padding: 12px 20px;
            border: none;
            border-radius: 50px;
            cursor: pointer;
            font-size: 16px;
            font-weight: bold;
            text-decoration: none;
            transition: background-color 0.3s, transform 0.2s;
            width: 100%;
            margin-top: 10px;
        }
        .btn-primary:hover {
            background-color: var(--cor-destaque-hover);
            transform: translateY(-2px);
        }
        .signup-link { margin-top: 25px; font-size: 14px; }
        .signup-link a { color: var(--cor-destaque); font-weight: bold; text-decoration: none; }
        .signup-link a:hover { text-decoration: underline; }

        /* ... (CSS do ícone home) ... */
        .home-link-login {
            position: fixed;
            top: 25px;
            left: 25px;
            color: #72978b; /* COR ALTERADA PARA VERDE PASTEL */
            text-decoration: none;
            z-index: 100;
            transition: transform 0.2s ease-in-out;
        }

        .home-link-login:hover {
            transform: scale(1.1);
        }
        
        .home-link-login svg {
            display: block;
        }

        /* **** ESTILO PARA A MENSAGEM DE ERRO **** */
        .caixa-erro {
            color: #721c24; /* Texto vermelho escuro */
            background-color: #f8d7da; /* Fundo rosa claro */
            border: 1px solid #f5c6cb; /* Borda rosa */
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
            text-align: center;
            font-weight: 500;
        }

    </style>
</head>
<body>

    <a href="/bookstyle_salao/index.php" class="home-link-login" title="Voltar ao Início">
        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" viewBox="0 0 16 16">
            <path d="M8.707 1.5a1 1 0 0 0-1.414 0L.646 8.146a.5.5 0 0 0 .708.708L8 2.207l6.646 6.647a.5.5 0 0 0 .708-.708L8.707 1.5z"/>
            <path d="m8 3.293 6 6V13.5a1.5 1.5 0 0 1-1.5 1.5h-9A1.5 1.5 0 0 1 2 13.5V9.293l6-6z"/>
        </svg>
    </a>

    <div class="login-container">
        <h2 class="login-title">Login Bookstyle</h2>
        
        <?php
        // **** ADIÇÃO 2: MOSTRA A MENSAGEM DE ERRO ****
        // Verifica se a variável de sessão 'erro_login' existe
        if (isset($_SESSION['erro_login'])) {
            
            // Exibe a mensagem de erro dentro de uma div estilizada
            echo '<div class="caixa-erro">';
            echo htmlspecialchars($_SESSION['erro_login']); // Mostra o erro
            echo '</div>';
            
            // Limpa a variável da sessão para não aparecer de novo
            unset($_SESSION['erro_login']);
        }
        ?>
        
        <form action="processa_login.php" method="POST">
            <div class="form-group">
                <label for="email">E-mail</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="senha">Senha</label>
                <input type="password" id="senha" name="senha" required>
            </div>
            
            <button type="submit" class="btn-primary">Entrar</button>
        </form>

        <p class="signup-link">
            Ainda não tem conta? <a href="cadastro.php">Cadastre-se</a>
        </p>
    </div>
    
    <!-- 
    NOTE: Eu limpei o final do seu arquivo, 
    pois havia uma tag </footer> e uma </div> extras 
    que estavam quebrando o HTML. 
    -->

</body>
</html>