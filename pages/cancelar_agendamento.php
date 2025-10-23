<?php
include '../conexao.php';

// Apenas usuários logados podem cancelar
if (!isset($_SESSION['usuario_id'])) {
    exit('Acesso negado.');
}

// Pega o ID do agendamento pela URL e o ID do cliente pela sessão
$id_agendamento = isset($_GET['id']) ? intval($_GET['id']) : 0;
$id_cliente = $_SESSION['usuario_id'];

// Se um ID de agendamento válido foi fornecido
if ($id_agendamento > 0) {
    // Prepara a query de atualização.
    // A condição "AND id_cliente = ?" é uma medida de segurança para garantir
    // que um usuário só possa cancelar os próprios agendamentos.
    $stmt = $conn->prepare("UPDATE agendamentos SET status = 'cancelado' WHERE id = ? AND id_cliente = ?");
    $stmt->bind_param("ii", $id_agendamento, $id_cliente);
    $stmt->execute();
    $stmt->close();
}

// Após processar, redireciona o usuário de volta para a lista de agendamentos
header("Location: meus_agendamentos.php");
exit();
?>