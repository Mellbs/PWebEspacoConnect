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

$email = trim($_POST['email'] ?? '');
$nova_senha = $_POST['nova_senha'] ?? '';

if (empty($email) || empty($nova_senha)) {
    echo json_encode(['status' => 'error', 'mensagem' => 'E-mail e nova senha são obrigatórios.']);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['status' => 'error', 'mensagem' => 'E-mail inválido.']);
    exit;
}

// Verificar se o usuário existe
$sql_check = "SELECT id_usuario FROM Usuarios WHERE email = ?";
$stmt_check = mysqli_prepare($conn, $sql_check);
mysqli_stmt_bind_param($stmt_check, 's', $email);
mysqli_stmt_execute($stmt_check);
mysqli_stmt_store_result($stmt_check);

if (mysqli_stmt_num_rows($stmt_check) == 0) {
    echo json_encode(['status' => 'error', 'mensagem' => 'E-mail não encontrado.']);
    mysqli_stmt_close($stmt_check);
    mysqli_close($conn);
    exit;
}

mysqli_stmt_close($stmt_check);

// Hash da nova senha
$senha_hash = password_hash($nova_senha, PASSWORD_DEFAULT);

// Atualizar a senha no banco de dados
$sql_update = "UPDATE usuarios SET senha = ? WHERE email = ?";
$stmt_update = mysqli_prepare($conn, $sql_update);
mysqli_stmt_bind_param($stmt_update, 'ss', $senha_hash, $email);

if (mysqli_stmt_execute($stmt_update)) {
    echo json_encode(['status' => 'success', 'mensagem' => 'Senha alterada com sucesso.']);
} else {
    echo json_encode(['status' => 'error', 'mensagem' => 'Erro ao alterar senha: ' . mysqli_error($conn)]);
}

mysqli_stmt_close($stmt_update);
mysqli_close($conn);
?>
