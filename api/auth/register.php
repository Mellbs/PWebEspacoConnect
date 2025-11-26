<?php
header('Content-Type: application/json');
include '../../agendamento-salas/db/conexao.php';

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

// Verificar se o e-mail já existe
$sql_check = "SELECT id_usuario FROM usuarios WHERE email = ?";
$stmt_check = mysqli_prepare($conn, $sql_check);
mysqli_stmt_bind_param($stmt_check, 's', $email);
mysqli_stmt_execute($stmt_check);
mysqli_stmt_store_result($stmt_check);

if (mysqli_stmt_num_rows($stmt_check) > 0) {
    echo json_encode(['status' => 'error', 'mensagem' => 'E-mail já cadastrado.']);
    mysqli_stmt_close($stmt_check);
    mysqli_close($conn);
    exit;
}

mysqli_stmt_close($stmt_check);

$sql = "INSERT INTO usuarios (nome, email, senha, papel) VALUES (?, ?, ?, ?)";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, 'ssss', $nome, $email, $senha, $papel);

if (mysqli_stmt_execute($stmt)) {
    // Iniciar sessão após cadastro bem-sucedido
    session_start();
    $_SESSION['usuario_id'] = mysqli_insert_id($conn);
    $_SESSION['usuario_nome'] = $nome;
    $_SESSION['usuario_email'] = $email;
    $_SESSION['usuario_papel'] = $papel;
    echo json_encode(['status' => 'success', 'mensagem' => 'Usuário cadastrado com sucesso.', 'papel' => $papel]);
} else {
    echo json_encode(['status' => 'error', 'mensagem' => 'Erro ao cadastrar usuário: ' . mysqli_error($conn)]);
}

mysqli_stmt_close($stmt);
mysqli_close($conn);
?>
