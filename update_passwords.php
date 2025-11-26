<?php
include 'agendamento-salas/db/conexao.php';

$sql = "UPDATE Usuarios SET senha = 'senha'";
$result = mysqli_query($conn, $sql);

if ($result) {
    echo "Passwords updated to 'senha'";
} else {
    echo "Error updating passwords";
}

mysqli_close($conn);
?>
