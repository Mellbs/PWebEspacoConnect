<?php
header('Content-Type: application/json');
include '../agendamento-salas/db/conexao.php';

$sql = "SELECT DISTINCT nome_recurso FROM Recursos";
$result = mysqli_query($conn, $sql);

$recursos = [];
while ($row = mysqli_fetch_assoc($result)) {
    $recursos[] = $row;
}

echo json_encode($recursos);
mysqli_close($conn);
?>
