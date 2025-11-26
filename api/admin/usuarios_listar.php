<?php
session_start();
header('Content-Type: application/json');
include '../../agendamento-salas/db/conexao.php';

// if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_papel'] !== 'gerente') {
//     echo json_encode(['status' => 'error', 'mensagem' => 'Acesso negado.']);
//     exit;
// }

$sql = "SELECT id_usuario, nome, email, senha, papel FROM Usuarios";
$result = mysqli_query($conn, $sql);

$usuarios = [];
while ($row = mysqli_fetch_assoc($result)) {
    $usuarios[] = $row;
}

echo json_encode($usuarios);

mysqli_close($conn);
?>
