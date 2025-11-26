<?php
include 'agendamento-salas/db/conexao.php';

$sql = "UPDATE Usuarios SET senha = '1234'";
$result = mysqli_query($conn, $sql);

if ($result) {
    echo "Todas as senhas foram descriptografadas para '1234'";
} else {
    echo "Erro ao descriptografar senhas";
}

mysqli_close($conn);
?>
