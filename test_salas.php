<?php
include 'agendamento-salas/db/conexao.php';

$result = mysqli_query($conn, 'SELECT * FROM Salas');
while ($row = mysqli_fetch_assoc($result)) {
    echo $row['id_sala'] . ' ' . $row['nome'] . PHP_EOL;
}
mysqli_close($conn);
?>
