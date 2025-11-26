<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');
header('Access-Control-Allow-Headers: Content-Type');
include '../../db/conexao.php';

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    echo json_encode(['status' => 'error', 'mensagem' => 'Método não permitido.']);
    exit;
}

$sql = "SELECT id_sala, nome, capacidade, descricao_sala, localizacao FROM Salas";
$result = mysqli_query($conn, $sql);

if ($result) {
    $salas = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $salas[] = $row;
    }
    echo json_encode(['status' => 'success', 'salas' => $salas]);
} else {
    echo json_encode(['status' => 'error', 'mensagem' => 'Erro ao listar salas: ' . mysqli_error($conn)]);
}

mysqli_close($conn);
?>
