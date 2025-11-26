<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');
include '../../db/conexao.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'error', 'mensagem' => 'Método não permitido.']);
    exit;
}

$id_sala = $_POST['id_sala'] ?? null;
$capacidade = $_POST['capacidade'] ?? null;
$descricao = $_POST['descricao'] ?? null;

if (!$id_sala) {
    echo json_encode(['status' => 'error', 'mensagem' => 'ID da sala não fornecido.']);
    exit;
}

$sql = "UPDATE Salas SET capacidade = ?, descricao_sala = ? WHERE id_sala = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, 'isi', $capacidade, $descricao, $id_sala);

if (mysqli_stmt_execute($stmt)) {
    if (mysqli_stmt_affected_rows($stmt) > 0) {
        echo json_encode(['status' => 'success', 'mensagem' => 'Sala atualizada com sucesso.']);
    } else {
        echo json_encode(['status' => 'error', 'mensagem' => 'Sala não encontrada ou sem alterações.']);
    }
} else {
    echo json_encode(['status' => 'error', 'mensagem' => 'Erro ao atualizar sala: ' . mysqli_error($conn)]);
}

mysqli_stmt_close($stmt);
mysqli_close($conn);
?>
