<?php
// --- Configurações de Sessão e Reporte de Erros ---
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
date_default_timezone_set('America/Sao_Paulo');

// --- Configurações do Banco de Dados ---
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root'); // Verifique se é 'root'
define('DB_PASSWORD', '');     // Verifique se está vazia
define('DB_NAME', 'bookstyle_salao');

// --- Tentativa de Conexão com o MySQL ---
$conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

if ($conn->connect_error) {
    die("Falha na conexão com o banco de dados: " . $conn->connect_error);
}

$conn->set_charset("utf8mb4");

// --- Função de Sanitização Segura ---
function sanitize($conn, $data) {
    if (is_array($data)) {
        // Uso de função anônima para evitar recursão infinita (o motivo do erro de memória)
        return array_map(function($item) use ($conn) {
            return sanitize($conn, $item);
        }, $data);
    }
    
    $data = strip_tags($data); 
    $data = $conn->real_escape_string($data);
    
    return $data;
}

?>