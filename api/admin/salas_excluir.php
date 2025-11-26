<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

include '../../db/conexao.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'error', 'message' => 'Método não permitido.']);
    exit;
}

$id_sala = $_POST['id_sala'] ?? '';
$email = $_POST['email'] ?? '';

if (empty($id_sala) || empty($email)) {
    echo json_encode(['status' => 'error', 'message' => 'ID da sala e email são obrigatórios.']);
    exit;
}

if (!str_ends_with($email, '@gerente.com')) {
    echo json_encode(['status' => 'error', 'message' => 'Acesso negado. Apenas contas terminadas em @gerente.com podem excluir salas.']);
    exit;
}

// Primeiro, excluir agendamentos relacionados
$sql_delete_agendamentos = "DELETE FROM Agendamentos WHERE id_sala = ?";
$stmt_agendamentos = mysqli_prepare($conn, $sql_delete_agendamentos);
if (!$stmt_agendamentos) {
    echo json_encode(['status' => 'error', 'message' => 'Erro ao preparar consulta de agendamentos: ' . mysqli_error($conn)]);
    exit;
}

mysqli_stmt_bind_param($stmt_agendamentos, 'i', $id_sala);
if (!mysqli_stmt_execute($stmt_agendamentos)) {
    echo json_encode(['status' => 'error', 'message' => 'Erro ao excluir agendamentos: ' . mysqli_stmt_error($stmt_agendamentos)]);
    mysqli_stmt_close($stmt_agendamentos);
    exit;
}

mysqli_stmt_close($stmt_agendamentos);

// Agora, excluir a sala
$sql_delete_sala = "DELETE FROM Salas WHERE id_sala = ?";
$stmt_sala = mysqli_prepare($conn, $sql_delete_sala);
if (!$stmt_sala) {
    echo json_encode(['status' => 'error', 'message' => 'Erro ao preparar consulta de sala: ' . mysqli_error($conn)]);
    exit;
}

mysqli_stmt_bind_param($stmt_sala, 'i', $id_sala);
if (mysqli_stmt_execute($stmt_sala)) {
    if (mysqli_stmt_affected_rows($stmt_sala) > 0) {
        echo json_encode(['status' => 'success', 'message' => 'Sala excluída com sucesso.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Sala não encontrada ou já excluída.']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Erro ao excluir sala: ' . mysqli_stmt_error($stmt_sala)]);
}

mysqli_stmt_close($stmt_sala);
mysqli_close($conn);
?>
