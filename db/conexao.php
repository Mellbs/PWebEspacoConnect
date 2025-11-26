<?php

$host = 'localhost';
$user = 'root';
$pass = '';
$db = 'EspacoConnect';

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    echo json_encode ([
        'status' => 'erro',
        'mensagem' => 'Erro ao conectar ao Banco de Dados' . mysqli_connect_error()
    ]);
    exit;
}

?>
