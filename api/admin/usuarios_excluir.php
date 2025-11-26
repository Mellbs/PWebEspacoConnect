<?php
session_start();
header('Content-Type: application/json');
include '../../agendamento-salas/db/conexao.php';

// if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_papel'] !== 'gerente') {
//     echo json_encode(['status' => 'error', 'mensagem' => 'Acesso negado.']);
//     exit;
// }

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'error', 'mensagem' => 'Método não permitido.']);
    exit;
}

$id = (int)($_POST['id'] ?? 0);

if (empty($id)) {
    echo json_encode(['status' => 'error', 'mensagem' => 'ID é obrigatório.']);
    exit;
}

$sql = "DELETE FROM Usuarios WHERE id_usuario = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, 'i', $id);

if (mysqli_stmt_execute($stmt)) {
    echo json_encode(['status' => 'success', 'mensagem' => 'Usuário excluído com sucesso.']);
} else {
    echo json_encode(['status' => 'error', 'mensagem' => 'Erro ao excluir usuário: ' . mysqli_error($conn)]);
}

mysqli_stmt_close($stmt);
mysqli_close($conn);
?>
