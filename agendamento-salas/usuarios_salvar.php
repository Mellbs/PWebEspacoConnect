<?php
include 'conexao.php';

$nome = $_POST['nome'];
$email = $_POST['email'];
$senha = $_POST['senha'];
$papel = $_POST['papel'];

$stmt = $conn->prepare("INSERT INTO Usuarios (nome, email, senha, papel) VALUES (?, ?, ?, ?)");
$stmt->bind_param("ssss", $nome, $email, $senha, $papel);
$stmt->execute();

echo json_encode(array('status' => 'success', 'mensagem' => 'Usuário cadastrado com sucesso!'));

$conn->close();
?>