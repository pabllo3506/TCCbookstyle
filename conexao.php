<?php
// CORREÇÃO CRÍTICA: Esta linha é OBRIGATÓRIA para o login funcionar.
// Ela deve ser a primeira coisa no arquivo para "ligar a memória" da sessão.
session_start();

$servidor = "localhost";
$usuario_db = "root"; // Renomeei para 'usuario_db' para ser mais específico
$senha_db = "";
$banco = "espaco_beleza_tcc";

// Cria a conexão
$conn = new mysqli($servidor, $usuario_db, $senha_db, $banco);

// Verifica a conexão
if ($conn->connect_error) {
    die("Falha na conexão com o banco de dados: " . $conn->connect_error);
}

// Garante a codificação correta para acentos
$conn->set_charset("utf8mb4");
?>