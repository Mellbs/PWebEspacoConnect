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

$nome = $_POST['nome'] ?? null;

if (!$nome) {
    echo json_encode(['status' => 'error', 'mensagem' => 'Nome da sala não fornecido.']);
    exit;
}

$sql = "INSERT INTO Salas (nome, capacidade, descricao_sala, localizacao) VALUES (?, 0, '', '')";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, 's', $nome);

if (mysqli_stmt_execute($stmt)) {
    $id_sala = mysqli_insert_id($conn);
    echo json_encode(['status' => 'success', 'id_sala' => $id_sala, 'mensagem' => 'Sala criada com sucesso.']);
} else {
    echo json_encode(['status' => 'error', 'mensagem' => 'Erro ao criar sala: ' . mysqli_error($conn)]);
}

mysqli_stmt_close($stmt);
mysqli_close($conn);
?>
