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

$nome = trim($_POST['nome'] ?? '');
$email = trim($_POST['email'] ?? '');
$senha = $_POST['senha'] ?? '';
$papel = $_POST['papel'] ?? '';

if (empty($nome) || empty($email) || empty($senha) || empty($papel)) {
    echo json_encode(['status' => 'error', 'mensagem' => 'Todos os campos são obrigatórios.']);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['status' => 'error', 'mensagem' => 'E-mail inválido.']);
    exit;
}

$sql = "INSERT INTO Usuarios (nome, email, senha, papel) VALUES (?, ?, ?, ?)";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, 'ssss', $nome, $email, $senha, $papel);

if (mysqli_stmt_execute($stmt)) {
    echo json_encode(['status' => 'success', 'mensagem' => 'Usuário adicionado com sucesso.']);
} else {
    echo json_encode(['status' => 'error', 'mensagem' => 'Erro ao adicionar usuário: ' . mysqli_error($conn)]);
}

mysqli_stmt_close($stmt);
mysqli_close($conn);
?>
