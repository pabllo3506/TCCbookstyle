<?php
/* =========================================
 * ARQUIVO: buscar_servicos.php
 * ========================================= */

// 1. INCLUIR A CONEXÃO
//    *** ESCOLHA A OPÇÃO CORRETA ABAIXO ***

// OPÇÃO 1 (Mais provável: /includes/config.php)
// Use esta linha se o seu config.php está em uma pasta 'includes' na raiz do projeto
include '../includes/config.php';

// OPÇÃO 2 (Na raiz: /config.php)
// Use esta linha se o seu config.php está na pasta raiz (junto com index.php)
// include '../config.php';

// OPÇÃO 3 (Dentro de pages: /pages/config.php)
// Use esta linha se o config.php está DENTRO da pasta 'pages'
// include 'config.php';


// 2. Define o tipo de resposta como JSON
//    (NÃO MEXA AQUI)
header('Content-Type: application/json');

// 3. Pega a categoria
//    (NÃO MEXA AQUI)
$categoria_selecionada = $_GET['categoria'] ?? '';

// 4. Se a categoria estiver vazia, retorna array vazio
//    (NÃO MEXA AQUI)
if (empty($categoria_selecionada)) {
    echo json_encode([]); 
    exit; 
}

// 5. Tenta consultar o banco
//    (NÃO MEXA AQUI, a menos que sua tabela/coluna tenha nome diferente)
try {
    
    // (A linha 24 do erro estava aqui)
    // Agora o $pdo deve existir
    
    // AJUSTE ESTA QUERY se sua tabela/coluna tiver nome diferente
    $stmt = $pdo->prepare("SELECT id, nome FROM servicos WHERE categoria = ? AND status = 'ativo'");
    
    // (A linha 27 do erro estava aqui)
    $stmt->execute([$categoria_selecionada]);
    
    $servicos = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // 6. Sucesso! Envia os serviços como JSON
    echo json_encode($servicos);

} catch (PDOException $e) {
    // 7. Erro! Envia o erro do banco como JSON
    http_response_code(500); 
    echo json_encode([
        'erro' => 'Falha ao consultar o banco de dados.',
        'detalhes' => $e->getMessage() 
    ]);
}
?>