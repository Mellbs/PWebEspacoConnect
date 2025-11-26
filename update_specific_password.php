<?php
include 'agendamento-salas/db/conexao.php';

$email = 'alvaro@usuario.com';
$newPassword = '1234';

$sql = "UPDATE Usuarios SET senha = '$newPassword' WHERE email = '$email'";
$result = mysqli_query($conn, $sql);

if ($result) {
    echo "Password updated for $email to $newPassword";
} else {
    echo "Error updating password";
}

mysqli_close($conn);
?>
