<?php

$host = 'localhost';
$user = 'root';
$pass = '';
$db = 'EspacoConnect';
$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
   echo json_encode([
        'status' => 'error',
        'mensagem' => 'Falha na conexão com o banco de dados: ' . mysqli_connect_error()
    ]);
    exit;   
}

?>