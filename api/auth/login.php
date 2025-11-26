<?php
session_start();
header('Content-Type: application/json');
include '../../agendamento-salas/db/conexao.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'error', 'mensagem' => 'Método não permitido.']);
    exit;
}

$email = trim($_POST['email'] ?? '');
$senha = $_POST['senha'] ?? '';

if (empty($email) || empty($senha)) {
    echo json_encode(['status' => 'error', 'mensagem' => 'E-mail e senha são obrigatórios.']);
    exit;
}

$sql = "SELECT id_usuario, nome, email, senha, papel FROM usuarios WHERE email = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, 's', $email);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if ($row = mysqli_fetch_assoc($result)) {
    // Check if password is hashed (new users) or plain text (existing users)
    if (password_verify($senha, $row['senha']) || $row['senha'] === $senha) {
        $_SESSION['usuario_id'] = $row['id_usuario'];
        $_SESSION['usuario_nome'] = $row['nome'];
        $_SESSION['usuario_email'] = $row['email'];
        $_SESSION['usuario_papel'] = $row['papel'];
        echo json_encode(['status' => 'success', 'mensagem' => 'Login realizado com sucesso.', 'papel' => $row['papel'], 'usuario' => ['username' => $row['nome'], 'email' => $row['email'], 'papel' => $row['papel']]]);
    } else {
        echo json_encode(['status' => 'error', 'mensagem' => 'Senha incorreta.']);
    }
} else {
    echo json_encode(['status' => 'error', 'mensagem' => 'E-mail não encontrado.']);
}

mysqli_stmt_close($stmt);
mysqli_close($conn);
?>
