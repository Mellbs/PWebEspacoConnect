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

// Fetch current user data
$sql_select = "SELECT nome, email, senha, papel FROM Usuarios WHERE id_usuario = ?";
$stmt_select = mysqli_prepare($conn, $sql_select);
mysqli_stmt_bind_param($stmt_select, 'i', $id);
mysqli_stmt_execute($stmt_select);
$result = mysqli_stmt_get_result($stmt_select);
$user = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt_select);

if (!$user) {
    echo json_encode(['status' => 'error', 'mensagem' => 'Usuário não encontrado.']);
    exit;
}

// Get new values, use current if not provided or empty
$nome = isset($_POST['nome']) && $_POST['nome'] !== '' ? trim($_POST['nome']) : $user['nome'];
$email = isset($_POST['email']) && $_POST['email'] !== '' ? trim($_POST['email']) : $user['email'];
$senha = isset($_POST['senha']) && $_POST['senha'] !== '' ? $_POST['senha'] : $user['senha'];
$papel = isset($_POST['papel']) && $_POST['papel'] !== '' ? $_POST['papel'] : $user['papel'];

// Validate provided fields
if (isset($_POST['nome']) && $_POST['nome'] !== '' && empty($nome)) {
    echo json_encode(['status' => 'error', 'mensagem' => 'Nome não pode ser vazio.']);
    exit;
}

if (isset($_POST['email']) && $_POST['email'] !== '') {
    if (empty($email)) {
        echo json_encode(['status' => 'error', 'mensagem' => 'E-mail não pode ser vazio.']);
        exit;
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['status' => 'error', 'mensagem' => 'E-mail inválido.']);
        exit;
    }
}

if (isset($_POST['papel']) && $_POST['papel'] !== '' && empty($papel)) {
    echo json_encode(['status' => 'error', 'mensagem' => 'Papel não pode ser vazio.']);
    exit;
}

$sql = "UPDATE Usuarios SET nome = ?, email = ?, senha = ?, papel = ? WHERE id_usuario = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, 'ssssi', $nome, $email, $senha, $papel, $id);

if (mysqli_stmt_execute($stmt)) {
    echo json_encode(['status' => 'success', 'mensagem' => 'Usuário editado com sucesso.']);
} else {
    echo json_encode(['status' => 'error', 'mensagem' => 'Erro ao editar usuário: ' . mysqli_error($conn)]);
}

mysqli_stmt_close($stmt);
mysqli_close($conn);
?>
