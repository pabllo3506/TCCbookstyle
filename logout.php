<?php
// 1. Inicia a sessão para poder acessá-la
session_start();

// 2. Limpa todas as variáveis da sessão (apaga os dados do "crachá")
session_unset();

// 3. Destrói a sessão (rasga o "crachá")
session_destroy();

// 4. Redireciona o usuário para a página de login
header("Location: login.php");
exit();
?>