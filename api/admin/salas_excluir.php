<?php
// salas_excluir.php
header('Content-Type: application/json');

// CORS - permitir chamadas do navegador (ajuste origem em produção)
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

// Responder preflight
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    echo json_encode(['status' => 'ok', 'message' => 'Preflight ok']);
    exit;
}

include '../../db/conexao.php'; // deve definir $conn

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['status' => 'error', 'message' => 'Método não permitido.']);
    exit;
}

// Lê dados tanto de form-urlencoded quanto de raw JSON
$contentType = $_SERVER['CONTENT_TYPE'] ?? '';
$input = [];
if (stripos($contentType, 'application/json') !== false) {
    $raw = file_get_contents('php://input');
    $json = json_decode($raw, true);
    if (json_last_error() === JSON_ERROR_NONE && is_array($json)) {
        $input = $json;
    }
} else {
    // form-urlencoded ou multipart/form-data
    $input = $_POST;
}

$id_sala = $input['id_sala'] ?? '';
$email = $input['email'] ?? '';

if ($id_sala === '' || $email === '') {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'ID da sala e email são obrigatórios.']);
    exit;
}

// Validar id como inteiro
if (!is_numeric($id_sala) || intval($id_sala) <= 0) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'ID da sala inválido.']);
    exit;
}
$id_sala = intval($id_sala);

// --- Controle de permissão ---
// Opção A: exigir e verificar sufixo @gerente.com (ativa)
// if (substr($email, -11) !== '@gerente.com') {
//     http_response_code(403);
//     echo json_encode(['status' => 'error', 'message' => 'Acesso negado. Apenas contas terminadas em @gerente.com podem excluir salas.']);
//     exit;
// }

// Opção B (recomendada): verificar token/sessão -> implemente sua lógica de autenticação no servidor.
// Por enquanto, o script assume que o email recebido é suficiente para auditoria, mas NÃO para autorização.

// Começar transação para garantir integridade
mysqli_begin_transaction($conn);

try {
    // 1) Excluir agendamentos relacionados (se a tabela existir)
    $sql_delete_agendamentos = "DELETE FROM Agendamentos WHERE id_sala = ?";
    $stmt_agend = mysqli_prepare($conn, $sql_delete_agendamentos);
    if (!$stmt_agend) {
        throw new Exception('Erro ao preparar consulta de agendamentos: ' . mysqli_error($conn));
    }
    mysqli_stmt_bind_param($stmt_agend, 'i', $id_sala);
    if (!mysqli_stmt_execute($stmt_agend)) {
        throw new Exception('Erro ao excluir agendamentos: ' . mysqli_stmt_error($stmt_agend));
    }
    mysqli_stmt_close($stmt_agend);

    // 2) Excluir a sala
    $sql_delete_sala = "DELETE FROM Salas WHERE id_sala = ?";
    $stmt_sala = mysqli_prepare($conn, $sql_delete_sala);
    if (!$stmt_sala) {
        throw new Exception('Erro ao preparar consulta de sala: ' . mysqli_error($conn));
    }
    mysqli_stmt_bind_param($stmt_sala, 'i', $id_sala);
    if (!mysqli_stmt_execute($stmt_sala)) {
        throw new Exception('Erro ao excluir sala: ' . mysqli_stmt_error($stmt_sala));
    }

    $affected = mysqli_stmt_affected_rows($stmt_sala);
    mysqli_stmt_close($stmt_sala);

    if ($affected > 0) {
        mysqli_commit($conn);
        echo json_encode(['status' => 'success', 'message' => 'Sala excluída com sucesso.']);
    } else {
        mysqli_commit($conn); // nada alterado, mas commit OK
        http_response_code(404);
        echo json_encode(['status' => 'error', 'message' => 'Sala não encontrada ou já excluída.']);
    }
} catch (Exception $e) {
    mysqli_rollback($conn);
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}

mysqli_close($conn);
